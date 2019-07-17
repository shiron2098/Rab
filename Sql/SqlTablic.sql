CREATE TABLE IF NOT EXISTS operators (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL UNIQUE,
    code VARCHAR(100) NOT NULL,
    software_provider VARCHAR(100) NOT NULL,
    connection_url VARCHAR(100) NOT NULL,
    user_name VARCHAR (60) NOT NULL,
    user_password VARCHAR(100) NOT NULL
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX Indexoperators on operators(software_provider);

CREATE TABLE IF NOT EXISTS commands(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX CodeIndexCommands on commands(code);


 CREATE TABLE IF NOT EXISTS software_providers(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL,
    FOREIGN KEY (code) REFERENCES operators (software_provider)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX CodeSoftware_Providers on software_providers(code);

CREATE TABLE IF NOT EXISTS command_details(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    software_provider_id INTEGER NOT NULL,
    command_id  INTEGER NOT NULL,
    execute_statement VARCHAR (255) NOT NULL UNIQUE,
    FOREIGN KEY (software_provider_id) REFERENCES software_providers(id),
    FOREIGN KEY (command_id) REFERENCES commands(id)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX Indexcommand_details on command_details(software_provider_id,command_id);

CREATE TABLE IF NOT EXISTS jobs (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    operator_id INTEGER NOT NULL,
    last_execute_dt TIMESTAMP,
    command_id INTEGER NOT NULL,
    FOREIGN KEY (command_id) REFERENCES command_details (id),
    FOREIGN KEY (operator_id) REFERENCES operators (id)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;

CREATE INDEX JobIndex on jobs(operator_id,last_execute_dt);
CREATE INDEX OperatorIndexJobs on jobs(command_id);

CREATE TABLE IF NOT EXISTS job_schedule (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    job_id INTEGER NOT NULL,
    execute_interval INTEGER,
FOREIGN KEY (job_id) REFERENCES jobs (id)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;

CREATE INDEX CodeAndDecriptionIndex on job_schedule(job_id,execute_interval);
