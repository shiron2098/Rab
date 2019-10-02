DO $$
BEGIN
    IF NOT EXISTS 
	(
		SELECT 1
        FROM "user".users u
        INNER JOIN role.roles r ON r.id = u.role_id
        WHERE r.name = 'admin'
    )
    THEN
        INSERT INTO "user".users
        (
        	user_global_key,
        	role_id,
        	email,
        	time_zone_name,
        	password_hash
        )
        SELECT
        	uuid_generate_v4()::text,
        	roles.id, 
        	'admin@fake.com',
        	'Eastern Standard Time',
        	'AAAAAQAAE4gAAAAQ0T1VKP5x23oztnRdR90Gfxsk2Z8lYOKHV57q7dUuSfFPnZYhuK93DarzHa3Pgcm6'
        FROM role.roles
        WHERE roles.name = 'admin'
        ON CONFLICT (LOWER(email))
        DO 
        UPDATE
        SET
            role_id = EXCLUDED.id,
            organization_global_key = NULL;
    END IF;
END$$;




