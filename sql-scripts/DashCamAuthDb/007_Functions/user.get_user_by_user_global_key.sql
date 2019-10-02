DROP FUNCTION IF EXISTS "user".get_user_by_user_global_key(VARCHAR);

CREATE FUNCTION "user".get_user_by_user_global_key(_user_global_key VARCHAR)
RETURNS SETOF "user".users_view
AS 
$$

    SELECT * 
    FROM "user".users_view u
    WHERE u.user_global_key = _user_global_key

$$ LANGUAGE sql;