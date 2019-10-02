CREATE TABLE IF NOT EXISTS maintenance.executed_one_time_scripts
(
   id                SERIAL           NOT NULL CONSTRAINT pk_maintenance_executedonetimescripts_id PRIMARY KEY,
   script_name       VARCHAR(200)     NOT NULL CONSTRAINT u_maintenance_executedonetimescripts_scriptname UNIQUE,
   create_datetime   TIMESTAMPTZ(4)   NOT NULL CONSTRAINT def_maintenance_executedonetimescripts_createdatetime DEFAULT(CURRENT_TIMESTAMP)  
)