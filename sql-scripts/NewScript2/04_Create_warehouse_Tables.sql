use t2s_dashboard;



----------------------------- Start Create AverageCollection Tables ----------------------------- 
drop table if exists Daily_Avg_Collect;

CREATE TABLE IF NOT EXISTS Daily_Avg_Collect  
(
				  date_num int NULL
                , operator_id	int NULL  
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Avg_Collect;

CREATE TABLE IF NOT EXISTS Weekly_Avg_Collect 
(
				  week_num int NULL
                , operator_id	int NULL
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Avg_Collect;

CREATE TABLE IF NOT EXISTS Monthly_Avg_Collect 
(
				  month_num int NULL
                , operator_id	int NULL
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

----------------------------- End Create AverageCollection Tables ----------------------------- 

----------------------------------------------------------------------------------------------------------------------

----------------------------- Start Create Collection Distribution Tables ----------------------------- 


drop table if exists Daily_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Daily_Collection_Distribution
(
				  date_num 					int NULL
                , operator_id	int NULL                  
                , less_50					int NULL
                , more_50_less_75 			int NULL
                , more_75_less_100 			int NULL
                , more_100_less_150			int NULL
                , more_150		 			int NULL                
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Weekly_Collection_Distribution
(
				  week_num int NULL
                , operator_id	int NULL                  
                , less_50					int NULL
                , more_50_less_75 			int NULL
                , more_75_less_100 			int NULL
                , more_100_less_150			int NULL
                , more_150		 			int NULL                
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Monthly_Collection_Distribution 
(
				  month_num int NULL
                , operator_id	int NULL                  
                , less_50					int NULL
                , more_50_less_75 			int NULL
                , more_75_less_100 			int NULL
                , more_100_less_150			int NULL
                , more_150		 			int NULL                
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

----------------------------- End Create Collection Distribution Tables ----------------------------- 

-----------------------------------------------------------------------------------------------------------------

----------------------------- Start Create missed stops tables ----------------------------- 

drop table if exists Daily_Missed_Stops;

CREATE TABLE IF NOT EXISTS Daily_Missed_Stops
(
				  date_num 					int NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Missed_Stops;

CREATE TABLE IF NOT EXISTS Weekly_Missed_Stops
(
				  week_num int NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL 
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month
  
drop table if exists Monthly_Missed_Stops;

CREATE TABLE IF NOT EXISTS Monthly_Missed_Stops
(
				  month_num int NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month


----------------------------- End Create missed stops Tables ----------------------------- 

-----------------------------------------------------------------------------------------------------------------

----------------------------- Start Create stockout Tables ----------------------------- 


drop table if exists Daily_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Daily_Stockouts_And_Not_Picked
(
				  date_num 					int NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
                , total_picked		int null
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Weekly_Stockouts_And_Not_Picked
(
				  week_num int NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
                , total_picked		int null
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Monthly_Stockouts_And_Not_Picked
(
				  month_num int NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
                , total_picked		int null
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month


----------------------------- End Create stockout Tables ----------------------------- 

-----------------------------------------------------------------------------------------------------------------


