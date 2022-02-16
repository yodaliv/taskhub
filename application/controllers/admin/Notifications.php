<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifications extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['notifications_model', 'workspace_model', 'users_model']);
        $this->load->library(['ion_auth', 'form_validation']);
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
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (is_admin()) {
            if (check_module($user_id, 'notifications') == 0) {
                $this->session->set_flashdata('message', ERROR_MESSAGE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'notifications') == 0) {
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
        if (!empty($workspace_id)) {
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id);
            $data['user_id'] = $user_id;
            $data['workspace_id'] = $workspace_id;
            $this->load->view('admin/notifications', $data);
        } else {
            $this->session->set_flashdata('message', NO_WORKSPACE);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
    }
    public function mark_all_as_read()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (is_admin()) {
            if (check_module($user_id, 'notifications') == 0) {
                $this->session->set_flashdata('message', ERROR_MESSAGE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'notifications') == 0) {
                $this->session->set_flashdata('message', NO_ACTIVE_PLAN);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
        $user_id = $this->uri->segment(4);
        if (!empty($user_id) && is_numeric($user_id)) {
            if ($this->notifications_model->mark_all_as_read($user_id)) {
                $this->session->set_flashdata('message', 'Marked as read successfully.');
                $this->session->set_flashdata('message_type', 'success');
                $response['error'] = false;
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', 'Something went wrong please try again!');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
                echo json_encode($response);
            }
        }
    }

    public function get_notifications_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            if (check_module($user_id, 'notifications') == 0 && is_admin()) {
                return false;
                exit();
            }
            $workspace_id = $this->session->userdata('workspace_id');
            return $this->notifications_model->get_notifications_list($this->session->userdata('user_id'), $workspace_id);
        }
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (is_admin()) {
            if (check_module($user_id, 'notifications') == 0) {
                $this->session->set_flashdata('message', ERROR_MESSAGE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        } else {
            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'notifications') == 0) {
                $this->session->set_flashdata('message', NO_ACTIVE_PLAN);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
        $notification_id = $this->uri->segment(4);

        if (!empty($notification_id) && is_numeric($notification_id)) {
            $result = $this->notifications_model->delete_notification($notification_id, $this->session->userdata('user_id'));
            if ($result == 1) {
                $this->session->set_flashdata('message', 'Notification deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = 'Notification deleted successfully.';
            }
            if ($result == 0) {
                $this->session->set_flashdata('message', 'Something went wrong please try again.');
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = false;
                $response['message'] = 'Something went wrong please try again.';
            }
            if ($result == 2) {
                $response['error'] = true;
                $response['message'] = 'You are not authorized to delete notifications';
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Something went wrong please try again!';
        }
        echo json_encode($response);
        return false;
        exit(0);
    }

    public function details()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (is_admin()) {
                if (check_module($user_id, 'notifications') == 0) {
                    $this->session->set_flashdata('message', ERROR_MESSAGE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                if (check_module($admin_id, 'notifications') == 0) {
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
            $notification_id = $this->uri->segment(4);

            if (empty($notification_id) || !is_numeric($notification_id) || $notification_id < 1) {
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit(0);
            }


            $notification = $this->notifications_model->get_notification_by_id($notification_id);
            if (!empty($notification) || isset($notification[0])) {
                $user = $this->users_model->get_user_by_id($notification[0]['user_id']);
                $notification_user_ids = explode(',', $notification[0]['user_ids']);
                $data['notification_users'] = $this->users_model->get_user_array_responce($notification_user_ids);
                $data['notification_data'] = $notification[0];
                $data['role'] = $this->session->userdata('role');
                $data['user_name'] = $user[0]['first_name'] . " " . $user[0]['last_name'];
                if (in_array($this->session->userdata('user_id'), $notification_user_ids) || is_admin()) {
                    $this->notifications_model->mark_notification_as_read($notification_id, $this->session->userdata('user_id'));
                    $workspace_id = $this->session->userdata('workspace_id');
                    if (!empty($workspace_id)) {
                        $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                        $this->load->view('admin/notification-details', $data);
                    } else {
                        redirect($this->session->userdata('role') . '/home', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', 'You are not authorized to view this notification!');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit(0);
                }
            } else {
                $this->session->set_flashdata('message', 'Notification doesn\'t exists!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }
}
