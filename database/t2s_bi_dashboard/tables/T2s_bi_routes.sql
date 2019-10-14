USE t2s_bi_dashboard;

drop table if exists routes;

CREATE TABLE IF NOT EXISTS routes(
  operator_id       int         not null
 ,rte_code			varchar(64) null
 ,rte_description   varchar(128)    null
 ,rte_source_id            int null
 ,rte_id            int null
 ,changed_or_new    varchar(10)  null
 ,created_dt        datetime null
 ,batch_id      varchar(60)     null
 ,record_id         int null
)  ENGINE=INNODB;