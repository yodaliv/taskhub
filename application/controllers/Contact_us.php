<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
ini_set('display_errors', 0);

class Contact_us extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function index()
    {

        $this->load->view('contact-us');
    }
    function send_mail()
    {
        $this->form_validation->set_rules('full_name', 'Full name', 'required');
        $this->form_validation->set_rules('mobile_no', 'Mobile no.', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() === FALSE) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                return false;
                exit();
            }
        }
        $mail_config = $this->mail_config();
        $this->email->initialize($mail_config);
        $this->email->set_newline("\r\n");
        $from_email = get_admin_email();
        $recepient = $this->input->post('email');
        $this->email->from($from_email, get_compnay_title());
		$this->email->reply_to($recepient, $this->input->post('full_name',true));
        $this->email->to($from_email);
        $subject = $this->input->post('subject');
        $message = '<b>Full name : </b>' . $this->input->post('full_name') . '<br>';
        $message .= '<b>Email : </b>' . $this->input->post('email') . '<br>';
        $message .= '<b>Mobile no. : </b>' . $this->input->post('mobile_no') . '<br>';
        $message .= '<b>Message : </b>' . $this->input->post('message') . '';
        $this->email->message($message);
        $this->email->subject($subject);
        if (!$this->email->send()) {
            $response['error'] = true;
            $response['message'] = "<p class='alert alert-danger'>Opps!!Something Went Wrong. Please Try Again Later. Sorry For Inconvenience.</p>";
			// $response['message'] = $this->email->print_debugger();
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $this->session->set_flashdata('message', strip_tags($this->email->print_debugger()));
            $this->session->set_flashdata('message_type', 'success');
			echo json_encode($response);
            return false;
            exit();
        } else {
            $response['error'] = false;
            $this->session->set_flashdata('message', 'Mail Sent Successfully We Will Reply You Soon!!.');
            $this->session->set_flashdata('message_type', 'success');
            echo json_encode($response);
            return false;
            exit();
        }
    }

    function quick_enquiry()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            if (validation_errors()) {
                $response['error'] = true;
                $response['message'] = validation_errors();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
                return false;
                exit();
            }
        }
        $mail_config = $this->mail_config();
        $this->email->initialize($mail_config);
		$this->email->set_newline("\r\n");
        $from_email = get_admin_email();
        $recepient = $this->input->post('email');
        $this->email->from($from_email, get_compnay_title());
        $this->email->reply_to($recepient, get_compnay_title());
        $this->email->to($from_email);
        $subject = 'Quick enquiry - '.get_compnay_title();
        $message = '<b>Email : </b>' . $this->input->post('email') . '';
        $this->email->message($message);
        $this->email->subject($subject);
		
        if (!$this->email->send()) {
            $response['error'] = true;
            // $response['message'] = $this->email->print_debugger();
            $response['message'] = "Opps!!Something Went Wrong";
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            return false;
            exit();
        } else {
            $response['error'] = false;
            $response['message'] = "Mail Sent Successfully";
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
            return false;
            exit();
        }
    }
    function mail_config()
    {
        $mail_config['smtp_host'] = get_smtp_host();
        $mail_config['smtp_port'] = get_smtp_port();
        $mail_config['smtp_user'] = get_admin_email();
        $mail_config['_smtp_auth'] = TRUE;
        $mail_config['smtp_pass'] = get_smtp_pass();
        $mail_config['smtp_crypto'] = 'tls';
        $mail_config['protocol'] = 'smtp';
        $mail_config['mailtype'] = get_mail_type();
        $mail_config['send_multipart'] = FALSE;
        $mail_config['charset'] = 'utf-8';
        $mail_config['wordwrap'] = TRUE;
        return $mail_config;
    }
}
