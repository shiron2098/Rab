DO $$
BEGIN
    DELETE
    FROM "user".users AS u
    USING role.roles AS r
    WHERE
        u.role_id = r.id
        AND
        u.organization_global_key IS NULL
        AND
        r.name <> 'admin';
END$$;