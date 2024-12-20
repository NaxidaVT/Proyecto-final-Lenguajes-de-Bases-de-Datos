-- Conectarse como SYSDBA
ALTER SESSION SET "_oracle_script"=TRUE;

-- Crear usuarios
CREATE USER TABLAS_ADMINISTRATIVO IDENTIFIED BY admin123
DEFAULT TABLESPACE USERS
TEMPORARY TABLESPACE TEMP
QUOTA UNLIMITED ON USERS;

CREATE USER TABLAS_PROFESOR IDENTIFIED BY profe123
DEFAULT TABLESPACE USERS
TEMPORARY TABLESPACE TEMP
QUOTA UNLIMITED ON USERS;

CREATE USER TABLAS_ESTUDIANTE IDENTIFIED BY estudiante123
DEFAULT TABLESPACE USERS
TEMPORARY TABLESPACE TEMP
QUOTA UNLIMITED ON USERS;

CREATE USER ACCESO_PHP IDENTIFIED BY php_password
DEFAULT TABLESPACE USERS
TEMPORARY TABLESPACE TEMP
QUOTA UNLIMITED ON USERS;

-- Otorgar permisos generales a los usuarios
GRANT CREATE SESSION, CREATE TABLE, CREATE SEQUENCE TO TABLAS_ADMINISTRATIVO;
GRANT CREATE SESSION, CREATE TABLE, CREATE SEQUENCE TO TABLAS_PROFESOR;
GRANT CREATE SESSION, CREATE TABLE, CREATE SEQUENCE TO TABLAS_ESTUDIANTE;
GRANT CREATE SESSION TO ACCESO_PHP;

-----------------------------------------------------
-- CREAR TABLAS EN EL SCHEMA `tablas_administrativo`
-----------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ADMINISTRATIVO;

CREATE TABLE AULA (
    ID_AULA NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NOMBRE_AULA VARCHAR2(100) NOT NULL,
    CAPACIDAD NUMBER NOT NULL
);

CREATE TABLE ANUNCIOS (
    ID_ANUNCIO NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    TITULO VARCHAR2(100) NOT NULL,
    FECHA DATE NOT NULL,
    DESCRIPCION VARCHAR2(255) NOT NULL,
    ID_AULA NUMBER
);

CREATE TABLE ACTIVIDADES (
    ID_ACTIVIDAD NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NOMBRE_ACTIVIDAD VARCHAR2(100) NOT NULL,
    ANNO NUMBER NOT NULL,
    ID_AULA NUMBER
);

-------------------------------------------------
-- CREAR TABLAS EN EL SCHEMA `tablas_estudiante`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ESTUDIANTE;

CREATE TABLE ESTUDIANTES (
    ID_ESTUDIANTE NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100),
    EMAIL VARCHAR2(100),
    CARRERA VARCHAR2(100)
);

CREATE TABLE MATRICULAS (
    ID_MATRICULA NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ID_ESTUDIANTE NUMBER,
    SEMESTRE VARCHAR2(50)
);

CREATE TABLE NOTIFICACIONES (
    ID_NOTIFICACION NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ID_ESTUDIANTE NUMBER,
    TITULO VARCHAR2(100),
    MENSAJE VARCHAR2(255),
    TELEFONO VARCHAR2(20),
    EMAIL VARCHAR2(100),
    FECHA_CREACION DATE DEFAULT SYSDATE
);

CREATE TABLE ACTIVIDADES_ESTUDIANTES (
    ID_ACTIVIDAD NUMBER,
    ID_ESTUDIANTE NUMBER
);

-------------------------------------------------
-- CREAR TABLAS EN EL SCHEMA `tablas_profesor`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_PROFESOR;

CREATE TABLE EXAMENES (
    ID_EXAMEN NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NOMBRE VARCHAR2(100),
    FECHA DATE
);

CREATE TABLE GRUPOS (
    ID_GRUPO NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NOMBRE_GRUPO VARCHAR2(100)
);

