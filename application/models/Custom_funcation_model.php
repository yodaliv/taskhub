<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Custom_funcation_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function format_size_units($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function get_count($field, $table, $where = '')
    {
        if (!empty($where))
            $where = "where " . $where;

        $query = $this->db->query("SELECT COUNT(" . $field . ") as total FROM " . $table . " " . $where . " ");
        $res = $query->result_array();
        if (!empty($res)) {
            return $res[0]['total'];
        }
    }

    function get_total_earning()
    {
        $query = $this->db->query("SELECT SUM(amount) as total FROM users_packages");
        $res = $query->result_array();
        if (!empty($res)) {
            return $res[0]['total'];
        }
    }

    function get_earning_summary()
    {
        $year = date("Y");
        $curdate = date('Y-m-d');
        $query = $this->db->query("SELECT SUM(amount) AS total_sale,DATE(date_created) AS purchase_date FROM users_packages WHERE YEAR(date_created) = '$year' AND DATE(date_created)<='$curdate' GROUP BY DATE(date_created) ORDER BY DATE(date_created) DESC  LIMIT 0,7");
        return $query->result_array();
    }

    function get_package_wise_earning()
    {
        $query = $this->db->query("SELECT title,sum(amount) as amount FROM `users_packages` group by title");
        return $query->result_array();
    }

    function get_packages_earnings()
    {

        $query = $this->db->query("SELECT p.id,p.title,sum(amount) as total from packages p left join users_packages up on p.id=up.package_id group by p.id");
        return $query->result_array();
    }

    function get_subscribed_packages_count()
    {

        $query = $this->db->query("SELECT COUNT(DISTINCT package_id) AS total FROM users_packages");
        $res = $query->result_array();
        if (!empty($res)) {
            return $res[0]['total'];
        }
    }

    function get_package_titles()
    {

        $query = $this->db->query("SELECT title from packages");
        $res = $query->result_array();
        return $res;
    }
    function get_earning($date)
    {
        $query = $this->db->query("SELECT sum(amount) as total from users_packages where date(date_created)='$date'");
        $res = $query->result_array();
        return $res[0]['total'];
    }
    function get_week_earning()
    {
        $d = strtotime("today");
        $start_week = strtotime("last sunday midnight", $d);
        $end_week = strtotime("next saturday", $d);
        $start = date("Y-m-d", $start_week);
        $end = date("Y-m-d", $end_week);
        $query = $this->db->query("SELECT sum(amount) as total from users_packages where date(date_created) >= '$start' and date(date_created) <= '$end'");

        $res = $query->result_array();
        return $res[0]['total'];
    }

    function get_month_earning($month)
    {
        $query = $this->db->query("SELECT sum(amount) as total from users_packages where month(date_created) = '$month'");
        $res = $query->result_array();
        return $res[0]['total'];
    }
    function get_year_earning($year)
    {
        $query = $this->db->query("SELECT sum(amount) as total from users_packages where year(date_created) = '$year'");
        $res = $query->result_array();
        return $res[0]['total'];
    }
}
