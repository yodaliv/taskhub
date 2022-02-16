<?php
error_reporting(0);
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('paypal_lib');
        $this->load->model(['workspace_model', 'packages_model', 'users_model']);
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['role'] = $this->session->userdata('role');

        $product_ids = explode(',', $user->workspace_id);

        $section = array_map('trim', $product_ids);

        $product_ids = $section;

        $this->data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
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

    public function paypal()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (!is_admin()) {
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
        $this->form_validation->set_rules('t_id', 'Tenure id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('p_id', 'Package id', 'trim|required|xss_clean|numeric');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $user_id = $this->data['user']->id;
        $tenure = $this->db->where('id', $_POST['t_id'])->get('packages_tenures')->row_array();
        $package = $this->db->where('id', $_POST['p_id'])->get('packages')->row_array();
        if (empty($tenure) || empty($package)) {
            $this->response['error'] = true;
            $this->response['message'] = "Something went wrong please try again!";
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        }
        $package_id = $package['id'];
        $tenure_id = $tenure['id'];
        $plan_type = $package['plan_type'];
        $current_to_date = $this->packages_model->get_to_date_by_user_id($user_id);
        $from_date = isset($current_to_date[0]['to_date']) && !empty($current_to_date[0]['to_date']) ? date('Y-m-d', strtotime($current_to_date[0]['to_date'] . ' +1 day')) : date('Y-m-d');
        $to_date = date('Y-m-d', strtotime($from_date . "+" . $tenure['months'] . " months"));
        // Set variables for paypal form
        $returnURL = base_url() . 'admin/payment/success';
        $cancelURL = base_url() . 'admin/payment/cancel';
        $notifyURL = base_url() . 'webhooks/paypal_webhook';
        $txn_id = time() . "-" . rand();

        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);
        $this->paypal_lib->add_field('item_name', $package['title']);
        $this->paypal_lib->add_field('custom', $this->data['user']->id . '|' . $package_id . '|' . $tenure_id . '|' . $from_date . '|' . $to_date . '|' . $plan_type . '|' . $tenure['tenure'] . '|' . $package['title']);
        $this->paypal_lib->add_field('amount', $tenure['rate']);
        // Render paypal form
        $this->paypal_lib->paypal_auto_form();
    }

    public function success()
    {
        if (isset($_POST['payment_method']) && !empty($_POST['payment_method']) && $_POST['payment_method'] == 'Razorpay') {
            $this->data['package_title'] = $_POST['package_title'];
            $this->data['from_date'] = date('Y-m-d', strtotime($_POST['from_date']));
            $this->data['to_date'] = date('Y-m-d', strtotime($_POST['to_date']));
        } elseif (!empty($this->session->userdata('payment_method')) && ($this->session->userdata('payment_method') == 'paypal')) {
            $package = $this->session->userdata('package');
            $from_date = $this->session->userdata('from_date');
            $to_date = $this->session->userdata('to_date');
            $this->data['package_title'] = $package;
            $this->data['from_date'] = $from_date;
            $this->data['to_date'] = $to_date;
        } elseif (!empty($this->session->userdata('payment_method')) && ($this->session->userdata('payment_method') == 'stripe')) {
            $user_id = $this->session->userdata('user_id');
            $package_id = $this->session->userdata('package_id');
            $tenure_id = $this->session->userdata('tenure_id');
            $package = $this->db->where('id', $package_id)->get('packages')->row_array();
            $tenure = $this->db->where('id', $tenure_id)->get('packages_tenures')->row_array();
            $current_to_date = $this->packages_model->get_to_date_by_user_id($user_id);
            $from_date = isset($current_to_date[0]['to_date']) && !empty($current_to_date[0]['to_date']) ? date("Y-m-d", (strtotime(date($current_to_date[0]['to_date'])) + 1)) : date('Y-m-d');
            $to_date = date('Y-m-d', strtotime($from_date . "+" . $tenure['months'] . " months"));
            $this->data['package_title'] = $package['title'];
            $this->data['from_date'] = $from_date;
            $this->data['to_date'] = $to_date;
            $this->send_package_purchased_email($this->session->userdata('user_id'), $this->data['package_title'], $this->data['from_date'], $this->data['to_date'], $package['plan_type']);
        }

        $this->load->view('admin/success', $this->data);
    }

    public function cancel()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect(base_url());
        }

        $this->load->view('admin/cancel', $this->data);
    }
    public function send_package_purchased_email($user_id, $title, $from_date, $to_date, $type)
    {

        $user_data = $this->users_model->get_user_by_id($user_id);
        $this->email->clear(TRUE);
        $config = $this->config->item('email_config');
        if (isset($config['smtp_user']) && !empty($config['smtp_user'])) {
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $from_email = get_admin_email();
            $this->email->from($from_email, get_compnay_title());
            $this->email->to($user_data[0]['email']);
            if ($type == 'free') {
                $this->email->subject('New plan added to your subscription successfully');
                $data['heading'] = 'New plan added to your subscription successfully';
                $data['message'] = "<p>Hello Dear <b>" . $user_data[0]['first_name'] . " " . $user_data[0]['last_name'] . "</b>, New free plan <b>" . $title . "</b> added to your subscription successfully.</p>
                    <p><b>Start Date : </b>" . $from_date . " <b>End date : </b>" . $to_date . "</p>
                    <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
            } else {
                $this->email->subject('New plan purchased successfully');
                $data['heading'] = 'New plan purchased successfully';
                $data['message'] = "<p>Hello Dear <b>" . $user_data[0]['first_name'] . " " . $user_data[0]['last_name'] . "</b>, New plan <b>" . $title . "</b> added to your subscription successfully.</p>
                    <p><b>Start Date : </b>" . $from_date . " <b>End date : </b>" . $to_date . "</p>
                    <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
            }
            $data['logo'] = base_url('assets/backend/icons/') . get_compnay_logo();
            $data['company_title'] = get_compnay_title();

            $this->email->message($this->load->view('admin/plan-purchased-email-template.php', $data, true));
            $this->email->send();
        }
    }
}
