SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE SCHEMA crm;

CREATE SCHEMA hr;

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;

SET search_path = crm, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE customers (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    employee_id integer NOT NULL
);

CREATE SEQUENCE customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE customers_id_seq OWNED BY customers.id;
SET search_path = hr, pg_catalog;

CREATE TABLE categories (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);

CREATE SEQUENCE departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE departments_id_seq OWNED BY categories.id;

CREATE TABLE employees (
    id integer NOT NULL,
    firstname character varying(255) NOT NULL,
    lastname character varying(255),
    date_of_birth date
);

CREATE SEQUENCE employees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE employees_id_seq OWNED BY employees.id;
SET search_path = public, pg_catalog;

CREATE TABLE departments (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);

CREATE SEQUENCE departments_id_seq
    START WITH 6
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE departments_id_seq OWNED BY departments.id;

CREATE TABLE roles (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);

CREATE SEQUENCE roles_id_seq
    START WITH 5
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE roles_id_seq OWNED BY roles.id;

CREATE TABLE users (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role_id integer NOT NULL,
    firstname character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    othernames character varying(255) DEFAULT 'None'::character varying,
    status integer NOT NULL DEFAULT '2',
    email character varying(255) NOT NULL,
    phone character varying(64) DEFAULT NULL::character varying,
    office integer,
    last_login_time timestamp without time zone,
    is_admin boolean
);

CREATE SEQUENCE users_id_seq
    START WITH 8
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE users_id_seq OWNED BY users.id;

CREATE VIEW users_view AS
 SELECT users.id,
    users.username,
    users.password,
    users.firstname,
    users.lastname,
    users.othernames,
    users.email,
    roles.name AS role
   FROM (users
     JOIN roles ON ((users.role_id = roles.id)));
SET search_path = crm, pg_catalog;

ALTER TABLE ONLY customers ALTER COLUMN id SET DEFAULT nextval('customers_id_seq'::regclass);
SET search_path = hr, pg_catalog;

ALTER TABLE ONLY categories ALTER COLUMN id SET DEFAULT nextval('departments_id_seq'::regclass);

ALTER TABLE ONLY employees ALTER COLUMN id SET DEFAULT nextval('employees_id_seq'::regclass);
SET search_path = public, pg_catalog;

ALTER TABLE ONLY departments ALTER COLUMN id SET DEFAULT nextval('departments_id_seq'::regclass);

ALTER TABLE ONLY roles ALTER COLUMN id SET DEFAULT nextval('roles_id_seq'::regclass);

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);
SET search_path = crm, pg_catalog;

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);
SET search_path = hr, pg_catalog;

ALTER TABLE ONLY categories
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY employees
    ADD CONSTRAINT employees_pkey PRIMARY KEY (id);
SET search_path = public, pg_catalog;

ALTER TABLE ONLY departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
SET search_path = crm, pg_catalog;

ALTER TABLE ONLY customers
    ADD CONSTRAINT customers_employee_id_fkey FOREIGN KEY (employee_id) REFERENCES hr.employees(id);
SET search_path = public, pg_catalog;

ALTER TABLE ONLY users
    ADD CONSTRAINT users_office_fkey FOREIGN KEY (office) REFERENCES departments(id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES roles(id);


