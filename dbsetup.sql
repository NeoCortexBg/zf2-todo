
BEGIN;

CREATE TABLE "user"
(
  user_sid serial NOT NULL,
  email character varying(300) NOT NULL,
  password character varying(50) NOT NULL,
  CONSTRAINT pkey_user_sid PRIMARY KEY (user_sid ),
  CONSTRAINT uniq_user_email UNIQUE (email )
)
WITH (
  OIDS=FALSE
);

INSERT INTO "user" (email, password) VALUES ('your@email.com', MD5('password'));

CREATE TABLE project
(
  project_sid serial NOT NULL,
  name character varying(100) NOT NULL,
  CONSTRAINT pkey_project_sid PRIMARY KEY (project_sid )
)
WITH (
  OIDS=FALSE
);

CREATE TABLE todo_status
(
  todo_status_sid serial NOT NULL,
  name character varying(20),
  CONSTRAINT pkey_todo_status_sid PRIMARY KEY (todo_status_sid )
)
WITH (
  OIDS=FALSE
);

INSERT INTO todo_status (todo_status_sid, name) VALUES (1, 'open');
INSERT INTO todo_status (todo_status_sid, name) VALUES (2, 'closed');
INSERT INTO todo_status (todo_status_sid, name) VALUES (3, 'resolved');
INSERT INTO todo_status (todo_status_sid, name) VALUES (4, 'waiting');
INSERT INTO todo_status (todo_status_sid, name) VALUES (5, 'postponed');

CREATE TABLE todo
(
  todo_sid serial NOT NULL,
  project_sid integer,
  priority integer NOT NULL DEFAULT 0,
  todo_status_sid integer NOT NULL,
  text text,
  date_created timestamp without time zone NOT NULL DEFAULT now(),
  CONSTRAINT pkey_todo_sid PRIMARY KEY (todo_sid ),
  CONSTRAINT fkey_todo_todo_status_sid_todo_status_todo_status_sid FOREIGN KEY (todo_status_sid)
      REFERENCES todo_status (todo_status_sid) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);



COMMIT;
