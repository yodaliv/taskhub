<?php

class Migrate extends CI_Controller
{

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        } else {
            if (is_super()) {
                $this->load->library('migration');
                if ($this->migration->latest()) {
                    echo "Migration successfully!";
                    return true;
                } else {
                    echo "Migration failed!";
                    return false;
                }
            } else {
                redirect($this->session->userdata('role') . '/home', 'refresh');
            }
        }
    }
}
