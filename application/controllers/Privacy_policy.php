<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privacy_policy extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->model(['settings_model', 'faqs_model']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
        $data['faqs'] = $this->faqs_model->get_faqs();
        $privacy_policy = get_system_settings('privacy_policy');
        $data['privacy_policy'] = $privacy_policy[0]['data'];
        $this->load->view('privacy-policy', $data);
    }
}
