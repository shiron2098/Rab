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
  FOREIGN KEY (Userid)  REFERENCES Operator (Operatorid)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
insert into Product (Code,Description,TimeTaskUpdated,Userid,DBNAME) values ('rterterter','2343453','1561148406','23','Product');
CREATE TABLE IF NOT EXISTS TableDate (
       id INTEGER PRIMARY KEY AUTO_INCREMENT,
       Monday INTEGER,
       Tuesday INTEGER,
       Wednesday INTEGER,
       Thursday INTEGER,
       Friday INTEGER,
       Saturday INTEGER,
       Sunday INTEGER,
       UserTimeId INTEGER unique
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE TABLE IF NOT EXISTS JobScheduler (
     id INTEGER PRIMARY KEY AUTO_INCREMENT,
     StartScheduler Varchar(50),
     Scheduler varchar(255),
     SQL_ZAP VARCHAR(150),
     Userid INTEGER,
     FOREIGN KEY (Userid)  REFERENCES Operator (Operatorid),
     FOREIGN KEY (id)  REFERENCES TableDate (UserTimeId)
)ENGINE InnoDB DEFAULT CHARSET = UTF8;
CREATE INDEX OperIndex on Operator(Operatorid);
CREATE INDEX  CodeAndDecriptionIndex on Product(Code,Description);
CREATE INDEX  NameAndPasswordIndex on Operator(Name,Password);
