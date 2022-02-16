<?php
/* <!--  START 
===============================================
Version :- V.2 		Author : ''    12-AUGUST-2020
=============================================== 
-->
*/
defined('BASEPATH') or exit('No direct script access allowed');

class Units extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'units_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
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
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {

                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            if (is_admin()) {
                if (check_module($user_id, 'finance') == 0) {
                    $this->session->set_flashdata('message', ERROR_MESSAGE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                if (check_module($admin_id, 'finance') == 0) {
                    $this->session->set_flashdata('message', NO_ACTIVE_PLAN);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            }
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
                $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
                $this->load->view('admin/units.php', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }

    public function create()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');

        if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {

            $response['error'] = true;
            $response['message'] = ERROR_MESSAGE;
            echo json_encode($response);
            return false;
            exit();
        }
        if (is_admin()) {
            if (check_module($user_id, 'finance') == 0) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = ERROR_MESSAGE;
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'finance') == 0) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = NO_ACTIVE_PLAN;
                echo json_encode($response);
                return false;
                exit();
            }
        }
        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'description' => strip_tags($this->input->post('description', true)),
                'workspace_id' => $this->session->userdata('workspace_id')
            );
            $unit_id = $this->units_model->add_unit($data);

            if ($unit_id != false) {
                $this->session->set_flashdata('message', 'Unit Added successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Unit could not Created! Try again!');
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


    public function get_unit_by_id($id = '')
    {
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $data = $this->units_model->get_unit_by_id($id);
            if (!empty($data)) {
                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($data[0]);
            } else {
                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($data[0]);
            }
        } else {
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($data[0]);
        }
    }

    public function edit()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        if (is_admin()) {
            if (check_module($user_id, 'finance') == 0) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = ERROR_MESSAGE;
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'finance') == 0) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = NO_ACTIVE_PLAN;
                echo json_encode($response);
                return false;
                exit();
            }
        }

        $this->form_validation->set_rules('title', str_replace(':', '', 'Title is empty.'), 'trim|required');
        $this->form_validation->set_rules('description', str_replace(':', '', 'description is empty.'), 'trim|required');
        $this->form_validation->set_rules('update_id', str_replace(':', '', 'update_id is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'description' => strip_tags($this->input->post('description', true))
            );

            if ($this->units_model->edit_unit($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Unit Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Unit could not Updated! Try again!');
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

    public function get_units_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $workspace_id = $this->session->userdata('workspace_id');
            return $this->units_model->get_units_list($workspace_id);
        }
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (!is_admin() && !is_workspace_admin($user_id,$workspace_id)) {
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $unit_id = $this->uri->segment(4);
        if (!empty($unit_id) && is_numeric($unit_id)  || $unit_id < 1) {
            if ($this->units_model->delete_unit($unit_id)) {
                $this->session->set_flashdata('message', 'Unit deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Unit could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect($this->session->userdata('role') . '/unit', 'refresh');
    }
}
/* 
// END
// ===============================================
// Version : V.2 		Author : ''    23-July-2020
// =============================================== 
*/
