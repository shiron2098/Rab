START TRANSACTION;
CREATE TABLE IF NOT EXISTS job_history (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    job_id INTEGER NOT NULL,
    command_name VARCHAR(100)NOT NULL,
    operator_id INTEGER NOT NULL,
    operator_name VARCHAR(100) NOT NULL,
    software_provider VARCHAR(50) NOT NULL,
    execute_start_time_dt DATETIME(3) NOT NULL,
    execute_end_time_dt DATETIME(3),
    status VARCHAR (10) NOT NULL,
    description VARCHAR(255) NOT NULL
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX Indexjob_history on job_history(job_id,execute_start_time_dt,execute_end_time_dt,status);
COMMIT;
