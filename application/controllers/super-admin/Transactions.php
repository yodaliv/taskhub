<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'notifications_model', 'transactions_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
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
            $role = $this->session->userdata('role');
            $data['role'] =  $role;
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->load->view('super-admin/transactions', $data);
        }
    }

    public function get_transaction_list()
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
            return $this->transactions_model->get_transaction_list();
        }
    }
    public function delete_transaction()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin() && !is_super()) {
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/transactions', 'refresh');
            return false;
            exit();
        }
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            $transaction = $this->transactions_model->get_transaction_by_id($id);
            if (isset($transaction[0]) && !empty($transaction[0])) {
                if ($transaction[0]['user_id'] == $this->session->userdata('user_id') || is_super()) {
                    if ($this->transactions_model->delete_transaction($id)) {

                        $this->session->set_flashdata('message', 'Transaction Deleted Successfully.');
                        $this->session->set_flashdata('message_type', 'success');
                        $response['error'] = false;
                    } else {
                        $this->session->set_flashdata('message', 'Transaction could not be deleted! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = true;
                        $response['message'] = 'Transaction could not be deleted! Try again!';
                    }
                } else {
                    $this->session->set_flashdata('message', 'You are not authorized to delete this transaction');
                    $this->session->set_flashdata('message_type', 'error');
                    $response['error'] = true;
                }
            } else {
                $this->session->set_flashdata('message', 'Something went wrong');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
            }
        }
        echo json_encode($response);
    }
}
