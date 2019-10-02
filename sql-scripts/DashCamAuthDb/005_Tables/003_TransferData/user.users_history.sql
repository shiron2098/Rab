DO $$
BEGIN
    DELETE
    FROM "user".users_history AS h
    USING "user".users_history AS h2
    LEFT OUTER JOIN "user".users AS u ON u.id = h2.user_id
    WHERE
        h.id = h2.id
        AND
        u.id IS NULL;
END$$;


DO $$
BEGIN
    IF NOT EXISTS 
	(
		SELECT 1
        FROM "user".users_history
        FETCH FIRST 1 rows only
    )
    THEN
        INSERT INTO "user".users_history
        (
            user_id,
            user_global_key,
            organization_global_key,
            role_id,
            email,
            first_name,
            last_name,
            work_phone,
            mobile_phone,
            fax,
            time_zone_name,
            password_hash
        )
        SELECT
            u.id,
            u.user_global_key,
            u.organization_global_key,
            u.role_id,
            u.email,
            u.first_name,
            u.last_name,
            u.work_phone,
            u.mobile_phone,
            u.fax,
            u.time_zone_name,
            u.password_hash
        FROM "user".users u;
    END IF;
END$$;