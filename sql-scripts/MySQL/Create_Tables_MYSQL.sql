CREATE TABLE IF NOT EXISTS operators (
  ops_record_id         INT AUTO_INCREMENT
 ,operator_name     varchar(64) UNIQUE  -- Unique
 ,operator_id       int   UNIQUE       -- unique
 ,operator_software varchar(64)
 ,record_timestamp	timestamp
 ,PRIMARY KEY (ops_record_id)
 )  ENGINE=INNODB;



CREATE TABLE IF NOT EXISTS points_of_sale (  -- for this table command: get_pos_bi   ; SQL: exec t2s_exportPos 'Yes'
  pos_record_id INT AUTO_INCREMENT
 ,operator_id       int
 ,pos_code			varchar(64) null  -- 
 ,pos_description   varchar(128)    null
 ,veq_code          varchar(64) null
 ,veq_description   varchar(128)    null
 ,loc_code          varchar(64) null
 ,loc_description   varchar(128)    null
 ,cus_code          varchar(164)     null
 ,cus_description   varchar(128)     null
 ,pos_id            varchar(32) null        -- update pos_id
 ,veq_id            varchar(32) null
 ,loc_id            varchar(32) null
 ,cus_id            varchar(32)null
 ,created_dt        datetime null
 ,batch_id          varchar(60) null
 ,record_timestamp	timestamp
 ,PRIMARY KEY (pos_record_id)
 )  ENGINE=INNODB;
 --
 /*

*/

 
 CREATE TABLE IF NOT EXISTS products (  -- for this table command: get_pro_bi   ; SQL: exec t2s_exportPRO 'Yes'
  pro_record_id INT AUTO_INCREMENT
 ,operator_id       int
 ,pro_code			varchar(64) null 
 ,pro_description   varchar(128) null
 ,pdf_code          varchar(64) null
 ,pdf_description   varchar(128) null
 ,pro_id            varchar(32) null  -- update using pro_id
 ,pdf_id            varchar(32) null
 ,created_dt        datetime null
 ,batch_id          varchar(60) null
 ,record_timestamp	timestamp
 ,PRIMARY KEY (pro_record_id)
 )  ENGINE=INNODB;
 

-- DROP TABLE IF EXISTS visits 
 CREATE TABLE IF NOT EXISTS visits (     -- for this table command: get_vvs_bi   ; SQL: exec t2s_exportVisits
  vvs_record_id INT AUTO_INCREMENT
 ,operator_id       int
 ,pos_id            varchar(32) null
 ,visit_date        datetime null
 ,week_num			int(2)
 ,month_num			int(2)
 ,vvs_id            varchar(32) null        -- update using vvs_id
 ,sco_id            varchar(32) null
 ,scheduled         varchar(3)  null  -- yes/no
 ,serviced			varchar(3)	null	-- yes/no
 ,collect			varchar(3)	null  -- yes/no
 ,actual_Sales_Bills decimal(16,2)   -- total collect, NULL if service
 ,actual_Sales_Coins decimal(16,2)   -- total collect, NULL if service
 ,number_of_columns int null		-- total number of columns in vending machine. 
 ,col_sold_out      int null    -- number of sold out columns when driver started service
 ,pro_sold_out      int null    -- number of sold out pro when driver started service
 ,col_empty_after   int null    -- number of sold out columns when driver ended service
 ,pro_empty_after   int null    -- number of sold out pro when driver ended service
 ,not_picked        int null    -- number of items not packed (when added < pre-kit number in service order)
 ,created_dt        datetime null
 ,batch_id          varchar(60) null
 ,record_timestamp	timestamp default now()
 ,PRIMARY KEY (vvs_record_id)
 )  ENGINE=INNODB;
 

 create table if not exists xml_log (
  log_record_id INT AUTO_INCREMENT
 ,operator_id       int
 ,command_type varchar(32) null  --
 ,xml_value longtext null
 ,batch_id          varchar(60) null
 ,record_timestamp	timestamp
 ,PRIMARY KEY (log_record_id)
 )  ENGINE=INNODB;
 