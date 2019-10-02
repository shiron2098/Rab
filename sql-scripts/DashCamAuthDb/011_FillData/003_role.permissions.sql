DO $$
DECLARE
    enabledAdmin    CONSTANT BOOL DEFAULT TRUE;
    enabledManager  CONSTANT BOOL DEFAULT TRUE;
    enabledDriver   CONSTANT BOOL DEFAULT TRUE;
    disabledAdmin   CONSTANT BOOL DEFAULT FALSE;
    disabledManager CONSTANT BOOL DEFAULT FALSE;
    disabledDriver  CONSTANT BOOL DEFAULT FALSE;
BEGIN
    CREATE TEMP TABLE temp_role_permissions
    (
        permission_name     VARCHAR(200),
        is_enabled_admin    BOOL,
        is_enabled_manager  BOOL,
        is_enabled_driver   BOOL
    );
    
    INSERT INTO temp_role_permissions (permission_name, is_enabled_admin, is_enabled_manager, is_enabled_driver)
    SELECT 'site.login',                          enabledAdmin, enabledManager,  disabledDriver UNION ALL
    SELECT 'site.organization.create',            enabledAdmin, disabledManager, disabledDriver UNION ALL
    SELECT 'site.organization.edit.all',          enabledAdmin, disabledManager, disabledDriver UNION ALL
    SELECT 'site.organization.view.all',          enabledAdmin, disabledManager, disabledDriver UNION ALL
    SELECT 'site.user.view.all',                  enabledAdmin, disabledManager, disabledDriver UNION ALL
    SELECT 'site.camera.view.all',                enabledAdmin, disabledManager, disabledDriver UNION ALL
    SELECT 'site.camera.create.unassigned',       enabledAdmin, disabledManager, disabledDriver;
 
 
    DELETE
    FROM role.permissions AS p
    WHERE p.name NOT IN (SELECT permission_name FROM temp_role_permissions);
    
    
    INSERT INTO role.permissions(name)
    SELECT
        permission_name
    FROM temp_role_permissions
    ON CONFLICT (name)
    DO NOTHING;


    INSERT INTO role.permission_values
    (
        role_id,
        permission_id,
        is_enabled
    )
    SELECT
        r.id,
        p.id,
        CASE
            WHEN r.name = 'admin'   THEN t.is_enabled_admin
            WHEN r.name = 'manager' THEN t.is_enabled_manager
            WHEN r.name = 'driver'  THEN t.is_enabled_driver
        END
    FROM role.permissions AS p
    JOIN temp_role_permissions AS t ON t.permission_name = p.name
    CROSS JOIN role.roles AS r
    ON CONFLICT (role_id, permission_id)
    DO UPDATE
    SET
        is_enabled = EXCLUDED.is_enabled;
    
    DROP TABLE IF EXISTS temp_role_permissions;
    
END$$;




