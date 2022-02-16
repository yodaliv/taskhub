<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tickets_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['projects_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }

    function get_ticket_types_list($user_id, $workspace_id = '')
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
            $where = " and (id like '%" . $search . "%' || title like '%" . $search . "%')";
        }
        $where .= !empty($workspace_id) ? ' and workspace_id =' . $workspace_id : '';
        $query = $this->db->query("SELECT count(id) as total FROM ticket_types WHERE user_id = $user_id" . $where);
        $total = 0;
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT * FROM ticket_types WHERE user_id = $user_id " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $action = '';
        $action_btn = '';
        $is_super = is_super() ? 1 : 0;
        foreach ($res as $row) {
            $action = '';
            $tempRow['id'] = $row['id'];
            $action_btn = '';
            if (is_super() || is_admin() || is_workspace_admin($this->session->userdata('user_id'), $this->session->userdata('workspace_id'))) {
                $action .= '<div class="dropdown card-widgets">
                    <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item has-icon modal-edit-ticket-type-btn" href="#" data-id="' . $row['id'] . '" data-super="' . $is_super . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                        <a class="dropdown-item has-icon delete-ticket-type-alert" href="#" data-id="' . $row['id'] . '" data-super="' . $is_super . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                    </div>
                </div>';
            }
            $tempRow['title'] = $row['title'];
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['action'] = $action;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_ticket_types($workspace_id)
    {
        $this->db->where('workspace_id', $workspace_id);
        $this->db->from('ticket_types');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_ticket_type_by_id($id)
    {

        $this->db->from('ticket_types');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }


    function add_ticket_type($data)
    {
        if ($this->db->insert('ticket_types', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function edit_ticket_type($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('ticket_types', $data))
            return true;
        else
            return false;
    }
    function delete_ticket_type($id)
    {
        $query = $this->db->query("SELECT id FROM tickets WHERE ticket_type_id=$id ");
        $data = $query->result_array();
        foreach ($data as $row) {
            $ticket_id = $row['id'];
            $query = $this->db->query("SELECT attachments FROM ticket_messages WHERE ticket_id=$ticket_id ");
            $data = $query->result_array();
            $attachments = json_decode($data[0]['attachments'], 1);
            for ($i = 0; $i < count($attachments); $i++) {
                if (file_exists('assets/backend/tickets/attachments/' . $attachments[$i])) {
                    unlink('assets/backend/tickets/attachments/' . $attachments[$i]);
                }
            }
            $this->db->delete('ticket_messages', array('ticket_id' => $ticket_id));
        }
        $this->db->delete('tickets', array('ticket_type_id' => $id));
        if ($this->db->delete('ticket_types', array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }
    function delete_ticket($id)
    {
        $query = $this->db->query("SELECT attachments FROM ticket_messages WHERE ticket_id=$id ");
        $data = $query->result_array();
        foreach ($data as $row) {
            $attachments = json_decode($row['attachments'], 1);
            for ($i = 0; $i < count($attachments); $i++) {
                if (file_exists('assets/backend/tickets/attachments/' . $attachments[$i])) {
                    unlink('assets/backend/tickets/attachments/' . $attachments[$i]);
                }
            }
        }
        $this->db->delete('ticket_messages', array('ticket_id' => $id));
        if ($this->db->delete('tickets', array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }
    function add_ticket($data)
    {
        if ($this->db->insert('tickets', $data))
            return $this->db->insert_id();
        else
            return false;
    }
    function get_tickets_list($user_id, $workspace_id)
    {

        $offset = 0;
        $limit = 10;
        $sort = 't.id';
        $order = 'ASC';
        $multipleWhere = '';
        $where = '';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "t.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`u.email`' => $search, '`u.phone`' => $search, '`t.subject`' => $search, '`t.email`' => $search, '`t.description`' => $search, '`tty.title`' => $search
            ];
        }
        $this->db->where('`t.workspace_id`', $workspace_id);

        if (!is_admin($user_id) && !is_workspace_admin($user_id, $workspace_id)) {
            $this->db->group_start();
            $this->db->where('`t.user_id`', $user_id);
            $this->db->or_where('find_in_set("' . $user_id . '", client_ids) <> 0');
            $this->db->group_end();
        }
        // $where[] = 'or find_in_set("'.$user_id.'", student_ids) <> 0';

        $count_res = $this->db->select(' COUNT(u.id) as `total`')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('tickets t')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        // print_r($this->db->last_query());
        $search_res = $this->db->select('t.*,tty.title,u.first_name,u.last_name,u.username')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('tickets t')->result_array();
        // print_r($this->db->last_query());

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $status = "";
        $tempRow = array();
        $users_icon = base_url('assets/backend/img/avatar/avatar-1.png');
        $clients_icon = base_url('assets/backend/img/avatar/avatar-4.png');
        $is_super = is_super() ? 1 : 0;
        foreach ($cat_search_res as $row) {
            $row = output_escaping($row);
            $operate = '<a href="javascript:void(0)" class="modal-view-ticket-btn btn btn-icon btn-sm btn-success mt-1 mb-1" data-id=' . $row['id'] . ' title="View"><i class="fa fa-comments"></i></a>';
            if (is_admin() || is_workspace_admin($user_id, $workspace_id) || $row['user_id'] == $user_id) {

                $operate .= ' <a href="javascript:void(0)" data-id=' . $row['id'] . ' data-super="' . $is_super . '" class="btn btn-icon btn-sm btn-primary mt-1 mb-1 modal-edit-ticket-btn"><i class="fa fa-edit"></i></a>';
                $operate .= ' <a href="javascript:void(0)" data-id=' . $row['id'] . ' data-super="' . $is_super . '" class="btn btn-icon btn-sm btn-danger mt-1 mb-1 delete-ticket-alert"><i class="fa fa-trash"></i></a>';
            }

            $tempRow['id'] = $row['id'];
            $tempRow['ticket_type_id'] = $row['ticket_type_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['subject'] = $row['subject'];
            $tempRow['email'] = $row['email'];
            $tempRow['description'] = $row['description'];
            if ($row['status'] == "1") {
                $status = '<label class="badge badge-secondary">PENDING</label>';
            } else if ($row['status'] == "2") {
                $status = '<label class="badge badge-info">OPENED</label>';
            } else if ($row['status'] == "3") {
                $status = '<label class="badge badge-success">RESOLVED</label>';
            } else if ($row['status'] == "4") {
                $status = '<label class="badge badge-danger">CLOSED</label>';
            } else if ($row['status'] == "5") {
                $status = '<label class="badge badge-warning">REOPENED</label>';
            }
            $tempRow['users'] = '<a href="#" class="modal-ticket-users-ajax" data-id="' . $row['id'] . '" title="Click to see ticket users"><img alt="image" class="mr-3 rounded-circle" width="40" src=' . $users_icon . '></a>';
            $tempRow['clients'] = '<a href="#" class="modal-ticket-clients-ajax" data-id="' . $row['id'] . '" title="Click to see ticket clients"><img alt="image" class="mr-3 rounded-circle" width="40" src=' . $clients_icon . '></a>';
            $tempRow['status'] = $status;
            $tempRow['last_updated'] = $row['last_updated'];
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['username'] = $row['user_id'] == $user_id ? 'You' : $row['first_name'] . ' ' . $row['last_name'];
            $tempRow['ticket_type'] = $row['title'];
            $tempRow['action'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_ticket_by_id($id)
    {
        $this->db->where('t.id', $id);
        $this->db->select('t.*,tt.title as ticket_type')->join('ticket_types tt', 'tt.id=t.ticket_type_id', 'left');
        return $this->db->get('tickets t')->result_array();
    }
    function edit_ticket($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('tickets', $data))
            return true;
        else
            return false;
    }
    function get_message_list($ticket_id = "", $user_id = "", $search = "", $offset = 0, $limit = 50, $sort = "tm.id", $order = "DESC", $data = array(), $msg_id = "")
    {
        $multipleWhere = '';

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "tm.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`t.subject`' => $search, '`tm.message`' => $search
            ];
        }

        if (!empty($ticket_id)) {
            $where['tm.ticket_id'] = $ticket_id;
        }

        if (!empty($user_id)) {
            $where['tm.user_id'] = $user_id;
        }
        if (!empty($msg_id)) {
            $where['tm.id'] = $msg_id;
        }

        $count_res = $this->db->select(' COUNT(tm.id) as `total`')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->or_where($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('ticket_messages tm')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('tm.*,t.subject,u.first_name,u.last_name')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->or_like($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('ticket_messages tm')->result_array();
        // print_r($this->db->last_query());
        $rows = $tempRow = $bulkData = array();
        $bulkData['total'] = $total;
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket Message(s) does not exist' : 'Message retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        $bulkData['csrfName'] = $this->security->get_csrf_token_name();
        $bulkData['csrfHash'] = $this->security->get_csrf_hash();
        if (!empty($cat_search_res)) {
            $data = $this->config->item('type');
            foreach ($cat_search_res as $row) {

                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['user_type'] = $row['user_type'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['ticket_id'] = $row['ticket_id'];
                $tempRow['message'] = (!empty($row['message'])) ? $row['message'] : "";
                $tempRow['name'] = $row['first_name'] . ' ' . $row['last_name'];
                if (!empty($row['attachments'])) {
                    $attachments = array();
                    $attachments = json_decode($row['attachments'], 1);
                    $counter = 0;
                    foreach ($attachments as $row1) {
                        $tmpRow['media'] = base_url('assets/backend/tickets/attachments/' . $row1);
                        $file = new SplFileInfo($row1);
                        $ext  = $file->getExtension();
                        if (in_array($ext, $data['image']['types'])) {
                            $tmpRow['type'] = "image";
                        } else if (in_array($ext, $data['video']['types'])) {
                            $tmpRow['type'] = "video";
                        } else if (in_array($ext, $data['document']['types'])) {
                            $tmpRow['type'] = "document";
                        } else if (in_array($ext, $data['archive']['types'])) {
                            $tmpRow['type'] = "archive";
                        }
                        $attachments[$counter] = $tmpRow;
                        $counter++;
                    }
                } else {
                    $attachments = array();
                }
                $tempRow['position'] = $row['user_id'] == $this->session->userdata('user_id') ? 'right' : 'left';
                $tempRow['attachments'] = $attachments;
                $tempRow['subject'] = $row['subject'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }

        print_r(json_encode($bulkData));
    }
    function add_ticket_message($data)
    {
        $data = escape_array($data);

        $ticket_msg_data = [
            'user_type' => $data['user_type'],
            'user_id' => $data['user_id'],
            'ticket_id' => $data['ticket_id'],
            'message' => $data['message']
        ];

        $this->db->insert('ticket_messages', $ticket_msg_data);
        $insert_id = $this->db->insert_id();
        if (!empty($insert_id)) {
            return  $insert_id;
        } else {
            return false;
        }
    }
    function get_messages($ticket_id = "", $user_id = "", $search = "", $offset = "", $limit = "", $sort = "", $order = "", $data = array(), $msg_id = "")
    {

        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`t.subject`' => $search, '`tm.message`' => $search
            ];
        }
        if (!empty($ticket_id)) {
            $where['tm.ticket_id'] = $ticket_id;
        }

        if (!empty($user_id)) {
            $where['tm.user_id'] = $user_id;
        }
        if (!empty($msg_id)) {
            $where['tm.id'] = $msg_id;
        }

        $count_res = $this->db->select(' COUNT(tm.id) as `total`')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('ticket_messages tm')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }
        $search_res = $this->db->select('tm.*,t.subject,u.first_name,u.last_name,u.username')->join('tickets t', 't.id=tm.ticket_id', 'left')->join('users u', 'u.id=tm.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('ticket_messages tm')->result_array();
        // print_r($this->db->last_query());
        $rows = $tempRow = $bulkData = $tmpRow = array();
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket Message(s) does not exist' : 'Message retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['user_type'] = $row['user_type'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['ticket_id'] = $row['ticket_id'];
                $tempRow['message'] = (!empty($row['message'])) ? $row['message'] : "";
                $tempRow['name'] = $row['first_name'] . ' ' . $row['last_name'];
                if (!empty($row['attachments'])) {
                    $attachments = array();
                    $attachments = json_decode($row['attachments'], 1);
                    $counter = 0;
                    foreach ($attachments as $row1) {
                        $tmpRow['media'] = get_image_url($row1);
                        $file = new SplFileInfo($row1);
                        $ext  = $file->getExtension();
                        if (in_array($ext, $data['image']['types'])) {
                            $tmpRow['type'] = "image";
                        } else if (in_array($ext, $data['video']['types'])) {
                            $tmpRow['type'] = "video";
                        } else if (in_array($ext, $data['document']['types'])) {
                            $tmpRow['type'] = "document";
                        } else if (in_array($ext, $data['archive']['types'])) {
                            $tmpRow['type'] = "archive";
                        }
                        $attachments[$counter] = $tmpRow;
                        $counter++;
                    }
                } else {
                    $attachments = array();
                }
                $tempRow['attachments'] = $attachments;
                $tempRow['position'] = $row['user_id'] == $this->session->userdata('user_id') ? 'right' : 'left';
                $tempRow['subject'] = $row['subject'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        return $bulkData;
    }
    function update_ticket_message($data, $id)
    {
        if (isset($data['attachments']) && !empty($data['attachments'])) {
            $data['attachments'] = json_encode($data['attachments']);
        }
        $this->db->where('id', $id);
        if ($this->db->update('ticket_messages', $data))
            return true;
        else
            return false;
    }
    function update_ticket($data, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('tickets', $data))
            return true;
        else
            return false;
    }
    function get_tickets($ticket_id = "", $ticket_type_id = "", $user_id = "", $status = "", $search = "", $offset = "", $limit = "1", $sort = "", $order = "")
    {

        $multipleWhere = '';
        $where = array();
        if (!empty($search)) {
            $multipleWhere = [
                '`u.id`' => $search, '`u.username`' => $search, '`u.email`' => $search, '`u.mobile`' => $search, '`t.subject`' => $search, '`t.email`' => $search, '`t.description`' => $search, '`tty.title`' => $search
            ];
        }
        if (!empty($ticket_id)) {
            $where['t.id'] = $ticket_id;
        }
        if (!empty($ticket_type_id)) {
            $where['t.ticket_type_id'] = $ticket_type_id;
        }
        if (!empty($user_id)) {
            $where['t.user_id'] = $user_id;
        }
        if (!empty($status)) {
            $where['t.status'] = $status;
        }
        $count_res = $this->db->select(' COUNT(u.id) as `total`')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $count_res->group_start();
            $count_res->or_like($multipleWhere);
            $count_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $count_res->where($where);
        }

        $cat_count = $count_res->get('tickets t')->result_array();
        foreach ($cat_count as $row) {
            $total = $row['total'];
        }

        $search_res = $this->db->select('t.*,tty.title,u.username')->join('ticket_types tty', 'tty.id=t.ticket_type_id', 'left')->join('users u', 'u.id=t.user_id', 'left');
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $search_res->group_start();
            $search_res->or_like($multipleWhere);
            $search_res->group_end();
        }
        if (isset($where) && !empty($where)) {
            $search_res->where($where);
        }

        $cat_search_res = $search_res->order_by($sort, $order)->limit($limit, $offset)->get('tickets t')->result_array();
        // print_r($this->db->last_query());
        $rows = $tempRow = $bulkData = array();
        $bulkData['error'] = (empty($cat_search_res)) ? true : false;
        $bulkData['message'] = (empty($cat_search_res)) ? 'Ticket(s) does not exist' : 'Tickets retrieved successfully';
        $bulkData['total'] = (empty($cat_search_res)) ? 0 : $total;
        if (!empty($cat_search_res)) {
            foreach ($cat_search_res as $row) {
                $row = output_escaping($row);
                $tempRow['id'] = $row['id'];
                $tempRow['ticket_type_id'] = $row['ticket_type_id'];
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['subject'] = $row['subject'];
                $tempRow['email'] = $row['email'];
                $tempRow['description'] = $row['description'];
                $tempRow['status'] = $row['status'];
                $tempRow['last_updated'] = $row['last_updated'];
                $tempRow['date_created'] = $row['date_created'];
                $tempRow['name'] = $row['username'];
                $tempRow['ticket_type'] = $row['title'];
                $rows[] = $tempRow;
            }
            $bulkData['data'] = $rows;
        } else {
            $bulkData['data'] = [];
        }
        return $bulkData;
    }
}
