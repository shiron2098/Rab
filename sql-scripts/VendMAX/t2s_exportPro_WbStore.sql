
if object_id('t2s_exportPro_WbStore', 'P' ) IS NOT NULL 
Begin 
drop proc t2s_exportPro_WbStore
end


go
-- returns all or only changed Pro


create proc t2s_exportPro_WbStore (@changes_only varchar(3) = 'Yes')  -- Yes/No NULL = Yes 
as
begin

declare @curr_dt    datetime = getdate()
declare @new_id     uniqueidentifier = newid()
declare @operator_id int

set @changes_only = coalesce(@changes_only, 'Yes')

if not exists (select 1 from T2S_Pro_Info_WbStore) 
begin
    set @changes_only ='No'
end

select @operator_id = operator_id from cp_extension

    -- save last snapshot
    select * into #tmp_T2S_Pro_Info_WbStore from T2S_Pro_Info_WbStore
    create unique clustered index #IX_t2s_exportPRO_PRO_Date ON #tmp_T2S_Pro_Info_WbStore(pro_source_id, pro_id)

    --clean up table
    truncate table T2S_Pro_Info_WbStore

    -- now create  table that would xold everything that needed for comparing and inserting 
   DELETE pt_Get_Multiple_Prices  -- this is view
    --WHERE  Connection_ID = @@SPID

    INSERT pt_Get_Multiple_Prices
    (
      connection_id,
      pos_source_id,
      pos_id,
      price_date,
      pkp_source_id,
      pkp_id
    )
    SELECT @@SPID, pos.pos_Source_Id, pos.pos_Id, GETDATE(), pkp.pkp_source_id, pkp.pkp_id--, 'Y'
    FROM points_of_sale pos --on pos.pos_source_id = sw_p.pos_source_id and pos.pos_id = sw_p.pos_id
            ,pkp_view pkp
    where pos.pos_type = 'D' and pos.pos_active = 'Y' and pkp.delivery = 'Y'
            
    EXEC Get_Multiple_Prices
         @skip_taxes = 'Y' 

    --combine prices for same pkp
    Select 
        Pkp_Source_ID, pkp_id
        ,convert(xml, (select convert(varchar(16), pxm.pos_source_id) + ':' + convert(varchar(16), pxm.pos_id) as '@pos_id'
                ,price as '@regular_price'
                    from pt_Get_Multiple_Prices pxm where pxm.pkp_source_id = pos.pkp_source_id and pxm.pkp_id = pos.pkp_id
                    order by pxm.POS_Source_ID, pxm.pos_id
                    for xml path('price'))) prices
    into #pos_xml
    from pt_Get_Multiple_Prices pos
    where pos.pkp_id is not null and pos.price is not null
    group by pos.pkp_source_id, pos.pkp_id

    ---- now get everything by product
    select 
                 coalesce(pro.code, '')				            AS pro_code			
                ,coalesce(pro.description, '')                  AS pro_description
                ,coalesce(pdf.description, pro.description)     AS pdf_description
                ,pro.pro_source_id                              AS pro_source_id
                ,pro.pro_id                                     AS pro_id
                ,pdf.pdf_source_id                              AS pdf_source_id
                ,pdf.pdf_id                                     AS pdf_id
                ,convert(xml, 
                            (select pkp.Calc_UNI_Desc as "@package", convert(varchar(8), pkp.Calc_UNI_Conversion_Factor) as "@count"
                                    ,convert(varchar(16), pkp.pkp_source_id) + ':' + convert(varchar(16), pkp.pkp_id) as "@pkp_id"
                                    ,pos.prices
                                from pkp_view pkp 
                                    inner join #pos_xml pos
                                        on (pkp.pkp_source_id = pos.pkp_source_id and pkp.pkp_id = pos.pkp_id )
                                where pro.pro_source_id = pkp.pro_source_id and pro.pro_id = pkp.pro_id and pkp.delivery = 'Y' 
                                order by pkp.Calc_UNI_Conversion_Factor 
                                for xml path('package')--, root('packages') 
                                ) 
                    )                                        AS pkp_info
    into #products_all
    from products pro
        inner join product_families pdf
            on pro.pdf_source_id = pdf.pdf_source_id and pro.pdf_id = pdf.pdf_id
    where exists (select 1 from pkp_view pkp where pro.pro_source_id = pkp.pro_source_id and pro.pro_id = pkp.pro_id and pkp.delivery = 'Y')
            and (
                getdate() > coalesce(pro.in_service_date, '1970-01-01')
                and getdate() < coalesce(pro.in_service_date, '2049-01-01')
            )


