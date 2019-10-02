DROP FUNCTION IF EXISTS "user".tr_func_user_users_insupd_history() CASCADE;

CREATE FUNCTION "user".tr_func_user_users_insupd_history()
RETURNS trigger AS
$$
BEGIN
    INSERT INTO "user".users_history
    (
        user_id,
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
    VALUES
    (
        NEW.id,
        NEW.user_global_key,
        NEW.organization_global_key,
        NEW.role_id,
        NEW.email,
        NEW.first_name,
        NEW.last_name,
        NEW.work_phone,
        NEW.mobile_phone,
        NEW.fax,
        NEW.time_zone_name,
        NEW.password_hash
    );

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tr_user_users_insupd_history
AFTER INSERT OR UPDATE ON "user".users
FOR EACH ROW
EXECUTE PROCEDURE "user".tr_func_user_users_insupd_history();