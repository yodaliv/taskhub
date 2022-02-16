<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms_conditions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['settings_model']);
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
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->load->view('super-admin/terms-conditions', $data);
        }
    }

    public function update()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = ERR_ALLOW_MODIFICATION;
            echo json_encode($response);
            return false;
            exit();
        }
        if (!is_super()) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = NOT_AUTHORIZED;
            echo json_encode($response);
            return false;
            exit();
        }
        $terms_conditions = get_system_settings('terms_conditions');
        if (!empty($terms_conditions)) {
            $data = array(
                'data' => $this->input->post('terms_conditions', true)
            );
        } else {
            $data = array(
                'type' => 'terms_conditions',
                'data' => $this->input->post('terms_conditions', true)
            );
        }
        if ($this->settings_model->save_settings('terms_conditions', $data)) {
            $this->session->set_flashdata('message', 'Terms conditions saved successfully.');
            $this->session->set_flashdata('message_type', 'success');
        } else {
            $this->session->set_flashdata('message', 'Terms conditions could not saved! Try again!');
            $this->session->set_flashdata('message_type', 'error');
        }
        $response['error'] = false;
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $response['message'] = 'Successful';
        echo json_encode($response);
    }
}
