<?php
/* <!--  START 
===============================================
Version :- V.2 		Author : ''    12-AUGUST-2020
=============================================== 
-->
*/
defined('BASEPATH') or exit('No direct script access allowed');

class Tickets extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'tickets_model', 'notifications_model', 'users_model', 'meetings_model']);
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
            if (is_admin()) {
                if (check_module($user_id, 'support_system') == 0) {
                    $this->session->set_flashdata('message', ERROR_MESSAGE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                if (check_module($admin_id, 'support_system') == 0) {
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
                $current_workspace_id = $this->workspace_model->get_workspace($this->session->userdata('workspace_id'));
                $user_ids = explode(',', $current_workspace_id[0]->user_id);
                $section = array_map('trim', $user_ids);
                $user_ids = $section;
                $data['all_user'] = $this->users_model->get_user($user_ids);
                $data['ticket_types'] = $this->tickets_model->get_ticket_types($workspace_id);
                $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
                $this->load->view('admin/tickets', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }
    public function ticket_types()
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
                if (check_module($user_id, 'support_system') == 0) {
                    $this->session->set_flashdata('message', ERROR_MESSAGE);
                    $this->session->set_flashdata('message_type', 'error');
                    redirect($this->session->userdata('role') . '/home', 'refresh');
                    return false;
                    exit();
                }
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace_id);
                if (check_module($admin_id, 'support_system') == 0) {
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
                $this->load->view('admin/manage-ticket-types', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }

    public function create_ticket_type()
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
            if (check_module($user_id, 'support_system') == 0) {
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
            if (check_module($admin_id, 'support_system') == 0) {
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

        if ($this->form_validation->run() === TRUE) {

            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
                'user_id' => $user_id,
                'workspace_id' => $workspace_id,
                'user_type' => 'admin'
            );
            $id = $this->tickets_model->add_ticket_type($data);

            if ($id != false) {
                $this->session->set_flashdata('message', 'Ticket type Added successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Ticket type could not Created! Try again!');
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


    public function get_ticket_type_by_id($id = '')
    {
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (!is_super() && !is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $data = $this->tickets_model->get_ticket_type_by_id($id);
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

    public function edit_ticket_type()
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
            if (check_module($user_id, 'support_system') == 0) {
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
            if (check_module($admin_id, 'support_system') == 0) {
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
        $this->form_validation->set_rules('update_id', str_replace(':', '', 'update_id is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $data = array(
                'title' => strip_tags($this->input->post('title', true)),
            );

            if ($this->tickets_model->edit_ticket_type($data, $this->input->post('update_id'))) {
                $this->session->set_flashdata('message', 'Ticket type Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Ticket type could not Updated! Try again!');
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

    public function get_ticket_types_list()
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
            return $this->tickets_model->get_ticket_types_list($user_id, $workspace_id);
        }
    }

    public function delete_ticket_type()
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

        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->tickets_model->delete_ticket_type($id)) {
                $response['error'] = false;
                $this->session->set_flashdata('message', 'Ticket type deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $response['error'] = true;
                $this->session->set_flashdata('message', 'Ticket type could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        echo (json_encode($response));
    }
    public function manage_tickets()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');

            if (!is_member() && !is_client()) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }

            $admin_id = get_admin_id_by_workspace_id($workspace_id);
            if (check_module($admin_id, 'support_system') == 0) {
                $this->session->set_flashdata('message', NO_ACTIVE_PLAN);
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
                $data['notifications'] = $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace[0]->id);
                $this->load->view('admin/manage-tickets', $data);
            } else {
                $this->session->set_flashdata('message', NO_WORKSPACE);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
        }
    }
    public function create_ticket()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (is_admin()) {
            if (check_module($user_id, 'support_system') == 0) {
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
            if (check_module($admin_id, 'support_system') == 0) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = NO_ACTIVE_PLAN;
                echo json_encode($response);
                return false;
            }
        }
        $this->form_validation->set_rules('ticket_type_id', str_replace(':', '', 'Ticket type is empty.'), 'trim|required');
        $this->form_validation->set_rules('subject', str_replace(':', '', 'Subject is empty.'), 'trim|required');
        $this->form_validation->set_rules('email', str_replace(':', '', 'Email is empty.'), 'trim|required');
        $this->form_validation->set_rules('description', str_replace(':', '', 'Description is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $ticket_type_id = strip_tags($this->input->post('ticket_type_id', true));
            $ticket_type = fetch_details(['id' => $ticket_type_id], 'ticket_types', 'id');
            if (empty($ticket_type)) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Invalid ticket type';
                echo json_encode($response);
                return false;
            }
            $admin_ids = get_workspace_admins($workspace_id);

            if (!empty($this->input->post('users'))) {
                $user_ids = implode(",", $this->input->post('users')) . ',' . $admin_ids;
            } else {
                $user_ids = $admin_ids;
            }
            $data = array(
                'workspace_id' => $workspace_id,
                'ticket_type_id' => $ticket_type_id,
                'user_id' => $user_id,
                'subject' => strip_tags($this->input->post('subject', true)),
                'email' => strip_tags($this->input->post('email', true)),
                'description' => strip_tags($this->input->post('description', true)),
                'user_ids' => $user_ids,
                'client_ids' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : ''
            );
            $id = $this->tickets_model->add_ticket($data);
            if ($id != false) {
                $this->session->set_flashdata('message', 'Ticket created successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Ticket could not Created! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
            $response['error'] = false;
            echo json_encode($response);
        } else {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }
    public function get_tickets_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            return $this->tickets_model->get_tickets_list($user_id, $workspace_id);
        }
    }
    public function get_ticket_participants_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            return $this->users_model->get_participants_list('tickets');
        }
    }
    public function delete_ticket()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            $ticket = $this->tickets_model->get_ticket_by_id($id);
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id) && $ticket[0]['user_id'] != $user_id) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }

            if ($this->tickets_model->delete_ticket($id)) {
                $this->session->set_flashdata('message', 'Ticket deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Ticket could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
    }
    public function get_ticket_by_id($id = '')
    {
        if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id)) {
            $data = $this->tickets_model->get_ticket_by_id($id);
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
    public function edit_ticket()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $workspace_id = $this->session->userdata('workspace_id');
        $this->form_validation->set_rules('id', str_replace(':', '', 'Ticket ID is empty.'), 'trim|required');
        $this->form_validation->set_rules('ticket_type_id', str_replace(':', '', 'Ticket type is empty.'), 'trim|required');
        $this->form_validation->set_rules('subject', str_replace(':', '', 'Subject is empty.'), 'trim|required');
        $this->form_validation->set_rules('email', str_replace(':', '', 'Email is empty.'), 'trim|required');
        $this->form_validation->set_rules('description', str_replace(':', '', 'Description is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            $ticket = $this->tickets_model->get_ticket_by_id($this->input->post('id'));
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id) && $ticket[0]['user_id'] != $user_id) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = NOT_AUTHORIZED;
                echo json_encode($response);
                return false;
            }
            $admin_ids = get_workspace_admins($workspace_id);

            if (!empty($this->input->post('users'))) {
                $user_ids = implode(",", $this->input->post('users')) . ',' . $admin_ids;
            } else {
                $user_ids = $admin_ids;
            }
            $data = array(
                'ticket_type_id' => strip_tags($this->input->post('ticket_type_id', true)),
                'subject' => strip_tags($this->input->post('subject', true)),
                'email' => strip_tags($this->input->post('email', true)),
                'description' => $this->input->post('description'),
                'user_ids' => $user_ids,
                'client_ids' => (!empty($this->input->post('clients'))) ? implode(",", $this->input->post('clients')) : ''
            );
            if ($this->tickets_model->edit_ticket($data, $this->input->post('id'))) {
                $this->session->set_flashdata('message', 'Ticket updated successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Ticket could not updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
            $response['error'] = false;
            echo json_encode($response);
        } else {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = validation_errors();
            echo json_encode($response);
        }
    }
    public function get_ticket_messages()
    {
        $this->form_validation->set_data($this->input->get());
        // $this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|numeric|required|xss_clean');
        // if (!$this->form_validation->run()) {
        //     $this->response['error'] = true;
        //     $this->response['message'] = strip_tags(validation_errors());
        //     $this->response['data'] = array();
        //     print_r(json_encode($this->response));
        // } else {
            $ticket_id = (isset($_GET['ticket_id']) && is_numeric($_GET['ticket_id']) && !empty(trim($_GET['ticket_id']))) ? $this->input->get('ticket_id', true) : "";
            $user_id = (isset($_GET['user_id']) && is_numeric($_GET['user_id']) && !empty(trim($_GET['user_id']))) ? $this->input->get('user_id', true) : "";
            $search = (isset($_GET['search']) && !empty(trim($_GET['search']))) ? $this->input->get('search', true) : "";
            $limit = (isset($_GET['limit']) && is_numeric($_GET['limit']) && !empty(trim($_GET['limit']))) ? $this->input->get('limit', true) : 50;
            $offset = (isset($_GET['offset']) && is_numeric($_GET['offset']) && !empty(trim($_GET['offset']))) ? $this->input->get('offset', true) : 0;
            $order = (isset($_GET['order']) && !empty(trim($_GET['order']))) ? $this->input->get('order', true) : 'DESC';
            $sort = (isset($_GET['sort']) && !empty(trim($_GET['sort']))) ? $this->input->get('sort', true) : 'id';
            $data = $this->config->item('type');
            $this->response =  $this->tickets_model->get_message_list($ticket_id, $user_id, $search, $offset, $limit, $sort, $order, $data, "");

            // print_r(json_encode($this->response));
        // }
    }
    public function send_message()
    {
        // echo count($_FILES['file']['name']);
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('ticket_id_', 'Ticket id', 'trim|required|numeric|xss_clean');
            // $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($this->response));
                return false;
            } else {
                $user_id = $this->session->userdata('user_id');
                $role = $this->session->userdata('role');
                $ticket_id = (isset($_POST['ticket_id_']) && !empty(trim($_POST['ticket_id_']))) ? $this->input->post('ticket_id_', true) : "";
                $message = (isset($_POST['message']) && !empty(trim($_POST['message']))) ? $this->input->post('message', true) : "";

                $data = array(
                    'user_type' => $role,
                    'user_id' => $user_id,
                    'ticket_id' => $ticket_id,
                    'message' => $message
                );
                $insert_id = $this->tickets_model->add_ticket_message($data);
                if (!empty($insert_id)) {
                    if (!empty($_FILES['file']['name'])) {
                        $file_names = array();
                        if (!is_dir('./assets/backend/tickets/attachments/')) {
                            mkdir('./assets/backend/tickets/attachments/', 0777, TRUE);
                        }
                        $m = count($_FILES['file']['name']);

                        $files = $_FILES;

                        for ($i = 0; $i < $m; $i++) {
                            $_FILES['file']['name'] = $files['file']['name'][$i];
                            $_FILES['file']['type'] = $files['file']['type'][$i];
                            $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
                            $_FILES['file']['error'] = $files['file']['error'][$i];
                            $_FILES['file']['size'] = $files['file']['size'][$i];

                            $config = array();
                            $config['upload_path'] = './assets/backend/tickets/attachments/';
                            $config['allowed_types'] = $this->config->item('allowed_types');;
                            $config['max_size']      = '0';
                            $config['max_height']      = '0';
                            $config['max_width']      = '0';
                            $config['overwrite']     = FALSE;
                            $this->load->library('upload', $config);
                            if ($this->upload->do_upload('file')) {
                                $file_data = $this->upload->data();
                                $file_name = $file_data['file_name'];
                                array_push($file_names, $file_name);
                            }
                        }
                        $data = array(
                            'attachments' => $file_names
                        );
                        $this->tickets_model->update_ticket_message($data, $insert_id);
                    }
                    $data1 = $this->config->item('type');
                    $result = $this->tickets_model->get_messages($ticket_id, $user_id, "", "", "1", "", "", $data1, $insert_id);
                    $this->response['error'] = false;
                    $this->response['message'] =  'Ticket message sent successfully';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = $result['data'][0];
                    print_r(json_encode($this->response));
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Ticket message could not be sent!';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                    print_r(json_encode($this->response));
                }
            }
        } else {
            redirect('auth', 'refresh');
        }
    }
    public function edit_ticket_status()
    {

        if ($this->ion_auth->logged_in()) {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                $this->response['error'] = true;
                $this->response['message'] = NOT_AUTHORIZED_OPERATION;
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
                return false;
            }
            $this->form_validation->set_rules('ticket_id', 'Ticket Id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['csrfName'] = $this->security->get_csrf_token_name();
                $this->response['csrfHash'] = $this->security->get_csrf_hash();
                $this->response['data'] = array();
            } else {
                $status = $this->input->post('status', true);
                $ticket_id = $this->input->post('ticket_id', true);
                $res = fetch_details('id=' . $ticket_id, 'tickets', '*');
                if (empty($res)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "User id is changed you can not udpate the ticket.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
                if ($status == PENDING && $res[0]['status'] == OPENED) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Current status is opened.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
                if ($status == OPENED && ($res[0]['status'] == RESOLVED || $res[0]['status'] == CLOSED)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Can't be OPEN but you can REOPEN the ticket.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
                if ($status == RESOLVED && $res[0]['status'] == CLOSED) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Current status is closed.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
                if ($status == REOPEN && ($res[0]['status'] == PENDING || $res[0]['status'] == OPENED)) {
                    $this->response['error'] = true;
                    $this->response['message'] = "Current status is pending or opened.";
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }

                $data = array(
                    'status' => $status
                );
                if ($this->tickets_model->update_ticket($data, $ticket_id)) {
                    $result = $this->tickets_model->get_tickets($ticket_id);
                    if (!empty($result)) {
                        /* Send notification */
                        $ticket_res = fetch_details(['ticket_id' => $ticket_id], 'ticket_messages', 'user_id');

                        $user_res = fetch_details(['id' => $ticket_res[0]['user_id']], "users", 'web_fcm', '',  '', '', '');
                        $fcm_ids[0][] = $user_res[0]['web_fcm'];
                        $fcm_admin_msg =  "Your Ticket status has been changed";
                        $fcm_admin_subject = (!empty($result['data'][0]['subject'])) ? $result['data'][0]['subject'] : "Ticket Message";
                        if (!empty($fcm_ids)) {
                            $fcmMsg = array(
                                'title' => $fcm_admin_subject,
                                'body' => $fcm_admin_msg,
                                'type' => "ticket_status",
                                'type_id' => $ticket_id
                            );
                            send_notification($fcmMsg, $fcm_ids);
                        }
                    }
                    $this->response['error'] = false;
                    $this->response['message'] =  'Ticket updated Successfully';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = $result['data'];
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] =  'Ticket Not Added';
                    $this->response['csrfName'] = $this->security->get_csrf_token_name();
                    $this->response['csrfHash'] = $this->security->get_csrf_hash();
                    $this->response['data'] = (!empty($this->response['data'])) ? $this->response['data'] : [];
                }
            }
            print_r(json_encode($this->response));
        } else {
            redirect('auth', 'refresh');
        }
    }
}
/* 
// END
// ===============================================
// Version : V.2 		Author : ''    23-July-2020
// =============================================== 
*/
