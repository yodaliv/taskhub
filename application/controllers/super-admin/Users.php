<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'notifications_model', 'packages_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function detail($id = '')
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            if (is_super()) {
                $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

                if (!empty($id)) {
                    $data['user_detail'] = $user_detail = ($this->ion_auth->logged_in()) ? $this->ion_auth->user($id)->row() : array();
                } else {
                    $data['user_detail'] =  $user_detail = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
                }
                $current_package = $this->packages_model->get_current_package($id);
                if (!empty($current_package) && isset($current_package[0])) {
                    $data['current_package'] = $current_package[0];
                }

                if (empty($user_detail)) {
                    redirect($this->session->userdata('role') . '/users/detail', 'refresh');
                } else {
                    $this->load->view('super-admin/user-detail', $data);
                }
            } else {
                redirect($this->session->userdata('role') . '/home', 'refresh');
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


    public function get_customers_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (is_super()) {
                return $this->users_model->get_customers_list();
            } else {
                $role = $this->session->userdata('role');
                redirect($role . '/home', 'refresh');
            }
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $role = $this->session->userdata('role');
            if (!is_super()) {
                redirect($role . '/home', 'refresh');
            }

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->load->view('super-admin/users', $data);
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
            if (!is_super()) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $user_id = $this->uri->segment(4);
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            if (!empty($user_id) && is_numeric($user_id)  || $user_id < 1) {
                $data['user_details'] = $this->users_model->get_user_by_id($user_id, true);
                $this->load->view('super-admin/edit-profile', $data);
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

    public function get_subscription_list($id)

    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!empty($id) && is_numeric($id)) {
                if (!is_super()) {
                    $this->session->set_flashdata('message', NOT_AUTHORIZED);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
                return $this->packages_model->get_subscription_list($id);
            } else {
                $this->session->set_flashdata('message', 'Something went wrong!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }

    public function add_subscription()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            $response['error'] = true;
            echo json_encode($response);
            return false;
            exit();
        }
        if (is_super()) {
            if (isset($_POST['package_id_']) && !empty($_POST['package_id_']) && is_numeric($_POST['package_id_']) && isset($_POST['tenure_id_']) && !empty($_POST['tenure_id_']) && is_numeric($_POST['tenure_id_'])) {
                $package_id = $_POST['package_id_'];
                $tenure_id = $_POST['tenure_id_'];
                $tenure = $this->db->where('id', $tenure_id)->get('packages_tenures')->row_array();
                $package = $this->db->where('id', $package_id)->get('packages')->row_array();

                if (!empty($tenure) && !empty($package)) {
                    $user_id = $_POST['user_id_'];
                    $current_to_date = $this->packages_model->get_to_date_by_user_id($user_id);
                    $from_date = isset($current_to_date[0]['to_date']) && !empty($current_to_date[0]['to_date']) ? date('Y-m-d', strtotime($current_to_date[0]['to_date'] . ' +1 day')) : date('Y-m-d');
                    $to_date = date('Y-m-d', strtotime($from_date . "+" . $tenure['months'] . " months"));
                    $package_data = [
                        'user_id' => $user_id,
                        'package_id' => $package_id,
                        'title' => $package['title'],
                        'tenure_id' => $tenure_id,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'amount' => $tenure['rate'],
                        'months' => $tenure['months'],
                        'plan_type' => $package['plan_type'],
                        'modules' => $package['modules'],
                        'tenure' => $tenure['tenure'],
                        'storage_unit' => $package['storage_unit'],
                        'storage_allowed' => $package['max_storage_size'],
                        'allowed_workspaces' => $package['max_workspaces'],
                        'allowed_employees' => $package['max_employees']
                    ];
                    $this->packages_model->add_user_package($package_data);
                    $response['error'] = false;
                    $this->session->set_flashdata('message', 'Package added to user\'s subscription list successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                    echo json_encode($response);
                    return false;
                    exit();
                } else {
                    $this->session->set_flashdata('message', 'Something went wrong please try again!');
                    $this->session->set_flashdata('message_type', 'error');
                    $response['error'] = true;
                    echo json_encode($response);
                    return false;
                    exit();
                }
            } else {
                $this->session->set_flashdata('message', 'Something went wrong please try again!');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }
}
