<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privacy_policy extends CI_Controller
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
            $this->load->view('super-admin/privacy-policy', $data);
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
        $privacy_policy = get_system_settings('privacy_policy');
        if (!empty($privacy_policy)) {
            $data = array(
                'data' => $this->input->post('privacy_policy', true)
            );
        } else {
            $data = array(
                'type' => 'privacy_policy',
                'data' => $this->input->post('privacy_policy', true)
            );
        }

        if ($this->settings_model->save_settings('privacy_policy', $data)) {
            $this->session->set_flashdata('message', 'Privacy policy saved successfully.');
            $this->session->set_flashdata('message_type', 'success');
        } else {
            $this->session->set_flashdata('message', 'Privacy policy could not saved! Try again!');
            $this->session->set_flashdata('message_type', 'error');
        }
        $response['error'] = false;
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $response['message'] = 'Successful';
        echo json_encode($response);
    }
}
