<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faqs extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(['packages_model','faqs_model']);
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
			$role = $this->session->userdata('role');
			$data['role'] =  $role;
			$data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
			$this->load->view('super-admin/faqs', $data);
		}
	}

	public function create()
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

		$this->form_validation->set_rules('question', 'Question', 'trim|required');
		$this->form_validation->set_rules('answer', 'Answer', 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'question' => $this->input->post('question', true),
				'answer' => $this->input->post('answer', true)
			);
			$id = $this->faqs_model->add_faq($data);
			if ($id != false) {
				$this->session->set_flashdata('message', 'FAQ created successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'FAQ could not Created! Try again!');
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
	public function edit()
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

		$this->form_validation->set_rules('question', 'Question', 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'question' => $this->input->post('question', true),
				'answer' => $this->input->post('answer', true),
				'status' => $this->input->post('status', true)
			);
			$id = $this->input->post('id');
			if ($this->faqs_model->update_faq($data, $id)) {
				$this->session->set_flashdata('message', 'FAQ updated successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'FAQ could not updated! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
			$response['error'] = false;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = 'Successful';
			echo json_encode($response);
		} else {
			print_r(validation_errors());
			$response['error'] = true;
			$response['csrfName'] = $this->security->get_csrf_token_name();
			$response['csrfHash'] = $this->security->get_csrf_hash();
			$response['message'] = validation_errors();
			echo json_encode($response);
		}
	}
	
	public function get_faq_list()
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
			return $this->faqs_model->get_faqs_list();
		}
	}
    public function get_faq_by_id()
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

			$id = $this->uri->segment(4);

			if (empty($id) || !is_numeric($id)) {
				redirect($this->session->userdata('role') . '/home', 'refresh');
				return false;
				exit(0);
			}
			$data = $this->faqs_model->get_faq_by_id($id);
			$data[0]['csrfName'] = $this->security->get_csrf_token_name();
			$data[0]['csrfHash'] = $this->security->get_csrf_hash();
			echo json_encode($data[0]);
		}
	}
	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth', 'refresh');
		}
		if (!is_super()) {
			$this->session->set_flashdata('message', NOT_AUTHORIZED);
			$this->session->set_flashdata('message_type', 'error');
			redirect($this->session->userdata('role') . '/home', 'refresh');
			return false;
			exit();
		}
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
			$this->session->set_flashdata('message_type', 'error');
			redirect($this->session->userdata('role') . '/home', 'refresh');
			return false;
			exit();
        }

		$id = $this->uri->segment(4);
		if (!empty($id) && is_numeric($id)  || $id < 1) {
			if ($this->faqs_model->delete_faq($id)) {
				$this->session->set_flashdata('message', 'FAQ deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				$this->session->set_flashdata('message', 'FAQ could not be deleted! Try again!');
				$this->session->set_flashdata('message_type', 'error');
			}
		}
	}
}
