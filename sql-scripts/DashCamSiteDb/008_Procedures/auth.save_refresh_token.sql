DROP PROCEDURE IF EXISTS auth.save_refresh_token(VARCHAR,VARCHAR,TIMESTAMP);

CREATE PROCEDURE auth.save_refresh_token
(
    _user_global_key     VARCHAR,
    _token_key           VARCHAR,
    _create_datetime_utc TIMESTAMP
)
AS 
$$
    INSERT INTO auth.refresh_tokens
    (
        user_global_key,
        token_key,
        create_datetime_utc
    )
    VALUES
    (
        _user_global_key,
        _token_key,
        _create_datetime_utc
    )
    ON CONFLICT (user_global_key)
    DO 
    UPDATE
    SET
        token_key = _token_key,
        create_datetime_utc = _create_datetime_utc;

$$ LANGUAGE sql;