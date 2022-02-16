<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_super()) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }

            $data['packages_earnings'] = $this->custom_funcation_model->get_packages_earnings();
            $data['package_wise_earnings'] = $this->custom_funcation_model->get_package_wise_earning();
            $data['total_packages'] = $this->custom_funcation_model->get_count('id', 'packages');
            $data['total_subscriptions'] = $this->custom_funcation_model->get_count('id', 'users_packages');
            $data['active_subscriptions'] = $this->custom_funcation_model->get_count('id', 'users_packages', 'NOW() BETWEEN from_date and to_date');
            $data['upcoming_subscriptions'] = $this->custom_funcation_model->get_count('id', 'users_packages', 'from_date > CURDATE()');
            $data['expired_subscriptions'] = $this->custom_funcation_model->get_count('id', 'users_packages', 'to_date < CURDATE()');
            $data['active_packages'] = $this->custom_funcation_model->get_count('id', 'packages', 'status=1');
            $data['deactive_packages'] = $this->custom_funcation_model->get_count('id', 'packages', 'status=0');
            $data['subscribed_packages'] = $this->custom_funcation_model->get_subscribed_packages_count();
            $data['total_user'] = $this->custom_funcation_model->get_count('id', 'users_groups', 'group_id=1');
            $data['total_earning'] = $this->custom_funcation_model->get_total_earning();
            $data['today_earning'] = $this->custom_funcation_model->get_earning(date('Y-m-d'));
            $data['yesterday_earning'] = $this->custom_funcation_model->get_earning(date('Y-m-d', strtotime("-1 days")));
            $data['day_before_yesterday_earning'] = $this->custom_funcation_model->get_earning(date('Y-m-d', strtotime("-2 days")));
            $data['week_earning'] = $this->custom_funcation_model->get_week_earning();
            $data['month_earning'] = $this->custom_funcation_model->get_month_earning(date('m'));
            $data['year_earning'] = $this->custom_funcation_model->get_year_earning(date('Y'));
            $data['earning_summary'] = $this->custom_funcation_model->get_earning_summary();
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->load->view('super-admin/home', $data);
        }
    }
}