CREATE TABLE EXAMENES_GRUPO (
    ID_EXAMEN_GRUPO NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ID_EXAMEN NUMBER,
    ID_GRUPO NUMBER,
    ID_ACTIVIDAD NUMBER
);

CREATE TABLE GRUPOS_ESTUDIANTES (
    ID_GRUPO NUMBER,
    ID_ESTUDIANTE NUMBER
);

-------------------------------------------------
-- AGREGAR CONSTRAINTS EN `tablas_administrativo`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ADMINISTRATIVO;

ALTER TABLE ANUNCIOS
    ADD CONSTRAINT FK_ANUNCIOS_AULA FOREIGN KEY (
        ID_AULA
    )
        REFERENCES AULA(
            ID_AULA
        );

ALTER TABLE ACTIVIDADES
    ADD CONSTRAINT FK_ACTIVIDADES_AULA FOREIGN KEY (
        ID_AULA
    )
        REFERENCES AULA(
            ID_AULA
        );

-------------------------------------------------
-- AGREGAR CONSTRAINTS EN `tablas_estudiante`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ESTUDIANTE;

ALTER TABLE MATRICULAS
    ADD CONSTRAINT FK_MATRICULA_EST FOREIGN KEY (
        ID_ESTUDIANTE
    )
        REFERENCES ESTUDIANTES(
            ID_ESTUDIANTE
        );

ALTER TABLE NOTIFICACIONES
    ADD CONSTRAINT FK_NOTIFICACIONES_EST FOREIGN KEY (
        ID_ESTUDIANTE
    )
        REFERENCES ESTUDIANTES(
            ID_ESTUDIANTE
        );

ALTER TABLE ACTIVIDADES_ESTUDIANTES
    ADD CONSTRAINT FK_ACT_EST_ACT FOREIGN KEY (
        ID_ACTIVIDAD
    )
        REFERENCES TABLAS_ADMINISTRATIVO.ACTIVIDADES(
            ID_ACTIVIDAD
        );

ALTER TABLE ACTIVIDADES_ESTUDIANTES
    ADD CONSTRAINT FK_ACT_EST_EST FOREIGN KEY (
        ID_ESTUDIANTE
    )
        REFERENCES ESTUDIANTES(
            ID_ESTUDIANTE
        );

-------------------------------------------------
-- AGREGAR CONSTRAINTS EN `tablas_profesor`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_PROFESOR;

ALTER TABLE EXAMENES_GRUPO
    ADD CONSTRAINT FK_EXAMEN_GRUPO_EX FOREIGN KEY (
        ID_EXAMEN
    )
        REFERENCES EXAMENES(
            ID_EXAMEN
        );

ALTER TABLE EXAMENES_GRUPO
    ADD CONSTRAINT FK_EXAMEN_GRUPO_GR FOREIGN KEY (
        ID_GRUPO
    )
        REFERENCES GRUPOS(
            ID_GRUPO
        );

ALTER TABLE EXAMENES_GRUPO
    ADD CONSTRAINT FK_EXAMEN_GRUPO_ACT FOREIGN KEY (
        ID_ACTIVIDAD
    )
        REFERENCES TABLAS_ADMINISTRATIVO.ACTIVIDADES(
            ID_ACTIVIDAD
        );

ALTER TABLE GRUPOS_ESTUDIANTES
    ADD CONSTRAINT FK_GRUPOS_EST_GR FOREIGN KEY (
        ID_GRUPO
    )
        REFERENCES GRUPOS(
            ID_GRUPO
        );

ALTER TABLE GRUPOS_ESTUDIANTES
    ADD CONSTRAINT FK_GRUPOS_EST_EST FOREIGN KEY (
        ID_ESTUDIANTE
    )
        REFERENCES TABLAS_ESTUDIANTE.ESTUDIANTES(
            ID_ESTUDIANTE
        );

-------------------------------------------------
-- PERMISOS DE ACCESO Y CONSULTA PARA `ACCESO_PHP`
-------------------------------------------------
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ADMINISTRATIVO;

