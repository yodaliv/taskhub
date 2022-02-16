<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tasks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'projects_model', 'milestones_model', 'notifications_model']);
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

    public function get_tasks_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            return $this->tasks_model->get_tasks_list();
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            if (check_module($user_id, 'tasks') == 0 && is_admin()) {
                $this->session->set_flashdata('message', ERROR_MESSAGE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
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
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }

            $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
            $user_ids = explode(',', $current_workspace_id[0]->user_id);
            $data['all_user'] = $this->users_model->get_user($user_ids);

            if (!empty($this->session->has_userdata('workspace_id'))) {

                $data['total_user'] = $this->custom_funcation_model->get_count('id', 'users', 'FIND_IN_SET(' . $this->session->userdata('workspace_id') . ', workspace_id)');

                $data['total_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id'));

                $data['todo_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="todo"');

                $data['inprogress_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="inprogress"');

                $data['review_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="review"');

                $data['done_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="done"');

                $data['total_project'] = $total_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id'));

                $data['notes'] = $notes = $this->custom_funcation_model->get_count('id', 'notes', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and user_id=' . $this->session->userdata('user_id') . '');
            } else {
                $data['total_user'] = 0;

                $data['total_task'] = 0;

                $data['todo_task'] = 0;

                $data['inprogress_task'] = 0;

                $data['review_task'] = 0;

                $data['done_task'] = 0;

                $data['total_project'] = $total_project = 0;

                $data['notes'] = $notes = 0;
            }

            if ($total_project != 0) {
                $finished_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="finished"');
                $finished_project = $finished_project * 100 / $total_project;
                $data['finished_project'] = bcdiv($finished_project, 1, 2);

                $ongoing_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="ongoing"');
                $ongoing_project =  $ongoing_project * 100 / $total_project;
                $data['ongoing_project'] = bcdiv($ongoing_project, 1, 2);

                $onhold_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="onhold"');
                $onhold_project = $onhold_project * 100 / $total_project;
                $data['onhold_project'] = bcdiv($onhold_project, 1, 2);
            } else {
                $data['finished_project'] = 0;
                $data['ongoing_project'] = 0;
                $data['onhold_project'] = 0;
            }

            $user_names = $this->users_model->get_user_names();
            $data['user_names'] = $user_names;
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $data['is_admin'] =  $this->ion_auth->is_admin();
                $this->load->view('admin/tasks-list', $data);
            } else {
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }
}
