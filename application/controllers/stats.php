<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stats extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
        if ($this->user->role !== "admin") {
            redirect('');
        }
    }

    public function index()
    {
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d");
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        //echo $firstday.':'.$lastday;
        $item = $this->input->post('item');
        $fecha='';

        if($item)
        {
            $arreglo=explode('~',$item);
            $lapso=$arreglo[0];
            $top=$arreglo[1];
            $month=$arreglo[2];
            $fecha=$arreglo[3];
        
        }
        if($fecha)$date=$fecha;
        //$top = $this->input->post('top');

        $TodaySales = Sale::find_by_sql("select sum(subtotal) AS sum FROM sales where DATE_FORMAT(date, '%Y-%m-%d') = '$date'");
        
        $sqlTop = "select name, product_id, sum(qt) AS totalquantity FROM sale_items where product_id >0 ";

        if($item)
        {
            if($lapso==1)
            {
                $sqlTop .= " AND DATE_FORMAT(date, '%Y') = $year ";
                $strDate = 'del año';
                $sqlLapso = "SELECT DISTINCT MONTH(date) as mes, ROUND(sum(subtotal)) AS sale, 
                                            (                                                                
                                            SELECT ROUND(sum(a.amount)) FROM expences a, catalog_expences b
                                            WHERE a.category_id=b.id AND a.category_id<>9 AND b.name <> 'Ingreso Extraordinario' AND YEAR(a.date)= $year and MONTH(a.date)=mes 
                                            GROUP BY MONTH(a.date)
                                            ) as expence
                                
                            FROM sales WHERE in_activo=1 AND YEAR(date)= $year GROUP BY mes ORDER BY mes asc";
                //echo $sqlLapso;
      
            }
            if($lapso==2)
            {
                $sqlTop .= " AND DATE_FORMAT(date, '%Y') = $year  AND DATE_FORMAT(date, '%m') = $month ";
                $strDate = 'del mes';
                $sqlLapso = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-%d') as dia, 
                                             DATE_FORMAT(date, '%d-%m') as diames,
                                             DATE_FORMAT(date, '%w') as texto, 
                                             ROUND(sum(total)) AS sale,
                                             (
                                             SELECT ROUND(sum(a.amount)) FROM expences a, catalog_expences b
                                             WHERE a.category_id=b.id AND a.category_id<>9 AND b.name <> 'Ingreso Extraordinario' AND a.date=dia 
                                             GROUP BY a.date
                                             ) as expence
                            
                            FROM sales WHERE in_activo=1 AND MONTH(date)= $month AND YEAR(date)= $year 
                            GROUP BY dia ORDER BY dia asc";

            }
            /*, 
                                (SELECT ROUND(sum(total)) FROM sales 
                                WHERE in_activo=1 AND DATE_FORMAT(date, '%Y-%m-%d')=dia  GROUP BY DATE_FORMAT(date, '%Y-%m-%d')) as sale*/
            if($lapso==3)
            {
                $firstday = date('Y-m-d', strtotime('sunday -6 days'));
                $lastday = date('Y-m-d', strtotime('sunday'));
        
                $sqlTop .= " AND DATE_FORMAT(date, '%Y-%m-%d') >= '$firstday' AND DATE_FORMAT(date, '%Y-%m-%d') <= '$lastday' ";

                $strDate = 'de la semana';
                $sqlLapso = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m-%d') as dia,
                                             DATE_FORMAT(date, '%d-%m') as diames,
                                             DATE_FORMAT(date, '%w') as texto, 
                                             ROUND(sum(total)) AS sale,
                                             (
                                             SELECT ROUND(sum(a.amount)) FROM expences a, catalog_expences b
                                             WHERE a.category_id=b.id AND a.category_id<>9 AND b.name <> 'Ingreso Extraordinario' AND a.date=dia 
                                             GROUP BY a.date
                                             ) as expence

                            FROM sales WHERE in_activo=1 AND DATE_FORMAT(date, '%Y-%m-%d') >= '$firstday' 
                            AND DATE_FORMAT(date, '%Y-%m-%d') <= '$lastday 23:23:59' 
                            
                            GROUP BY dia ORDER BY dia asc";

            }
            if($lapso==4)
            {
                $sqlTop .= " AND DATE_FORMAT(date, '%Y-%m-%d') = '$date' ";
                $strDate = 'del día';
                $sqlLapso = "SELECT DISTINCT DATE_FORMAT(date, '%H') as hora, 
                                             ROUND(sum(total)) AS sale,
                                             (
                                             SELECT ROUND(sum(a.amount)) FROM expences a, catalog_expences b
                                             WHERE a.category_id=b.id AND a.category_id<>9  AND b.name <> 'Ingreso Extraordinario' AND DATE_FORMAT(a.created_date, '%H')=hora AND DATE_FORMAT(a.date, '%Y-%m-%d') = '$date' 
                                             GROUP BY DATE_FORMAT(a.created_date, '%H')
                                             ) as expence
                                
                            FROM sales WHERE  in_activo=1 AND DATE_FORMAT(date, '%Y-%m-%d') = '$date' 
                            GROUP BY hora ORDER BY hora asc";

            }
            //$strDate='$Y';
        }
        else
        {
            
            $sqlTop .= " AND DATE_FORMAT(date, '%Y') = $year ";
            $strDate = "del año";
            $lapso=1;
            $sqlLapso = "SELECT DISTINCT MONTH(date) as mes, ROUND(sum(subtotal)) AS sale, 
                                            (                                                                
                                            SELECT ROUND(sum(a.amount)) FROM expences a, catalog_expences b
                                            WHERE a.category_id=b.id AND a.category_id<>9 AND b.name <> 'Ingreso Extraordinario' AND YEAR(a.date)= $year and MONTH(a.date)=mes 
                                            GROUP BY MONTH(a.date)
                                            ) as expence
                                
                            FROM sales WHERE in_activo=1 AND YEAR(date)= $year GROUP BY mes ORDER BY mes asc";

      
        }
        
        if($item)
        {
            $sqlTop .= " GROUP BY product_id ORDER BY SUM(qt) DESC LIMIT ".$top;
        }
        else
        {
            $sqlTop .= " GROUP BY product_id ORDER BY SUM(qt) DESC LIMIT 5";
            $top=5;
        }
        //echo $sqlLapso;
        //die();
        $Top5product = Sale_item::find_by_sql($sqlTop);
        
     
        $salesExpences = Sale::find_by_sql($sqlLapso);

        $monthlySales = Sale::find_by_sql("SELECT SUM(IF(MONTH = 1, numRecords, 0)) AS 'january',SUM(IF(MONTH = 1, totaltax, 0)) AS 'januarytax',SUM(IF(MONTH = 1, totaldiscount, 0)) AS 'januarydisc',SUM(IF(MONTH = 2, numRecords, 0)) AS 'feburary',SUM(IF(MONTH = 2, totaltax, 0)) AS 'feburarytax',SUM(IF(MONTH = 2, totaldiscount, 0)) AS 'feburarydisc',SUM(IF(MONTH = 3, numRecords, 0)) AS 'march',SUM(IF(MONTH = 3, totaltax, 0)) AS 'marchtax',SUM(IF(MONTH = 3, totaldiscount, 0)) AS 'marchdisc',SUM(IF(MONTH = 4, numRecords, 0)) AS 'april',SUM(IF(MONTH = 4, totaltax, 0)) AS 'apriltax',SUM(IF(MONTH = 4, totaldiscount, 0)) AS 'aprildisc',SUM(IF(MONTH = 5, numRecords, 0)) AS 'may',SUM(IF(MONTH = 5, totaltax, 0)) AS 'maytax',SUM(IF(MONTH = 5, totaldiscount, 0)) AS 'maydisc',SUM(IF(MONTH = 6, numRecords, 0)) AS 'june',SUM(IF(MONTH = 6, totaltax, 0)) AS 'junetax',SUM(IF(MONTH = 6, totaldiscount, 0)) AS 'junedisc',SUM(IF(MONTH = 7, numRecords, 0)) AS 'july',SUM(IF(MONTH = 7, totaltax, 0)) AS 'julytax',SUM(IF(MONTH = 7, totaldiscount, 0)) AS 'julydisc',SUM(IF(MONTH = 8, numRecords, 0)) AS 'august',SUM(IF(MONTH = 8, totaltax, 0)) AS 'augusttax',SUM(IF(MONTH = 8, totaldiscount, 0)) AS 'augustdisc',SUM(IF(MONTH = 9, numRecords, 0)) AS 'september',SUM(IF(MONTH = 9, totaltax, 0)) AS 'septembertax',SUM(IF(MONTH = 9, totaldiscount, 0)) AS 'septemberdisc',SUM(IF(MONTH = 10, numRecords, 0)) AS 'october',SUM(IF(MONTH = 10, totaltax, 0)) AS 'octobertax',SUM(IF(MONTH = 10, totaldiscount, 0)) AS 'octoberdisc',SUM(IF(MONTH = 11, numRecords, 0)) AS 'november',SUM(IF(MONTH = 11, totaltax, 0)) AS 'novembertax',SUM(IF(MONTH = 11, totaldiscount, 0)) AS 'novemberdisc',SUM(IF(MONTH = 12, numRecords, 0)) AS 'december',SUM(IF(MONTH = 12, totaltax, 0)) AS 'decembertax',SUM(IF(MONTH = 12, totaldiscount, 0)) AS 'decemberdisc',SUM(numRecords) AS total, SUM(totaltax) AS totalstax, SUM(totaldiscount) AS totaldisc FROM ( SELECT id, MONTH(date) AS MONTH, ROUND(sum(total)) AS numRecords, ROUND(sum(taxamount)) AS totaltax, ROUND(sum(discountamount)) AS totaldiscount FROM sales WHERE in_activo=1 AND DATE_FORMAT(date, '%Y') = $year GROUP BY id, MONTH ) AS SubTable1");

        $monthlyExp = Expence::find_by_sql("SELECT SUM(IF(MONTH = 1, numRecords, 0)) AS 'january', SUM(IF(MONTH = 2, numRecords, 0)) AS 'feburary', SUM(IF(MONTH = 3, numRecords, 0)) AS 'march', SUM(IF(MONTH = 4, numRecords, 0)) AS 'april', SUM(IF(MONTH = 5, numRecords, 0)) AS 'may', SUM(IF(MONTH = 6, numRecords, 0)) AS 'june', SUM(IF(MONTH = 7, numRecords, 0)) AS 'july', SUM(IF(MONTH = 8, numRecords, 0)) AS 'august', SUM(IF(MONTH = 9, numRecords, 0)) AS 'september', SUM(IF(MONTH = 10, numRecords, 0)) AS 'october', SUM(IF(MONTH = 11, numRecords, 0)) AS 'november', SUM(IF(MONTH = 12, numRecords, 0)) AS 'december', SUM(numRecords) AS total FROM ( SELECT id, MONTH(date) AS MONTH, ROUND(sum(amount)) AS numRecords FROM expences WHERE DATE_FORMAT(date, '%Y') = $year AND category_id not in (9, 11) GROUP BY id, MONTH ) AS SubTable1");
        
        $this->view_data['customers'] = Customer::all();
        $this->view_data['Products'] = Product::all();
        $this->view_data['Stores'] = Store::all();
        $this->view_data['Warehouses'] = Warehouse::all();
        $this->view_data['monthly'] = $monthlySales;
        $this->view_data['monthlyExp'] = $monthlyExp;
        $this->view_data['salesExpences'] = $salesExpences;
        $this->view_data['date'] = $date;
        $this->view_data['year'] = $year;
        $this->view_data['month'] = $month;
        $this->view_data['tituloStat'] = "Estadísticas ".$strDate;
        $this->view_data['tituloTop'] = $top." Productos TOP ".$strDate;
        $this->view_data['lapso'] = $lapso;
        $this->view_data['top'] = $top;
        $this->view_data['Top5product'] = $Top5product;
        $this->view_data['TodaySales'] = number_format((float)$TodaySales[0]->sum, $this->setting->decimals, '.', '');
        $this->view_data['CustomerNumber'] = Customer::count();
        ;
        $this->view_data['CategoriesNumber'] = Category::count();
        ;
        $this->view_data['ProductNumber'] = Product::count();
        ;
        $this->content_view = 'stats';
    }
}
