from fastapi import FastAPI, Depends, HTTPException, status, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import Optional, List
from datetime import datetime, date
from sqlalchemy import (
    Column, String, Integer, DateTime, BigInteger, Text, 
    create_engine, ForeignKey, Boolean, Numeric, func
)
from sqlalchemy.orm import relationship, sessionmaker, Session
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.dialects.postgresql import UUID
from sqlalchemy.exc import IntegrityError
import uuid

# --- CONFIGURACIÓN DE BASE DE DATOS Y MODELOS SQLAlchemy ---

# Asumiendo que la conexión y la estructura son correctas.
SQLALCHEMY_DATABASE_URL = (
    "postgresql+psycopg2://postgres:postgres@172.17.0.2:30432/postgres"
)
engine = create_engine(SQLALCHEMY_DATABASE_URL)
SessionLocal = sessionmaker(bind=engine, autocommit=False, autoflush=False)
Base = declarative_base()

# Modelos SQLAchemy

class Usuario(Base):
    """Modelo SQLAlchemy para la tabla de Usuarios (Pacientes)."""
    __tablename__ = "usuario"
    __table_args__ = {'schema': 'hcd'}
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    documento_id = Column(BigInteger, unique=True, nullable=False)
    nombre_completo = Column(String(255), nullable=False)
    pais_nacionalidad = Column(String(100), default="Colombia")
    fecha_nacimiento = Column(DateTime)
    edad = Column(Integer)
    sexo = Column(String(10)) # M o F (Usado para filtro y género biológico)
    genero = Column(String(20)) # Puede ser 'M', 'F', u otra identidad
    ocupacion = Column(String(100))
    # NOTA: Se añade un campo simulado 'activo' para que el frontend funcione mejor
    activo = Column(Boolean, default=True) 

class Atencion(Base):
    """Modelo SQLAlchemy para la tabla de Atenciones (Usado para la última consulta)."""
    __tablename__ = "atencion"
    __table_args__ = {"schema": "hcd"}
    atencion_id = Column(Integer, primary_key=True, autoincrement=True)
    documento_id = Column(BigInteger, ForeignKey("hcd.usuario.documento_id"), nullable=False)
    fecha_ingreso = Column(DateTime)
    # ... otros campos de Atencion ...


# Dependencia de DB
def get_db():
    """Generador de sesión de base de datos."""
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

# --- ESQUEMAS PYDANTIC PARA VALIDACIÓN DE DATOS ---

class PacienteBase(BaseModel):
    """Campos comunes para la creación y actualización de pacientes."""
    nombre_completo: str = Field(..., max_length=255)
    edad: Optional[int] = Field(None, ge=1, le=120)
    sexo: str = Field(..., max_length=1, pattern="^[MF]$") # Solo M o F
    genero_identidad: Optional[str] = Field(None, alias="genero", max_length=20)
    pais_nacionalidad: Optional[str] = "Colombia"
    ocupacion: Optional[str] = None
    activo: Optional[bool] = True

class PacienteCreate(PacienteBase):
    """Esquema para crear un nuevo paciente."""
    documento_id: int = Field(..., gt=0, description="Número de documento único.")
    # Los campos simulados para el frontend no existen realmente en el modelo
    telefono: Optional[str] = None
    correo: Optional[str] = None

class PacienteUpdate(PacienteBase):
    """Esquema para actualizar un paciente (todos los campos son opcionales)."""
    nombre_completo: Optional[str] = None
    sexo: Optional[str] = Field(None, max_length=1, pattern="^[MF]$")
    # Los campos simulados para el frontend no existen realmente en el modelo
    telefono: Optional[str] = None
    correo: Optional[str] = None
    
    # Configuramos el ORM mode para que acepte datos de SQLAlchemy
    class Config:
        from_attributes = True

# --- CONFIGURACIÓN FASTAPI ---

app = FastAPI(title="HCD API Controller - Admisionista CRUD")

