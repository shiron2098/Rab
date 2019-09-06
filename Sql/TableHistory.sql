START TRANSACTION;
CREATE TABLE IF NOT EXISTS job_history (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    job_id INTEGER NOT NULL,
    execute_dt TIMESTAMP NOT NULL,
    status VARCHAR (10) NOT NULL
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX IndexHistory on job_history(execute_dt,status);
COMMIT;



--Create Database t2s_bi_dashboard
--insert every retuned xml into xml_log

--need to fill in table Operators with correct data from Vendmax from cp_extension table.
