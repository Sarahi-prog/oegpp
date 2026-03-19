CREATE DATABASE oegpp; 
--agregando variable correo para mas formas de ingreso en la aplicación
CREATE TABLE administradores ( 
    id_admin SERIAL PRIMARY KEY, 
    usuario VARCHAR(100) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE
    ); 
CREATE TABLE trabajadores ( 
    id_trabajador SERIAL PRIMARY KEY, 
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
    trabajador_id INT NOT NULL REFERENCES trabajadores(id_trabajador) ON DELETE CASCADE UNIQUE,
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
--El registro o codigo de un libro tiene la siguiente forma OEGPP-L(numero de libro aqui)
--En vez de guardar COD se guardara Registro pues COD se considerara sin importancia
--En registro solo se guardara el numero de orden mas no las iniciales del curso o diplomado
--tambien se guardara el folio para una facil busqueda.
CREATE TABLE registros_capacitacion ( 
    id_registro SERIAL PRIMARY KEY, 
    trabajador_id INT NOT NULL REFERENCES trabajadores(id_trabajador) ON DELETE CASCADE, 
    curso_id INT NOT NULL REFERENCES cursos(id_curso) ON DELETE CASCADE, 
    libro_id INT NOT NULL REFERENCES libros_registro(id_libro) ON DELETE CASCADE, 
    registro INT NOT NULL, 
    horas_realizadas INT NOT NULL, 
    fecha_inicio DATE, 
    fecha_fin DATE, 
    fecha_emision DATE NOT NULL,
    folio VARCHAR(20)
    );

select * from libros_registro 
where tipo ='certificados' 

