<?php
/* <!--  START 
===============================================
Version :- V.2 		Author : ''    12-AUGUST-2020
=============================================== 
-->
*/
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');
class Send_mail extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'notifications_model', 'users_model', 'mail_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');
        $this->config->load('taskhub');

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
            if (!is_admin()) {
                $this->session->set_flashdata('message', 'You are not authorized to access this page!');
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
            }
            $temp = array();
            $temp1 = array();
            $workspace_id = $this->session->userdata('workspace_id');
            if (!empty($workspace_id)) {
                $user_emails = $this->users_model->get_user_emails($workspace_id);
                $to_emails = $this->mail_model->get_to_emails($workspace_id);

                foreach ($to_emails as $to_email) {
                    $to_email = explode(",", $to_email['to']);
                    for ($i = 0; $i < count($to_email); $i++) {
                        array_push($temp, $to_email[$i]);
                    }
                }
                foreach ($user_emails as $user_email) {
                    $user_email = explode(",", $user_email['email']);
                    for ($j = 0; $j < count($user_email); $j++) {
                        array_push($temp1, $user_email[$j]);
                    }
                }
                // print_r($temp);
                $final = array_merge($temp, $temp1);
                $final = array_unique($final);
                array_unshift($final, "");
                unset($final[0]);
                $data['emails'] = $final;
                $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                $this->load->view('admin/send-mail', $data);
            } else {
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }



    public function send()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('message', 'You are not authorized to access this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $this->form_validation->set_rules('to', str_replace(':', '', 'to is empty.'), 'trim|required');
        $this->form_validation->set_rules('subject', str_replace(':', '', 'subject is empty.'), 'trim|required');
        $this->form_validation->set_rules('message', str_replace(':', '', 'message is empty.'), 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $email_settings = get_settings_by_admin_id($this->data['admin_id'], 'email');
            if (!$email_settings) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $this->session->set_flashdata('message', 'Email settings not configured');
                $this->session->set_flashdata('message_type', 'error');
                echo json_encode($response);
                return false;
                exit();
            }

            $data = array(
                'to' => strip_tags($this->input->post('to', true)),
                'subject' => strip_tags($this->input->post('subject', true)),
                'message' => $this->input->post('message', true),
                'status' => strip_tags($this->input->post('status', true)),
                'workspace_id' => $this->session->userdata('workspace_id')
            );
            $id = $this->mail_model->add_mail($data);

            if ($id != false) {
                $save_as_draft =  strip_tags($this->input->post('is_draft', true));
                if (!empty($_FILES['file']['name'])) {
                    $file_names = array();
                    if (!is_dir('./assets/attachments/')) {
                        mkdir('./assets/attachments/', 0777, TRUE);
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
                        $config['upload_path'] = './assets/attachments/';
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

                    $file_names = implode(",", $file_names);
                    $data = array(
                        'attachments' => $file_names
                    );
                    $this->mail_model->update_mail($data, $id);
                }
                if (empty($save_as_draft)) {
                    $this->email->clear(TRUE);
                    $this->load->library('email');
                    $this->email->initialize($email_settings);
                    $this->email->set_newline("\r\n");
                    $from_email = get_admin_company_email($this->data['admin_id']);
                    $this->email->from($from_email, get_admin_company_title($this->data['admin_id']));
                    $recepients = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');
                    $this->email->to($recepients);
                    $this->email->subject($subject);
                    $data['subject'] = $subject;
                    $data['message'] = $message;
                    $data['logo'] = base_url('assets/backend/icons/') . get_admin_company_logo($this->data['admin_id']);
                    $data['company_title'] = get_admin_company_title($this->data['admin_id']);
                    $this->email->message($this->load->view('admin/send-mail-email-template.php', $data, true));
                    $mail = $this->mail_model->get_mail_by_id($id);
                    if (!empty($mail[0]['attachments'])) {
                        $attachments = explode(",", $mail[0]['attachments']);
                        for ($i = 0; $i < count($attachments); $i++) {
                            if (file_exists('./assets/attachments/' . $attachments[$i])) {
                                $file = './assets/attachments/' . $attachments[$i];
                                $this->email->attach($file);
                            }
                        }
                    }
                    if ($this->email->send()) {
                        $response['error'] = false;
                        $this->session->set_flashdata('message', 'Mail Sent successfully.');
                        $this->session->set_flashdata('message_type', 'success');
                        $data = array(
                            'status' => 1
                        );
                        $this->mail_model->update_mail($data, $id);
                    } else {
                        echo $this->email->print_debugger();
                        $response['error'] = true;
                        $this->session->set_flashdata('message', 'Mail could not sent! Try again!.');
                        $this->session->set_flashdata('message_type', 'error');
                        $data = array(
                            'status' => 2
                        );
                        $this->mail_model->update_mail($data, $id);
                    }
                } else {
                    $response['error'] = false;
                    $this->session->set_flashdata('message', 'Mail saved as draft successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                    $data = array(
                        'status' => 3
                    );
                    $this->mail_model->update_mail($data, $id);
                }
            } else {
                $this->session->set_flashdata('message', 'Something went wrong! please try again!');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
            $response['message'] = validation_errors();
        }
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }

    public function send_now()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('message', 'You are not authorized to access this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }
        $email_settings = get_settings_by_admin_id($this->data['admin_id'], 'email');
        if (!$email_settings) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $this->session->set_flashdata('message', 'Email settings not configured');
            $this->session->set_flashdata('message_type', 'error');
            echo json_encode($response);
            return false;
            exit();
        }
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id) && $id > 0) {
            $mail = $this->mail_model->get_mail_by_id($id);
            if (!empty($mail) && isset($mail[0])) {
                $this->email->clear(TRUE);
                $this->email->initialize($email_settings);
                $this->email->set_newline("\r\n");
                $from_email = get_admin_company_email($this->data['admin_id']);
                $this->email->from($from_email, get_admin_company_title($this->data['admin_id']));
                $recepients = $mail[0]['to'];
                $subject = $mail[0]['subject'];
                $message = $mail[0]['message'];
                $this->email->to($recepients);
                $this->email->subject($subject);
                if (!empty($mail[0]['attachments'])) {
                    $attachments = explode(",", $mail[0]['attachments']);
                    for ($i = 0; $i < count($attachments); $i++) {
                        if (file_exists('./assets/attachments/' . $attachments[$i])) {
                            $file = './assets/attachments/' . $attachments[$i];
                            $this->email->attach($file);
                        }
                    }
                }
                $data['subject'] = $subject;
                $data['message'] = $message;
                $data['logo'] = base_url('assets/backend/icons/') . get_admin_company_logo($this->data['admin_id']);
                $data['company_title'] = get_admin_company_title($this->data['admin_id']);
                $this->email->message($this->load->view('admin/send-mail-email-template.php', $data, true));
                if ($this->email->send()) {
                    $response['error'] = false;
                    $this->session->set_flashdata('message', 'Mail Sent successfully.');
                    $this->session->set_flashdata('message_type', 'success');
                    $data = array(
                        'status' => 1
                    );
                    $this->mail_model->update_mail($data, $id);
                } else {
                    $response['error'] = true;
                    $this->session->set_flashdata('message', 'Mail could not sent! Try again!.');
                    $this->session->set_flashdata('message_type', 'error');
                    $data = array(
                        'status' => 2
                    );
                    $this->mail_model->update_mail($data, $id);
                }
            } else {
                $this->session->set_flashdata('message', 'Invalid access detected!');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
            }
        } else {
            $this->session->set_flashdata('message', 'Invalid access detected!');
            $this->session->set_flashdata('message_type', 'error');
            $response['error'] = true;
        }
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        echo json_encode($response);
    }


    public function details()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_admin()) {
                $this->session->set_flashdata('message', 'You are not authorized to access this page!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $id = $this->uri->segment(4);
            if (!empty($id) && is_numeric($id) && $id > 0) {
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
                $mail = $this->mail_model->get_mail_by_id($id);
                if (!empty($mail)) {
                    $data['mail'] = $mail[0];
                    $workspace_id = $this->session->userdata('workspace_id');
                    if (!empty($workspace_id)) {
                        $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
                        $this->load->view('admin/mail-details', $data);
                    } else {
                        redirect($this->session->userdata('role') . '/home', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Invalid access detected!');
                    $this->session->set_flashdata('message_type', 'error');
                    redirect('admin/send-mail', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'Invalid access detected!');
                $this->session->set_flashdata('message_type', 'error');
                redirect('admin/send-mail', 'refresh');
            }
        }
    }

    public function get_mail_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_admin()) {
                $this->session->set_flashdata('message', 'You are not authorized to access this page!');
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $workspace_id = $this->session->userdata('workspace_id');
            return $this->mail_model->get_mail_list($workspace_id);
        }
    }

    public function delete()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('message', 'You are not authorized to access this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            if ($this->mail_model->delete_mail($id)) {
                $this->session->set_flashdata('message', 'Mail deleted successfully.');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Mail could not be deleted! Try again!');
                $this->session->set_flashdata('message_type', 'error');
            }
        }
        redirect('admin/send-mail', 'refresh');
    }
}
/* 
// END
// ===============================================
// Version : V.2 		Author : ''    23-July-2020
// =============================================== 
*/
