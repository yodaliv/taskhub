<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Faqs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function get_faqs_list()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'ASC';
        $where = '';
        $get = $this->input->get();
        if (isset($get['sort']))
            $sort = strip_tags($get['sort']);
        if (isset($get['offset']))
            $offset = strip_tags($get['offset']);
        if (isset($get['limit']))
            $limit = strip_tags($get['limit']);
        if (isset($get['order']))
            $order = strip_tags($get['order']);
        if (isset($get['search']) &&  !empty($get['search'])) {
            $search = strip_tags($get['search']);
            $where = " WHERE (id like '%" . $search . "%' OR question like '%" . $search . "%' OR answer like '%" . $search . "%' OR date_created like '%" . $search . "%')";
        }
        $query = $this->db->query("SELECT count(id) as total FROM faqs" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT * FROM faqs" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
        $res = $query->result_array();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($res as $row) {
            if ($row['status'] == 1) {
                $status = '<div class="badge badge-success">Active</div>';
            } else {
                $status = '<div class="badge badge-danger">De-active</div>';
            }
            $action = '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item has-icon modal-edit-faq-ajax" href="#" data-id="' . $row['id'] . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                        <a class="dropdown-item has-icon delete-faq-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                    </div>
                </div>';

            $action_btns = '<div class="btn-group no-shadow">
                            ' . $action . '
                </div>';
            $tempRow['id'] = $row['id'];
            $tempRow['question'] = $row['question'];
            $tempRow['answer'] = $row['answer'];
            $tempRow['status'] = $status;
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_faq_by_id($id)
    {
        $this->db->from('faqs');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function add_faq($data)
    {
        if ($this->db->insert('faqs', $data))
            return $this->db->insert_id();
        else
            return false;
    }
    function update_faq($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('faqs', $data);
    }

    function delete_faq($id)
    {
        if ($this->db->delete('faqs', array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }
    function get_faqs()
    {
        $this->db->order_by("id", "desc");
        $this->db->where("status", 1);
        $query = $this->db->get('faqs');
        return $query->result_array();
    }
}
