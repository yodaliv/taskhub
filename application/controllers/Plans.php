<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Plans extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['packages_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {
        $data['packages'] = $this->packages_model->get_packages();
        $this->load->view('plans', $data);
    }
}
