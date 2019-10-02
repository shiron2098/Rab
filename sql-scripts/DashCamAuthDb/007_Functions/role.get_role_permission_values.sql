DROP FUNCTION IF EXISTS role.get_role_permission_values(VARCHAR);

CREATE FUNCTION role.get_role_permission_values
(
    _role_name VARCHAR
)
RETURNS TABLE (name VARCHAR, is_enabled BOOL)
AS 
$$

    SELECT
        p.name          AS name,
        pv.is_enabled   AS is_enabled
    FROM role.roles AS r
    JOIN role.permission_values AS pv ON pv.role_id = r.id
    JOIN role.permissions AS p ON p.id = pv.permission_id
    WHERE r.name = _role_name

$$ LANGUAGE sql;