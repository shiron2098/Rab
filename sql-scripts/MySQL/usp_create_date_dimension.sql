use t2s_bi_dashboard;
/*use t2s_dashboard;*/

drop procedure if exists usp_create_date_dimension;

truncate table Dim_Date;

delimiter //

CREATE PROCEDURE usp_create_date_dimension (in param_start_date datetime, param_end_date datetime)
BEGIN

  Declare var_StartDate datetime;
  Declare var_EndDate datetime;
  Declare var_RunDate datetime;

-- Set date variables

  Set var_StartDate = param_start_date; 
  Set var_EndDate = param_end_date; 
  Set var_RunDate = var_StartDate;

	WHILE var_RunDate <= var_EndDate DO

		INSERT Into Dim_Date (
				 date_num,   			--
				 date,					--
				 day_num,				--
				 day_of_year,			--
				 day_of_week,			--
				 day_of_week_name,		--
				 week_num,				--
				 week_begin_date,		--
				 week_end_date,			--
                 
				 prev_week_num,			-- int(2),
				 prev_week_begin_date,	-- datetime,  -- week begin date
				 prev_week_end_date,	-- datetime, -- week end date
				 prev_2_week_num, 		-- int(2),
				 prev_2_week_begin_date, -- datetime,  -- week begin date
				 prev_2_week_end_date,	-- datetime, -- week end date
                 
				 month_num,				--
				 month_name,			--
				 year_month_num,		--

			     prev_month_num, 		-- int (2) ,  -- month in number, ie. 12
				 prev_month_name, 		-- varchar(20),  -- month in name, ie. December
				 prev_year_month_num,	-- int(6),  -- year and month in number, ie. 201212
				 prev_month_year, 		-- int(2),			-- year of the prev. months
 
				 quarter_num,			--
				 year_num,				--
				 created_date, 
				 updated_date 
				)
		select 
				CONCAT(year(var_RunDate), lpad(MONTH(var_RunDate),2,'0'),lpad(day(var_RunDate),2,'0')) 		-- date_num
				,var_RunDate 																				-- date
				,day(var_RunDate) 																			-- day_num
				,DAYOFYEAR(var_RunDate) 																	-- day_of_year
				,DAYOFWEEK(var_RunDate) 																	-- day_of_week
				,DAYNAME(var_RunDate) 																		-- day_of_week_name
				,WEEK(var_RunDate) 																			-- week_num
				,DATE_ADD(var_RunDate, INTERVAL(1-DAYOFWEEK(var_RunDate)) DAY) 								-- week_begin_date
				,ADDTIME(DATE_ADD(var_RunDate, INTERVAL(7-DAYOFWEEK(var_RunDate)) DAY),'23:59:59') 			-- week_end_date

				,WEEK(DATE_ADD(var_RunDate, INTERVAL ((1-DAYOFWEEK(var_RunDate))-7) DAY)) 							-- prev_week_num
				,DATE_ADD(var_RunDate, INTERVAL ((1-DAYOFWEEK(var_RunDate))-7) DAY) 								-- prev_week_begin_date
				,ADDTIME(DATE_ADD(var_RunDate, INTERVAL ((7-DAYOFWEEK(var_RunDate))-7) DAY),'23:59:59')				-- prev_week_end_date
                ,WEEK(DATE_ADD(var_RunDate, INTERVAL ((1-DAYOFWEEK(var_RunDate))-14) DAY))							-- prev_2_week_num
				,DATE_ADD(var_RunDate, INTERVAL ((1-DAYOFWEEK(var_RunDate))-14) DAY) 								-- prev_2_week_begin_date
				,ADDTIME(DATE_ADD(var_RunDate, INTERVAL ((7-DAYOFWEEK(var_RunDate))-7) DAY),'23:59:59')				-- prev_2_week_end_date

				,MONTH(var_RunDate) 																		-- month_num
				,MONTHNAME(var_RunDate) 																	-- month_name
				,CONCAT(year(var_RunDate), lpad(MONTH(var_RunDate),2,'0')) 									-- year_month_num

				,MONTH(date_add(var_RunDate,interval -1 month)) 											-- prev_month_num
				,MONTHNAME(date_add(var_RunDate,interval -1 month)) 										-- prev_month_name
				,CONCAT(year(date_add(var_RunDate,interval -1 month)),lpad(MONTH(date_add(var_RunDate,interval -1 month)),2,'0')) -- prev_year_month_num
				,year(date_add(var_RunDate,interval -1 month)) 												-- prev_month_year

				,QUARTER(var_RunDate) 																		-- quarter_num
				,YEAR(var_RunDate) 																			-- year_num
				,now() 																						-- created_date
				,now() 																						-- update_date
				;

		Set var_RunDate = ADDDATE(var_RunDate,1);

	END WHILE;
 commit;
END;
//

delimiter ;
CALL usp_create_date_dimension('2015/01/01', '2025/12/31'); 


Select * from Dim_Date order by date_num