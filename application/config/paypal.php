<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal IPN Class
// ------------------------------------------------------------------------
$paypal_settings = get_system_settings('paypal_payment_method');
$paypal_settings = json_decode($paypal_settings[0]['data'],1);
// Use PayPal on Sandbox or Live
$config['sandbox'] = $paypal_settings['paypal_mode']=='sandbox'?TRUE:FALSE; // FALSE for live environment

// PayPal Business Email ID
$config['business'] = $paypal_settings['business_email'];

// If (and where) to log ipn to file
$config['paypal_lib_ipn_log_file'] = '';
$config['paypal_lib_ipn_log'] = TRUE;

// Where are the buttons located at 
$config['paypal_lib_button_path'] = '';

// What is the default currency?
$config['paypal_lib_currency_code'] = 'USD';

?>
