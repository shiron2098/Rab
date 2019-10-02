DO $$
BEGIN
    IF NOT EXISTS 
	(
		SELECT *
        FROM information_schema.table_constraints 
        WHERE table_schema = 'user' AND table_name = 'users' AND constraint_name = 'fk_user_users_role_roles_roleid'
    )
    THEN
        ALTER TABLE "user".users ADD CONSTRAINT fk_user_users_role_roles_roleid FOREIGN KEY (role_id) REFERENCES role.roles (id);
    END IF;
END$$;