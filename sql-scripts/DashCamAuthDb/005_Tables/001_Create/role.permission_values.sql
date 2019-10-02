CREATE TABLE IF NOT EXISTS role.permission_values
(
    role_id         INT     NOT NULL,
    permission_id   INT     NOT NULL,
    is_enabled      BOOL    NOT NULL,
    
    
    CONSTRAINT pk_role_permissionvalues_roleid_permissionid PRIMARY KEY(role_id, permission_id)
)