<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clients extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'notifications_model']);
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
            $role = $this->session->userdata('role');
            if (is_client()) {
                redirect($role . '/home', 'refresh');
            }
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (is_admin()) {
                if (check_module($user_id, 'users') == 0) {
                    $this->session->set_flashdata('message', ERROR_MESSAGE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                    return false;
                    exit();
                }
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                if (check_module($admin_id, 'users') == 0) {
                    $this->session->set_flashdata('message', NO_ACTIVE_PLAN);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            }

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $workspace_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $workspace_ids);

            $workspace_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($workspace_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $data['is_admin'] =  $this->ion_auth->is_admin();

            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $section = array_map('trim', $user_ids);
            $user_ids = $section;

            $data['all_user'] = $this->users_model->get_user($user_ids);
            $data['not_in_workspace_user'] = $this->users_model->get_user_not_in_workspace($user_ids);

            $admin_ids = explode(',', $current_workspace_id[0]->admin_id);
            $section = array_map('trim', $admin_ids);
            $data['admin_ids'] = $admin_ids = $section;

            $super_admin_ids = $this->users_model->get_all_super_admins_id(1);

            foreach ($super_admin_ids as $super_admin_id) {
                $temp_ids[] = $super_admin_id['user_id'];
            }
            $data['super_admin_ids'] = $temp_ids;
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('admin/clients', $data);
            } else {
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }


    public function get_users_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            if (is_client()) {
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            return $this->users_model->get_users_list($workspace_id, $user_id);
        }
    }
}
