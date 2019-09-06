use t2s_bi_dashboard;
/*use t2s_dashboard;*/


/*
drop table if exists Dim_Date 
*/
CREATE TABLE IF NOT EXISTS Dim_Date 
(
				 date_num int(8),    -- numeric value, in YYYYMMDD, 20080818  -- primay key.
				 date datetime ,     -- date: 2008-08-18 00:00:00
				 day_num int (2),    -- numeric value, 18
				 day_of_year int(4), -- the day of the year 
				 day_of_week int(2), -- the day of the week
				 day_of_week_name varchar(20), -- day of week name (Monday, Tuesday,etc)
				 
				 week_num int (2), --  week of the year 
				 week_begin_date datetime,  -- week begin date
				 week_end_date datetime, -- week end date
				 
				 prev_week_num int(2),
				 prev_week_begin_date datetime,  -- week begin date
				 prev_week_end_date datetime, -- week end date
				 
				 prev_2_week_num int(2),
				 prev_2_week_begin_date datetime,  -- week begin date
				 prev_2_week_end_date datetime, -- week end date
				 
				 month_num int (2) ,  -- month in number, ie. 12
				 month_name varchar(20),  -- month in name, ie. December
				 year_month_num int(6),  -- year and month in number, ie. 201212
				 
				 prev_month_num int (2) ,  -- month in number, ie. 12
				 prev_month_name varchar(20),  -- month in name, ie. December
				 prev_year_month_num int(6),  -- year and month in number, ie. 201212
				 prev_month_year int(2),			-- year of the prev. months
				 
				 quarter_num int (2),  -- quarter in number, ie 4
				 year_num int (4), -- year in number, ie, 2012
				 created_date timestamp default current_timestamp ,  -- date record was created
				 updated_date timestamp default current_timestamp , -- date record was updated
				 primary key (date_num)
 );

drop table if exists Daily_Avg_Collect;

CREATE TABLE IF NOT EXISTS Daily_Avg_Collect  
(
				  date_num int(8) NULL
                , operator_id	int NULL  
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
--                 , week_average_trend decimal(16,2) NULL
--                 , week_min_trend decimal(16,2) NULL
--                 , week_max_trend   decimal(16,2) NULL
--                 , month_average_trend decimal(16,2) NULL
--                 , month_min_trend decimal(16,2) NULL
--                 , month_max_trend   decimal(16,2) NULL
				, created_date timestamp not null   -- date record was created
);

drop table if exists Weekly_Avg_Collect;

CREATE TABLE IF NOT EXISTS Weekly_Avg_Collect 
(
				  week_num int(2) NULL
                , operator_id	int NULL
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
--                 , month_min_trend decimal(16,2) NULL
--                 , month_max_trend   decimal(16,2) NULL
				, created_date timestamp not null    -- date record was created
);

drop table if exists Monthly_Avg_Collect;

CREATE TABLE IF NOT EXISTS Monthly_Avg_Collect 
(
				  month_num int(2) NULL
                , operator_id	int NULL
                , average_collect decimal(16,2) NULL
                , min_collect decimal(16,2) NULL
                , max_collect decimal(16,2) NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month



drop table if exists Daily_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Daily_Collection_Distribution
(
				  date_num 					int(2) NULL
                , operator_id	int NULL                  
                , less_50					int(2) NULL
                , more_50_less_75 			int(2) NULL
                , more_75_less_100 			int(2) NULL
                , more_100_less_150			int(2) NULL
                , more_150		 			int(2) NULL                
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Weekly_Collection_Distribution
(
				  week_num int(2) NULL
                , operator_id	int NULL                  
                , less_50					int(2) NULL
                , more_50_less_75 			int(2) NULL
                , more_75_less_100 			int(2) NULL
                , more_100_less_150			int(2) NULL
                , more_150		 			int(2) NULL                
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Collection_Distribution;

CREATE TABLE IF NOT EXISTS Monthly_Collection_Distribution 
(
				  month_num int(2) NULL
                , operator_id	int NULL                  
                , less_50					int(2) NULL
                , more_50_less_75 			int(2) NULL
                , more_75_less_100 			int(2) NULL
                , more_100_less_150			int(2) NULL
                , more_150		 			int(2) NULL                
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Daily_Missed_Stops;

CREATE TABLE IF NOT EXISTS Daily_Missed_Stops
(
				  date_num 					int(2) NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Missed_Stops;

CREATE TABLE IF NOT EXISTS Weekly_Missed_Stops
(
				  week_num int(2) NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL                
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Missed_Stops;

CREATE TABLE IF NOT EXISTS Monthly_Missed_Stops
(
				  month_num int(2) NULL
                , operator_id	int NULL                  
                , scheduled_stops int NULL
                , missed_stops int NULL
                , out_of_schedule_stops int NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Daily_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Daily_Stockouts_And_Not_Picked
(
				  date_num 					int(2) NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
				, created_date timestamp not null   -- date record was created
); -- trend is comapred to the same but for the past week or month

drop table if exists Weekly_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Weekly_Stockouts_And_Not_Picked
(
				  week_num int(2) NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month

drop table if exists Monthly_Stockouts_And_Not_Picked;

CREATE TABLE IF NOT EXISTS Monthly_Stockouts_And_Not_Picked
(
				  month_num int(2) NULL
                , operator_id	int NULL                  
                , before_stockouts int NULL
                , after_stockouts int NULL
                , before_percentage int NULL
                , after_percentage int NULL
                , not_picked		int NULL
				, created_date timestamp not null    -- date record was created
);-- trend is comapred to the same but for the past week or month
