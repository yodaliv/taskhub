<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'settings_model', 'chat_model', 'notifications_model', 'packages_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language', 'file', 'form']);
        $this->load->library('session');
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

    public function create_fonts()
    {
        if (!$this->ion_auth->logged_in() || !is_super()) {
            redirect('auth', 'refresh');
        } else {

            $this->form_validation->set_rules('fonts', str_replace(':', '', 'Fonts is empty.'), 'trim|required');
            if ($this->form_validation->run() === FALSE) {

                $this->session->set_flashdata('message', validation_errors());
                $this->session->set_flashdata('message_type', 'success');
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = validation_errors();
                echo json_encode($response);
                return false;
            }
            $fonts = strip_tags($this->input->post('fonts', true));
            if (write_file('assets/backend/fonts/my-fonts.json', $fonts)) {
                $response['error'] = false;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Fonts Created Successful";
                echo json_encode($response);
            } else {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Fonts Created Successful";
                echo json_encode($response);
            }
        }
    }
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {

            if (!is_super()) {
                redirect($this->session->userdata('role') . '/home', 'refresh');
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
            }
            $workspace_id = $this->session->userdata('workspace_id');
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $this->load->view('super-admin/settings', $data);
        }
    }

    public function save_settings()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $user_id = $this->session->userdata('user_id');
        $workspace_id = $this->session->userdata('workspace_id');
        if (!is_admin()  && !is_workspace_admin($user_id, $workspace_id)) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = NOT_AUTHORIZED_OPERATION;
            echo json_encode($response);
            return false;
            exit();
        } else {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response['error'] = true;
                $response['is_reload'] = 1;
                $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
                $this->session->set_flashdata('message_type', 'error');
                echo json_encode($response);
                return false;
                exit();
            }

            $setting_type = strip_tags($this->input->post('setting_type', true));

            if ($setting_type == 'general') {

                $this->form_validation->set_rules('company_title', str_replace(':', '', 'Title is empty.'), 'trim|required');
                if ($this->form_validation->run() === FALSE) {

                    $this->session->set_flashdata('message', validation_errors());
                    $this->session->set_flashdata('message_type', 'success');
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                    return false;
                }

                $company_title = strip_tags($this->input->post('company_title', true));
                $company_title = $this->db->escape_str($company_title);
                // echo 'logo-admin'.$ext;
                if (!empty($_FILES['full_logo']['name'])) {
                    $file_name = "logo-admin-" . $this->data['admin_id'] . "." . pathinfo($_FILES['full_logo']['name'], PATHINFO_EXTENSION);
                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = TRUE;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $config['file_name'] = $file_name;
                    $this->load->library('upload', $config);
                    $old_logo = strip_tags($this->input->post('full_logo_old', true));
                    if (!empty($this->input->post('full_logo_old')) && file_exists('./application/libraries/tcpdf/' . $this->input->post('full_logo_old')) && $this->input->post('full_logo_old') != 'logo.png') {
                        unlink('./application/libraries/tcpdf/' . $old_logo);
                    }
                    if (file_exists('./assets/backend/icons/' . $old_logo) && $old_logo != 'logo.png') {
                        unlink('./assets/backend/icons/' . $old_logo);
                    }
                    if ($this->upload->do_upload('full_logo')) {

                        copy('./assets/backend/icons/' . $file_name, './application/libraries/tcpdf/' . $file_name);
                        $full_logo = $this->upload->data();
                    } else {
                        $this->session->set_flashdata('message', 'Full logo could not Edited! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = True;
                        $response['message'] = 'Not Successful';
                        echo json_encode($response);
                    }
                } else {
                    $full_logo_old = strip_tags($this->input->post('full_logo_old', true));
                }
                if (!empty($_FILES['half_logo']['name'])) {
                    $half_logo_file_name = "logo-half-admin-" . $this->data['admin_id'] . "." . pathinfo($_FILES['half_logo']['name'], PATHINFO_EXTENSION);
                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = TRUE;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $config['file_name'] = $half_logo_file_name;
                    $this->load->library('upload', $config);
                    $old_logo_half = strip_tags($this->input->post('half_logo_old', true));
                    if (file_exists('./assets/backend/icons/' . $old_logo_half) && $old_logo_half != 'logo-half.png') {
                        unlink('./assets/backend/icons/' . $old_logo_half);
                    }
                    if ($this->upload->do_upload('half_logo')) {
                        $half_logo = $this->upload->data();
                    } else {
                        $this->session->set_flashdata('message', 'Half logo could not Edited! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = True;
                        $response['message'] = 'Not Successful';
                        echo json_encode($response);
                    }
                } else {
                    $half_logo_old = strip_tags($this->input->post('half_logo_old', true));
                }

                if (!empty($_FILES['favicon']['name'])) {
                    $favicon_file_name = "favicon-admin-" . $this->data['admin_id'] . "." . pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = TRUE;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $config['file_name'] = $favicon_file_name;
                    $this->load->library('upload', $config);
                    $old_logo_half = strip_tags($this->input->post('half_logo_old', true));
                    if (file_exists('./assets/backend/icons/' . $old_logo_half) && $old_logo_half != 'logo-half.png') {
                        unlink('./assets/backend/icons/' . $old_logo_half);
                    }
                    if ($this->upload->do_upload('favicon')) {
                        $favicon = $this->upload->data();
                    } else {
                        $this->session->set_flashdata('message', 'Favicon could not Edited! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = True;
                        $response['message'] = 'Not Successful';
                        echo json_encode($response);
                    }
                } else {
                    $favicon_old = strip_tags($this->input->post('favicon_old', true));
                }
                $logo = !empty($full_logo['file_name']) ? $file_name : $full_logo_old;
                $half_logo = !empty($half_logo['file_name']) ? $half_logo_file_name : $half_logo_old;
                $favicon = !empty($favicon['file_name']) ? $favicon_file_name : $favicon_old;

                $data = array(
                    'company' => !empty($company_title) ? $company_title : '',
                    'logo' => $logo,
                    'half_logo' => $half_logo,
                    'favicon' => $favicon,
                );
            } elseif ($setting_type == 'email') {
                $this->form_validation->set_rules('email', str_replace(':', '', 'email is empty.'), 'trim|required|valid_email');
                $this->form_validation->set_rules('password', str_replace(':', '', 'password is empty.'), 'trim|required');
                $this->form_validation->set_rules('smtp_host', str_replace(':', '', 'smtp host is empty.'), 'trim|required');
                $this->form_validation->set_rules('smtp_port', str_replace(':', '', 'smtp port is empty.'), 'trim|required|integer');

                if ($this->form_validation->run() === TRUE) {

                    $email = strip_tags($this->input->post('email', true));
                    $email = $this->db->escape_str($email);
                    $password = strip_tags($this->input->post('password', true));
                    $password = $this->db->escape_str($password);
                    $smtp_host = strip_tags($this->input->post('smtp_host', true));
                    $smtp_host = $this->db->escape_str($smtp_host);
                    $smtp_port = strip_tags($this->input->post('smtp_port', true));
                    $smtp_port = $this->db->escape_str($smtp_port);
                    $mail_content_type = strip_tags($this->input->post('mail_content_type', true));
                    $mail_content_type = $this->db->escape_str($mail_content_type);
                    $smtp_encryption = strip_tags($this->input->post('smtp_encryption', true));
                    $smtp_encryption = $this->db->escape_str($smtp_encryption);

                    $data_json = array(
                        'email' => !empty($email) ? $email : '',
                        'password' => !empty($password) ? $password : '',
                        'smtp_host' => !empty($smtp_host) ? $smtp_host : '',
                        'smtp_port' => !empty($smtp_port) ? $smtp_port : '',
                        'mail_content_type' => !empty($mail_content_type) ? $mail_content_type : '',
                        'smtp_encryption' => !empty($smtp_encryption) ? $smtp_encryption : ''
                    );

                    $data = array(
                        'email_config' => json_encode($data_json)
                    );
                } else {
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                    return false;
                }
            }
            $user_id = $this->session->userdata('user_id');
            if ($this->settings_model->save_settings($setting_type, $data, $user_id)) {

                $this->session->set_flashdata('message', 'Setting Updated successfully.');
                $this->session->set_flashdata('message_type', 'success');

                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Successful';
                echo json_encode($response);
            } else {
                $this->session->set_flashdata('message', 'Setting could not Updated! Try again!');
                $this->session->set_flashdata('message_type', 'error');

                $response['error'] = true;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Not Successful';
                echo json_encode($response);
            }
        }
    }

    public function setting_detail()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (!is_admin() && !is_workspace_admin($user_id, $workspace_id)) {
                redirect($this->session->userdata('role') . '/home', 'refresh');
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
            }

            if (is_admin()) {
                $admin_id = $user->id;
            } else {
                $admin_id = get_admin_id_by_workspace_id($workspace[0]->id);
            }
            $data['company_title'] = get_admin_company_title($admin_id);
            $data['full_logo'] = get_admin_company_logo($admin_id);
            $data['half_logo'] = get_admin_company_half_logo($admin_id);
            $data['favicon'] = get_admin_company_favicon($admin_id);
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $workspace_id = $this->session->userdata('workspace_id');
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $this->load->view('admin/setting-detail', $data);
        }
    }
}
