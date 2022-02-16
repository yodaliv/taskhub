<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packages_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
    }
    function get_packages_list()
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
            $where = " WHERE (id like '%" . $search . "%' OR title like '%" . $search . "%' OR date_created like '%" . $search . "%')";
        }
        if (isset($get['status']) &&  $get['status'] != '') {
            $status = strip_tags($get['status']);
            $where .= empty($where) ? " WHERE status = '" . $status . "'" : " AND status = '" . $status . "'";
        }
        if (isset($get['package_type']) && !empty($get['package_type'])) {
            $where .= empty($where) ? " WHERE plan_type = '" . $get['package_type'] . "'" : " AND plan_type = '" . $get['package_type'] . "'";
        }
        $query = $this->db->query("SELECT count(id) as total FROM packages" . $where);
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT * FROM packages" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);
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
            if ($row['plan_type'] == 'free') {
                $plan_type = '<div class="badge badge-success">FREE</div>';
            } else {
                $plan_type = '<div class="badge badge-info">PAID</div>';
            }
            if ($row['storage_unit'] == 'mb') {
                $storage_unit = 'MB';
            } else {
                $storage_unit = 'GB';
            }
            $temp = '<ul>';
            $modules = json_decode($row['modules'], true);
            $temp .= isset($modules['projects']) && $modules['projects'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Projects</span>' : '<span class="badge badge-danger m-1"><i class="fa fa-times"></i> Projects</span>';
            $temp .= isset($modules['tasks']) && $modules['tasks'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Tasks</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Tasks</span>';
            $temp .= isset($modules['calendar']) && $modules['calendar'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Calendar</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Calendar</span>';
            $temp .= isset($modules['chat']) && $modules['chat'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Chat</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Chat</span>';
            $temp .= isset($modules['finance']) && $modules['finance'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Finance</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Finance</span>';
            $temp .= isset($modules['users']) && $modules['users'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Users</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Users</span>';
            $temp .= isset($modules['clients']) && $modules['clients'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Clients</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Clients</span>';
            $temp .= isset($modules['activity_logs']) && $modules['activity_logs'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Activity logs</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Activity logs</span>';
            $temp .= isset($modules['leave_requests']) && $modules['leave_requests'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Leave requests</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Leave requests</span>';
            $temp .= isset($modules['notes']) && $modules['notes'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Notes</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Notes</span>';
            $temp .= isset($modules['mail']) && $modules['mail'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Mail</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Mail</span>';
            $temp .= isset($modules['announcements']) && $modules['announcements'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Announcements</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Announcements</span>';
            $temp .= isset($modules['notifications']) && $modules['notifications'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Notifications</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Notifications</span>';
            $temp .= isset($modules['sms_notifications']) && $modules['sms_notifications'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> SMS Notifications</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> SMS Notifications</span>';
            $temp .= isset($modules['support_system']) && $modules['support_system'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Support System</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Support System</span>';
            $temp .= isset($modules['meetings']) && $modules['meetings'] == 1 ? '<span class="badge badge-success m-1"><i class="fa fa-check"></i> Meetings</span>' : '<span class="badge badge-danger  m-1"><i class="fa fa-times"></i> Meetings</span>';
            $default_package = default_package();
            if ($default_package == $row['id']) {
                $is_default = TRUE;
            }else{
                $is_default = FALSE;
            }
            $action = '<div class="dropdown card-widgets">
                                <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item has-icon" href="' . base_url('super-admin/packages/edit-package/' . $row['id']) . '"><i class="fas fa-pencil-alt"></i>' . (!empty($this->lang->line('label_edit')) ? $this->lang->line('label_edit') : 'Edit') . '</a>
                                    <a class="dropdown-item has-icon delete-package-alert" href="#" data-id="' . $row['id'] . '" data-default="' . $is_default . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
                                </div>
                            </div>';
            $action_btns = '<div class="btn-group no-shadow">
                        ' . $action . '
                        </div>';
            $default = $row['id'] == default_package() ? ' <span class="badge badge-info projects-badge">Default</span>' : '';
            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'] . $default;
            $tempRow['max_workspaces'] = $row['max_workspaces'] == 0 ? 'Unlimited' : $row['max_workspaces'];
            $tempRow['max_employees'] = $row['max_employees'] == 0 ? 'Unlimited' : $row['max_employees'];
            $tempRow['max_storage_size'] = $row['max_storage_size'] == 0 ? 'Unlimited' : $row['max_storage_size'] . ' ' . $storage_unit;
            $tempRow['storage_unit'] = $row['max_storage_size'] == 0 ? 'N/A' : $storage_unit;
            $tempRow['plan_type'] = $plan_type;
            $tempRow['position_no'] = $row['position_no'];
            $tempRow['description'] = $row['description'];
            $tempRow['status'] = $status;
            $tempRow['modules'] = $temp;
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['action'] = $action_btns;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function get_package_by_id($id)
    {

        $this->db->from('packages');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_current_package($user_id)
    {
        $current_date = date('Y-m-d');
        $query = $this->db->query("select * from users_packages where user_id=" . $user_id . " and  from_date <= '" . $current_date . "' and to_date >= '" . $current_date . "'");
        $res = $query->result_array();
        return $res;
    }

    function get_package_id_by_user_id($id)
    {
        $this->db->select('package_id');
        $this->db->from('users_packages');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_tenure_id_by_user_id($id)
    {
        $this->db->select('tenure_id');
        $this->db->from('users_packages');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_to_date_by_user_id($user_id)
    {
        $this->db->select_max('to_date');
        $this->db->from('users_packages');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_tenure_by_id($id)
    {
        $this->db->from('packages_tenures');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function add_package($data)
    {
        if ($this->db->insert('packages', $data))
            return $this->db->insert_id();
        else
            return false;
    }
    function add_user_package($data)
    {
        if ($this->db->insert('users_packages', $data))
            return $this->db->insert_id();
        else
            return false;
    }
    function update_package($data, $package_id)
    {
        $this->db->where('id', $package_id);
        return $this->db->update('packages', $data);
    }

    function delete_package($id)
    {
        if ($this->db->delete('packages', array('id' => $id))) {
            $this->db->delete('packages_tenures', array('package_id' => $id));
            return true;
        } else {
            return false;
        }
    }
    function get_packages()
    {

        $this->db->order_by("position_no", "asc");
        $this->db->where("status", 1);
        $query = $this->db->get('packages');
        return $query->result_array();
    }

    function add_package_tenure($data)
    {
        if ($this->db->insert('packages_tenures', $data))
            return $this->db->insert_id();
        else
            return false;
    }

    function get_package_tenures($id)
    {
        $this->db->from('packages_tenures');
        $this->db->where('package_id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }

    function update_package_tenure($data, $tenure_id)
    {
        $this->db->where('id', $tenure_id);
        return $this->db->update('packages_tenures', $data);
    }

    function delete_package_tenure($id)
    {
        if ($this->db->delete('packages_tenures', array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    function get_tenures_by_package_id($id)
    {
        $this->db->from('packages_tenures');
        $this->db->where('package_id', $id);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_subscription_by_id($id)
    {
        $this->db->from('users_packages');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_subscription_list($user_id = "")
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
            $where = " where (u.first_name like '%" . $search . "%'|| u.last_name like '%" . $search . "%' || title like '%" . $search . "%' || plan_type like '%" . $search . "%' || from_date like '%" . $search . "%' || to_date like '%" . $search . "%' || date_created like '%" . $search . "%')";
        }
        if (isset($get['type']) && !empty($get['type']) && $get['type'] == 'active') {
            $where .= empty($where) ? " where NOW() between from_date and to_date" : " and NOW() between from_date and to_date";
        }
        if (isset($get['type']) && !empty($get['type']) && $get['type'] == 'expired') {
            $where .= empty($where) ? " where to_date < CURDATE()" : " and to_date < CURDATE()";
        }
        if (isset($get['type']) && !empty($get['type']) && $get['type'] == 'upcoming') {
            $where .= empty($where) ? " where from_date > CURDATE()" : " and from_date > CURDATE()";
        }
        if (!empty($user_id)) {
            $where .= empty($where) ? " where user_id=$user_id" : " and user_id=$user_id";
        }
        $query = $this->db->query("SELECT count(*) as total FROM users_packages up left join users u on up.user_id=u.id" . $where);
        $total = 0;
        $res = $query->result_array();
        foreach ($res as $row) {
            $total = $row['total'];
        }

        $query = $this->db->query("SELECT up.*,u.first_name,u.last_name FROM users_packages up left join users u on up.user_id=u.id" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit);

        $res = $query->result_array();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $date = date('Y-m-d');
        foreach ($res as $row) {
            $action = '<div class="dropdown card-widgets">
            <a href="#" class="btn btn-light" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item has-icon delete-subscription-alert" href="#" data-id="' . $row['id'] . '"><i class="far fa-trash-alt"></i>' . (!empty($this->lang->line('label_delete')) ? $this->lang->line('label_delete') : 'Delete') . '</a>
            </div>
        </div>';

            $action_btns = '<div class="btn-group no-shadow">
            ' . $action . '
            </div>';
            if ($date >= date("Y-m-d", strtotime($row['from_date'])) && $date <= date("Y-m-d", strtotime($row['to_date']))) {
                $active = ' <span class="badge badge-info projects-badge">Active</span>';
            } elseif ($date > date("Y-m-d", strtotime($row['to_date']))) {
                $active = ' <span class="badge badge-danger projects-badge">Expired</span>';
            } elseif ($date < date("Y-m-d", strtotime($row['from_date']))) {
                $active = ' <span class="badge badge-warning projects-badge">Up coming</span>';
            } else {
                $active = '';
            }
            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['package_id'] = $row['package_id'];
            $tempRow['title'] = $row['title'] . $active;
            $tempRow['from_date'] = date("Y-m-d", strtotime($row['from_date']));
            $tempRow['to_date'] = date("Y-m-d", strtotime($row['to_date']));
            $tempRow['amount'] = !empty($row['amount']) ? get_currency_symbol() . ' ' . $row['amount'] : get_currency_symbol() . '0';
            $tempRow['months'] = $row['months'];
            $tempRow['payment_method'] = !empty($row['payment_method']) ? $row['payment_method'] : '-';
            $tempRow['plan_type'] = $row['plan_type'] == 'free' ? '<span class="badge badge-success">FREE</span>' : '<span class="badge badge-danger">PAID</span>';
            $tempRow['tenure'] = $row['tenure'];
            $tempRow['user_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $tempRow['date_created'] = $row['date_created'];
            $tempRow['action'] = $action_btns;

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    function delete_subscription($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('users_packages'))
            return true;
        else
            return false;
    }
}