# Configuración CORS 
origins = [
    "http://localhost",
    "http://localhost:8000", # Puerto comun de Laravel (si lo estas usando)
    "http://127.0.0.1:8000",
    "*" # Permisivo para desarrollo
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


# --- FUNCIONES HELPER ---

def get_paciente_by_documento(db: Session, documento_id: int):
    """Busca un paciente por su número de documento."""
    return db.query(Usuario).filter(Usuario.documento_id == documento_id).first()


# ======================================================
# ENDPOINTS CRUD COMPLETOS PARA LA VISTA DE PACIENTES
# ======================================================

# 1. READ (Listar y Paginar) - EL EXISTENTE

@app.get("/pacientes/admisionista", response_model=dict)
def listar_pacientes_admisionista(
    page: int = Query(1, ge=1, alias="page"),
    limit: int = Query(10, ge=1, le=100, alias="limit"),
    search: Optional[str] = Query(None, alias="search"),
    estado: Optional[str] = Query(None, alias="estado"), # 'activo' o 'inactivo'
    genero: Optional[str] = Query(None, alias="genero"), # 'M' o 'F'
    db: Session = Depends(get_db)
):
    """
    Lista y pagina pacientes con búsqueda y filtros. Retorna el formato de paginación compatible con el frontend.
    """
    
    # 1. Consulta Base (usando Usuario)
    query = db.query(Usuario)
    
    # 2. Aplicar Filtros y Búsqueda
    if search:
        search_lower = f"%{search.lower()}%"
        # Búsqueda por documento_id o nombre_completo
        query = query.filter(
            (Usuario.documento_id.cast(String).ilike(search_lower)) |
            (Usuario.nombre_completo.ilike(search_lower))
        )
        
    if genero and genero.upper() in ('M', 'F'):
        query = query.filter(Usuario.sexo == genero.upper())
        
    if estado:
        is_activo = estado.lower() == 'activo'
        query = query.filter(Usuario.activo == is_activo)

    # 3. Contar el Total (sin paginación)
    total = query.count()
    
    # 4. Aplicar Paginación
    offset = (page - 1) * limit
    # Ordenamos por documento_id (BigInteger)
    pacientes_db = query.order_by(Usuario.documento_id).limit(limit).offset(offset).all() 
    
    # 5. Obtener Última Consulta de la tabla Atencion
    documento_ids = [p.documento_id for p in pacientes_db]
    
    last_atenciones_map = {}
    if documento_ids:
        # Se obtiene la última fecha de ingreso para cada paciente en la lista actual
        subquery_last_atencion = (
            db.query(
                Atencion.documento_id.label("documento_id"),
                func.max(Atencion.fecha_ingreso).label("ultima_consulta")
            )
            .filter(Atencion.documento_id.in_(documento_ids))
            .group_by(Atencion.documento_id)
            .subquery()
        )
        
        last_atenciones = db.query(subquery_last_atencion).all()
        last_atenciones_map = {item.documento_id: item.ultima_consulta for item in last_atenciones}
    
    # 6. Formatear la Respuesta
    pacientes_data = []
    for paciente in pacientes_db:
        ultima_consulta = last_atenciones_map.get(paciente.documento_id)
        
        pacientes_data.append({
            "documento_id": str(paciente.documento_id), 
            "nombre_completo": paciente.nombre_completo,
            "edad": paciente.edad,
            "genero": paciente.sexo, # Usamos 'sexo' como 'genero' para compatibilidad con el frontend
            
            # Campos simulados/No existentes en el modelo:
            "telefono": "N/A", 
            "correo": "Sin email",
            
            # Campos calculados:
            "ultima_consulta": ultima_consulta.isoformat() if ultima_consulta else None,
            "activo": paciente.activo, 
        })
        
    # 7. Formatear la Respuesta de Paginación
    total_pages = (total + limit - 1) // limit if total > 0 else 1

    return {
        "data": pacientes_data, 
        "total": total,
        "per_page": limit,
        "current_page": page,
        "last_page": total_pages,
    }


# 2. CREATE (Crear un nuevo paciente)

@app.post("/pacientes", status_code=status.HTTP_201_CREATED)
def crear_paciente(paciente: PacienteCreate, db: Session = Depends(get_db)):
    """
    Crea un nuevo registro de paciente.
    """
    # 1. Verificar si el documento_id ya existe
    db_paciente = get_paciente_by_documento(db, paciente.documento_id)
    if db_paciente:
        raise HTTPException(
            status_code=status.HTTP_409_CONFLICT,
            detail=f"Ya existe un paciente con el documento ID: {paciente.documento_id}"
        )
    
    # 2. Crear nueva instancia de Usuario
    try:
        new_paciente = Usuario(
            documento_id=paciente.documento_id,
            nombre_completo=paciente.nombre_completo,
            edad=paciente.edad,
            sexo=paciente.sexo,
            genero=paciente.genero_identidad, # Usa el campo 'genero' del modelo SQLA
            pais_nacionalidad=paciente.pais_nacionalidad,
            ocupacion=paciente.ocupacion,
            activo=paciente.activo
        )
        
        # 3. Guardar en la base de datos
        db.add(new_paciente)
        db.commit()
        db.refresh(new_paciente)
        
        return {"message": "Paciente creado exitosamente", "documento_id": new_paciente.documento_id}
    except IntegrityError:
        db.rollback()
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Error de integridad de la base de datos (Ej: Campo obligatorio faltante)."
        )
    except Exception as e:
        db.rollback()
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Error inesperado al crear paciente: {e}"
        )


# 3. UPDATE (Actualizar un paciente existente)

@app.put("/pacientes/{documento_id}")
def actualizar_paciente(
    documento_id: int, 
    paciente_update: PacienteUpdate, 
    db: Session = Depends(get_db)
):
    """
    Actualiza la información de un paciente por su documento ID.
    """
    db_paciente = get_paciente_by_documento(db, documento_id)
    
    if not db_paciente:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Paciente con documento ID {documento_id} no encontrado."
        )

    # Actualizar solo los campos proporcionados en el body
    update_data = paciente_update.model_dump(exclude_unset=True)
    
    for key, value in update_data.items():
        if key == 'genero_identidad': # Mapear de Pydantic a SQLAlchemy
            setattr(db_paciente, 'genero', value)
        else:
            setattr(db_paciente, key, value)
            
    try:
        db.commit()
        db.refresh(db_paciente)
        return {"message": "Paciente actualizado exitosamente", "documento_id": documento_id}
    except Exception as e:
        db.rollback()
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Error al actualizar paciente: {e}"
        )


# 4. DELETE (Eliminar un paciente)

@app.delete("/pacientes/{documento_id}", status_code=status.HTTP_204_NO_CONTENT)
def eliminar_paciente(documento_id: int, db: Session = Depends(get_db)):
    """
    Elimina un paciente por su documento ID.
    NOTA: En entornos reales, se preferiría la 'eliminación suave' (soft delete), 
    marcando el campo 'activo=False' en lugar de borrar el registro. Aquí se borra 
    para fines demostrativos de CRUD.
    """
    db_paciente = get_paciente_by_documento(db, documento_id)
    
    if not db_paciente:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail=f"Paciente con documento ID {documento_id} no encontrado."
        )
        
    try:
        db.delete(db_paciente)
        db.commit()
        return None # 204 No Content
    except Exception as e:
        db.rollback()
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail=f"Error al eliminar paciente: {e}"
        )


# Endpoint de prueba para verificar que el servidor está activo
@app.get("/")
def read_root():
    return {"message": "FastAPI HCD Admisionista running with full CRUD endpoints!"}