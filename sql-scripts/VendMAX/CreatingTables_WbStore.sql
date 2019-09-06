--Complete Get Products with correct XML

--truncate table T2S_POS_Info_WbStore
--select count (1) from T2S_POS_Info_WbStore

if object_id('T2S_POS_Info_WbStore', 'U' ) IS NOT NULL 
Begin 
drop table T2S_POS_Info_WbStore
end
go
create table T2S_POS_Info_WbStore (
     operator_id       int         not null
    ,pos_source_Id          int NULL
    ,pos_id                 int NULL
    ,customer_name          varchar(64) NULL
    ,user_email             varchar(256) NULL
    ,POS_Price              varchar(16) NULL
    ,last_name              varchar(64) NULL
    ,first_name             varchar(64) NULL
    ,billing_first_name     varchar(64) NULL
    ,billing_last_name      varchar(64) NULL
    ,billing_company        varchar(64) NULL
    ,billing_email          varchar(64) NULL
    ,billing_phone          varchar(16) NULL
    ,billing_country        varchar(64) NULL
    ,billing_addr1          varchar(64) NULL
    ,billing_addr2          varchar(64) NULL
    ,billing_city           varchar(64) NULL
    ,billing_state          varchar(4) NULL
    ,billing_zip            varchar(12) NULL
    ,ship_to_first_name     varchar(64) NULL
    ,ship_to_last_name      varchar(64) NULL
    ,ship_to_company        varchar(64) NULL
    ,ship_to_email          varchar(64) NULL
    ,ship_to_phone          varchar(16) NULL
    ,ship_to_addr1          varchar(64) NULL
    ,ship_to_addr2          varchar(64) NULL
    ,ship_to_zip            varchar(12) NULL
    ,ship_to_city           varchar(64) NULL
    ,ship_to_state          varchar(4) NULL
    ,ship_to_country        varchar(64) NULL
    ,tax_exampt             varchar(16) NULL
    ,next_delivery_date     varchar(16) NULL
    ,cus_id                 varchar(16) NULL
    ,loc_id                 varchar(16) NULL
    ,user_peo_id            varchar(16) NULL
    ,bill_to_id             varchar(16) NULL
     ,changed_or_new    varchar(10)  null  --'changed', 'new', 'not changed'
     ,created_dt        datetime null
     ,batch_id          uniqueidentifier null
     ,record_id         int identity(1,1) 
 )
go
--create unique clustered index IX_t2s_exportPos_POS_Date ON T2S_POS_Info_WbStore()
go

if object_id('T2S_PRO_Info_WbStore', 'U' ) IS NOT NULL 
Begin 
drop table T2S_PRO_Info_WbStore 
end
go

create table T2S_PRO_Info_WbStore (
  operator_id       int         not null
 ,pro_code			varchar(64) null
 ,pro_description   varchar(128) null
 ,pdf_description   varchar(128) null
 ,pro_source_id     int null
 ,pro_id            int null
 ,pdf_source_id     int null
 ,pdf_id            int null
 ,pkp_info          xml null
 ,changed_or_new    varchar(10)  null  --'changed', 'new', 'not changed'
 ,created_dt        datetime null
 ,batch_id          uniqueidentifier null
 ,record_id         int identity(1,1) 
 )
go
create unique clustered index IX_t2s_exportPRO_PRO_Date ON T2S_PRO_Info_WbStore(pro_source_id, pro_id)
go
