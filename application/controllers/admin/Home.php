<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'tasks_model', 'users_model', 'notifications_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        if ($this->ion_auth->logged_in()) {
            if (is_admin()) {
                $admin_id = $this->session->userdata('user_id');
            } else {
                $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
                $product_ids = explode(',', $user->workspace_id);
                $workspace = $this->workspace_model->get_workspace($product_ids);
                if (!empty($workspace)) {
                    if (!$this->session->has_userdata('workspace_id')) {
                        $this->session->set_userdata('workspace_id', $workspace[0]->id);
                    }
                    $admin_id = get_admin_id_by_workspace_id($this->session->userdata('workspace_id'));
                } else {
                    redirect('auth/logout');
                }
            }
            $this->data['admin_id'] = $admin_id;
        } else {
            redirect('auth', 'refresh');
        }
    }

    public function get_tasks_list($id = '')
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = !empty($id && is_numeric($id)) ? $id : (!empty($this->uri->segment(3) && is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : $this->session->userdata('user_id'));
            return $this->tasks_model->get_tasks_list($workspace_id, $user_id);
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $product_ids = explode(',', $user->workspace_id);
            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
                $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
                $data['total_user'] = $this->custom_funcation_model->get_count('id', 'users_groups', 'user_id IN (' . $current_workspace_id[0]->user_id . ') AND (group_id =1 || group_id=2)');
                $data['total_client'] = $this->custom_funcation_model->get_count('id', 'users_groups', 'user_id IN (' . $current_workspace_id[0]->user_id . ') AND group_id=3');
                $data['notes'] = $notes = $this->custom_funcation_model->get_count('id', 'notes', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and user_id=' . $this->session->userdata('user_id') . '');
                $user_id = $this->session->userdata('user_id');
                
                $role = $this->session->userdata('role');
                $workspace_id = $this->session->userdata('workspace_id');
                if (is_admin() || is_workspace_admin($user_id, $workspace_id)) {
                    $total_projects = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id'));
                } elseif (is_member()) {
                    $total_projects = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' AND FIND_IN_SET(' . $user_id . ',user_id)');                    
                } elseif (is_client()) {
                    $total_projects = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' AND FIND_IN_SET(' . $user_id . ',client_id)');
                } else {
                    $total_project = 0;
                }
                if ($total_projects != 0) {
                    if (is_admin() || is_workspace_admin($user_id, $workspace_id)) {

                        /* for admin */
                        $data['total_project'] = $total_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id'));
                        $data['total_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id'));

                        $finished_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="finished"');
                        $data['finished_project_count'] = $finished_project;
                        $finished_project = $finished_project * 100 / $total_project;
                        $data['finished_project'] = bcdiv($finished_project, 1, 2);

                        $ongoing_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="ongoing"');
                        $data['ongoing_project_count'] = $ongoing_project;
                        $ongoing_project =  $ongoing_project * 100 / $total_project;
                        $data['ongoing_project'] = bcdiv($ongoing_project, 1, 2);

                        $onhold_project = $this->custom_funcation_model->get_count('id', 'projects', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="onhold"');
                        $data['onhold_project_count'] = $onhold_project;
                        $onhold_project = $onhold_project * 100 / $total_project;
                        $data['onhold_project'] = bcdiv($onhold_project, 1, 2);

                        $data['todo_task'] = $this->custom_funcation_model->get_count('id', '`tasks`', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="todo"');
                        $data['inprogress_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="inprogress"');
                        $data['review_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="review"');
                        $data['done_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'workspace_id=' . $this->session->userdata('workspace_id') . ' and status="done"');
                    } elseif (is_member()) {
                        /* for member */
                        $data['total_project'] = $total_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id'));
                        
                        $data['total_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id'));

                        $finished_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="finished"');
                        $data['finished_project_count'] = $finished_project;
                        $finished_project = $finished_project * 100 / $total_project;                        
                        $data['finished_project'] = bcdiv($finished_project, 1, 2);
                        $ongoing_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="ongoing"');
                        $data['ongoing_project_count'] = $ongoing_project;
                        $ongoing_project =  $ongoing_project * 100 / $total_project;
                        $data['ongoing_project'] = bcdiv($ongoing_project, 1, 2);

                        $onhold_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="onhold"');
                        $data['onhold_project_count'] = $onhold_project;
                        $onhold_project = $onhold_project * 100 / $total_project;
                        $data['onhold_project'] = bcdiv($onhold_project, 1, 2);

                        $data['todo_task'] = $this->custom_funcation_model->get_count('id', '`tasks`', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="todo"');
                        $data['inprogress_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="inprogress"');
                        $data['review_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="review"');
                        $data['done_task'] = $this->custom_funcation_model->get_count('id', 'tasks', 'FIND_IN_SET(' . $user_id . ',user_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="done"');
                    } else {
                        /* for clients */
                        $data['total_project'] = $total_project = $this->custom_funcation_model->get_count('p.id', '`projects` p, users u, users_groups ug', 'u.id = ' . $user->id . ' and u.id = ug.user_id AND FIND_IN_SET( ' . $user->id . ',p.client_id ) and p.workspace_id=' . $this->session->userdata('workspace_id'));
                        $data['total_task'] = $this->custom_funcation_model->get_count('t.id', '`tasks` t, projects p, users u', 'u.id = ' . $user->id . ' and FIND_IN_SET( ' . $user->id . ',p.client_id ) and p.id = t.project_id and p.workspace_id=' . $this->session->userdata('workspace_id'));


                        $finished_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',client_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="finished"');
                        $data['finished_project_count'] = $finished_project;
                        $finished_project = $finished_project * 100 / $total_project;
                        $data['finished_project'] = bcdiv($finished_project, 1, 2);

                        $ongoing_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',client_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="ongoing"');
                        $data['ongoing_project_count'] = $ongoing_project;
                        $ongoing_project =  $ongoing_project * 100 / $total_project;
                        $data['ongoing_project'] = bcdiv($ongoing_project, 1, 2);

                        $onhold_project = $this->custom_funcation_model->get_count('id', 'projects', 'FIND_IN_SET(' . $user_id . ',client_id) AND workspace_id=' . $this->session->userdata('workspace_id') . ' and status="onhold"');
                        $data['onhold_project_count'] = $onhold_project;
                        $onhold_project = $onhold_project * 100 / $total_project;
                        $data['onhold_project'] = bcdiv($onhold_project, 1, 2);


                        $data['todo_task'] = $this->custom_funcation_model->get_count('t.id', '`tasks` t, projects p, users u', 'u.id = ' . $user->id . ' and FIND_IN_SET( ' . $user->id . ',p.client_id ) and p.id = t.project_id and p.workspace_id=' . $this->session->userdata('workspace_id') . ' and t.status="todo"');
                        $data['inprogress_task'] = $this->custom_funcation_model->get_count('t.id', '`tasks` t, projects p, users u', 'u.id = ' . $user->id . ' and FIND_IN_SET( ' . $user->id . ',p.client_id  ) and p.id = t.project_id and p.workspace_id=' . $this->session->userdata('workspace_id') . ' and t.status="inprogress"');
                        $data['review_task'] = $this->custom_funcation_model->get_count('t.id', '`tasks` t, projects p, users u', 'u.id = ' . $user->id . ' and FIND_IN_SET( ' . $user->id . ',p.client_id  ) and p.id = t.project_id and p.workspace_id=' . $this->session->userdata('workspace_id') . ' and t.status="review"');
                        $data['done_task'] = $this->custom_funcation_model->get_count('t.id', '`tasks` t, projects p, users u', 'u.id = ' . $user->id . ' and FIND_IN_SET( ' . $user->id . ',p.client_id  ) and p.id = t.project_id and p.workspace_id=' . $this->session->userdata('workspace_id') . ' and t.status="done"');

                        $data['total_notes'] = $this->custom_funcation_model->get_count('id', 'notes', 'user_id = ' . $user->id . ' and workspace_id=' . $this->session->userdata('workspace_id') . ' ');
                    }
                } else {
                    // $data['total_user'] = 0;

                    // $data['total_client'] = 0;

                    $data['total_task'] = 0;

                    $data['todo_task'] = 0;

                    $data['inprogress_task'] = 0;

                    $data['review_task'] = 0;

                    $data['done_task'] = 0;

                    $data['total_project'] = $total_project = 0;

                    $data['total_notes'] = $notes = 0;
                    $data['finished_project'] = 0;
                    $data['finished_project_count'] = 0;
                    $data['ongoing_project'] = 0;
                    $data['ongoing_project_count'] = 0;
                    $data['onhold_project'] = 0;
                    $data['onhold_project_count'] = 0;
                }
            } else {
                $data['total_user'] = 0;

                $data['total_client'] = 0;

                $data['total_task'] = 0;

                $data['todo_task'] = 0;

                $data['inprogress_task'] = 0;

                $data['review_task'] = 0;

                $data['done_task'] = 0;

                $data['total_project'] = $total_project = 0;

                $data['total_notes'] = $notes = 0;
                $data['finished_project'] = 0;
                $data['finished_project_count'] = 0;
                $data['ongoing_project'] = 0;
                $data['ongoing_project_count'] = 0;
                $data['onhold_project'] = 0;
                $data['onhold_project_count'] = 0;
            }
            $role = $this->session->userdata('role');
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $this->session->userdata('workspace_id'));
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $data['role'] =  $role;
            $this->load->view('admin/home', $data);
        }
    }
}
