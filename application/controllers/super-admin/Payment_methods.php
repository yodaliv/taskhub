<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_methods extends CI_Controller
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
            $this->load->view('super-admin/payment-methods', $data);
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
        $paypal_payment_method = get_system_settings('paypal_payment_method');
        $data = array(
            'paypal_mode' => strip_tags($this->input->post('paypal_mode', true)),
            'currency_code' => strip_tags($this->input->post('currency_code', true)),
            'business_email' => strip_tags($this->input->post('business_email', true)),
            'status' => strip_tags($this->input->post('status', true)),
            'currency_code' => strip_tags($this->input->post('currency_code', true))
        );
        $data = escape_array($data);
        if (!empty($paypal_payment_method)) {
            $data = array(
                'data' => json_encode($data)
            );
        } else {
            $data = array(
                'type' => 'paypal_payment_method',
                'data' => json_encode($data)
            );
        }
        if ($this->settings_model->save_settings('paypal_payment_method', $data)) {
            $saved_paypal = 1;
        }

        $razorpay_payment_method = get_system_settings('razorpay_payment_method');
        $data = array(
            'razorpay_key_id' => strip_tags($this->input->post('razorpay_key_id', true)),
            'razorpay_secret_key' => strip_tags($this->input->post('razorpay_secret_key', true)),
            'razorpay_status' => strip_tags($this->input->post('razorpay_status', true))
        );
        $data = escape_array($data);
        if (!empty($razorpay_payment_method)) {
            $data = array(
                'data' => json_encode($data)
            );
        } else {
            $data = array(
                'type' => 'razorpay_payment_method',
                'data' => json_encode($data)
            );
        }
        if ($this->settings_model->save_settings('razorpay_payment_method', $data)) {
            $saved_razorpay = 1;
        }

        $stripe_payment_method = get_system_settings('stripe_payment_method');
        $data = array(
            'stripe_payment_mode' => strip_tags($this->input->post('stripe_payment_mode', true)),
            'stripe_currency_code' => strip_tags($this->input->post('stripe_currency_code', true)),
            'stripe_publishable_key' => strip_tags($this->input->post('stripe_publishable_key', true)),
            'stripe_secret_key' => strip_tags($this->input->post('stripe_secret_key', true)),
            'stripe_webhook_secret_key' => strip_tags($this->input->post('stripe_webhook_secret_key', true)),
            'stripe_status' => strip_tags($this->input->post('stripe_status', true))
        );
        $data = escape_array($data);
        if (!empty($stripe_payment_method)) {
            $data = array(
                'data' => json_encode($data)
            );
        } else {
            $data = array(
                'type' => 'stripe_payment_method',
                'data' => json_encode($data)
            );
        }
        if ($this->settings_model->save_settings('stripe_payment_method', $data)) {
            $saved_stripe = 1;
        }
        if ($saved_stripe == 1 && $saved_razorpay == 1 && $saved_paypal == 1) {
            $this->session->set_flashdata('message', 'Information saved successfully!');
            $this->session->set_flashdata('message_type', 'success');
        } else {
            $this->session->set_flashdata('message', 'Information could not saved! Try again!');
            $this->session->set_flashdata('message_type', 'error');
        }
        $response['error'] = false;
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        $response['message'] = 'Successful';
        echo json_encode($response);
    }
}
