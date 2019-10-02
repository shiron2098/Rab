DROP VIEW IF EXISTS "user".users_view CASCADE;

CREATE VIEW "user".users_view AS
SELECT
    u.user_global_key           AS user_global_key,
    u.organization_global_key   AS organization_global_key,
    u.email                     AS email,
    u.first_name                AS first_name,
    u.last_name                 AS last_name,
    u.work_phone                AS work_phone,
    u.mobile_phone              AS mobile_phone,
    u.fax                       AS fax,
    u.time_zone_name            AS time_zone_name,
    u.password_hash             AS password_hash,
    r.name                      AS role_name
FROM "user".users u
INNER JOIN role.roles r on r.id = u.role_id


/*
	SELECT * FROM "user".users_view
*/