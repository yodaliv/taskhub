<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transactions_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function get_transaction_list($user_id = "")
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
            $where = " and (package like '%" . $search . "%')";
        }
        if (!empty($user_id)) {
            $where .= empty($where) ? " where user_id=$user_id" : " and user_id=$user_id";
        }
        $query = $this->db->query("SELECT count(*) as total FROM transactions" . $where);
        $total = 0;
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT t.*,p.title as package,u.first_name,u.last_name FROM transactions t left join packages p ON p.id=t.package_id left join users u on t.user_id=u.id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $date = date('Y-m-d H:i:s');

        foreach ($res as $row) {
            $action = '<div class="dropdown card-widgets">
            <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item has-icon delete-transaction-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
            </div>
        </div>';

            $action_btns = '<div class="btn-group no-shadow">
            ' . $action . '
            </div>';
            if ($row['status'] == 'verified') {
                $status = ' <span class="badge badge-success projects-badge">Verified</span>';
            } else {
                $status = '-';
            }
            $type = ucfirst($row['type']);
            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['user'] = $row['first_name'] . ' ' . $row['last_name'];
            $tempRow['item_id'] = $row['item_id'];
            $tempRow['package'] = $row['package'];
            $tempRow['type'] = $type;
            $tempRow['txn_id'] = $row['txn_id'];
            $tempRow['status'] = $status;
            $tempRow['currency_code'] = strtoupper($row['currency_code']);
            $tempRow['message'] = $row['message'];
            $tempRow['amount'] = !empty($row['amount']) ? get_currency_symbol() . ' ' . $row['amount'] : get_currency_symbol() . '0';
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['action'] = $action_btns;

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_transaction_by_id($id)
    {
        $this->db->from('transactions');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function delete_transaction($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('transactions'))
            return true;
        else
            return false;
    }
}
