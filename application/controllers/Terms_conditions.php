<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms_conditions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {

        $terms_conditions = get_system_settings('terms_conditions');
        $data['terms_conditions'] = $terms_conditions[0]['data'];
        $this->load->view('terms-conditions', $data);
    }
}
