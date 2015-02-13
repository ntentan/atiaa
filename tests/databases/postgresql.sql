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
    othernames character varying(255) DEFAULT NULL::character varying,
    status integer NOT NULL,
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

ALTER TABLE ONLY departments ALTER COLUMN id SET DEFAULT nextval('departments_id_seq'::regclass);

ALTER TABLE ONLY roles ALTER COLUMN id SET DEFAULT nextval('roles_id_seq'::regclass);

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);

ALTER TABLE ONLY departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_office_fkey FOREIGN KEY (office) REFERENCES departments(id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES roles(id);

