<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Twilio
 *
 * Author: Ben Edmunds
 *		  ben.edmunds@gmail.com
 *         @benedmunds
 *
 * Location:
 *
 * Created:  03.29.2011
 *
 * Description:  Twilio configuration settings.
 *
 *
 */

/**
 * Mode ("sandbox" or "prod")
 **/
$twilio_settings = get_system_settings('twilio');
$twilio_settings = isset($twilio_settings[0]['data']) ? json_decode($twilio_settings[0]['data'], 1) : '';
$config['mode'] = isset($twilio_settings['mode']) ? $twilio_settings['mode'] : '';

/**
 * Account SID
 **/
$config['account_sid'] = isset($twilio_settings['account_sid']) ? $twilio_settings['account_sid'] : '';

/**
 * Auth Token
 **/
$config['auth_token'] = isset($twilio_settings['auth_token']) ? $twilio_settings['auth_token'] : '';

/**
 * API Version
 **/
// $config['api_version']   = $twilio_settings['api_version'];
$config['api_version'] = isset($twilio_settings['api_version']) ? $twilio_settings['api_version'] : '';

/**
 * Twilio Phone Number
 **/
$config['number'] = isset($twilio_settings['number']) ? $twilio_settings['number'] : '';


/* End of file twilio.php */