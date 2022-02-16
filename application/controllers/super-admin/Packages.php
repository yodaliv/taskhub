<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packages extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['packages_model']);
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
            $this->load->view('super-admin/packages', $data);
        }
    }

    public function create_package()
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
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
            $this->load->view('super-admin/create-package', $data);
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
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('plan_type', 'Plan type', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() === TRUE) {
            $title = strip_tags($this->input->post('title', true));
            $title = $this->db->where('title', $title)->get('packages')->row_array();
            if (!empty($title)) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Package with this title already exists';
                echo json_encode($response);
                return false;
                exit();
            }
            $support_system = isset($_POST['support_system']) && !empty($_POST['support_system']) ? strval(1) : strval(0);
            $meetings = isset($_POST['meetings']) && !empty($_POST['meetings']) ? strval(1) : strval(0);            
            $modules = array("projects" => strip_tags($this->input->post('is-projects-allowed', true)), "tasks" => strip_tags($this->input->post('is-tasks-allowed', true)), "calendar" => strip_tags($this->input->post('is-calendar-allowed', true)), "chat" => strip_tags($this->input->post('is-chat-allowed', true)), "finance" => strip_tags($this->input->post('is-finance-allowed', true)), "users" => strip_tags($this->input->post('is-users-allowed', true)), "clients" => strip_tags($this->input->post('is-clients-allowed', true)), "activity_logs" => strip_tags($this->input->post('is-activity-logs-allowed', true)), "leave_requests" => strip_tags($this->input->post('is-leave-requests-allowed', true)), "notes" => strip_tags($this->input->post('is-notes-allowed', true)), "mail" => strip_tags($this->input->post('is-mail-allowed', true)), "announcements" => strip_tags($this->input->post('is-announcements-allowed', true)), "notifications" => strip_tags($this->input->post('is-notifications-allowed', true)), "sms_notifications" => strip_tags($this->input->post('is-sms-notifications-allowed', true)), "meetings" => $meetings, "support_system" => $support_system);
            $encoded_modules = json_encode($modules);
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'max_workspaces' => strip_tags($this->input->post('max_workspaces', true)),
                'max_employees' => strip_tags($this->input->post('max_employees', true)),
                'max_storage_size' => strip_tags($this->input->post('storage_limit', true)),
                'storage_unit' => strip_tags($this->input->post('storage_unit', true)),
                'plan_type' => strip_tags($this->input->post('plan_type', true)),
                'position_no' => strip_tags($this->input->post('sequence_no', true)),
                'modules' => $encoded_modules,
                'description' => strip_tags($this->input->post('description', true)),
            );
            $tenure = $this->input->post("tenure");
            $months = $this->input->post("months");
            $rate = $this->input->post("rate");

            if (!empty($tenure)) {
                $package_id = $this->packages_model->add_package($data);
                $package_tenure_ids = [];
                if ($package_id != false) {
                    for ($j = 0; $j < count($tenure); $j++) {
                        $data = array(
                            'package_id' => strip_tags($package_id, true),
                            'tenure' => $tenure[$j],
                            'months' => $months[$j],
                            'rate' => $rate[$j]
                        );
                        $package_tenure_id = $this->packages_model->add_package_tenure($data);
                        if ($package_tenure_id != false) {
                            array_push($package_tenure_ids, $package_tenure_id);
                        }
                    }
                    $package_tenure_ids = implode(",", $package_tenure_ids);
                    $data = array(
                        'tenure_ids' => $package_tenure_ids
                    );
                    $this->packages_model->update_package($data, $package_id);
                    $response['error'] = false;
                    $this->session->set_flashdata('message', 'Package created successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $response['error'] = false;
                    $this->session->set_flashdata('message', 'Package could not created! try again!');
                    $this->session->set_flashdata('message_type', 'error');
                }
            } else {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Please add at least one tenure';
            }
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
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('sequence_no', 'Sequence no.', 'trim|required');
        $this->form_validation->set_rules('plan_type', 'Plan type', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $package_id = $this->input->post('package_id');
            $title = strip_tags($this->input->post('title', true));
            $this->db->from('packages');
            $this->db->where('title', $title);
            $this->db->where('id !=', $package_id);
            $query = $this->db->get();
            $count = $query->num_rows();

            if ($count >= 1) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Package with this title already exists';
                echo json_encode($response);
                return false;
                exit();
            }
            $support_system = isset($_POST['support_system']) && !empty($_POST['support_system']) ? strval(1) : strval(0);
            $meetings = isset($_POST['meetings']) && !empty($_POST['meetings']) ? strval(1) : strval(0);
            $modules = array("projects" => strip_tags($this->input->post('is-projects-allowed', true)), "tasks" => strip_tags($this->input->post('is-tasks-allowed', true)), "calendar" => strip_tags($this->input->post('is-calendar-allowed', true)), "chat" => strip_tags($this->input->post('is-chat-allowed', true)), "finance" => strip_tags($this->input->post('is-finance-allowed', true)), "users" => strip_tags($this->input->post('is-users-allowed', true)), "clients" => strip_tags($this->input->post('is-clients-allowed', true)), "activity_logs" => strip_tags($this->input->post('is-activity-logs-allowed', true)), "leave_requests" => strip_tags($this->input->post('is-leave-requests-allowed', true)), "notes" => strip_tags($this->input->post('is-notes-allowed', true)), "mail" => strip_tags($this->input->post('is-mail-allowed', true)), "announcements" => strip_tags($this->input->post('is-announcements-allowed', true)), "notifications" => strip_tags($this->input->post('is-notifications-allowed', true)), "notifications" => strip_tags($this->input->post('is-notifications-allowed', true)), "sms_notifications" => strip_tags($this->input->post('is-sms-notifications-allowed', true)), "support_system" => $support_system,"meetings" => $meetings);
            $encoded_modules = json_encode($modules);
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'max_workspaces' => strip_tags($this->input->post('max_workspaces', true)),
                'max_employees' => strip_tags($this->input->post('max_employees', true)),
                'max_storage_size' => strip_tags($this->input->post('storage_limit', true)),
                'storage_unit' => strip_tags($this->input->post('storage_unit', true)),
                'plan_type' => strip_tags($this->input->post('plan_type', true)),
                'position_no' => strip_tags($this->input->post('sequence_no', true)),
                'modules' => $encoded_modules,
                'description' => strip_tags($this->input->post('description', true)),
                'status' => strip_tags($this->input->post('status', true))
            );
            $tenure = $this->input->post("tenure");
            $months = $this->input->post("months");
            $rate = $this->input->post("rate");
            $tenure_ids = $this->input->post("tenure_id");
            if (!empty($tenure)) {
                $package_id = $this->input->post('package_id');
                $package_tenure_ids_array = [];
                if ($this->packages_model->update_package($data, $package_id)) {
                    for ($i = 0; $i < count($tenure); $i++) {
                        $data = array(
                            'package_id' => strip_tags($package_id, true),
                            'tenure' => $tenure[$i],
                            'months' => $months[$i],
                            'rate' => $rate[$i]
                        );
                        if (!empty($tenure_ids[$i])) {
                            $this->packages_model->update_package_tenure($data, $tenure_ids[$i]);
                            array_push($package_tenure_ids_array, $tenure_ids[$i]);
                        } else {
                            $package_tenure_id = $this->packages_model->add_package_tenure($data);
                            if ($package_tenure_id != false) {
                                array_push($package_tenure_ids_array, $package_tenure_id);
                            }
                        }
                    }
                    $package_tenure_ids = implode(",", $package_tenure_ids_array);
                    $data = array(
                        'tenure_ids' => $package_tenure_ids
                    );
                    $this->packages_model->update_package($data, $package_id);
                    if (!empty($_POST['deleted_tenure_ids'])) {
                        $deleted_tenure_ids = explode(",", $_POST['deleted_tenure_ids']);
                        for ($k = 0; $k < count($deleted_tenure_ids); $k++) {
                            $this->packages_model->delete_package_tenure($deleted_tenure_ids[$k]);
                        }
                    }
                    $this->session->set_flashdata('message', 'Package updated successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                } else {
                    $this->session->set_flashdata('message', 'Package could not update! Try again!');
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
                $response['message'] = 'Please add at least one tenure';
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            print_r(validation_errors());
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }
    public function edit_package()
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
            if (empty($id) || !is_numeric($id) || $id < 1) {
                redirect($this->session->userdata('role') . '/packages', 'refresh');
                return false;
                exit(0);
            }
            $package = $this->packages_model->get_package_by_id($id);
            if (isset($package[0]) && !empty($package[0])) {
                $data['package'] = $package[0];
                $data['package_id'] = $id;
                $data['modules'] = json_decode($package[0]['modules'], 1);
                $data['package_tenures'] = $this->packages_model->get_package_tenures($id);
                $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
                $this->load->view('super-admin/edit-package', $data);
            } else {
                $this->session->set_flashdata('message', 'Package does not exists!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/packages', 'refresh');
                return false;
                exit();
            }
        }
    }
    public function get_packages_list()
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
            return $this->packages_model->get_packages_list();
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
            redirect('super-admin/packages', 'refresh');
        }

        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            $is_default = $this->input->post('is_default');
            if ($this->packages_model->delete_package($id)) {
                if ($is_default) {
                    reset_default_package();
                }
                $this->session->set_flashdata('message', 'Package deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Package could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('super-admin/packages', 'refresh');
    }

    public function details()
    {
        redirect('super-admin/packages', 'refresh');
        return false;
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
            if (empty($id) || !is_numeric($id) || $id < 1) {
                redirect($this->session->userdata('role') . '/packages', 'refresh');
                return false;
                exit(0);
            }
            $package = $this->packages_model->get_package_by_id($id);
            if (isset($package[0]) && !empty($package[0])) {
                $data['package'] = $package[0];
                $data['package_id'] = $id;
                $data['modules'] = json_decode($package[0]['modules'], 1);
                $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
                $this->load->view('super-admin/package-details', $data);
            } else {
                $this->session->set_flashdata('message', 'Package does not exists!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/packages', 'refresh');
                return false;
                exit();
            }
        }
    }

    public function get_tenures_by_package_id($id = '')
    {
        $tenures = '<option value="">Select</option>';
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $data = $this->packages_model->get_tenures_by_package_id($id);
            if (!empty($data)) {
                foreach ($data as $tenure) {
                    $tenures .= '<option value="' . $tenure['id'] . '">' . $tenure['tenure'] . '</option>';
                }
                $data[0]['tenures'] = $tenures;
                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($data[0]);
            } else {
                $data[0]['tenures'] = $tenures;
                $data[0]['csrfName'] = $this->security->get_csrf_token_name();
                $data[0]['csrfHash'] = $this->security->get_csrf_hash();
                echo json_encode($data[0]);
            }
        } else {
            $data[0]['tenures'] = $tenures;
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($data[0]);
        }
    }

    public function package_list($user_id = "")
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else
		if (is_super()) {

            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $packages = $this->packages_model->get_packages();
            $data['packages'] = $packages;
            $data['role'] = $this->session->userdata('role');
            $data['user_id'] = $user_id;
            $data['is_admin'] =  is_admin();
            $this->load->view('super-admin/packages-list', $data);
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }
}