GRANT SELECT, INSERT, UPDATE, DELETE ON AULA TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON ANUNCIOS TO ACCESO_PHP;
GRANT SELECT ON ACTIVIDADES TO ACCESO_PHP;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ESTUDIANTE;

GRANT SELECT, INSERT, UPDATE, DELETE ON ESTUDIANTES TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON MATRICULAS TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON NOTIFICACIONES TO ACCESO_PHP;
GRANT SELECT ON ACTIVIDADES_ESTUDIANTES TO ACCESO_PHP;
GRANT DELETE ON TABLAS_PROFESOR.GRUPOS_ESTUDIANTES TO acceso_php;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_PROFESOR;

GRANT SELECT, INSERT, UPDATE, DELETE ON EXAMENES TO ACCESO_PHP;
GRANT SELECT ON GRUPOS TO ACCESO_PHP;
GRANT SELECT ON EXAMENES_GRUPO TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON GRUPOS_ESTUDIANTES TO ACCESO_PHP;
GRANT SELECT ON TABLAS_PROFESOR.GRUPOS_ESTUDIANTES TO acceso_php;

-------------------------------------------------
-- CREAR TRIGGERS
-------------------------------------------------
CREATE TABLE ACCESO_PHP.REGISTRO_CAMBIOS_EXAMENES (
    ID_REGISTRO NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ID_EXAMEN NUMBER,
    CAMPO_MODIFICADO VARCHAR2(50),
    VALOR_ANTERIOR VARCHAR2(100),
    VALOR_NUEVO VARCHAR2(100),
    FECHA_MODIFICACION TIMESTAMP DEFAULT SYSTIMESTAMP,
    USUARIO_MODIFICACION VARCHAR2(50)
);


CREATE OR REPLACE TRIGGER TRG_NOTIFICAR_EXAMEN
AFTER INSERT OR UPDATE ON TABLAS_PROFESOR.EXAMENES
FOR EACH ROW
DECLARE
    CURSOR grupos_estudiantes IS
        SELECT GE.ID_ESTUDIANTE
        FROM TABLAS_PROFESOR.EXAMENES_GRUPO EG
        JOIN TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE ON EG.ID_GRUPO = GE.ID_GRUPO
        WHERE EG.ID_EXAMEN = :NEW.ID_EXAMEN;
BEGIN
    FOR estudiante IN grupos_estudiantes LOOP
        INSERT INTO TABLAS_ESTUDIANTE.NOTIFICACIONES (
            ID_ESTUDIANTE, 
            TITULO, 
            MENSAJE, 
            FECHA_CREACION
        ) VALUES (
            estudiante.ID_ESTUDIANTE,
            'Nuevo/Modificado Examen',
            'El examen "' || :NEW.NOMBRE || '" ha sido ' ||
            CASE
                WHEN INSERTING THEN 'creado'
                WHEN UPDATING THEN 'modificado'
            END || ' para el grupo correspondiente.',
            SYSDATE
        );
    END LOOP;
END;
/


-------------------------------------------------
-- SECUENCIAS Y PERMISOS ADICIONALES
-------------------------------------------------

-- Crear secuencias asociadas a las tablas
ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ESTUDIANTE;

CREATE SEQUENCE TABLAS_ESTUDIANTE.SEQ_ESTUDIANTE
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE SEQUENCE TABLAS_ESTUDIANTE.SEQ_MATRICULAS
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ADMINISTRATIVO;

CREATE SEQUENCE TABLAS_ADMINISTRATIVO.SEQ_AULA
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE SEQUENCE TABLAS_ADMINISTRATIVO.SEQ_ANUNCIOS
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE SEQUENCE TABLAS_ADMINISTRATIVO.SEQ_ACTIVIDADES
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_PROFESOR;

CREATE SEQUENCE TABLAS_PROFESOR.SEQ_EXAMENES
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE SEQUENCE TABLAS_PROFESOR.SEQ_GRUPOS
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE SEQUENCE TABLAS_PROFESOR.SEQ_EXAMENES_GRUPO
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

