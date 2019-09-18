use t2s_bi_dashboard;


drop table if exists Dim_Date;

CREATE TABLE IF NOT EXISTS Dim_Date 
(
				 date_num int(8),    -- numeric value, in YYYYMMDD, 20080818  -- primay key.
				 date datetime,     -- date: 2008-08-18 00:00:00
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
				 
				 prev_month_num int (2) ,  -- month in number, ie. 12
				 prev_month_name varchar(20),  -- month in name, ie. December
				 prev_month_year_num int(2),			-- year of the prev. months
				 
				 quarter_num int (2),  -- quarter in number, ie 4
				 year_num int (4), -- year in number, ie, 2012
				 created_date timestamp  default current_timestamp,  -- date record was created
				 updated_date timestamp  default current_timestamp, -- date record was updated
				 primary key (date_num)
 );

-- select * from  Dim_Date 

-- CALL usp_create_date_dimension('2015/01/01', '2025/12/31'); 

