CREATE TABLE IF NOT EXISTS role.roles
(
    id        SERIAL           NOT NULL CONSTRAINT pk_role_roles_id PRIMARY KEY,
    name      VARCHAR(200)     NOT NULL CONSTRAINT u_role_roles_name UNIQUE
)