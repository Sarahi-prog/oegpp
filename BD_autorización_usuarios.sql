-- ===============================================
-- TABLA PARA AUTORIZACIÓN DE USUARIOS
-- ===============================================

-- Crear tabla de solicitudes de autorización
CREATE TABLE IF NOT EXISTS solicitudes_registro (
    id_solicitud SERIAL PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombres VARCHAR(100) NULL,
    apellidos VARCHAR(100) NULL,
    dni VARCHAR(20) UNIQUE NULL,
    celular VARCHAR(15),
    area VARCHAR(100),
    estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aprobado', 'rechazado')),
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_autorizacion TIMESTAMP NULL,
    motivo_rechazo TEXT NULL,
    autorizado_por INT NULL REFERENCES administradores(id_admin),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modificar columnas para permitir NULL si la tabla ya existe
ALTER TABLE solicitudes_registro ALTER COLUMN nombres DROP NOT NULL;
ALTER TABLE solicitudes_registro ALTER COLUMN apellidos DROP NOT NULL;
ALTER TABLE solicitudes_registro ALTER COLUMN dni DROP NOT NULL;

-- Crear índices para mejor rendimiento
CREATE INDEX idx_solicitud_estado ON solicitudes_registro(estado);
CREATE INDEX idx_solicitud_usuario ON solicitudes_registro(usuario);
CREATE INDEX idx_solicitud_correo ON solicitudes_registro(correo);
CREATE INDEX idx_solicitud_fecha ON solicitudes_registro(fecha_solicitud DESC);

-- NOTA: Cuando se apruebe una solicitud, crear el usuario en la tabla de trabajadores
-- y cambiar el estado a 'aprobado'
