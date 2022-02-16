<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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

    public function detail($id = '')
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            // if (is_client()) {
            //     redirect($this->session->userdata('role') . '/home', 'refresh');
            // }
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

            if (!empty($id)) {
                $data['user_detail'] = $user_detail = ($this->ion_auth->logged_in()) ? $this->ion_auth->user($id)->row() : array();
            } else {
                $data['user_detail'] =  $user_detail = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            }

            if (empty($user_detail)) {
                redirect($this->session->userdata('role') . '/users/detail', 'refresh');
            }

            $workspace_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $workspace_ids);

            $workspace_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($workspace_ids);
            if (!empty($workspace)) {
                if (!$this->session->has_userdata('workspace_id')) {
                    $this->session->set_userdata('workspace_id', $workspace[0]->id);
                }
                $data['is_admin'] =  $this->ion_auth->is_admin();
                $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
                $this->load->view('admin/user-detail', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }

    function deactive($id = '')
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $id = !empty($id) ? $id : $this->uri->segment(4);

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && !empty($id) && is_numeric($id)) {
            $activation = $this->ion_auth->deactivate($id);

            if ($activation) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = $this->ion_auth->messages();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = true;
                $response['message'] = $this->ion_auth->errors();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            }
        } else {

            $response['error'] = true;
            $response['message'] = $this->ion_auth->errors();
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }

    function activate($id = '')
    {
        $id = !empty($id) ? $id : $this->uri->segment(4);

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && !empty($id) && is_numeric($id)) {
            $activation = $this->ion_auth->activate($id);

            if ($activation) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = $this->ion_auth->messages();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = true;
                $response['message'] = $this->ion_auth->errors();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            }
        } else {

            $response['error'] = true;
            $response['message'] = $this->ion_auth->errors();
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }


    public function get_users_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            return $this->users_model->get_users_list($workspace_id, $user_id);
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
            $data['role'] = $role;
            $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
            $this->load->view('admin/users', $data);
        }
    }

    function search_user_by_email($email = '')
    {
        if ($this->ion_auth->logged_in() && !empty(trim($email))) {
            $data = $this->users_model->get_users_by_email($email);
            if (!empty($data) && isset($data[0]['password'])) {
                unset($data[0]['password']);

                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();

                print_r(json_encode($data));
            } else {

                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();

                print_r(json_encode($data));
            }
        } else {
            return false;
        }
    }

    function get_user_by_id($id = '')
    {
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $data = $this->users_model->get_user_by_id($id);
            if (!empty($data) && isset($data[0]['password'])) {
                unset($data[0]['password']);

                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                $data[0]['name'] = $data[0]['first_name'] . ' ' . $data[0]['last_name'];

                print_r(json_encode($data));
            } else {

                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                $data[0]['name'] = $data[0]['first_name'] . ' ' . $data[0]['last_name'];

                print_r(json_encode($data));
            }
        } else {


            $data[0]['email'] = '';
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            print_r(json_encode($data));
        }
    }

    function get_user_by_email($email = '')
    {
        if ($this->ion_auth->logged_in() && !empty($email)) {
            $data = $this->users_model->get_user_by_email($email);
            if (!empty($data) && isset($data[0]['password'])) {
                unset($data[0]['password']);

                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($data));
            } else {
                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                $data[0]['email'] = '';
                print_r(json_encode($data));
            }
        } else {


            $data[0]['email'] = '';
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();

            print_r(json_encode($data));
        }
    }

    function make_user_admin($id = '')
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $id = !empty($id) ? $id : $this->uri->segment(4);
        $workspace_id = $this->session->userdata('workspace_id');
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            if ($this->users_model->make_user_admin($workspace_id, $id)) {
                $this->session->set_flashdata('message', 'Member Added as a Admin.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', 'Admin Added as a Member.');
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = true;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            }
        } else {

            $response['error'] = true;
            $response['message'] = 'Successful';
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }

    function make_user_super_admin($id = '')
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $id = !empty($id) ? $id : $this->uri->segment(4);
        $workspace_id = $this->session->userdata('workspace_id');
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            if ($this->users_model->make_user_admin($workspace_id, $id)) {

                $this->users_model->make_user_super_admin($id);

                $this->session->set_flashdata('message', 'Member Added as a Super Admin.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', 'Super Admin Added as a Member.');
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = true;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            }
        } else {

            $response['error'] = true;
            $response['message'] = 'Successful';
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }
    function remove_user_from_admin($id = '')
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $id = !empty($id) ? $id : $this->uri->segment(4);
        $workspace_id = $this->session->userdata('workspace_id');
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $is_admin = ($this->ion_auth->is_admin()) ? true : '';
            $user_id = $this->session->userdata('user_id');
            if (is_admin()) {
                $user_package_id = get_user_package_id_by_user_id($user_id);
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                $user_package_id = get_user_package_id_by_user_id($admin_id);
            }
            $max_employees = get_max_employees_by_user_package_id($user_package_id['id']);
            $max_employees = $max_employees['allowed_employees'];
            $total_employees = get_total_employees($workspace_id);
            $total_employees = $total_employees['total'];

            if ($total_employees >= $max_employees && $max_employees != 0 && !empty($max_employees)) {
                $response['error'] = true;

                $this->session->set_flashdata('message', 'Max employees limit reached');
                $this->session->set_flashdata('message_type', 'error');
                echo json_encode($response);
                return false;
            }
            if ($this->users_model->remove_user_from_admin($workspace_id, $id, $is_admin)) {
                $this->session->set_flashdata('message', 'Member removed from Admin.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            } else {

                $this->session->set_flashdata('message', 'Member Added as an Admin.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = true;
                $response['message'] = 'Successful';
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($response);
            }
        } else {

            $response['error'] = true;
            $response['message'] = 'Successful';
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($response);
        }
    }

    function remove_user_from_workspace($id = '')
    {

        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $id = !empty($id) ? $id : $this->uri->segment(4);
        $workspace_id = $this->session->userdata('workspace_id');
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            if ($this->users_model->remove_user_from_workspace($workspace_id, $id, 'remove')) {
                $this->session->set_flashdata('message', 'Member removed from Workspace.');
                $this->session->set_flashdata('message_type', 'success');
                if ($id == $this->session->userdata('user_id')) {
                    $this->session->unset_userdata('workspace_id');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                } else {
                    redirect($this->session->userdata('role') . '/users', 'refresh');
                }
                return true;
            } else {
                $this->session->set_flashdata('message', 'Not Successful.');
                $this->session->set_flashdata('message_type', 'error');
                return false;
            }
        } else {
            return false;
        }
        redirect($this->session->userdata('role') . '/home', 'refresh');
    }

    public function edit_profile()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
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


            $u_id = $this->uri->segment(4);
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            if (!empty($u_id) && is_numeric($u_id) && $u_id > 0) {
                $data['user_details'] = $this->users_model->get_user_by_id($u_id, true);
                $product_ids = explode(',', $user->workspace_id);

                $section = array_map('trim', $product_ids);

                $product_ids = $section;

                $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
                if (isset($workspace[0]) && !empty($workspace[0])) {
                    if (!$this->session->has_userdata('workspace_id')) {
                        $this->session->set_userdata('workspace_id', $workspace[0]->id);
                    }
                    $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
                    $data['is_admin'] =  $this->ion_auth->is_admin();
                    $this->load->view('admin/edit-profile', $data);
                } else {
                    $this->session->set_flashdata('message', NO_WORKSPACE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $this->session->set_flashdata('message', 'Invalid access detected!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }
    public function get_user_data()
    {
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $workspace_id = $this->session->userdata('workspace_id');
            $data = $this->users_model->get_user_data($workspace_id);
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
}
