<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2021.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.0                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
if (!defined('IN_PHPMEGATEMP_CP')){exit;}

class admin_dashboard
{
    public function index_dashboard()
    {
        global $hooks, $db;
        $day    = date('d');
        $month  = date('m');
        $year   = date('Y');
        admin_header(get_admin_languages('dashboard'));
        admin_content_header(array('title' => get_admin_languages('dashboard')));
        admin_content_section_start(false);
        echo '<div class="row">';
        $hooks->do_action( 'admin_dashboard_stat_boxes' );
        echo '</div>
        <div class="row">';
        display_dashboard_info_box2(array(
            'col'           => 'col-md-4 col-sm-4 col-xs-12',
            'icon'          => 'far fa-chart-bar',
            'title'         => get_admin_languages('visits_the_year'),
            'color'         => '1',
            'number_pre'    => '100',
            'number_count'  => $this->get_numrows(COUNTER_TABLE,"WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y')='{$year}'"),
        ));
        display_dashboard_info_box2(array(
            'col'           => 'col-md-4 col-sm-4 col-xs-12',
            'icon'          => 'far fa-chart-bar',
            'title'         => get_admin_languages('visits_the_month'),
            'color'         => '2',
            'number_pre'    => $this->get_visits_pre('month'),
            'number_count'  => $this->get_numrows(COUNTER_TABLE,"WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m')='{$year}{$month}'"),
        ));
        display_dashboard_info_box2(array(
            'col'           => 'col-md-4 col-sm-4 col-xs-12',
            'icon'          => 'far fa-chart-bar',
            'title'         => get_admin_languages('visits_the_day'),
            'color'         => '3',
            'number_pre'    => $this->get_visits_pre('day'),
            'number_count'  => $this->get_numrows(COUNTER_TABLE,"WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m%d')='{$year}{$month}{$day}'"),
        ));
        admin_content_section_end();
        admin_footer();
    }
    
    public function get_numrows($table, $where = '')
    {
        global $db;
        $sql = "SELECT * FROM {$table} {$where}";
        $num = $db->sql_numrows($sql);
        return ($num)? $num : '0';
    }

    public function get_visits_pre($type, $day = false, $month = false, $year = false)
    {
        global $db;
        $day            = ($day)? $day : date('d');
        $month          = ($month)? $month : date('m');
        $year           = ($year)? $year : date('Y');
        $total_year     = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y')='{$year}'");
        $total_month    = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m')='{$year}{$month}'");
        $total_day      = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m%d')='{$year}{$month}{$day}'");
        if($type == 'month' and ($total_month or $total_year))
        {
            $per = ( $total_month / $total_year  ) * 100;
            return format_num($per,2);
        }
        elseif($type == 'day' and ($total_month or $total_day))
        {
            $per = ( $total_day / $total_month  ) * 100;
            return format_num($per,2);
        }
        else
        {
            return '0';
        }
    }
}
?>