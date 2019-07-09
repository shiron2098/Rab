CREATE TABLE IF NOT EXISTS Operator (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
Operatorid INTEGER NOT NULL UNIQUE,
Name VARCHAR(100) NOT NULL,
Password varchar(100) NOT NULL,
Softprovider VARCHAR(30) NOT NULL,
TimeOper INTEGER NOT NULL,
connection_string VARCHAR(255),
DBNAME VARCHAR(15)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
insert into Operator (Operatorid,Name,Password,Softprovider,TimeOper,connection_string,DBNAME) values ('23','class2','2479465' ,'stylesoft','1561131406','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Operator');
CREATE TABLE IF NOT EXISTS Product (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
Code VARCHAR(255) NOT NULL,
Description VARCHAR(255) NOT NULL,
TimeTaskUpdated varchar(50),
Userid Integer NOT NULL,
DBNAME VARCHAR(15),
FOREIGN KEY (Userid) REFERENCES Operator (Operatorid)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
insert into Product (Code,Description,TimeTaskUpdated,Userid,DBNAME) values ('rterterter','2343453','1561148406','23','Product');
CREATE TABLE IF NOT EXISTS JobScheduler (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
StartScheduler INTEGER NOT NULL,
LastTake INTEGER,
SQL_ZAP VARCHAR(150) UNIQUE,
Userid INTEGER,
FOREIGN KEY (Userid) REFERENCES Operator (Operatorid)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE TABLE IF NOT EXISTS TableDate (
id INTEGER PRIMARY KEY AUTO_INCREMENT,
Monday INTEGER,
Tuesday INTEGER,
Wednesday INTEGER,
Thursday INTEGER,
Friday INTEGER,
Saturday INTEGER,
Sunday INTEGER,
Jobid INTEGER NOT NULL,
FOREIGN KEY (Jobid) REFERENCES JobScheduler (id)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE TABLE IF NOT EXISTS TableTimeDate (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Monday VARCHAR(30),
    Tuesday VARCHAR(30),
    Wednesday VARCHAR(30),
    Thursday VARCHAR(30),
    Friday VARCHAR(30),
    Saturday VARCHAR(30),
    Sunday VARCHAR(30),
    JobTimeid INTEGER NOT NULL,
    FOREIGN KEY (JobTimeid) REFERENCES TableDate (id)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX OperIndex on Operator(Operatorid);
CREATE INDEX CodeAndDecriptionIndex on Product(Code,Description);
CREATE INDEX NameAndPasswordIndex on Operator(Name,Password);
