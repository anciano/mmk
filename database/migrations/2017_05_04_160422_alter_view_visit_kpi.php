<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewVisitKpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    $drop = <<<EOD
drop VIEW  view_visit_valid_cust;
drop VIEW  view_visit_line_cust;
drop VIEW  view_visit_line_cust_count;
drop VIEW  view_visit_day_cust;
drop VIEW  view_visit_day_cust_count;
drop VIEW  view_visit_month_cust;
drop VIEW  view_visit_month_cust_count;
drop VIEW  view_visit_day_cost;
drop VIEW  view_visit_day_cost_sum;
drop VIEW  view_visit_month_cost;
drop VIEW  view_visit_month_cost_sum;
drop VIEW  view_visit_kpi;
EOD;


	    //
	    $query[] = <<<EOD
#有效门店数
CREATE
OR REPLACE VIEW view_visit_valid_store AS SELECT
	femp_id,
	COUNT(1) AS valid_store_total
FROM
	st_stores
WHERE
	fdocument_status='C' and fforbid_status='A' and fline_id is not NULL
GROUP BY
	femp_id;
EOD;

	    $query[] = <<<EOD
#线路总门店数
CREATE
OR REPLACE VIEW view_visit_store AS SELECT
	femp_id,
	COUNT(1) AS store_total
FROM
	st_stores
GROUP BY
	femp_id;
EOD;
	    $query[] = <<<EOD
#日应拜访门店数
CREATE
OR REPLACE VIEW view_visit_day_store AS SELECT DISTINCT
	count(fstore_id) as day_store_total,
	femp_id,
	DATE_FORMAT(fdate, '%Y-%m-%d') fdate
FROM
	visit_store_calendar
GROUP BY
	femp_id,
	fdate;
EOD;

	    $query[] = <<<EOD
#日已拜访的门店数
CREATE
OR REPLACE VIEW view_visit_day_store_done AS SELECT
	DATE_FORMAT(fdate, '%Y-%m-%d') fdate,
	femp_id,
	COUNT(fstore_id) AS day_store_done_total
FROM
	visit_store_calendar
WHERE
	fstatus=3
GROUP BY
	femp_id,
	fdate;
EOD;

	    $query[] = <<<EOD
#月应拜访门店数
CREATE
OR REPLACE VIEW view_visit_month_store AS SELECT
	count(fstore_id) as month_store_total,
	femp_id,
	DATE_FORMAT(fdate, '%Y-%m') fmonth
FROM
	visit_store_calendar
GROUP BY
	femp_id,
	DATE_FORMAT(fdate, '%Y-%m');
EOD;

	    $query[] = <<<EOD
#月已拜访的门店数
CREATE
OR REPLACE VIEW view_visit_month_store_done AS SELECT
	count(fstore_id) as month_store_done_total,
	femp_id,
	DATE_FORMAT(fdate, '%Y-%m') fmonth
FROM
	visit_store_calendar
WHERE
	fstatus=3
GROUP BY
	femp_id,
	DATE_FORMAT(fdate, '%Y-%m');
EOD;

	    $query[] = <<<EOD
#日门店拜访用时
CREATE
OR REPLACE VIEW view_visit_day_cost AS SELECT
	DATE_FORMAT(fdate, '%Y-%m-%d') fdate,
	femp_id,
	fstore_id,
	fbegin,
	fend,
	timestampdiff(SECOND, fbegin, fend) AS store_cost_second
FROM
	visit_store_calendar;
EOD;

	    $query[] = <<<EOD
#日门店拜访用时汇总
	CREATE
OR REPLACE VIEW view_visit_day_cost_sum AS SELECT
	fdate,
	femp_id,
	sum(store_cost_second) AS store_cost_second_total
FROM
	view_visit_day_cost
GROUP BY
	fdate,
	femp_id;
EOD;

	    $query[] = <<<EOD
#月门店拜访用时
CREATE
OR REPLACE VIEW view_visit_month_cost AS SELECT
	DATE_FORMAT(fdate, '%Y-%m') fmonth,
	femp_id,
	fstore_id,
	fbegin,
	fend,
	timestampdiff(SECOND, fbegin, fend) AS store_cost_second
FROM
	visit_store_calendar;
EOD;

	    $query[] = <<<EOD
#月门店拜访用时汇总
CREATE
OR REPLACE VIEW view_visit_month_cost_sum AS SELECT
	fmonth,
	femp_id,
	sum(store_cost_second) AS store_cost_second_total
FROM
	view_visit_month_cost
GROUP BY
	fmonth,
	femp_id;
EOD;
	    $query[] = <<<EOD
#DAY
CREATE
OR REPLACE VIEW view_visit_day AS
select DISTINCT DATE_FORMAT(fdate, '%Y-%m-%d') fdate from visit_store_calendar;
EOD;

	    $query[] = <<<EOD
#employee day
CREATE
OR REPLACE VIEW view_visit_employee_day AS
select 
s.femp_id, 
s.store_total,
d.fdate,
DATE_FORMAT(d.fdate, '%Y-%m') fmonth
from view_visit_store s
LEFT JOIN view_visit_day d on 1=1;
EOD;

	    $query[] = <<<EOD
#kpi view
CREATE
OR REPLACE VIEW view_visit_kpi AS SELECT
	ed.fdate,
	ed.femp_id,
	emp.fname,
	pos.fname AS position_name,
	st.store_total,
	vst.valid_store_total,
	ds.day_store_total,
	dsd.day_store_done_total,
	ms.month_store_total,
	msd.month_store_done_total,
	msd.month_store_done_total / vst.valid_store_total * 100 AS rate,
	dcs.store_cost_second_total AS day_cost_total,
	mcs.store_cost_second_total AS month_cost_total,
	round(
		mcs.store_cost_second_total / msd.month_store_done_total
	) AS store_avg_cost
FROM
	view_visit_employee_day ed
INNER JOIN bd_employees emp ON ed.femp_id = emp.id
LEFT JOIN bd_positions pos ON emp.fpost_id = pos.id

LEFT JOIN view_visit_store st ON ed.femp_id = st.femp_id
LEFT JOIN view_visit_valid_store vst ON ed.femp_id = vst.femp_id
LEFT JOIN view_visit_day_store ds on ed.femp_id = ds.femp_id and ds.fdate=ed.fdate
LEFT JOIN view_visit_day_store_done dsd on ed.femp_id=dsd.femp_id and dsd.fdate=ed.fdate
LEFT JOIN view_visit_month_store ms ON ed.femp_id = ms.femp_id AND ed.fmonth = ms.fmonth
LEFT JOIN view_visit_month_store_done msd ON ed.femp_id = msd.femp_id AND ed.fmonth = msd.fmonth
LEFT JOIN view_visit_day_cost_sum dcs on ed.femp_id=dcs.femp_id and dcs.fdate=ed.fdate
LEFT JOIN view_visit_month_cost_sum mcs ON ed.femp_id = mcs.femp_id AND ed.fmonth = mcs.fmonth
EOD;

	    foreach ($query as $q)
	        DB::statement($q);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
	    //
    }
}
