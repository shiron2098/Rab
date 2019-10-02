CREATE TABLE IF NOT EXISTS role.permissions
(
    id        SERIAL           NOT NULL CONSTRAINT pk_role_permissions_id PRIMARY KEY,
    name      VARCHAR(200)     NOT NULL CONSTRAINT u_role_permissions_name UNIQUE
)