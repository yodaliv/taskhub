<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Crone_jobs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->model(['']);
        $this->load->helper(['url', 'language', 'form']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    public function projects_deadline_reminder()
    {
        $this->db->select('id,title,user_id,client_id');
        $this->db->where('end_date', date("Y-m-d"));
        $query = $this->db->get('projects');
        $result = $query->result_array();
        $subject = 'Project deadline reminder';
        if (isset($result[0]) && !empty($result[0])) {
            foreach ($result as $res) {
                $ids = $res['user_id'] . ',' . $res['client_id'];
                $ids = explode(",", $ids);
                $ids = array_unique($ids);
                $project_title = $res['title'];
                $project_id = $res['id'];
                foreach ($ids as $id) {
                    $this->db->select('first_name,last_name,email');
                    $this->db->where('id', $id);
                    $query = $this->db->get('users');
                    $result = $query->result_array();

                    if (!empty($result[0]['email'])) {
                        $to_email = $result[0]['email'];
                        $message = "<p>Hello Dear <b>" . $result[0]['first_name'] . " " . $result[0]['last_name'] . "</b>, today is the deadline of the project <b>" . $project_title . "</b>, ID <b>#" . $project_id . "</b> assigned to you please take note of it. <a href=".base_url('admin/projects/details/'.$project_id)." target='_blank'>Click here</a> to see more</p>
                                <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
                        $this->send_mail($to_email, $subject, $message);
                    }
                }
            }
        }
    }
    public function tasks_deadline_reminder()
    {
        $this->db->select('id,title,user_id');
        $this->db->where('due_date', date("Y-m-d"));
        $query = $this->db->get('tasks');
        $result = $query->result_array();
        $subject = 'Task deadline reminder';
        if (isset($result[0]) && !empty($result[0])) {
            foreach ($result as $res) {
                $ids = $res['user_id'];
                $ids = explode(",", $ids);
                $ids = array_unique($ids);
                $task_title = $res['title'];
                $task_id = $res['id'];
                foreach ($ids as $id) {
                    $this->db->select('first_name,last_name,email');
                    $this->db->where('id', $id);
                    $query = $this->db->get('users');
                    $result = $query->result_array();

                    if (!empty($result[0]['email'])) {
                        $to_email = $result[0]['email'];
                        $message = "<p>Hello Dear <b>" . $result[0]['first_name'] . " " . $result[0]['last_name'] . "</b>, today is the deadline of the task <b>" . $task_title . "</b>, ID <b>#" . $task_id . "</b> assigned to you please take note of it. <a href=".base_url('admin/projects/tasks/'.$task_id)." target='_blank'>Click here</a> to see more</p>
                                <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
                        $this->send_mail($to_email, $subject, $message);
                    }
                }
            }
        }
    }
    public function plan_expiration_reminder()
    {
        // $user_ids = '';
        $this->db->select('id,title,user_id');
        $this->db->where('to_date', date("Y-m-d"));
        $query = $this->db->get('users_packages');
        $result = $query->result_array();
        $subject = 'Plan expiration reminder';
        foreach ($result as $res) {
            $this->db->select('first_name,last_name,email');
            $this->db->where('id', $res['user_id']);
            $query = $this->db->get('users');
            $result = $query->result_array();

            if (!empty($result[0]['email'])) {
                $to_email = $result[0]['email'];
                $message = "<p>Hello Dear <b>" . $result[0]['first_name'] . " " . $result[0]['last_name'] . "</b>,your plan <b>" . $res['title'] . "</b>, ID <b>#" . $res['id'] . "</b> expiring today please take note of it.</p>
                        <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
                $this->send_mail($to_email, $subject, $message);
            }
        }
    }
    public function send_mail($to, $subject, $message)
    {
        $this->email->clear(TRUE);
        $config = $this->config->item('email_config');
        if (isset($config['smtp_user']) && !empty($config['smtp_user'])) {
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $from_email = get_admin_email();
            $this->email->from($from_email, get_compnay_title());
            $this->email->to($to);
            $this->email->subject($subject);
            $data['logo'] = base_url('assets/backend/icons/') . get_compnay_logo();
            $data['company_title'] = get_compnay_title();
            $data['heading'] = "<h1>" . $subject . "</h1>";
            $data['message'] = $message;
            $this->email->message($this->load->view('admin/project-task-deadline-reminder-email-template.php', $data, true));
            $this->email->send();
        }
        
    }
}
