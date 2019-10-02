DROP FUNCTION IF EXISTS auth.get_refresh_token_by_user_global_key(VARCHAR);

CREATE FUNCTION auth.get_refresh_token_by_user_global_key(_user_global_key VARCHAR)
RETURNS SETOF auth.refresh_tokens
AS 
$$

    SELECT
        t.user_global_key       AS user_global_key,
        t.token_key             AS token_key,
        t.create_datetime_utc   AS create_datetime_utc 
    FROM auth.refresh_tokens t
    WHERE
        t.user_global_key = _user_global_key

$$ LANGUAGE sql;