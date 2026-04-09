# oegpp

CREATE DATABASE oegpp; 
--agregando variable correo para mas formas de ingreso en la aplicación
CREATE TABLE administradores ( 
    id_admin SERIAL PRIMARY KEY, 
    usuario VARCHAR(100) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE
    ); 



CREATE TABLE intentos_login (
    id SERIAL PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    usuario VARCHAR(100),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    exitoso BOOLEAN DEFAULT FALSE
    );



CREATE TABLE clientes ( 
    id_cliente SERIAL PRIMARY KEY, 
    dni VARCHAR(15) NOT NULL UNIQUE, 
    nombres VARCHAR(120) NOT NULL, 
    apellidos VARCHAR(120) NOT NULL, 
    correo VARCHAR(120)
    );
--se quita folio de cursos pues un curso puede tener varios folios, se tendra que acer la busqueda con los trabajadores.
CREATE TABLE cursos ( 
    id_curso SERIAL PRIMARY KEY, 
    codigo_curso VARCHAR(50) NOT NULL UNIQUE, 
    nombre_curso VARCHAR(200) NOT NULL, 
    tipo VARCHAR(20) CHECK (tipo IN ('certificados','diplomados')) NOT NULL, 
    horas_totales INT NOT NULL
    );

CREATE TABLE modulos ( 
    id_modulo SERIAL PRIMARY KEY, 
    curso_id INT NOT NULL REFERENCES cursos(id_curso) ON DELETE CASCADE, 
    nombre_modulo VARCHAR(200) NOT NULL, 
    horas INT NOT NULL, 
    fecha_inicio DATE, 
    fecha_fin DATE 
    ); 
--Agregrando tabla notas_modulo para registrar las notas de cada módulo por trabajador, con referencia a los cursos y módulos correspondientes
CREATE TABLE notas_modulo (
    id_nota SERIAL PRIMARY KEY,
    cliente INT NOT NULL REFERENCES clientes(id_cliente) ON DELETE CASCADE UNIQUE,
    modulo_id INT NOT NULL REFERENCES modulos(id_modulo) ON DELETE CASCADE UNIQUE,
    nota DECIMAL(5,2) CHECK (nota >= 0 AND nota <= 20),
    fecha_registro DATE DEFAULT CURRENT_DATE
);
--Cambiando a certificados la variable de tipo para evitar el uso de Cursos
--Canbio del nombre de la variable año a anio paa evitar conflictos
--agregando fecha de finalización del libro
CREATE TABLE libros_registro ( 
    id_libro SERIAL PRIMARY KEY, 
    tipo VARCHAR(20) CHECK (tipo IN ('certificados','diplomados')) NOT NULL, 
    numero_libro INT NOT NULL, 
    anio_inicio INT NOT NULL,
    fecha_fin DATE
    ); 

ALTER TABLE libros_registro ADD COLUMN
 distrito VARCHAR(300);

ALTER TABLE libros_registro ADD COLUMN
 provincia VARCHAR(300);

ALTER TABLE libros_registro ADD COLUMN
 descripcion VARCHAR(300);
 
--El registro o codigo de un libro tiene la siguiente forma OEGPP-L(numero de libro aqui)
--En vez de guardar COD se guardara Registro pues COD se considerara sin importancia
--En registro solo se guardara el numero de orden mas no las iniciales del curso o diplomado
--tambien se guardara el folio para una facil busqueda.
CREATE TABLE registros_capacitacion ( 
    id_registro SERIAL PRIMARY KEY, 
    clientes_id INT NOT NULL REFERENCES clientes(id_cliente) ON DELETE CASCADE, 
    curso_id INT NOT NULL REFERENCES cursos(id_curso) ON DELETE CASCADE, 
    libro_id INT NOT NULL REFERENCES libros_registro(id_libro) ON DELETE CASCADE, 
    registro INT NOT NULL, 
    horas_realizadas INT NOT NULL, 
    fecha_inicio DATE, 
    fecha_fin DATE, 
    fecha_emision DATE NOT NULL,
    folio VARCHAR(20)
    );





--Tablas para trazabilidad de error, actividades y sesiones

CREATE TABLE actividad_logs (
    id SERIAL PRIMARY KEY,
    usuario_id INT,
    accion VARCHAR(50),
    tabla_afectada VARCHAR(50),
    registro_id INT,
    descripcion TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE error_logs (
    id SERIAL PRIMARY KEY,
    usuario_id INT,
    mensaje TEXT,
    tipo VARCHAR(50),
    archivo VARCHAR(255),
    linea INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

CREATE TABLE sesiones (
    id SERIAL PRIMARY KEY,
    usuario_id INT,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_fin TIMESTAMP,
    activa BOOLEAN DEFAULT TRUE,
);

-- Actualizar contraseñas existentes a hashed (ejecutar después de implementar hashing en PHP)
-- Mejor: Crear un script PHP para hashear las existentes, ya que MD5 no es seguro.

-- Para mayor seguridad, agregar índices si no existen
CREATE INDEX IF NOT EXISTS idx_admin_usuario ON administradores (usuario);
CREATE INDEX IF NOT EXISTS idx_admin_correo ON administradores (correo);
CREATE INDEX IF NOT EXISTS idx_admin_correo ON administradores (correo);

UPDATE administradores SET password = CONCAT('$2y$10$', SUBSTRING(MD5(RAND()), 1, 22), '$', password) WHERE LENGTH(password) < 60;

ALTER TABLE trabajadores 
ADD COLUMN celular VARCHAR(15),
ADD COLUMN area VARCHAR(100);

ALTER TABLE administradores
ADD COLUMN rol BOOLEAN DEFAULT TRUE,
ADD COLUMN verificado BOOLEAN DEFAULT FALSE;

-- Agregar campos para seguridad y control de login
ALTER TABLE administradores
ADD COLUMN bloqueado BOOLEAN DEFAULT FALSE,
ADD COLUMN fecha_bloqueo TIMESTAMP;

-- Ajustar rol para que sea más flexible (si antes era BOOLEAN)
ALTER TABLE administradores
ALTER COLUMN rol TYPE VARCHAR(50) USING rol::VARCHAR,
ALTER COLUMN rol SET DEFAULT 'regular';
      
