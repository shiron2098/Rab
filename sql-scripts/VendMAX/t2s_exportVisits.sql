
if object_id('t2s_exportVisits', 'P' ) IS NOT NULL 
Begin 
drop proc t2s_exportVisits
end

go
-- returns all or only changed Pro


create proc t2s_exportVisits (@date_from datetime = NULL, @date_to datetime = NULL)  --if dates are null we will take visits posted after last time import was successfull
as
begin



--
    declare @curr_dt    datetime = getdate()
    declare @new_id     uniqueidentifier = newid()
    declare @last_retrieve_date datetime = NULL
    declare @operator_id int


    If @date_from is null or @date_to is null 
        begin
            select @last_retrieve_date = max(created_dt) from T2S_visit_Info_BI where successful_import = 'yes'
            select @last_retrieve_date = coalesce(@last_retrieve_date, '2000-01-01')
        end

--select @last_retrieve_date

    select @operator_id = operator_id from cp_extension
    --select @operator_id
    
    -- get everything that could be voided after last time
    select vvs.pos_source_id, vvs.pos_id, vvs.visit_datetime, vvs.vvs_source_id, vvs.vvs_id
    into #voided_vv 
    from vend_visits vvs 
    Where financial_status = 'V' 
        and (
                (@last_retrieve_date  is not null and void_datetime >= @last_retrieve_date)
                or 
                (@last_retrieve_date  is null and vvs.visit_DateTime >= @date_from and vvs.visit_DateTime < (@date_to + 1))
            )
        and exists (select 1 from T2S_visit_Info_BI t2s where vvs.vvs_source_id = t2s.vvs_source_id AND vvs.vvs_id = t2s.vvs_id)


--select * from #voided_vv

    -- now build table to store all the data
    select vvs.pos_source_id, vvs.pos_id, vvs.visit_datetime, vvs.vvs_source_id, vvs.vvs_id, vvs.actual_Sales_Bills, vvs.actual_Sales_Coins
                , vve.Real_Column_Sellouts 
                , vve.Real_Product_Sellouts            
                , case when exists (select 1 from T2S_visit_Info_BI t2s where vvs.vvs_source_id = t2s.vvs_source_id AND vvs.vvs_id = t2s.vvs_id)
                        then 'changed' else 'new'
                    end                                                             AS changed_or_new
    into #posted_vv        
    from vend_visits vvs
            left outer join Vend_Visits_Table_Extension vve	
				ON (vvs.vvs_source_id = vve.vvs_source_id AND vvs.vvs_id = vve.vvs_id)			
	Where financial_status = 'P'
        and (
                (@last_retrieve_date  is not null and vvs.fin_post_datetime >= @last_retrieve_date)
                or 
                (@last_retrieve_date  is null and vvs.visit_DateTime >= @date_from and vvs.visit_DateTime < (@date_to + 1))
            )
        
