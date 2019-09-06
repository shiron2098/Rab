if object_id('T2S_POS_Info_BI', 'U' ) IS NOT NULL 
Begin 
drop table T2S_POS_Info_BI
end
go
create table T2S_POS_Info_BI (
  operator_id       int         not null
 ,pos_code			varchar(64) null
 ,pos_description   varchar(128)    null
 ,veq_code          varchar(64) null
 ,veq_description   varchar(128)    null
 ,loc_code          varchar(64) null
 ,loc_description   varchar(128)    null
 ,cus_code          varchar(164)     null
 ,cus_description   varchar(128)     null
 ,pos_source_id     int null
 ,pos_id            int null
 ,veq_source_id     int null
 ,veq_id            int null
 ,loc_source_id     int null
 ,loc_id            int null
 ,cus_source_id     int null
 ,cus_id            int null
 ,changed_or_new    varchar(10)  null  --'changed', 'new', 'not changed'
 ,created_dt        datetime null
 ,batch_id          uniqueidentifier null
 ,record_id         int identity(1,1) 
 )
go
create unique clustered index IX_t2s_exportPos_POS_Date ON T2S_POS_Info_BI(created_dt, pos_source_id, pos_id)
go

if object_id('T2S_PRO_Info_BI', 'U' ) IS NOT NULL 
Begin 
drop table T2S_PRO_Info_BI 
end
go

create table T2S_PRO_Info_BI (
  operator_id       int         not null
 ,pro_code			varchar(64) null
 ,pro_description   varchar(128) null
 ,pdf_code          varchar(64) null
 ,pdf_description   varchar(128) null
 ,pro_source_id     int null
 ,pro_id            int null
 ,pdf_source_id     int null
 ,pdf_id            int null
 ,changed_or_new    varchar(10)  null  --'changed', 'new', 'not changed'
 ,created_dt        datetime null
 ,batch_id          uniqueidentifier null
 ,record_id         int identity(1,1) 
 )
go
create unique clustered index IX_t2s_exportPRO_PRO_Date ON T2S_PRO_Info_BI(created_dt, pro_source_id, pro_id)
go

if object_id('T2S_visit_Info_BI', 'U' ) IS NOT NULL 
Begin 
drop table T2S_visit_Info_BI 
end
go

create table T2S_visit_Info_BI (
  operator_id       int         not null
 ,pos_source_id     int null
 ,pos_id            int null
 ,visit_date        datetime null
 ,vvs_source_id     int null
 ,vvs_id            int null
 ,sco_source_id     int null
 ,sco_id            int null
 ,scheduled         varchar(3)  null  -- yes/no
 ,serviced         varchar(3)  null  -- yes/no
 ,collect         varchar(3)  null  -- yes/no
 ,actual_Sales_Bills decimal(16,2)   -- total collect, NULL if service
 ,actual_Sales_Coins decimal(16,2)   -- total collect, NULL if service
 ,number_of_columns int null
 ,col_sold_out      int null    -- number of sold out columns when driver started service
 ,pro_sold_out      int null    -- number of sold out pro when driver started service
 ,col_empty_after   int null    -- number of sold out columns when driver ended service
 ,pro_empty_after   int null    -- number of sold out pro when driver ended service
 ,not_picked        int null    -- number of items not packed (when added < pre-kit number in service order)
 ,changed_or_new    varchar(10) null  --'changed', 'new', 'not changed'
 ,record_status     varchar(10) null  -- voided, posted
 ,created_dt        datetime null
 ,batch_id          uniqueidentifier null
 ,successful_import  varchar(3) null  -- yes/no
 ,record_id         int identity(1,1) 
 )
go
create unique clustered index IX_t2s_exportVisits_vvs_Date ON T2S_visit_Info_BI(created_dt, vvs_source_id, vvs_id)
go
create index IX_t2s_exportVisits_date_vvs ON T2S_visit_Info_BI(vvs_source_id, vvs_id, created_dt)
go
