START TRANSACTION;
CREATE TABLE IF NOT EXISTS operators (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL UNIQUE,
    code VARCHAR(100) NOT NULL,
    software_provider VARCHAR(100) NOT NULL,
    connection_url VARCHAR(100) NOT NULL,
    user_name VARCHAR (60) NOT NULL,
    user_password VARCHAR(100) NOT NULL
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    operator_id INTEGER NOT NULL,
    command VARCHAR(255) NOT NULL,
    last_execute_dt TIMESTAMP,
FOREIGN KEY (operator_id) REFERENCES operators (id)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE TABLE IF NOT EXISTS job_schedule (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    job_id INTEGER NOT NULL,
    execute_interval INTEGER,
FOREIGN KEY (job_id) REFERENCES jobs (id)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX OperIndex on jobs(operator_id,last_execute_dt);
CREATE INDEX CodeAndDecriptionIndex on job_schedule(job_id,execute_interval);
COMMIT;
