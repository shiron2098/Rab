DROP PROCEDURE IF EXISTS "user".save_user(VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR,VARCHAR);

CREATE PROCEDURE "user".save_user
(
    _user_global_key            VARCHAR,
    _organization_global_key    VARCHAR,
    _user_role                  VARCHAR,
    _email                      VARCHAR,
    _first_name                 VARCHAR,
    _last_name                  VARCHAR,
    _work_phone                 VARCHAR,
    _mobile_phone               VARCHAR,
    _fax                        VARCHAR,
    _time_zone_name             VARCHAR,
    _password_hash              VARCHAR
)
AS 
$$
    INSERT INTO "user".users
    (
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
        _user_global_key,
        _organization_global_key,
        r.id,
        _email,
        _first_name,
        _last_name,
        _work_phone,
        _mobile_phone,
        _fax,
        _time_zone_name,
        _password_hash
    FROM role.roles r
    WHERE r.name = _user_role
    ON CONFLICT (user_global_key) DO UPDATE
    SET
        organization_global_key = _organization_global_key,
        role_id = EXCLUDED.role_id,
        email = _email,
        first_name = _first_name,
        last_name = _last_name,
        work_phone = _work_phone,
        mobile_phone = _mobile_phone,
        fax = _fax,
        time_zone_name = _time_zone_name,
        password_hash = _password_hash,
        update_datetime_utc = NOW() AT TIME ZONE 'UTC'
$$ LANGUAGE sql;