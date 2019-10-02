DO $$
BEGIN
    IF NOT EXISTS 
	(
		SELECT *
        FROM information_schema.table_constraints 
        WHERE table_schema = 'role' AND table_name = 'permission_values' AND constraint_name = 'fk_role_permissionvalues_role_roles_roleid'
    )
    THEN
        ALTER TABLE role.permission_values 
        ADD CONSTRAINT fk_role_permissionvalues_role_roles_roleid 
        FOREIGN KEY (role_id) REFERENCES role.roles (id)
        ON DELETE CASCADE;
    END IF;
    
    IF NOT EXISTS 
	(
		SELECT *
        FROM information_schema.table_constraints 
        WHERE table_schema = 'role' AND table_name = 'permission_values' AND constraint_name = 'fk_role_permissionvalues_role_permissions_permissionid'
    )
    THEN
        ALTER TABLE role.permission_values 
        ADD CONSTRAINT fk_role_permissionvalues_role_permissions_permissionid 
        FOREIGN KEY (permission_id) REFERENCES role.permissions (id)
        ON DELETE CASCADE;
    END IF;
END$$;