DROP FUNCTION IF EXISTS site.get_users(INT, INT);

CREATE FUNCTION site.get_users(_offset INT, _count INT)
RETURNS SETOF site.users_view
AS 
$$

    SELECT u.*
    FROM site.users_view u
    WHERE u.role_name <> 'admin'
    ORDER BY u.email
    OFFSET _offset LIMIT _count
    
$$ LANGUAGE sql;

/*

SELECT site.get_users(0, 20)

*/