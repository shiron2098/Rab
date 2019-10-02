INSERT INTO role.roles (name)
VALUES ('admin'),
       ('manager'),
       ('driver')
ON CONFLICT ON CONSTRAINT u_role_roles_name 
DO NOTHING;