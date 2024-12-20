Inserts y Consultas Tablas
//////////////////////////

INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 101', 30);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 102', 25);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Laboratorio 1', 20);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Laboratorio 2', 15);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Auditorio', 100);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 201', 40);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 202', 35);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Sala de Reuniones', 10);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Biblioteca', 50);
INSERT INTO tablas_administrativo.aula (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 203', 28);

INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Reunión General', SYSDATE, 'Reunión de profesores', 1);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Cambio de Horario', SYSDATE, 'Actualización de horarios', 2);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Mantenimiento', SYSDATE, 'Cierre por mantenimiento', 3);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Nuevo Curso', SYSDATE, 'Apertura de curso avanzado', 4);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Reunión de Alumnos', SYSDATE, 'Asamblea general', 5);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Conferencia', SYSDATE, 'Invitación a charla técnica', 6);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Taller Práctico', SYSDATE, 'Taller de habilidades', 7);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Cierre de Inscripciones', SYSDATE, 'Última fecha para inscripciones', 8);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Graduación', SYSDATE, 'Ceremonia de fin de curso', 9);
INSERT INTO tablas_administrativo.anuncios (TITULO, FECHA, DESCRIPCION, ID_AULA) VALUES ('Exposición', SYSDATE, 'Exposición de proyectos', 10);

INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Taller de Robótica', 2024, 3);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Curso de IA', 2024, 4);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Jornada de Limpieza', 2023, 5);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Competencia de Programación', 2024, 6);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Hackathon', 2023, 7);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Seminario Técnico', 2024, 8);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Círculo de Lectura', 2023, 9);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Feria de Ciencias', 2023, 10);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Concurso de Matemáticas', 2024, 2);
INSERT INTO tablas_administrativo.actividades (NOMBRE_ACTIVIDAD, ANNO, ID_AULA) VALUES ('Festival Artístico', 2023, 1);

INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Juan Pérez', 'juan.perez@example.com', 'Ingeniería');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Ana Gómez', 'ana.gomez@example.com', 'Matemáticas');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Luis Martínez', 'luis.martinez@example.com', 'Física');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('María López', 'maria.lopez@example.com', 'Química');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Carlos Sánchez', 'carlos.sanchez@example.com', 'Biología');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Laura Fernández', 'laura.fernandez@example.com', 'Historia');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Pedro Ramírez', 'pedro.ramirez@example.com', 'Literatura');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Sofía Torres', 'sofia.torres@example.com', 'Filosofía');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Diego Castro', 'diego.castro@example.com', 'Ingeniería');
INSERT INTO tablas_estudiante.estudiantes (NOMBRE, EMAIL, CARRERA) VALUES ('Lucía Vega', 'lucia.vega@example.com', 'Arte');

INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (1, '2024-1');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (2, '2024-1');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (3, '2024-2');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (4, '2023-2');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (5, '2023-1');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (6, '2023-1');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (7, '2024-2');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (8, '2024-2');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (9, '2023-2');
INSERT INTO tablas_estudiante.matriculas (ID_ESTUDIANTE, SEMESTRE) VALUES (10, '2023-1');


INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (1, 'Recordatorio de Pago', 'Recuerda pagar la matrícula', '555-1234', 'juan.perez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (2, 'Inscripción', 'Abierta inscripción de cursos', '555-5678', 'ana.gomez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (3, 'Entrega de Documentos', 'Fecha límite para entregar documentos', '555-9101', 'luis.martinez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (4, 'Cambio de Horario', 'Actualización en horario', '555-1122', 'maria.lopez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (5, 'Taller Práctico', 'Invitación al taller', '555-3344', 'carlos.sanchez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (6, 'Resultados', 'Consulta tus calificaciones', '555-5566', 'laura.fernandez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (7, 'Aviso Importante', 'Información urgente', '555-7788', 'pedro.ramirez@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (8, 'Conferencia', 'Charla técnica', '555-9900', 'sofia.torres@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (9, 'Curso Nuevo', 'Curso avanzado disponible', '555-1233', 'diego.castro@example.com');
INSERT INTO tablas_estudiante.notificaciones (ID_ESTUDIANTE, TITULO, MENSAJE, TELEFONO, EMAIL) VALUES (10, 'Cierre de Inscripciones', 'Última fecha para inscripciones', '555-5677', 'lucia.vega@example.com');

-- Tabla ACTIVIDADES_ESTUDIANTES
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (1, 1);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (2, 2);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (3, 3);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (4, 4);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (5, 5);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (6, 6);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (7, 7);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (8, 8);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (9, 9);
INSERT INTO tablas_estudiante.actividades_estudiantes (ID_ACTIVIDAD, ID_ESTUDIANTE) VALUES (10, 10);

INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Parcial 1', SYSDATE);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Parcial 2', SYSDATE + 10);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Final', SYSDATE + 20);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Recuperación', SYSDATE + 30);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Diagnóstico', SYSDATE - 5);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Práctico', SYSDATE + 15);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Oral', SYSDATE + 25);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Virtual', SYSDATE + 35);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen de Ensayo', SYSDATE + 45);
INSERT INTO tablas_profesor.examenes (NOMBRE, FECHA) VALUES ('Examen Experimental', SYSDATE + 50);

INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo A');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo B');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo C');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo D');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo E');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo F');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo G');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo H');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo I');
INSERT INTO tablas_profesor.grupos (NOMBRE_GRUPO) VALUES ('Grupo J');

INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (1, 1, 1);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (2, 2, 2);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (3, 3, 3);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (4, 4, 4);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (5, 5, 5);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (6, 6, 6);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (7, 7, 7);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (8, 8, 8);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (9, 9, 9);
INSERT INTO tablas_profesor.examenes_grupo (ID_EXAMEN, ID_GRUPO, ID_ACTIVIDAD) VALUES (10, 10, 10);

INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (1, 1);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (2, 2);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (3, 3);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (4, 4);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (5, 5);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (6, 6);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (7, 7);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (8, 8);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (9, 9);
INSERT INTO tablas_profesor.grupos_estudiantes (ID_GRUPO, ID_ESTUDIANTE) VALUES (10, 10);


///////////////////////////////////////////////////////////////////////////////////////////


select * from tablas_administrativo.actividades;
select * from tablas_administrativo.aula;
select * from tablas_administrativo.anuncios;

select * from tablas_profesor.examenes;
select * from tablas_profesor.examenes_grupo;
select * from tablas_profesor.grupos;
select * from tablas_profesor.grupos_estudiantes;

select * from tablas_estudiante.matriculas;
select * from tablas_estudiante.notificaciones;
select * from tablas_estudiante.estudiantes;
select * from tablas_estudiante.actividades_estudiantes;
