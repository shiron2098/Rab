CREATE TABLE IF NOT EXISTS Operators (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
Name VARCHAR(30) NOT NULL UNIQUE,
Code VARCHAR(100) NOT NULL,
Connection_Softprovider VARCHAR(100) NOT NULL,
Connection_Url VARCHAR(100) NOT NULL,
User_name VARCHAR (150) NOT NULL,
User_password VARCHAR(150)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
/*insert into Operators (Name,Code,Connection_Softprovider,Connection_Url,User_name,User_password) values ('Anton','class2','Vendmax' ,'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466');*/
CREATE TABLE IF NOT EXISTS Jobs (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
Operatorid INTEGER NOT NULL,
Command VARCHAR(255) NOT NULL,
last_execute_dt TIMESTAMP,
FOREIGN KEY (Operatorid) REFERENCES Operators (id)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
/*insert into Jobs (Operatorid,Command,last_execute_dt) values ('1','select pro.code,pro.description from Products pro',now());*/
CREATE TABLE IF NOT EXISTS job_scheduler (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
job_id INTEGER NOT NULL,
execute_interval INTEGER,
FOREIGN KEY (job_id) REFERENCES Jobs (id)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX OperIndex on Jobs(Operatorid);
CREATE INDEX CodeAndDecriptionIndex on job_scheduler(job_id,execute_interval);
