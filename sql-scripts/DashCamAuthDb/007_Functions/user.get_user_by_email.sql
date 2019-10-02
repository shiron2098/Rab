DROP FUNCTION IF EXISTS "user".get_user_by_email(VARCHAR);

CREATE FUNCTION "user".get_user_by_email(_email VARCHAR)
RETURNS SETOF "user".users_view
AS 
$$

    SELECT * 
    FROM "user".users_view u
    WHERE LOWER(u.email) = LOWER(_email)

$$ LANGUAGE sql;