<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkout extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'notifications_model', 'packages_model']);
        $this->load->library(['ion_auth', 'form_validation', 'paypal_lib']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        if ($this->ion_auth->logged_in()) {
            if (is_admin()) {
                $admin_id = $this->session->userdata('user_id');
            } else {
                $admin_id = get_admin_id_by_workspace_id($this->session->userdata('workspace_id'));
            }
            $this->data['admin_id'] = $admin_id;
        } else {
            redirect('auth', 'refresh');
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else
		if (is_admin()) {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            if (isset($_POST['package_id']) && !empty($_POST['package_id']) && is_numeric($_POST['package_id']) && isset($_POST['tenure_id']) && !empty($_POST['tenure_id']) && is_numeric($_POST['tenure_id'])) {
                $package = $this->packages_model->get_package_by_id($_POST['package_id']);
                $data['package'] = $package[0];
                $tenure = $this->packages_model->get_tenure_by_id($_POST['tenure_id']);
                $data['tenure'] = $tenure[0];
            }
            $current_package = $this->packages_model->get_current_package($user_id);
            if (!empty($current_package) && isset($current_package[0])) {
                $data['current_package'] = $current_package[0];
            }
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $data['is_admin'] =  is_admin();
            $data['role'] =  $this->session->userdata('role');
            $this->load->view('admin/checkout', $data);
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }
}
