
if object_id('t2s_exportPro_BI', 'P' ) IS NOT NULL 
Begin 
drop proc t2s_exportPro_BI
end


go
-- returns all or only changed Pro


create proc t2s_exportPro_BI (@changes_only varchar(3) = 'Yes')  -- Yes/No NULL = Yes
as
begin

declare @curr_dt    datetime = getdate()
declare @new_id     uniqueidentifier = newid()
declare @operator_id int

set @changes_only = coalesce(@changes_only, 'Yes')

if not exists (select 1 from T2S_Pro_Info_BI) 
begin
    set @changes_only ='No'
end

select @operator_id = operator_id from cp_extension



-- insert only changes
        insert into T2S_Pro_Info_BI
        select 
             @operator_id                                   AS operator_id
            ,coalesce(pro.code, '')				            AS pro_code			
            ,coalesce(pro.description, '')                  AS pro_description
            ,coalesce(pdf.code, pro.code)                   AS pdf_code
            ,coalesce(pdf.description, pro.description)     AS pdf_description
            ,pro.pro_source_id                              AS pro_source_id
            ,pro.pro_id                                     AS pro_id
            ,pdf.pdf_source_id                              AS pdf_source_id
            ,pdf.pdf_id                                     AS pdf_id
            ,case when t2s.pro_source_id is null then 'new'
                  else 'changed'
                end                                                                             AS changed_or_new
            ,@curr_dt                                                                           AS created_dt
            ,@new_id                                                                            AS batch_id
        --    ,record_id              AS Record_id
        from products pro
            inner join product_families pdf
                on pro.pdf_source_id = pdf.pdf_source_id and pro.pdf_id = pdf.pdf_id
            left outer join(Select pro_source_id, pro_id, max(created_dt) max_dt from T2S_PRO_Info group by pro_source_id, pro_id) max_dt
                on pro.pro_source_id = max_dt.pro_source_id and pro.pro_id = max_dt.pro_id 
            left outer join T2S_PRO_Info_BI t2s
                on t2s.pro_source_id = max_dt.pro_source_id and t2s.pro_id = max_dt.pro_id 
                        and max_dt.max_dt = t2s.created_dt
        Where   
             (
                    t2s.pro_source_id is null 
	             or pro.code		      <> t2s.pro_code			
                 or pro.description       <> t2s.pro_description
                 or pdf.code              <> t2s.pdf_code
                 or pdf.description       <> t2s.pdf_description
                 or pdf.pdf_source_id     <> t2s.pdf_source_id
                 or pdf.pdf_id            <> t2s.pdf_id
             )
             --and pos.description is null

    Select 
             t2s.operator_id
            ,t2s.pro_code			
            ,t2s.pro_description
            ,t2s.pdf_code
            ,t2s.pdf_description
            ,convert(varchar(16), t2s.pro_source_id) + ':' + convert(varchar(16), t2s.pro_id) as pro_id
            --,t2s.pro_id
            ,convert(varchar(16), t2s.pdf_source_id) + ':' + convert(varchar(16), t2s.pdf_id) as pdf_id
            --,t2s.pdf_id
            ,case 
                    when t2s.created_dt = @curr_dt then t2s.changed_or_new 
                    else 'not changed' 
                end as changed_or_new
            ,t2s.created_dt
            ,t2s.batch_id
            ,t2s.record_id
    From T2S_PRO_Info_BI t2s
            Inner join(Select pro_source_id, pro_id, max(created_dt) max_dt from T2S_PRO_Info_BI group by pro_source_id, pro_id) max_dt
                on t2s.pro_source_id = max_dt.pro_source_id and t2s.pro_id = max_dt.pro_id and max_dt.max_dt = t2s.created_dt
    where 
        @changes_only = 'No'  -- all records
        or (@changes_only <> 'No'  -- all records
                and batch_id = @new_id)
    order by t2s.pro_code
    FOR XML RAW ('Product'), ROOT ('Products');


end
go

--begin tran



--exec t2s_exportPRO 'Yes'

----select * from Products

--update products set code = 'AR_PRO_Updated' where code = 'TEST4'  --duplicate code?
----update product_families set code = 'AR_PDF_Updated' where code = 'GENCFBEV'
------update locations set code = 'AR_LOC_Updated' where code = 'PGFAC'
------update customers set code = 'AR_CUS_Updated' where code = 'LOWE'



--------select * from products where code = '00208'
--------select * from products where pro_id = 6144

--exec t2s_exportPRO 'Yes'

------exec t2s_exportPRO 'No'




--rollback