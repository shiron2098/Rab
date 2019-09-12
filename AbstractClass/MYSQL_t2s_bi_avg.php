<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/MYSQL_t2s_bi_data.php';


class MYSQL_t2s_bi_avg extends MYSQL_t2s_bi_data
{
    protected function daily_stopsAVG($datetime, $datetimeavg)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select avg(scheduled_stops)as stops, avg(missed_stops)as missedstops,avg(out_of_schedule_stops)as outstops
                      from Daily_Missed_Stops
                    WHERE date_num  >= $datetimeavg and date_num <= $datetime"
        );
        if ($result !== false) {
            $row = mysqli_fetch_assoc($result);
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select *
                      from Daily_Missed_Stops
                    WHERE date_num = $datetime"
            );
            $row2 = mysqli_fetch_assoc($result);
        }
        if ($result !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'missed_stops':
                        if ($row['missedstops'] <= $row2['missed_stops']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                    case 'out_of_schedule_stops':
                        if ($row['outstops'] <= $row2['out_of_schedule_stops']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                }
            }
        }
        return $upandown;
    }

    protected function daily_stockouts_AVG($datetime, $datetimeavg)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select avg(after_stockouts)as afterstockouts
                      from Daily_Stockouts_And_Not_Picked
                    WHERE date_num  >= $datetimeavg and date_num <= $datetime"
        );
        if ($result !== false) {
            $row = mysqli_fetch_assoc($result);
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select *
                      from Daily_Stockouts_And_Not_Picked
                    WHERE date_num = $datetime"
            );
            $row2 = mysqli_fetch_assoc($result);
        }
        if ($result !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'after_stockouts':
                        if ($row['afterstockouts'] <= $row2['after_stockouts']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                }
            }
        }
        return $upandown;
    }

    protected function daily_items_AVG($datetime, $datetimeavg)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select avg(not_picked)as notpicked
                      from Daily_Stockouts_And_Not_Picked
                    WHERE date_num  >= $datetimeavg and date_num <= $datetime"
        );
        if ($result !== false) {
            $row = mysqli_fetch_assoc($result);
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select *
                      from Daily_Stockouts_And_Not_Picked
                    WHERE date_num = $datetime"
            );
            $row2 = mysqli_fetch_assoc($result);
        }
        if ($result !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'before_stockouts':
                        if ($row['notpicked'] <= $row2['not_picked']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                }
            }
        }
        return $upandown;
    }

    protected function daily_distribution_AVG($datetime, $datetimeavg)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select avg(less_50) as less50,avg(more_50_less_75) as more50less75,avg(more_75_less_100) as more75less100,avg(more_100_less_150) as more100less150,avg(more_150) as more150
                      from Daily_Collection_Distribution
                    WHERE date_num  >= $datetimeavg and date_num <= $datetime"
        );
        if ($result !== false) {
            $row = mysqli_fetch_assoc($result);
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select *
                      from Daily_Collection_Distribution
                    WHERE date_num = $datetime"
            );
            $row2 = mysqli_fetch_assoc($result);
        }
        if ($result !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'less_50':
                        if ($row['less50'] <= $row2['less_50']) {
                            $upandown[] = array(
                                'minTresholdSales' => '',
                                'maxTresholdSales' => '50',
                                'numberOfPos' => (string)$row['less50'],
                                'trend' => 'down');
                            break;
                        } else {
                            $upandown[] = array(
                                'minTresholdSales' => '',
                                'maxTresholdSales' =>  '50',
                                'numberOfPos' => (string)$row['less50'],
                                'trend' => 'up');
                            break;
                        }
                    case 'more_50_less_75':
                        if ($row['more50less75'] <= $row2['more_50_less_75']) {
                            $upandown[] = array(
                                'minTresholdSales' => '50',
                                'maxTresholdSales' => '75',
                                'numberOfPos' => (string) $row['more50less75'],
                                'trend' => 'down');
                            break;
                        } else {
                            $upandown[] = array(
                                'minTresholdSales' => '50',
                                'maxTresholdSales' => '75',
                                'numberOfPos' => (string) $row['more50less75'],
                                'trend' => 'up');
                            break;
                            break;
                        }
                    case 'more_75_less_100':
                        if ($row['more75less100'] <= $row2['more_75_less_100']) {
                            $upandown[] = array(
                                'minTresholdSales' => '75',
                                'maxTresholdSales' => '100',
                                'numberOfPos' => (string)$row['more75less100'],
                                'trend' => 'down');
                            break;
                        } else {
                            $upandown[] = array(
                                'minTresholdSales' => '75',
                                'maxTresholdSales' => '100',
                                'numberOfPos' => (string) $row['more75less100'],
                                'trend' => 'up');
                            break;
                        }
                    case 'more_100_less_150':
                        if ($row['more100less150'] <= $row2['more_100_less_150']) {
                            $upandown[] = array(
                                'minTresholdSales' => '100',
                                'maxTresholdSales' => '150',
                                'numberOfPos' => (string) $row['more100less150'],
                                'trend' => 'down');
                            break;
                        } else {
                            $upandown[] = array(
                                'minTresholdSales' => '100',
                                'maxTresholdSales' => '150',
                                'numberOfPos' => (string) $row['more100less150'],
                                'trend' => 'up');
                            break;
                        }
                    case 'more_150':
                        if ($row['more150'] <= $row2['more_150']) {
                            $upandown[] = array(
                                'minTresholdSales' => '150',
                                'maxTresholdSales' => '',
                                'numberOfPos' => (string) $row['more150'],
                                'trend' => 'down');
                            break;
                        } else {
                            $upandown[] = array(
                                'minTresholdSales' => '150',
                                'maxTresholdSales' => '',
                                'numberOfPos' => (string)$row['more150'],
                                'trend' => 'up');
                            break;
                        }
                }
            }
        }
        return $upandown;
    }
    protected function daily_revenue_AVG($datetime, $datetimeavg)
    {
        static::DbconnectT2S_BI();
        $result = mysqli_query(
            MYSQLDataOperator::$linkConnectT2S,
            "select avg(average_collect)as averagecollect, avg(min_collect)as mincollect,avg(max_collect)as maxcollect
                      from Daily_Avg_Collect
                    WHERE date_num  >= $datetimeavg and date_num <= $datetime"
        );
        if ($result !== false) {
            $row = mysqli_fetch_assoc($result);
            $result = mysqli_query(
                MYSQLDataOperator::$linkConnectT2S,
                "select *
                      from Daily_Avg_Collect
                    WHERE date_num = $datetime"
            );
            $row2 = mysqli_fetch_assoc($result);
        }
        if ($result !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'average_collect':
                        if ($row['averagecollect'] <= $row2['average_collect']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                    case 'min_collect':
                        if ($row['mincollect'] <= $row2['min_collect']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                    case 'max_collect':
                        if ($row['maxcollect'] <= $row2['max_collect']) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                }
            }
        }
        return $upandown;
    }

}