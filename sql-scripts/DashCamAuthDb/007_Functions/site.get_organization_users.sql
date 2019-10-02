DROP FUNCTION IF EXISTS site.get_organization_users(VARCHAR, INT, INT);

CREATE FUNCTION site.get_organization_users(_organization_global_key VARCHAR, _offset INT, _count INT)
RETURNS SETOF site.users_view
AS 
$$

    SELECT u.*
    FROM site.users_view u
    WHERE u.organization_global_key = _organization_global_key
    ORDER BY u.email
    OFFSET _offset LIMIT _count
    
$$ LANGUAGE sql;

/*

SELECT site.get_organization_users('d9338ad5-6d6d-4947-b399-34aa15d13948', 0, 20)

*/