<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db->query('SET time_zone = "+05:30"');
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function save_settings($setting_type, $data, $admin_id = '')
    {
        if ($admin_id == '') {
            $settings = get_system_settings($setting_type);
            if (isset($settings[0]['data'])) {
                $this->db->where('type', $setting_type);
                return $this->db->update('settings', $data);
            } else {
                if ($this->db->insert('settings', $data))
                    return $this->db->insert_id();
                else
                    return false;
            }
        } else {

            return $this->db->set($data)->where('id', $admin_id)->update('users');
        }
    }
}
