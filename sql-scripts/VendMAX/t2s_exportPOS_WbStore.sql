-- truncate table T2S_POS_Info_WbStore
if object_id('t2s_exportPOS_WbStore', 'P' ) IS NOT NULL 
Begin 
drop proc t2s_exportPOS_WbStore
end


go
-- returns all or only changed Pro


create proc t2s_exportPOS_WbStore (@changes_only varchar(3) = 'Yes')  -- Yes/No NULL = Yes ; @return_result - to return xml or not. Yes/No, NULL = Yes. 
as
begin

declare @curr_dt    datetime = getdate()
declare @new_id     uniqueidentifier = newid()
declare @operator_id int

set @changes_only = coalesce(@changes_only, 'Yes')

if not exists (select 1 from T2S_POS_Info_WbStore) 
begin
    set @changes_only ='No'
end

select @operator_id = operator_id from cp_extension

-- delete records to exclude table overflow
-- TODO: identify root cause 
truncate table T2S_POS_Info_WbStore

    -- save last snapshot
	select * into #old_T2S_POS_Info_WbStore from T2S_POS_Info_WbStore
--    create unique clustered index #IX_t2s_exportPRO_PRO_Date ON #old_T2S_POS_Info_WbStore(pos_source_id, pos_id)

    truncate table T2S_POS_Info_WbStore
    
    select 
        pos.pos_source_id
        ,pos.pos_id
        ,cus.description + ' POS: ' + coalesce(pos.description, '')                         as customer_name
        ,coalesce(peo.e_mail, '')                                                           as user_email
        ,convert(varchar(16), pos.pos_source_id) + ':' + convert(varchar(16), pos.pos_id)   as POS_Price
        ,coalesce(peo.last_name, '')                                                        as last_name
        ,coalesce(peo.first_name, '')                                                       as first_name
        --billing info
         ,COALESCE(loc_peo.first_name, cus_peo.first_name, nat_peo.first_name, '''')                    AS billing_first_name
         ,COALESCE(loc_peo.last_name, cus_peo.last_name, nat_peo.last_name, '''')                       AS billing_last_name
         ,COALESCE(loc_bill.ma_addr1, cus_bill.ma_addr1, nat_bill.ma_addr1, '''')                       AS billing_company
         ,COALESCE(loc_bill.e_mail, cus_bill.e_mail, nat_bill.e_mail, '''')                              AS billing_email
         ,COALESCE(loc_bill.main_phone, cus_bill.main_phone, nat_bill.main_phone, '''')                  AS billing_phone
         ,COALESCE(loc_bill.ma_country, cus_bill.ma_country, nat_bill.ma_country, '''')                 AS billing_country
         ,COALESCE(loc_bill.ma_addr2, cus_bill.ma_addr2, nat_bill.ma_addr2, '''')                       AS billing_addr1
         ,COALESCE(loc_bill.ma_addr3, cus_bill.ma_addr3, nat_bill.ma_addr3, '''')                       AS billing_addr2
         ,COALESCE(loc_bill.ma_city, cus_bill.ma_city, nat_bill.ma_city, '''')                          AS billing_city
         ,COALESCE(loc_bill.ma_state, cus_bill.ma_state, nat_bill.ma_state, '''')                       AS billing_state
         ,COALESCE(loc_bill.ma_zip, cus_bill.ma_zip, nat_bill.ma_zip, '''')                             AS billing_zip
        --ship to
        ,coalesce(ship_peo.first_name, '')                                                              AS ship_to_first_name
        ,coalesce(ship_peo.last_name, '')                                                              AS ship_to_last_name
        ,loc.addr1                                                                                      AS ship_to_company
        ,loc.e_mail                                                                                     AS ship_to_email
        , loc.main_phone                                                                                 AS ship_to_phone
        , loc.addr2                                                                                      AS ship_to_addr1
        , loc.addr3                                                                                      AS ship_to_addr2
        , loc.zip                                                                                        AS ship_to_zip
        , loc.city                                                                                       AS ship_to_city
        , loc.state                                                                                      AS ship_to_state
        , loc.country																					as ship_to_country
         --Now we check who to bill to (the "sold to" field on the invoice)
        ,case when COALESCE(loc_bill.tax_id_number, cus_bill.tax_id_number, nat_bill.tax_id_number, '''') <>'' 
                    then 'exampt' else 'not' end                                                            as tax_exampt
        ,'2019-01-01'                                                                                       as next_delivery_date
        ,convert(varchar(16), cus.cus_source_id) + ':' + convert(varchar(16), cus.cus_id)                   as cus_id
        ,convert(varchar(16), loc.loc_source_id) + ':' + convert(varchar(16), loc.loc_id)                   as loc_id
        ,convert(varchar(16), peo.peo_source_id) + ':' + convert(varchar(16), peo.peo_id)                   as user_peo_id
        ,convert(varchar(16), pos.bill_to_source_id) + ':' + convert(varchar(16), pos.bill_to_id) 
                    + ':' + coalesce(pos.bill_to, '')                                                       as bill_to_id
        ,row_number() over(partition by convert(varchar(16), peo.peo_source_id) + ':' + convert(varchar(16), peo.peo_id) 
                            order by cus.description + 'POS: ' + coalesce(pos.description, '')
                                    ,coalesce(peo.e_mail, cus.description) )                                as user_num
    into #new_T2S_POS_Info_WbStore 
    from points_of_sale pos
        LEFT OUTER JOIN dbo.loc_full_info loc --WITH (NOLOCK)
            ON
                     loc.loc_source_id          = pos.loc_source_id
              AND    loc.loc_id                 = pos.loc_id
        left outer join people ship_peo --WITH (NOLOCK)
            ON       loc.Primary_Contact_PEO_ID = ship_peo.peo_id and loc.Primary_Contact_PEO_Source_ID = ship_peo.peo_source_id      
        LEFT OUTER JOIN dbo.customers cus --WITH (NOLOCK)
            ON
                     cus.cus_source_id          = loc.cus_source_id
              AND    cus.cus_id                 = loc.cus_id
        left outer join people peo --WITH (NOLOCK)
            ON
                     peo.master_table_code = 'cus'
                and peo.master_source_id = cus.cus_source_id
                and peo.master_id = cus.cus_id
        LEFT OUTER JOIN dbo.loc_full_info loc_bill --WITH (NOLOCK)
          ON
                     pos.bill_to_source_id      = loc_bill.loc_source_id
              AND    pos.bill_to_id             = loc_bill.loc_id
              AND    pos.bill_to                = 'L'
        left outer join people loc_peo
          ON        loc_bill.Primary_Contact_PEO_ID = loc_peo.peo_id and loc_bill.Primary_Contact_PEO_Source_ID = loc_peo.peo_source_id      
        LEFT OUTER JOIN dbo.cus_full_info cus_bill --WITH (NOLOCK)
          ON
                     pos.bill_to_source_id      = cus_bill.cus_source_id
              AND    pos.bill_to_id             = cus_bill.cus_id
              AND    pos.bill_to                = 'C'
        left outer join people cus_peo
          ON        cus_bill.Primary_Contact_PEO_ID = cus_peo.peo_id and cus_bill.Primary_Contact_PEO_Source_ID = cus_peo.peo_source_id
        LEFT OUTER JOIN dbo.nat_full_info nat_bill --WITH (NOLOCK)
          ON
                     pos.bill_to_source_id      = nat_bill.nat_source_id
              AND    pos.bill_to_id             = nat_bill.nat_id
              AND    pos.bill_to                = 'N'
        left outer join people nat_peo
          ON        nat_bill.Primary_Contact_PEO_ID = nat_peo.peo_id and nat_bill.Primary_Contact_PEO_Source_ID = nat_peo.peo_source_id       


--select user_num, * from #new_T2S_POS_Info_WbStore  
--where customer_name like '%Quick%'
--order by user_email


    insert into T2S_POS_Info_WbStore
    select 
         @operator_id
        ,COALESCE(new.pos_source_Id            ,old.pos_source_Id)			        as pos_source_Id
        ,COALESCE(new.pos_id                   ,old.pos_id)     			        as pos_id
        ,COALESCE(new.customer_name            ,old.customer_name)     		        as customer_name
        ,COALESCE(replace(
                    (case when new.user_email = ''
                            then replace(new.user_peo_id, ':', '') + case when new.user_num < 2 
                                then '' 
                                else '_' + convert(varchar(8), new.user_num ) 
                            end + '@' + new.customer_name 
                            else new.user_email 
                        end
                     ), ' ', '')
                     ,old.user_email)     		                                    as user_email
        ,COALESCE(new.POS_Price                ,old.POS_Price)                      as POS_Price
        ,COALESCE(new.last_name                ,old.last_name)                      as last_name
        ,COALESCE(new.first_name               ,old.first_name)                     as first_name
											  
		,COALESCE(new.billing_first_name       ,old.billing_first_name)             AS billing_first_name
        ,COALESCE(new.billing_last_name        ,old.billing_last_name)              AS billing_last_name
        ,COALESCE(new.billing_company          ,old.billing_company)                AS billing_company
        ,COALESCE(new.billing_email            ,old.billing_email)                  AS billing_email
        ,COALESCE(new.billing_phone            ,old.billing_phone)                  AS billing_phone
        ,COALESCE(new.billing_country          ,old.billing_country)                AS billing_country
        ,COALESCE(new.billing_addr1            ,old.billing_addr1)                  AS billing_addr1
        ,COALESCE(new.billing_addr2            ,old.billing_addr2)                  AS billing_addr2
        ,COALESCE(new.billing_city             ,old.billing_city)                   AS billing_city
        ,COALESCE(new.billing_state            ,old.billing_state)                  AS billing_state
        ,COALESCE(new.billing_zip              ,old.billing_zip)                    AS billing_zip
											  
		,COALESCE(new.ship_to_first_name       ,old.ship_to_first_name)             AS ship_to_first_name
        ,COALESCE(new.ship_to_last_name        ,old.ship_to_last_name)              AS ship_to_last_name
        ,COALESCE(new.ship_to_company          ,old.ship_to_company)                AS ship_to_company
        ,COALESCE(new.ship_to_email            ,old.ship_to_email)                  AS ship_to_email
        ,COALESCE(new.ship_to_phone            ,old.ship_to_phone)                  AS ship_to_phone
        ,COALESCE(new.ship_to_addr1            ,old.ship_to_addr1)                  AS ship_to_addr1
        ,COALESCE(new.ship_to_addr2            ,old.ship_to_addr2)                  AS ship_to_addr2
        ,COALESCE(new.ship_to_zip              ,old.ship_to_zip)                    AS ship_to_zip
        ,COALESCE(new.ship_to_city             ,old.ship_to_city)                   AS ship_to_city
        ,COALESCE(new.ship_to_state            ,old.ship_to_state)                  AS ship_to_state
        ,COALESCE(new.ship_to_country          ,old.ship_to_country)                as ship_to_country
											  
		,COALESCE(new.tax_exampt               ,old.tax_exampt)                     as tax_exampt
        ,COALESCE(new.next_delivery_date       ,old.next_delivery_date)             as next_delivery_date
        ,COALESCE(new.cus_id                   ,old.cus_id)                         as cus_id
        ,COALESCE(new.loc_id                   ,old.loc_id)                         as loc_id
        ,COALESCE(new.user_peo_id              ,old.user_peo_id)                    as user_peo_id
        ,COALESCE(new.bill_to_id               ,old.bill_to_id)                     as bill_to_id
        
     ,Case 
                when new.pos_source_Id is null then 'deleted'
                when old.pos_source_id is null then 'new'
                when 
                            coalesce(old.customer_name        ,'')  <>   coalesce(new.customer_name        ,'')
                         or coalesce(old.user_email           ,'')  <>   coalesce(new.user_email           ,'')
                         or coalesce(old.POS_Price            ,'')  <>   coalesce(new.POS_Price            ,'')
                         or coalesce(old.last_name            ,'')  <>   coalesce(new.last_name            ,'')
                         or coalesce(old.first_name           ,'')  <>   coalesce(new.first_name           ,'')
                         or coalesce(old.billing_first_name   ,'')  <>   coalesce(new.billing_first_name   ,'')
                         or coalesce(old.billing_last_name    ,'')  <>   coalesce(new.billing_last_name    ,'')
                         or coalesce(old.billing_company      ,'')  <>   coalesce(new.billing_company      ,'')
                         or coalesce(old.billing_email        ,'')  <>   coalesce(new.billing_email        ,'')
                         or coalesce(old.billing_phone        ,'')  <>   coalesce(new.billing_phone        ,'')
                         or coalesce(old.billing_country      ,'')  <>   coalesce(new.billing_country      ,'')
                         or coalesce(old.billing_addr1        ,'')  <>   coalesce(new.billing_addr1        ,'')
                         or coalesce(old.billing_addr2        ,'')  <>   coalesce(new.billing_addr2        ,'')
                         or coalesce(old.billing_city         ,'')  <>   coalesce(new.billing_city         ,'')
                         or coalesce(old.billing_state        ,'')  <>   coalesce(new.billing_state        ,'')
                         or coalesce(old.billing_zip          ,'')  <>   coalesce(new.billing_zip          ,'')
                         or coalesce(old.ship_to_first_name   ,'')  <>   coalesce(new.ship_to_first_name   ,'')
                         or coalesce(old.ship_to_last_name    ,'')  <>   coalesce(new.ship_to_last_name    ,'')
                         or coalesce(old.ship_to_company      ,'')  <>   coalesce(new.ship_to_company      ,'')
                         or coalesce(old.ship_to_email        ,'')  <>   coalesce(new.ship_to_email        ,'')
                         or coalesce(old.ship_to_phone        ,'')  <>   coalesce(new.ship_to_phone        ,'')
                         or coalesce(old.ship_to_addr1        ,'')  <>   coalesce(new.ship_to_addr1        ,'')
                         or coalesce(old.ship_to_addr2        ,'')  <>   coalesce(new.ship_to_addr2        ,'')
                         or coalesce(old.ship_to_zip          ,'')  <>   coalesce(new.ship_to_zip          ,'')
                         or coalesce(old.ship_to_city         ,'')  <>   coalesce(new.ship_to_city         ,'')
                         or coalesce(old.ship_to_state        ,'')  <>   coalesce(new.ship_to_state        ,'')
                         or coalesce(old.ship_to_country      ,'')  <>   coalesce(new.ship_to_country      ,'')
                         or coalesce(old.tax_exampt           ,'')  <>   coalesce(new.tax_exampt           ,'')
                         or coalesce(old.next_delivery_date   ,'')  <>   coalesce(new.next_delivery_date   ,'')
                         or coalesce(old.cus_id               ,'')  <>   coalesce(new.cus_id               ,'')
                         or coalesce(old.loc_id               ,'')  <>   coalesce(new.loc_id               ,'')
                         or coalesce(old.pos_id               ,'')  <>   coalesce(new.pos_id               ,'')
                         or coalesce(old.user_peo_id          ,'')  <>   coalesce(new.user_peo_id          ,'')
                         or coalesce(old.bill_to_id           ,'')  <>   coalesce(new.bill_to_id           ,'')
                            then 'changed'
                else 'same'
            end                                                                 AS changed_or_new
         ,@curr_dt                                                              AS created_dt        
         ,@new_id                                                               AS batch_id
    from #new_T2S_POS_Info_WbStore new
        full outer join #old_T2S_POS_Info_WbStore old
            on new.pos_source_id = old.pos_source_id and new.pos_id = old.pos_id


    select 
                     operator_id       
                    ,convert(varchar(16), pos_source_Id) + ':' + convert(varchar(16), pos_id) as pos_id       
                    ,customer_name       
                    ,user_email          
                    ,POS_Price           
                    ,last_name           
                    ,first_name          
                    ,billing_first_name  
                    ,billing_last_name   
                    ,billing_company     
                    ,billing_email       
                    ,billing_phone       
                    ,billing_country     
                    ,billing_addr1       
                    ,billing_addr2       
                    ,billing_city        
                    ,billing_state       
                    ,billing_zip         
                    ,ship_to_first_name  
                    ,ship_to_last_name   
                    ,ship_to_company     
                    ,ship_to_email       
                    ,ship_to_phone       
                    ,ship_to_addr1       
                    ,ship_to_addr2       
                    ,ship_to_zip         
                    ,ship_to_city        
                    ,ship_to_state       
                    ,ship_to_country     
                    ,tax_exampt          
                    ,next_delivery_date  
                    ,cus_id              
                    ,loc_id              
                    ,user_peo_id         
                    ,bill_to_id          
                    ,changed_or_new                                 
                    ,created_dt                                     
                    ,batch_id                                       
                    ,record_id                                       
    from T2S_POS_Info_WbStore
        where (@changes_only <> 'Yes' and changed_or_new <> 'deleted')
            or (@changes_only = 'Yes' and changed_or_new <> 'same')
    order by customer_name, user_email
        for xml auto
end
go

begin tran
--exec t2s_exportPOS_WbStore 'No'
--select top 1 * from T2S_POS_Info_WbStore order by user_email
--select count(1) from T2S_POS_Info_WbStore
--select top 1 * from pro_view
rollback
