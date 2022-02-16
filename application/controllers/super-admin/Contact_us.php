<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact_us extends CI_Controller
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
            $this->load->view('super-admin/contact-us', $data);
        }
    }

    public function update()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
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
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();            
            $response['message'] = ERR_ALLOW_MODIFICATION;            
            echo json_encode($response);
            return false;
            exit();
        }

        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('contact_no', 'Contact no.', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->form_validation->run() === TRUE) {
            $contact_us = get_system_settings('contact_us');
            $data = array(
                'address' => strip_tags($this->input->post('address', true)),
                'contact_no' => strip_tags($this->input->post('contact_no', true)),
                'email' => strip_tags($this->input->post('email', true))
            );
            $data = escape_array($data);
            if (!empty($contact_us)) {
                $data = array(
                    'data' => json_encode($data)
                );
            } else {
                $data = array(
                    'type' => 'contact_us',
                    'data' => json_encode($data)
                );
            }
            if ($this->settings_model->save_settings('contact_us', $data)) {
                $this->session->set_flashdata('message', 'Contact us saved successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Contact us could not saved! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }
}