--select * from #posted_vv
    



    -- now find what hapenned after visit with sellouts and in the same time find diff between added and expected added from SCO

    select vvs.vvs_source_id, vvs.vvs_id, max(sco.sco_source_id) AS sco_source_id, max(sco.sco_id) AS sco_id
          , sum(case 
                when vvi.ending_inv_calc is not null 
                    then case 
                            when abs(vvi.added) < coalesce(sci.quantity, 0) 
                                then sci.quantity - vvi.added
                            else 0
                        end
                else NULL
            end)                                                AS not_packed        
        ,  sum(case                                             -- calc not filled columns after visit 
                when vvi.ending_inv_calc is not null and vvi.user3 = 'SELLOUT' and coalesce(abs(added),0) <=0
                    then 1
                else 0
            end)                                                AS col_empty_after           
        -- NOT IMPLEMENTED
        , 0                                                AS pro_empty_after                   
        -- NOT IMPLEMENTED
        , 0                                                AS number_Of_Columns 
    into #posted_items
    from #posted_vv vvs
            left outer join vend_visit_items vvi
				ON (vvs.vvs_source_id = vvi.vvs_source_id AND vvs.vvs_id = vvi.vvs_id)
            left outer join vend_visit_items_table_extension vie with (nolock) 
                on vie.vvs_source_id = vvi.vvs_source_id AND vie.vvs_id = vvi.vvs_id
                        AND vie.tray_num = vvi.tray_num AND vie.column_num = vvi.column_num
            LEFT OUTER JOIN vm_service_order SCO WITH (NOLOCK)
                ON (SCO.POS_Source_ID = vvs.pos_source_id AND SCO.POS_ID = vvs.pos_id AND convert(varchar(30), SCO.fulfillment_date,101) = convert(varchar(32), vvs.visit_DateTime, 101)) 
            LEFT OUTER JOIN vm_service_order_items SCI WITH (NOLOCK)
				ON ( SCO.sco_source_id = sci.sco_source_id AND SCO.sco_id = SCI.sco_id
                        AND  vvi.tray_num = sci.tray_num and vvi.column_num = sci.column_num
                    )
    group by vvs.vvs_source_id, vvs.vvs_id    

    insert into T2S_visit_Info_BI (
              operator_id  
             ,pos_source_id     --int null
             ,pos_id            --int null
             ,visit_date        --datetime null
             ,vvs_source_id     --int null
             ,vvs_id            --int null
             ,sco_source_id     --int null
             ,sco_id            --int null
             ,scheduled         --varchar(3)  null  -- yes/no
             ,serviced          ----varchar(3)  null  -- yes/no
             ,collect           --varchar(3)    null    --yes/no
             ,actual_Sales_Bills --decimal(16,2)   -- total collect, NULL if service
             ,actual_Sales_Coins --decimal(16,2)   -- total collect, NULL if service
             ,number_of_columns     --int null
             ,col_sold_out      --int null    -- number of sold out columns when driver started service
             ,pro_sold_out      --int null    -- number of sold out pro when driver started service
             ,col_empty_after   --int null    -- number of sold out columns when driver ended service
             ,pro_empty_after   --int null    -- number of sold out pro when driver ended service
             ,not_picked        --int null    -- number of items not packed (when added < pre-kit number in service order)
             ,changed_or_new    --varchar(10)  null  --'changed', 'new', 'not changed'
             ,record_status     
             ,created_dt        --datetime null
             ,batch_id          --uniqueidentifier null
             ,successful_import  --varchar(3) null  -- yes/no
             )
     select 
          @operator_id
         ,pos_source_id     --int null
         ,pos_id            --int null
         ,visit_datetime        --datetime null
         ,vvs_source_id     --int null
         ,vvs_id            --int null
         ,NULL              --sco_source_id     int null
         ,NULL              --sco_id            int null
         ,NULL              --scheduled         varchar(3)  null  -- yes/no
         ,NULL              --serviced          varchar(3)  null  -- yes/no
         ,NULL              --collect           varchar(3)    null    --yes/no
         ,NULL              --actual_Sales_Bills decimal(16,2)   -- total collect, NULL if service
         ,NULL              --actual_Sales_Coins decimal(16,2)   -- total collect, NULL if service
         ,NULL              --number_of_columns
         ,NULL              --col_sold_out      int null    -- number of sold out columns when driver started service
         ,NULL              --pro_sold_out      int null    -- number of sold out pro when driver started service
         ,NULL              --col_empty_after   int null    -- number of sold out columns when driver ended service
         ,NULL              --pro_empty_after   int null    -- number of sold out pro when driver ended service
         ,NULL              --not_picked        int null    -- number of items not packed (when added < pre-kit number in service order)
         ,'changed'         --changed_or_new    varchar(10)  null  --'changed', 'new', 'not changed'
         ,'voided'          --record_status
         ,@curr_dt          --created_dt        datetime null
         ,@new_id           --          uniqueidentifier null
         ,'No'              --  successful importvarchar(3) not null  -- yes/no
    from #voided_vv 
    union ALL
     select
          @operator_id
         ,vvs.pos_source_id     --int not null
         ,vvs.pos_id            --int not null
         ,vvs.visit_datetime        --datetime not null
         ,vvs.vvs_source_id     --int not null
         ,vvs.vvs_id            --int not null
         ,vve.sco_source_id     --int not null
         ,vve.sco_id            --int not null
         -------------- NOT IMPLEMENTED ------------------
         ,'Yes'    --vvs.NULL              --scheduled         varchar(3)  not null  -- yes/no
         ,'Yes'    --vvs.NULL              --serviced varchar(3)  not null  -- yes/no
         ,Case when vvs.actual_Sales_Bills is not null or actual_Sales_Coins is not null 
                then 'Yes' 
                else 'No'
             End   --vvs.NULL              --collect          varchar(3)  not null  -- yes/no
         -------------- NOT IMPLEMENTED ------------------
         ,vvs.actual_Sales_Bills              --actual_Sales_Bills decimal(16,2)   -- total collect, NULL if service
         ,vvs.actual_Sales_Coins              --actual_Sales_Coins decimal(16,2)   -- total collect, NULL if service
         -------------- NOT IMPLEMENTED ------------------
         ,35                                -- Number_of_columns null
         -------------- NOT IMPLEMENTED ------------------
         ,vvs.Real_Column_Sellouts              --col_sold_out      int not null    -- number of sold out columns when driver started service
         ,vvs.Real_Product_Sellouts              --pro_sold_out      int not null    -- number of sold out pro when driver started service
         ,vve.col_empty_after   --int not null    -- number of sold out columns when driver ended service
         ,vve.pro_empty_after   --int not null    -- number of sold out pro when driver ended service
         ,vve.not_packed        --int not null    -- number of items not packed (when added < pre-kit number in service order)
         ,vvs.changed_or_new    --varchar(10)  not null  --'changed', 'new', 'not changed'
         ,'posted'          --record_status
         ,@curr_dt          --created_dt        datetime not null
         ,@new_id           --          uniqueidentifier not null
         ,'No'              --  successful importvarchar(3) not null  -- yes/no
    from #posted_vv vvs
        inner join #posted_items vve
            on (vvs.vvs_source_id = vve.vvs_source_id AND vvs.vvs_id = vve.vvs_id)			

    --EXEC scheduling_get_for_reports
    --     @start_date        = @visit_date,
    --     @end_date          = @visit_date_to,  --Added by Sergey Osipchik on [2012/12/11] to apply feature #5666 from bugzilla.
    --     @show_un_scheduled = 'N',
    --     @show_vending      = 'Y',
    --     @show_micromarket  = 'Y',
    --     @show_delivery     = 'N',
    --     @show_coldfood     = 'N'


    select
              operator_id  
             ,convert(varchar(16), t2s.pos_source_id) + ':' + convert(varchar(16), t2s.pos_id) as pos_id
             ,visit_date        --datetime not null
             ,datepart(WEEK,visit_date)                      as week_num
             ,datepart(MONTH,visit_date)                     as month_num
             ,convert(varchar(16), t2s.vvs_source_id) + ':' + convert(varchar(16), t2s.vvs_id) as vvs_id
             ,convert(varchar(16), t2s.sco_source_id) + ':' + convert(varchar(16), t2s.sco_id) as sco_id
             ,t2s.scheduled         --varchar(3)  not null  -- yes/no
             ,t2s.serviced         --varchar(3)  not null  -- yes/no
             ,t2s.collect         --varchar(3)  not null  -- yes/no
             ,actual_Sales_Bills --decimal(16,2)   -- total collect, NULL if service
             ,actual_Sales_Coins --decimal(16,2)   -- total collect, NULL if service
             ,number_of_columns
             ,col_sold_out      --int not null    -- number of sold out columns when driver started service
             ,pro_sold_out      --int not null    -- number of sold out pro when driver started service
             ,col_empty_after   --int not null    -- number of sold out columns when driver ended service
             ,pro_empty_after   --int not null    -- number of sold out pro when driver ended service
             ,not_picked        --int not null    -- number of items not packed (when added < pre-kit number in service order)
             ,changed_or_new    --varchar(10)  not null  --'changed', 'new', 'not changed'
             ,record_status     
             ,created_dt        --datetime not null
             ,batch_id          --uniqueidentifier not null
             ,record_id
    from T2S_visit_Info_BI t2s
    where batch_id = @new_id
    for xml raw('Visit'), Root ('Visits');
end
GO

--begin tran
------update T2S_visit_Info set successful_import = 'YES'
------select * from T2S_visit_Info
--exec t2s_exportVisits
------select * from T2S_visit_Info
--rollback

