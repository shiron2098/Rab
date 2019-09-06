-- Command: Get_POS_BI


if object_id('t2s_exportPos', 'P' ) IS NOT NULL 
Begin 
drop proc t2s_exportPos
end


go
-- returns all or only changed POS 
--<POSs>
--  <POS pos_code="131428" pos_description="AC ABC News Washington 7th FL" veq_code="MMACABCNW05" veq_description="Snack Wall 1" loc_code="MMACABCNW" loc_description="AC ABC News Washington 7th FL" cus_code="MMACABCNW" cus_description="AC ABC News Washington" pos_source_id="2039" pos_id="131428" veq_source_id="2039" veq_id="14206" loc_source_id="2039" loc_id="11518" cus_source_id="2039" cus_id="8313" changed_or_new="not changed" created_dt="2019-08-05T09:00:24.510" batch_id="9945782E-B46B-486C-8B12-D5B17F983280" record_id="45149" />
--</POSs>

create proc t2s_exportPos (@changes_only varchar(3) = 'Yes')  -- Yes/No NULL = Yes
as
begin

declare @curr_dt    datetime = getdate()
declare @new_id     uniqueidentifier = newid()
declare @operator_id int

set @changes_only = coalesce(@changes_only, 'Yes')


if not exists (select 1 from T2S_Pos_Info) 
begin
    set @changes_only ='No'
end

select @operator_id = operator_id from cp_extension



-- insert only changes
        insert into T2S_POS_Info
        select 
             @operator_id                                   AS operator_id
            ,coalesce(pos.code, '')				            AS pos_code			
            ,coalesce(pos.description, '')                  AS pos_description
            ,coalesce(veq.code, pos.code)                   AS veq_code
            ,coalesce(veq.description, pos.description)     AS veq_description
            ,coalesce(loc.code, cus.code)                   AS loc_code
            ,coalesce(loc.description, cus.description)     AS loc_description
            ,coalesce(cus.code, '')                         AS cus_code
            ,coalesce(cus.description, '')                  AS cus_description
            ,pos.pos_source_id      AS pos_source_id
            ,pos.pos_id             AS pos_id
            ,veq.veq_source_id      AS veq_source_id
            ,veq.veq_id             AS veq_id
            ,loc.loc_source_id      AS loc_source_id
            ,loc.loc_id             AS loc_id
            ,cus.cus_source_id      AS cus_source_id
            ,cus.cus_id             AS cus_id             
            ,case when t2s.pos_source_id is null then 'new'
                  else 'changed'
                end                                                                             AS changed_or_new
            ,@curr_dt                                                                           AS created_dt
            ,@new_id                                                                            AS batch_id
        --    ,record_id              AS Record_id
        from points_of_sale pos
            inner join vending_equipment veq
                on veq.pos_source_id = pos.pos_source_id and veq.pos_id = pos.pos_id
            inner join locations loc
                on loc.loc_source_id = pos.loc_source_id and loc.loc_id = pos.loc_id
            inner join customers cus
                on cus.cus_source_id = loc.cus_source_id and cus.cus_id = loc.cus_id
            left outer join(Select pos_source_id, pos_id, max(created_dt) max_dt from T2S_POS_Info group by pos_source_id, pos_id) max_dt
                on pos.pos_source_id = max_dt.pos_source_id and pos.pos_id = max_dt.pos_id 
            left outer join T2S_POS_Info t2s
                on t2s.pos_source_id = max_dt.pos_source_id and t2s.pos_id = max_dt.pos_id 
                        and max_dt.max_dt = t2s.created_dt
        Where
             (
                    t2s.pos_source_id is null 
	             or pos.code		      <> t2s.pos_code			
                 or pos.description       <> t2s.pos_description
                 or veq.code              <> t2s.veq_code
                 or veq.description       <> t2s.veq_description
                 or loc.code              <> t2s.loc_code
                 or loc.description       <> t2s.loc_description
                 or cus.code              <> t2s.cus_code
                 or cus.description       <> t2s.cus_description
                 or veq.veq_source_id     <> t2s.veq_source_id
                 or veq.veq_id            <> t2s.veq_id
                 or loc.loc_source_id     <> t2s.loc_source_id
                 or loc.loc_id            <> t2s.loc_id
                 or cus.cus_source_id     <> t2s.cus_source_id
                 or cus.cus_id            <> t2s.cus_id            
             )
             --and pos.description is null

    Select 
             t2s.operator_id
            ,t2s.pos_code			
            ,t2s.pos_description
            ,t2s.veq_code
            ,t2s.veq_description
            ,t2s.loc_code
            ,t2s.loc_description
            ,t2s.cus_code
            ,t2s.cus_description
            ,convert(varchar(16), t2s.pos_source_id) + ':' + convert(varchar(16), t2s.pos_id) as pos_id
            --,t2s.pos_source_id
            --,t2s.pos_id
            ,convert(varchar(16), t2s.veq_source_id) + ':' + convert(varchar(16), t2s.veq_id) as veq_id
            --,t2s.veq_source_id
            --,t2s.veq_id
            ,convert(varchar(16), t2s.loc_source_id) + ':' + convert(varchar(16), t2s.loc_id) as loc_id
            --,t2s.loc_source_id
            --,t2s.loc_id
            ,convert(varchar(16), t2s.cus_source_id) + ':' + convert(varchar(16), t2s.cus_id) as cus_id
            --,t2s.cus_source_id
            --,t2s.cus_id    
            ,case 
                    when created_dt = @curr_dt then t2s.changed_or_new 
                    else 'not changed' 
                end as changed_or_new
            ,t2s.created_dt
            ,t2s.batch_id
            ,t2s.record_id
    From T2S_POS_Info t2s
            Inner join(Select pos_source_id, pos_id, max(created_dt) max_dt from T2S_POS_Info group by pos_source_id, pos_id) max_dt
                on t2s.pos_source_id = max_dt.pos_source_id and t2s.pos_id = max_dt.pos_id and max_dt.max_dt = t2s.created_dt
    where 
        @changes_only = 'No'  -- all records
        or (@changes_only <> 'No'  -- all records
                and batch_id = @new_id)
    order by t2s.pos_code
    FOR XML RAW ('POS'), ROOT ('POSs');


end
go

--begin tran

--exec t2s_exportPos 'YES'

----select * from points_of_sale
--update points_of_sale set code = 'AR_POS_Updated' where code = '1751'
----update vending_equipment set code = 'AR_VEQ_Updated' where code = 'mmritzbevcooler2'
----update locations set code = 'AR_LOC_Updated' where code = 'PGFAC'
----update customers set code = 'AR_CUS_Updated' where code = 'LOWE'

--exec t2s_exportPos 'Yes'

----exec t2s_exportPos 'No'




--rollback