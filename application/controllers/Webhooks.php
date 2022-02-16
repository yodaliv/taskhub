<?php
error_reporting(0);
ini_set('display_errors', 0);
defined('BASEPATH') or exit('No direct script access allowed');

class Webhooks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['packages_model', 'users_model', 'workspace_model']);
        $this->load->library(['ion_auth', 'form_validation', 'paypal_lib', 'session']);
        $this->load->helper(['url', 'language', 'form', 'file']);
        $this->load->library('session');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $admin_id = $this->session->userdata('user_id');
        $this->data['admin_id'] = $admin_id;
    }
    public function paypal_webhook()
    {

        // Paypal posts the transaction data
        $paypalInfo = $this->input->post();
        write_file('paypal_log_file.php', 'Paypal Webhook --> ' . var_export($paypalInfo, true));
        if (!empty($paypalInfo)) {
            // Validate and get the ipn response
            $ipnCheck = $this->paypal_lib->validate_ipn($paypalInfo);
            // Check whether the transaction is valid
            if ($ipnCheck && $paypalInfo['payer_status'] == 'verified') {
                $customData = explode('|', $paypalInfo['custom']);
                $tenure = $this->db->where('id', $customData[2])->get('packages_tenures')->row_array();
                $package = $this->db->where('id', $customData[1])->get('packages')->row_array();
                $package_data = [
                    'user_id' => $customData[0],
                    'package_id' => $customData[1],
                    'title' => $package['title'],
                    'tenure_id' => $tenure['id'],
                    'from_date' => $customData[3],
                    'to_date' => $customData[4],
                    'amount' => $tenure['rate'],
                    'months' => $tenure['months'],
                    'payment_method' => 'Paypal',
                    'plan_type' => $package['plan_type'],
                    'modules' => $package['modules'],
                    'tenure' => $tenure['tenure'],
                    'storage_unit' => $package['storage_unit'],
                    'storage_allowed' => $package['max_storage_size'],
                    'allowed_workspaces' => $package['max_workspaces'],
                    'allowed_employees' => $package['max_employees']
                ];


                // Insert the transaction data in the database
                $id = $this->packages_model->add_user_package($package_data);
                $transaction_data = [
                    'item_id' => $id,
                    'package_id' => $customData[1],
                    'user_id' => $customData[0],
                    'type' => 'paypal',
                    'txn_id'         => $paypalInfo["txn_id"],
                    'amount'    => $paypalInfo["mc_gross"],
                    'status'         => $paypalInfo['payer_status'],
                    'message'         => 'Payment Verified',
                    'currency_code'  => $paypalInfo["mc_currency"]
                ];
                $this->db->insert('transactions', $transaction_data);
                $newdata = array(
                    'payment_method'  => 'paypal',
                    'from_date'     => $customData[3],
                    'to_date' => $customData[4],
                    'package' => $customData[7]
                );
                $this->session->set_userdata($newdata);
                $this->send_package_purchased_email($customData[0], $package['title'], $customData[3], $customData[4], $package['plan_type']);
                // $this->session->userdata('payment_method', 'paypal');
                // $this->session->userdata('from_date', $customData[3]);
                // $this->session->userdata('to_date', $customData[4]);
                // $this->session->userdata('package', $customData[7]);
                redirect(base_url('admin/payment/success'), 'refresh');
            } else {
                redirect(base_url('admin/payment/cancel'), 'refresh');
                return false;
            }
        } else {
            redirect(base_url('admin/payment/cancel'), 'refresh');
            return false;
        }
    }
    public function stripe_webhook()
    {
        $this->load->library(['stripe']);
        $credentials = $this->stripe->get_credentials();
        $request_body = file_get_contents('php://input');
        $event = json_decode($request_body, FALSE);
        write_file('custom_log_file.php', 'Stripe Webhook --> ' . var_export($event, true));

        $http_stripe_signature = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : "";
        $result = $this->stripe->construct_event($request_body, $http_stripe_signature, $credentials['webhook_key']);

        if ($result == "Matched") {
            if ($event->type == 'charge.succeeded') {
                $user_id = $event->data->object->metadata->user_id;
                $package_id = $event->data->object->metadata->package_id;
                $tenure_id = $event->data->object->metadata->tenure_id;
                $tenure = $this->db->where('id', $tenure_id)->get('packages_tenures')->row_array();
                $package = $this->db->where('id', $package_id)->get('packages')->row_array();
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
                    'payment_method' => 'Stripe',
                    'plan_type' => $package['plan_type'],
                    'modules' => $package['modules'],
                    'tenure' => $tenure['tenure'],
                    'storage_unit' => $package['storage_unit'],
                    'storage_allowed' => $package['max_storage_size'],
                    'allowed_workspaces' => $package['max_workspaces'],
                    'allowed_employees' => $package['max_employees']
                ];
                $id = $this->packages_model->add_user_package($package_data);
                $transaction_data = [
                    'item_id' => $id,
                    'package_id' => $package_id,
                    'user_id' => $user_id,
                    'type' => 'stripe',
                    'txn_id'         => $event->data->object->payment_intent,
                    'amount'    => $event->data->object->amount,
                    'status'         => 'verified',
                    'message'         => 'Payment Verified',
                    'currency_code'  => $event->data->object->currency
                ];
                $transaction_id = $this->db->insert('transactions', $transaction_data);
                if ($id != false && $transaction_id != false) {
                    redirect(base_url('admin/payment/success'), 'refresh');
                    return false;
                } else {
                    $response['error'] = true;
                    $response['transaction_status'] = $event->type;
                    $response['message'] = "Something went wrong!";
                    echo json_encode($response);
                    redirect(base_url('admin/payment/cancel'), 'refresh');
                    return false;
                }
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction could not be detected.";
                echo json_encode($response);
                redirect(base_url('admin/payment/cancel'), 'refresh');
                return false;
            }
        } else {
            log_message('error', 'Stripe Webhook | Invalid Server Signature  --> ' . var_export($result, true));
            redirect(base_url('admin/payment/cancel'), 'refresh');
            return false;
        }
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
