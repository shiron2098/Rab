CREATE TABLE IF NOT EXISTS Code (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  Operatorid INTEGER NOT NULL UNIQUE,
  Name VARCHAR(255) NOT NULL,
  Softprovider VARCHAR(255) NOT NULL UNIQUE,
  TimeTask INTEGER NOT NULL,
  connection_string VARCHAR(255),
  SQL_ZAP VARCHAR(255)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
insert into Code (Operatorid,Name,Softprovider,TimeTask,connection_string,SQL_ZAP) values ('5234523','Operver','stylesoft','1561131406','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','select pro.code, pro.description from Products pro');
CREATE TABLE IF NOT EXISTS Product (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  Operatorid INTEGER NOT NULL UNIQUE,
  Name VARCHAR(255) NOT NULL,
  Softprovider VARCHAR(255) NOT NULL UNIQUE,
  TimeTask INTEGER NOT NULL,
  connection_string VARCHAR(255),
  SQL_ZAP VARCHAR(255)
) ENGINE InnoDB DEFAULT CHARSET = UTF8;
insert into Product (Operatorid,Name,Softprovider,TimeTask,connection_string,SQL_ZAP) values ('435234','Prods','stylesoft','1561148406','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','select pro.code from Products pro');
