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

        if (!$this->ion_auth->logged_in() || !is_super()) {
            redirect('auth', 'refresh');
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
                $this->form_validation->set_rules('app_url', str_replace(':', '', 'App url is empty.'), 'trim|required');
                $this->form_validation->set_rules('currency_shortcode', str_replace(':', '', 'Currency Shortcode is empty.'), 'trim|required');
                $this->form_validation->set_rules('currency_full_form', str_replace(':', '', 'Currency Full Form is empty.'), 'trim|required');
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
                $currency_full_form = strip_tags($this->input->post('currency_full_form', true));
                $currency_full_form = $this->db->escape_str($currency_full_form);
                $currency_symbol = strip_tags($this->input->post('currency_symbol', true));
                $currency_symbol = $this->db->escape_str($currency_symbol);
                $currency_shortcode = strip_tags($this->input->post('currency_shortcode', true));
                $currency_shortcode = $this->db->escape_str($currency_shortcode);
                $default_package = strip_tags($this->input->post('default_package', true));
                $default_package = $this->db->escape_str($default_package);
                $default_tenure = strip_tags($this->input->post('default_tenure', true));
                $default_tenure = $this->db->escape_str($default_tenure);
                if ($this->input->post('hide_budget') && $this->input->post('hide_budget') == 'on') {
                    $hide_budget = 1;
                } else {
                    $hide_budget = 0;
                }

                if (!empty($_FILES['full_logo']['name'])) {

                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = false;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('full_logo')) {
                        $full_logo = $this->upload->data();
                    } else {
                        $this->session->set_flashdata('message', 'Full logo could not Edited! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = True;
                        $response['message'] = 'Not Successful';
                        echo json_encode($response);
                    }
                } else {
                    $full_logo = strip_tags($this->input->post('full_logo_old', true));
                }

                if (!empty($_FILES['half_logo']['name'])) {

                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = false;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $this->load->library('upload', $config);
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
                    $half_logo = strip_tags($this->input->post('half_logo_old', true));
                }

                if (!empty($_FILES['favicon']['name'])) {

                    $config['upload_path']          = './assets/backend/icons/';
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['overwrite']             = false;
                    $config['max_size']             = 10000;
                    $config['max_width']            = 0;
                    $config['max_height']           = 0;
                    $this->load->library('upload', $config);
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
                    $favicon = strip_tags($this->input->post('favicon_old', true));
                }

                $timezone = !empty($this->input->post('mysql_timezone') && trim($this->input->post('mysql_timezone')) == '00:00') ? '+' . trim(strip_tags($this->input->post('mysql_timezone', true))) : strip_tags($this->input->post('mysql_timezone', true));
                $app_url = !empty($this->input->post('app_url')) ? strip_tags($this->input->post('app_url', true)) : '';
                $data_json = array(
                    'app_url' => $this->db->escape_str($app_url),
                    'company_title' => !empty($company_title) ? $company_title : '',
                    'full_logo' => !empty($full_logo['file_name']) ? $full_logo['file_name'] : $full_logo,
                    'half_logo' => !empty($half_logo['file_name']) ? $half_logo['file_name'] : $half_logo,
                    'favicon' => !empty($favicon['file_name']) ? $favicon['file_name'] : $favicon,
                    'php_timezone' => !empty($this->input->post('php_timezone')) ? strip_tags($this->input->post('php_timezone', true)) : '',
                    'mysql_timezone' => $timezone,
                    'currency_full_form' => $currency_full_form,
                    'currency_symbol' => $currency_symbol,
                    'currency_shortcode' => $currency_shortcode,
                    'default_package' => $default_package,
                    'default_tenure' => $default_tenure,
                    'hide_budget' => $hide_budget,
                    'system_font' => !empty($this->input->post('system_fonts')) ? strip_tags($this->input->post('system_fonts', true)) : 'default',
                );
                $data = array(
                    'data' => json_encode($data_json)
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
                        'data' => json_encode($data_json)
                    );
                } else {
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                    return false;
                }
            } elseif ($setting_type == 'system') {
                $setting_type = 'web_fcm_settings';

                $this->form_validation->set_rules('fcm_server_key', str_replace(':', '', 'fcm server key is empty.'), 'trim|required');
                $this->form_validation->set_rules('apiKey', str_replace(':', '', 'Web api Key is empty.'), 'trim|required');
                $this->form_validation->set_rules('projectId', str_replace(':', '', 'Project Id is empty.'), 'trim|required');
                $this->form_validation->set_rules('messagingSenderId', str_replace(':', '', 'Sender Id is empty.'), 'trim|required|integer');

                if ($this->form_validation->run() === TRUE) {
                    $fcm_server_key = strip_tags($this->input->post('fcm_server_key', true));
                    $fcm_server_key = $this->db->escape_str($fcm_server_key);
                    $apiKey = strip_tags($this->input->post('apiKey', true));
                    $apiKey = $this->db->escape_str($apiKey);
                    $projectId = strip_tags($this->input->post('projectId', true));
                    $projectId = $this->db->escape_str($projectId);
                    $messagingSenderId = strip_tags($this->input->post('messagingSenderId', true));
                    $messagingSenderId = $this->db->escape_str($messagingSenderId);

                    $data_json = array(
                        'fcm_server_key' => !empty($fcm_server_key) ? $fcm_server_key : '',
                        'apiKey' => !empty($apiKey) ? $apiKey : '',
                        'projectId' => !empty($projectId) ? $projectId : '',
                        'authDomain' => !empty($projectId) ? $projectId . '.firebaseapp.com' : '',
                        'databaseURL' => !empty($projectId) ? 'https://' . $projectId . '.firebaseio.com' : '',
                        'storageBucket' => !empty($projectId) ? $projectId . '.appspot.com' : '',
                        'messagingSenderId' => !empty($messagingSenderId) ? $messagingSenderId : ''
                    );

                    $data = array(
                        'data' => json_encode($data_json)
                    );

                    $apiKey = !empty($apiKey) ? $apiKey : '';
                    $projectId = !empty($projectId) ? $projectId : '';
                    $authDomain = !empty($projectId) ? $projectId . '.firebaseapp.com' : '';
                    $databaseURL = !empty($projectId) ? 'https://' . $projectId . '.firebaseio.com' : '';
                    $storageBucket = !empty($projectId) ? $projectId . '.appspot.com' : '';
                    $messagingSenderId = !empty($messagingSenderId) ? $messagingSenderId : '';

                    $template_path     = 'assets/backend/js/fcmsettings.js';

                    $output_path     = 'firebase-messaging-sw.js';

                    $database_file = file_get_contents($template_path);

                    $new  = str_replace("%APIKEY%", $apiKey, $database_file);
                    $new  = str_replace("%AUTHDOMAIN%", $authDomain, $new);
                    $new  = str_replace("%DATABASEURL%", $databaseURL, $new);
                    $new  = str_replace("%PROJECTID%", $projectId, $new);
                    $new  = str_replace("%STRORAGEBUCKET%", $storageBucket, $new);
                    $new  = str_replace("%MESSAGINGSENDERID%", $messagingSenderId, $new);

                    write_file($output_path, $new);
                } else {
                    $response['error'] = true;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    $response['message'] = validation_errors();
                    echo json_encode($response);
                    return false;
                }
            } elseif ($setting_type == 'twilio') {
                $setting_type = 'twilio';

                $this->form_validation->set_rules('mode', str_replace(':', '', 'Mode is empty.'), 'trim|required');
                $this->form_validation->set_rules('account_sid', str_replace(':', '', 'Account SID is empty.'), 'trim|required');
                $this->form_validation->set_rules('auth_token', str_replace(':', '', 'Auth token is empty.'), 'trim|required');
                $this->form_validation->set_rules('api_version', str_replace(':', '', 'API version is empty.'), 'trim|required');
                $this->form_validation->set_rules('twilio_number', str_replace(':', '', 'Twilio number is empty.'), 'trim|required');

                if ($this->form_validation->run() === TRUE) {
                    $mode = strip_tags($this->input->post('mode', true));
                    $mode = $this->db->escape_str($mode);
                    $account_sid = strip_tags($this->input->post('account_sid', true));
                    $account_sid = $this->db->escape_str($account_sid);
                    $auth_token = strip_tags($this->input->post('auth_token', true));
                    $auth_token = $this->db->escape_str($auth_token);
                    $api_version = strip_tags($this->input->post('api_version', true));
                    $api_version = $this->db->escape_str($api_version);
                    $twilio_number = strip_tags($this->input->post('twilio_number', true));
                    $twilio_number = $this->db->escape_str($twilio_number);

                    $data_json = array(
                        'mode' => !empty($mode) ? $mode : '',
                        'account_sid' => !empty($account_sid) ? $account_sid : '',
                        'auth_token' => !empty($auth_token) ? $auth_token : '',
                        'api_version' => !empty($api_version) ? $api_version : '',
                        'twilio_number' => !empty($twilio_number) ? $twilio_number : ''

                    );

                    $data = array(
                        'type' => 'twilio',
                        'data' => json_encode($data_json)
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

            if ($this->settings_model->save_settings($setting_type, $data)) {

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
            $default_package = default_package();
            $data['tenures'] = $this->packages_model->get_tenures_by_package_id($default_package);
            $data['packages'] = $this->packages_model->get_packages();
            $data['my_fonts'] = file_get_contents("assets/backend/fonts/my-fonts.json");
            $data['is_admin'] =  $this->ion_auth->is_admin();
            $workspace_id = $this->session->userdata('workspace_id');
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $this->load->view('super-admin/setting-detail', $data);
        }
    }
}