-------------------------------------------------
-- PERMISOS PARA EL USUARIO CENTRALIZADO
-------------------------------------------------

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ADMINISTRATIVO;

GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ADMINISTRATIVO.AULA TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ADMINISTRATIVO.ANUNCIOS TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ADMINISTRATIVO.ACTIVIDADES TO ACCESO_PHP;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_ESTUDIANTE;

GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ESTUDIANTE.ESTUDIANTES TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ESTUDIANTE.MATRICULAS TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ESTUDIANTE.NOTIFICACIONES TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_ESTUDIANTE.ACTIVIDADES_ESTUDIANTES TO ACCESO_PHP;

ALTER SESSION SET CURRENT_SCHEMA = TABLAS_PROFESOR;

GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_PROFESOR.EXAMENES TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_PROFESOR.GRUPOS TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_PROFESOR.EXAMENES_GRUPO TO ACCESO_PHP;
GRANT SELECT, INSERT, UPDATE, DELETE ON TABLAS_PROFESOR.GRUPOS_ESTUDIANTES TO ACCESO_PHP;

-------------------------------------------------
-- EJEMPLO DE INSERTS CON LLAVES FORÁNEAS
-------------------------------------------------

-- Insertar datos en AULA (TABLAS_ADMINISTRATIVO)
INSERT INTO TABLAS_ADMINISTRATIVO.AULA (NOMBRE_AULA, CAPACIDAD) VALUES ('Aula 101', 30);

-- Insertar datos en GRUPOS (TABLAS_PROFESOR)
INSERT INTO TABLAS_PROFESOR.GRUPOS (NOMBRE_GRUPO) VALUES ('Grupo A');

-- Insertar datos en ESTUDIANTES (TABLAS_ESTUDIANTE)
INSERT INTO TABLAS_ESTUDIANTE.ESTUDIANTES (NOMBRE, EMAIL, CARRERA) VALUES ('Juan Perez', 'juan.perez@example.com', 'Ingeniería en Sistemas');

-- Insertar datos en MATRÍCULAS (TABLAS_ESTUDIANTE)
INSERT INTO TABLAS_ESTUDIANTE.MATRICULAS (ID_ESTUDIANTE, SEMESTRE) VALUES (1, '2024-1');

-- Insertar datos en NOTIFICACIONES (TABLAS_ESTUDIANTE)
INSERT INTO TABLAS_ESTUDIANTE.NOTIFICACIONES (ID_ESTUDIANTE, TITULO, MENSAJE) VALUES (1, 'Bienvenido', 'Bienvenido al nuevo semestre.');

-- Insertar datos en GRUPOS_ESTUDIANTES (TABLAS_PROFESOR)
INSERT INTO TABLAS_PROFESOR.GRUPOS_ESTUDIANTES (ID_GRUPO, ID_ESTUDIANTE) VALUES (1, 1);

-------------------------------------------------
-- VALIDACIONES Y AJUSTES POST-INSTALACIÓN
-------------------------------------------------

-- Verificar la configuración de claves foráneas
SELECT * FROM USER_CONSTRAINTS WHERE CONSTRAINT_TYPE = 'R';

-- Probar una inserción que genere error por capacidad de aula
BEGIN
    INSERT INTO TABLAS_PROFESOR.GRUPOS_ESTUDIANTES (ID_GRUPO, ID_ESTUDIANTE) VALUES (1, 2);
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
END;
/

-- Validar que las secuencias funcionan correctamente
SELECT TABLAS_ESTUDIANTE.SEQ_ESTUDIANTE.NEXTVAL FROM DUAL;
SELECT TABLAS_PROFESOR.SEQ_GRUPOS.NEXTVAL FROM DUAL;

-- Consultar datos insertados para pruebas
SELECT * FROM TABLAS_ADMINISTRATIVO.AULA;
SELECT * FROM TABLAS_ESTUDIANTE.ESTUDIANTES;
SELECT * FROM TABLAS_PROFESOR.GRUPOS_ESTUDIANTES;