-- and finally insert what needed with correct status

    --get new snapshot
    insert into T2S_Pro_Info_WbStore
    select 
         @operator_id                                           AS operator_id
        ,coalesce(new.pro_code, '')				                AS pro_code			
        ,coalesce(new.pro_description, '')                      AS pro_description
        ,coalesce(new.pdf_description, new.pro_description)     AS pdf_description
        ,coalesce(new.pro_source_id, old.pro_source_id)         AS pro_source_id
        ,coalesce(new.pro_id, old.pro_id)                       AS pro_id
        ,coalesce(new.pdf_source_id, old.pdf_source_id)         AS pdf_source_id
        ,coalesce(new.pdf_id, old.pdf_id)                       AS pdf_id
        ,new.pkp_info
        ,case 
                when new.pro_source_id is null then 'deleted'
                when old.pro_source_id is null then 'new'
                when
                           coalesce(new.pro_code, '-99')				                <> coalesce(old.pro_code, '-98')				                
                        or coalesce(new.pro_description, '-99')                         <> coalesce(old.pro_description, '-98')                      
                        or coalesce(new.pdf_description, new.pro_description, '-99')    <> coalesce(old.pdf_description, old.pro_description, '-98')     
                        or coalesce(new.pdf_source_id, -99)                             <> coalesce(old.pdf_source_id, -98)         
                        or coalesce(new.pdf_id, -99)                                    <> coalesce(old.pdf_id, -98)                       
                        or coalesce(convert(varchar(max),new.pkp_info), '-99')          <> coalesce(convert(varchar(max),old.pkp_info), '-98')                                           
                    then 'changed'
                else 'same'
            end                                                                             AS changed_or_new

        ,@curr_dt                                                                           AS created_dt
        ,@new_id                                                                            AS batch_id
    From #products_all new 
        FULL OUTER JOIN #tmp_T2S_Pro_Info_WbStore old
            on new.pro_source_id = old.pro_source_id and new.pro_id = old.pro_id

    --return full catalogue or changes only
    select 
         operator_id                                    AS "@operator_id"
        ,pro_code		                                AS "@pro_code"			
        ,pro_description                                AS "@pro_description"
        ,pdf_description                                AS "@pdf_description"
        --,pro_source_id                                  AS "@pro_source_id"
        ,convert(varchar(8), pro_source_id) + ':' + convert(varchar(8), pro_id) AS "@pro_id"
        --,pdf_source_id                                  AS "@pdf_source_id"
        ,convert(varchar(8), pdf_source_id) + ':' + convert(varchar(8), pdf_id) AS "@pdf_id"
        ,changed_or_new                                 AS "@changed_or_new"
        ,created_dt                                     AS "@created_dt"    
        ,batch_id                                       AS "@batch_id"
        ,record_id                                      AS "@record_id"        
        ,pkp_info                                       AS packages      
    from T2S_Pro_Info_WbStore
    where (@changes_only <> 'Yes' and changed_or_new <> 'deleted')
        or (@changes_only = 'Yes' and changed_or_new <> 'same')
    for xml path('product'), root('products')
--FOR XML RAW ('Product'), ROOT ('Products');

end
go

--begin tran



--------update products set code = 'AR_PRO_Updated' where code = 'TEST4'  --duplicate code?
----------update product_families set code = 'AR_PDF_Updated' where code = 'GENCFBEV'
------------update locations set code = 'AR_LOC_Updated' where code = 'PGFAC'
------------update customers set code = 'AR_CUS_Updated' where code = 'LOWE'



--------------select * from products where code = '00208'
--------------select * from products where pro_id = 6144

------425010G removed product by in service date  +   --   deleted 1.
------425010A removed product by disabling delivery  + deleted
------425020H remove product by taking delivery out of service  + 
------425010B changed pro code  + 425010B-AR  -- + Changed
------ 425010C - changed pro desc  + Decaf Cola - AR  + Changed
------425010D - changed pdf id  + to 34  + Changed
------ 425010E - added pkp for delivery (changed)  +-- added each  + Changed
------425020F  -- removed one pkp for delivery  +--  removed each
------425020I  - change price for one POS for box for POS <price pos_id="3333:1" regular_price="0.0000" />  -- POS 119
------ add product: 

----select * from points_of_sale where pos_id = 1


--exec t2s_exportPRO_WbStore 'No'

----select * from T2S_Pro_Info_WbStore



--rollback