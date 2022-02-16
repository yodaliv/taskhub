<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['workspace_model', 'notifications_model', 'packages_model', 'transactions_model', 'users_model']);
        $this->load->library(['ion_auth', 'form_validation', 'paypal_lib', 'razorpay', 'stripe']);
        $this->load->helper(['url', 'language']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        if ($this->ion_auth->logged_in()) {
            if (!is_super()) {
                if (is_admin()) {
                    $admin_id = $this->session->userdata('user_id');
                } else {
                    $admin_id = get_admin_id_by_workspace_id($this->session->userdata('workspace_id'));
                }
                $this->data['admin_id'] = $admin_id;
            }
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else
		if (is_admin()) {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            $workspace_id = $this->session->userdata('workspace_id');
            $user_id = $this->session->userdata('user_id');
            $current_package = $this->packages_model->get_current_package($user_id);
            if (!empty($current_package) && isset($current_package[0])) {
                $data['current_package'] = $current_package[0];
            }
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $data['is_admin'] =  is_admin();
            $data['role'] =  $this->session->userdata('role');
            $this->load->view('admin/billing', $data);
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }

    public function Packages()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else
		if (is_admin()) {
            $data['user'] = $user = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();

            $product_ids = explode(',', $user->workspace_id);

            $section = array_map('trim', $product_ids);

            $product_ids = $section;

            $data['workspace'] = $workspace = $this->workspace_model->get_workspace($product_ids);
            $workspace_id = $this->session->userdata('workspace_id');
            $packages = $this->packages_model->get_packages();
            $data['packages'] = $packages;
            $data['notifications'] = !empty($workspace_id) ? $this->notifications_model->get_notifications($this->session->userdata['user_id'], $workspace_id) : array();
            $data['is_admin'] =  is_admin();
            $data['role'] =  $this->session->userdata('role');
            $this->load->view('admin/packages', $data);
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }
    public function get_tenure_by_id($id = '')
    {
        if (!empty($id) && is_numeric($id)) {
            $data = $this->packages_model->get_tenure_by_id($id);
            $data[0]['csrfName'] = $this->security->get_csrf_token_name();
            $data[0]['csrfHash'] = $this->security->get_csrf_hash();
            echo json_encode($data[0]);
        } else {
            $this->session->set_flashdata('message', 'Something went wrong!');
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
        }
    }

    public function get_subscription_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_admin()) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $user_id = $this->session->userdata('user_id');
            return $this->packages_model->get_subscription_list($user_id);
        }
    }
    public function get_transaction_list()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (!is_admin()) {
                $this->session->set_flashdata('message', NOT_AUTHORIZED);
                $this->session->set_flashdata('message_type', 'error');
                redirect($this->session->userdata('role') . '/home', 'refresh');
                return false;
                exit();
            }
            $user_id = $this->session->userdata('user_id');
            return $this->transactions_model->get_transaction_list($user_id);
        }
    }
    public function delete_subscription()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/subscriptions', 'refresh');
            return false;
            exit();
        }
        if (!is_admin() && !is_super()) {
            $this->session->set_flashdata('message', NOT_AUTHORIZED);
            $this->session->set_flashdata('message_type', 'error');
            redirect($this->session->userdata('role') . '/home', 'refresh');
            return false;
            exit();
        }

        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)  || $id < 1) {
            $subscription = $this->packages_model->get_subscription_by_id($id);
            if (isset($subscription[0]) && !empty($subscription[0])) {
                if ($subscription[0]['user_id'] == $this->session->userdata('user_id') || is_super()) {
                    if ($this->packages_model->delete_subscription($id)) {

                        $this->session->set_flashdata('message', 'Subscription Deleted Successfully.');
                        $this->session->set_flashdata('message_type', 'success');
                        $response['error'] = false;
                    } else {
                        $this->session->set_flashdata('message', 'Subscription could not be deleted! Try again!');
                        $this->session->set_flashdata('message_type', 'error');

                        $response['error'] = true;
                        $response['message'] = 'Subscription could not be deleted! Try again!';
                    }
                } else {
                    $this->session->set_flashdata('message', 'You are not authorized to delete this subscription');
                    $this->session->set_flashdata('message_type', 'error');
                    $response['error'] = true;
                }
            } else {
                $this->session->set_flashdata('message', 'Something went wrong');
                $this->session->set_flashdata('message_type', 'error');
                $response['error'] = true;
            }
        }
        echo json_encode($response);
    }
    public function add_subscription()
    {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        } else
		if (is_admin()) {
            $user_id = $this->session->userdata('user_id');
            $tenure = $this->db->where('id', $_POST['tenure_id'])->get('packages_tenures')->row_array();
            $package = $this->db->where('id', $_POST['package_id'])->get('packages')->row_array();
            if (empty($tenure) || empty($package)) {
                $response['error'] = true;
                $response['message'] = "Something went wrong please try again!";
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($response));
                return false;
            }
            if (!isset($_POST['payment_method']) || $_POST['payment_method'] == '' && $tenure['rate'] != 0) {
                $response['error'] = true;
                $response['message'] = "Please select payment method.";
                $response['data'] = array();
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                print_r(json_encode($response));
                return false;
            }
            $package_id = $package['id'];
            $tenure_id = $tenure['id'];
            $payment_method = $_POST['payment_method'];
            $amount = $tenure['rate'];
            $plan_type = $package['plan_type'];
            $current_to_date = $this->packages_model->get_to_date_by_user_id($user_id);
            $from_date = isset($current_to_date[0]['to_date']) && !empty($current_to_date[0]['to_date']) ? date('Y-m-d', strtotime($current_to_date[0]['to_date'] . ' +1 day')) : date('Y-m-d');
            $to_date = date('Y-m-d', strtotime($from_date . "+" . $tenure['months'] . " months"));
            if ($amount == 0) {
                $package_data = [
                    'user_id' => $user_id,
                    'package_id' => $package_id,
                    'title' => $package['title'],
                    'tenure_id' => $tenure_id,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'amount' => $amount,
                    'months' => $tenure['months'],
                    'payment_method' => '',
                    'plan_type' => $plan_type,
                    'modules' => $package['modules'],
                    'tenure' => $tenure['tenure'],
                    'storage_unit' => $package['storage_unit'],
                    'storage_allowed' => $package['max_storage_size'],
                    'allowed_workspaces' => $package['max_workspaces'],
                    'allowed_employees' => $package['max_employees']
                ];
                if ($amount == 0) {
                    $id = $this->packages_model->add_user_package($package_data);
                    if ($id != false) {
                        $this->send_package_purchased_email($package['title'], $from_date, $to_date, $plan_type);
                        $this->session->set_flashdata('message', 'Subscription added successfully!');
                        $this->session->set_flashdata('message_type', 'success');
                        $response['error'] = false;
                        echo json_encode($response);
                        return false;
                    } else {
                        $this->session->set_flashdata('message', 'Something went wrong!');
                        $this->session->set_flashdata('message_type', 'error');
                        $response['error'] = true;
                        echo json_encode($response);
                        return false;
                    }
                }
            }
            if ($payment_method == "Razorpay") {
                if (!verify_payment_transaction($_POST['razorpay_payment_id'],'razorpay')) {
                    $response['error'] = true;
                    $response['message'] = "Invalid Razorpay Payment Transaction.";
                    $response['data'] = array();
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    print_r(json_encode($response));
                    return false;
                } else {
                    $package_data = [];
                    $package_data = [
                        'user_id' => $user_id,
                        'package_id' => $package_id,
                        'title' => $package['title'],
                        'tenure_id' => $tenure_id,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'amount' => $amount,
                        'months' => $tenure['months'],
                        'payment_method' => 'Razorpay',
                        'plan_type' => $plan_type,
                        'modules' => $package['modules'],
                        'tenure' => $tenure['tenure'],
                        'storage_unit' => $package['storage_unit'],
                        'storage_allowed' => $package['max_storage_size'],
                        'allowed_workspaces' => $package['max_workspaces'],
                        'allowed_employees' => $package['max_employees']
                    ];
                    $id = $this->packages_model->add_user_package($package_data);
                    $this->send_package_purchased_email($package['title'], $from_date, $to_date, $plan_type);
                    $transaction_data = [
                        'item_id' => $id,
                        'package_id' => $package_id,
                        'user_id' => $this->session->userdata('user_id'),
                        'type' => 'razorpay',
                        'txn_id'         => $_POST["razorpay_payment_id"],
                        'amount'    => $amount,
                        'status'         => 'verified',
                        'message'         => 'Payment Verified',
                        'currency_code'  => 'INR'
                    ];
                    $this->db->insert('transactions', $transaction_data);
                    $response['error'] = false;
                    $response['package_title'] = $package['title'];
                    $response['from_date'] = $from_date;
                    $response['to_date'] = $to_date;
                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();
                    print_r(json_encode($response));
                    return false;
                }
            } elseif ($payment_method == "Paypal") {
                if ($this->session->has_userdata('package_id')) {
                    $this->session->unset_userdata('package_id');
                }
                if ($this->session->has_userdata('from_date')) {
                    $this->session->unset_userdata('from_date');
                }
                if ($this->session->has_userdata('to_date')) {
                    $this->session->unset_userdata('to_date');
                }
                if ($this->session->has_userdata('payment_method')) {
                    $this->session->unset_userdata('payment_method');
                }
                if ($this->session->has_userdata('package')) {
                    $this->session->unset_userdata('package');
                }
                $this->session->set_userdata('from_date', $from_date);
                $this->session->set_userdata('to_date', $to_date);
                $this->session->set_userdata('package', $package['title']);
                $this->session->set_userdata('package_id', $package['id']);
                $this->session->set_userdata('payment_method', 'paypal');
                $response['error'] = false;
                $response['message'] = 'Redirecting for payment...';
                echo json_encode($response);
                return false;
                exit();
            } elseif ($payment_method == "Stripe") {
                if ($this->session->has_userdata('package_id')) {
                    $this->session->unset_userdata('package_id');
                }
                if ($this->session->has_userdata('tenure_id')) {
                    $this->session->unset_userdata('tenure_id');
                }
                if ($this->session->has_userdata('payment_method')) {
                    $this->session->unset_userdata('payment_method');
                }
                $this->session->set_userdata('package_id', $_POST['package_id']);
                $this->session->set_userdata('tenure_id', $_POST['tenure_id']);
                $this->session->set_userdata('payment_method', 'stripe');
                $response['error'] = false;
                $response['payment_method'] = 'stripe';
                $response['message'] = 'Processing payment';
                echo json_encode($response);
                return false;
                exit();
            }
        } else {
            $this->session->set_flashdata('message', 'You are not authorized to view this page!');
            $this->session->set_flashdata('message_type', 'error');
            $response['error'] = false;
            echo json_encode($response);
            return false;
        }
    }
    public function pre_payment_setup()
    {
        if ($this->ion_auth->logged_in()) {
            $user_id = $this->session->userdata('user_id');
            $tenure = $this->packages_model->get_tenure_by_id($_POST['tenure_id']);
            // print_r($tenure);
            if ($_POST['payment_method'] == "Razorpay") {
                $amount = $tenure[0]['rate'];
                // echo $amount;
                $order = $this->razorpay->create_order($amount * 100);
                $this->response['order_id'] = $order['id'];
            } elseif ($_POST['payment_method'] == "Stripe") {
                // $data = [];
                $user = $this->db->select('first_name,last_name,address,city,state,zip_code,country')
                    ->where(['id' => $user_id])
                    ->get('users')->result_array();
                $customer = array('name' => $user[0]['first_name'] . ' ' . $user[0]['last_name'], 'line1' => $user[0]['address'], 'postal_code' => $user[0]['zip_code'], 'city' => $user[0]['city'], 'state' => $user[0]['state'], 'country' => $user[0]['country']);
                $customer = $this->stripe->create_customer($customer);
                $order = $this->stripe->create_payment_intent(array('customer' => $customer['id'], 'amount' => $tenure[0]['rate']), array('user_id' => $user_id, 'package_id' => $_POST['package_id'], 'tenure_id' => $_POST['tenure_id']), array('name' => $user[0]['first_name'] . ' ' . $user[0]['last_name'], 'line1' => $user[0]['address'], 'postal_code' => $user[0]['zip_code'], 'city' => $user[0]['city'], 'state' => $user[0]['state'], 'country' => $user[0]['country']));
                $this->response['client_secret'] = $order['client_secret'];
                $this->response['id'] = $order['id'];
            }
            $this->response['error'] = false;
            $this->response['message'] = "Client Secret Get Successfully.";
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Unauthorised access is not allowed.";
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function send_package_purchased_email($title, $from_date, $to_date, $type)
    {
        if ($this->ion_auth->logged_in()) {
            $user_data = $this->users_model->get_user_by_id($this->data['admin_id']);
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
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Unauthorised access is not allowed.";
            print_r(json_encode($this->response));
            return false;
        }
    }
}
