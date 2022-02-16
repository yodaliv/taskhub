<?php
defined('BASEPATH') or exit('No direct script access allowed');

function is_admin($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_admin($id)) {
        return true;
    }
    return false;
}

function is_member($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_member($id)) {
        return true;
    }
    return false;
}

function is_editor($user_id = '')
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT id FROM workspace WHERE id=$workspace_id AND FIND_IN_SET($user_id,admin_id)");
    $config = $query->row_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}

function is_client($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_client($id)) {
        return true;
    }
    return false;
}
function is_super($id = FALSE)
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_super($id)) {
        return true;
    }
    return false;
}
function is_rtl($lang = '')
{
    $CI = &get_instance();
    if (empty($lang)) {
        $lang = $CI->session->userdata('site_lang');
    }
    $CI->db->from('languages');
    $CI->db->where(['language' => $lang, 'is_rtl' => 1]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}

function get_system_fonts()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->system_font) && !empty($config->system_font)) {
        $my_fonst = $config->system_font;
    } else {
        $my_fonst = 'default';
    }
    $return_my_fonts = '';
    $my_fonts = json_decode(file_get_contents("assets/backend/fonts/my-fonts.json"));
    if (!empty($my_fonts) && is_array($my_fonts)) {
        foreach ($my_fonts as $my_font) {
            if ($my_font->id == $my_fonst) {
                $return_my_fonts = $my_font;
            }
        }
    } else {
        return false;
    }
    if (!empty($return_my_fonts)) {
        return $return_my_fonts;
    } else {
        return 'default';
    }
}

// to get 'system_configurations' from settings table
function get_system_settings($setting_type)
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => $setting_type]);
    $query = $CI->db->get();
    $config = $query->result_array();
    // $config = json_decode($config[0]['data'],1);
    if (!empty($config)) {
        return $config;
    } else {
        return false;
    }
}

function hide_budget()
{
    $CI = &get_instance();

    if ($CI->ion_auth->is_admin()) {
        return false;
    }

    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->hide_budget) && !empty($config->hide_budget) && $config->hide_budget == 1) {
        return true;
    } else {
        return false;
    }
}
function default_package()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->default_package) && !empty($config->default_package)) {
        return $config->default_package;
    } else {
        return false;
    }
}
function default_tenure()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (isset($config->default_tenure) && !empty($config->default_tenure)) {
        return $config->default_tenure;
    } else {
        return false;
    }
}
function is_leaves_editor($user_id = '')
{
    $CI = &get_instance();
    if (empty($user_id)) {
        $user_id = $CI->session->userdata('user_id');
    }
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT id FROM workspace WHERE id=$workspace_id AND FIND_IN_SET($user_id,leave_editors)");
    $config = $query->row_array();
    if (!empty($config)) {
        return true;
    } else {
        return false;
    }
}


function get_workspace($id = '')
{
    $CI = &get_instance();
    if (empty($id)) {
        $id = $CI->session->userdata('workspace_id');
    }
    $CI->db->from('workspace');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return $config[0]['title'];
    }
}

function get_system_version()
{
    $CI = &get_instance();
    $CI->db->from('updates');
    $CI->db->order_by("id", "desc");
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config)) {
        return $config[0]['version'];
    }
}

function get_languages()
{
    $CI = &get_instance();
    $CI->db->from('languages');
    // $CI->db->where(['type'=>$setting_type]);
    $query = $CI->db->get();
    $config = $query->result_array();
    // $config = json_decode($config[0]['data'],1);
    if (!empty($config)) {
        return $config;
    }
}

function getTimezoneOptions()
{
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (
                !empty($zone['timezone_id'])
                and
                !in_array($zone['timezone_id'], $added)
                and
                in_array($zone['timezone_id'], $idents)
            ) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime(null, $z);
                $zone['time'] = $c->format('H:i a');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }

    array_multisort($offset, SORT_ASC, $data);

    $i = 0;
    $temp = array();
    foreach ($data as $key => $row) {
        $temp[0] = $row['time'];
        $temp[1] = formatOffset($row['offset']);
        $temp[2] = $row['timezone_id'];
        $options[$i++] = $temp;
    }

    if (!empty($options)) {
        return $options;
    }
}

function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
}


function get_compnay_title()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->company_title)) {
        return $config->company_title;
    }
}


function get_compnay_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->full_logo)) {
        return $config->full_logo;
    }
}
/*For admin*/
function get_admin_company_email($admin_id)
{
    $CI = &get_instance();
    $CI->db->select('email_config');
    $CI->db->from('users');
    $CI->db->where(['id' => $admin_id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['email_config']);

    if (!empty($config->email)) {
        return  $config->email;
    } else {
        return 'admin@gmail.com';
    }
}

function get_admin_company_title($id)
{
    $CI = &get_instance();
    $CI->db->select('company');
    $CI->db->from('users');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (!empty($config[0]['company'])) {
        return $config[0]['company'];
    } else {
        $title = get_compnay_title();
        if (!$title) {
            return 'Taskhub';
        } else {
            return $title;
        }
    }
}

function get_admin_company_logo($id)
{
    $CI = &get_instance();
    $CI->db->select('logo');
    $CI->db->from('users');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (isset($config[0]['logo']) && !empty($config[0]['logo'])) {
        return $config[0]['logo'];
    } else {
        $logo = get_compnay_logo();
        if (!$logo) {
            return 'logo.png';
        } else {
            return $logo;
        }
    }
}
function get_admin_company_half_logo($id)
{
    $CI = &get_instance();
    $CI->db->select('half_logo');
    $CI->db->from('users');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (isset($config[0]['half_logo']) && !empty($config[0]['half_logo'])) {
        return $config[0]['half_logo'];
    } else {
        $half_logo = get_half_logo();
        if (!$half_logo) {
            return 'logo-half.png';
        } else {
            return $half_logo;
        }
    }
}
function get_admin_company_favicon($id)
{
    $CI = &get_instance();
    $CI->db->select('favicon');
    $CI->db->from('users');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $config = $query->result_array();
    if (isset($config[0]['favicon']) && !empty($config[0]['favicon'])) {
        return $config[0]['favicon'];
    } else {
        $favicon = get_favicon();
        if (!$favicon) {
            return 'logo-half.png';
        } else {
            return $favicon;
        }
    }
}
function get_currency_symbol()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->currency_symbol)) {
        return $config->currency_symbol;
    } elseif (!empty($config->currency_shortcode)) {
        return $config->currency_shortcode;
    } else {
        return '$';
    }
}
/*For super admin (default function)*/
function get_admin_email()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->email)) {
        return  $config->email;
    }
}
function get_smtp_host()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->smtp_host)) {
        return  $config->smtp_host;
    }
}
function get_smtp_pass()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->password)) {
        return  $config->password;
    }
}
function get_smtp_crypto()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->smtp_encryption)) {
        return  $config->smtp_encryption;
    }
}
function get_smtp_port()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'email']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->smtp_port)) {
        return  $config->smtp_port;
    }
}
function get_mail_type()

{

    $CI = &get_instance();

    $CI->db->from('settings');

    $CI->db->where(['type' => 'email']);

    $query = $CI->db->get();

    $config = $query->result_array();

    $config = json_decode($config[0]['data']);



    if (!empty($config->mail_content_type)) {

        return  $config->mail_content_type;
    }
}
function get_favicon()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->favicon)) {
        return  $config->favicon;
    }
}
function get_half_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->half_logo)) {
        return  $config->half_logo;
    }
}

function get_full_logo()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->full_logo)) {
        return  $config->full_logo;
    }
}

function get_base_url()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);

    if (!empty($config->app_url)) {
        return $config->app_url;
    } else {
        return false;
    }
}

function get_count($field, $table, $where = '')
{
    if (!empty($where))
        $where = "where " . $where;

    $CI = &get_instance();
    $query = $CI->db->query("SELECT COUNT(" . $field . ") as total FROM " . $table . " " . $where . " ");
    $res = $query->result_array();
    if (!empty($res)) {
        return $res[0]['total'];
    }
}

function get_chat_count()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $workspace_id = $CI->session->userdata('workspace_id');
    $query = $CI->db->query("SELECT COUNT(id) as total FROM messages WHERE workspace_id=$workspace_id AND to_id=$user_id AND is_read=1 AND type='person'");
    $res = $query->result_array();

    $query1 = $CI->db->query("SELECT COUNT(id) as total FROM chat_group_members WHERE workspace_id=$workspace_id AND user_id=$user_id AND is_read=1");
    $res1 = $query1->result_array();


    return $res[0]['total'] + $res1[0]['total'];
}

function get_chat_theme()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $CI->db->from('users');
    $CI->db->where(['id' => $user_id]);
    $query = $CI->db->get();
    $config = $query->result_array();

    if (!empty($config[0]['chat_theme'])) {
        return $config[0]['chat_theme'];
    } else {
        return false;
    }
}

function get_user_name()
{
    $CI = &get_instance();
    $user_id = $CI->session->userdata('user_id');
    $CI->db->from('users');
    $CI->db->where(['id' => $user_id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['first_name']) && !empty($data[0]['last_name'])) {
        return $data[0]['first_name'] . ' ' . $data[0]['last_name'];
    } else {
        return false;
    }
}

function get_project_title($id)
{
    $CI = &get_instance();
    $CI->db->from('projects');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_project_id_by_file_id($file_id)
{
    $CI = &get_instance();
    $CI->db->from('project_media');
    $CI->db->where(['id' => $file_id]);
    $CI->db->where(['type' => 'project']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['type_id'])) {
        return $data[0]['type_id'];
    } else {
        return false;
    }
}

function get_file_name($id)
{
    $CI = &get_instance();
    $CI->db->from('project_media');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['file_name'])) {
        return $data[0]['file_name'];
    } else {
        return false;
    }
}

function get_milestone_title($id)
{
    $CI = &get_instance();
    $CI->db->from('milestones');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_project_id_by_milestone_id($id)
{
    $CI = &get_instance();
    $CI->db->from('milestones');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['project_id'])) {
        return $data[0]['project_id'];
    } else {
        return false;
    }
}

function get_project_id_by_task_id($task_id)
{
    $CI = &get_instance();
    $CI->db->from('tasks');
    $CI->db->where(['id' => $task_id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['project_id'])) {
        return $data[0]['project_id'];
    } else {
        return false;
    }
}

function get_task_title($id)
{
    $CI = &get_instance();
    $CI->db->from('tasks');
    $CI->db->where(['id' => $id]);
    $query = $CI->db->get();
    $data = $query->result_array();
    if (!empty($data[0]['title'])) {
        return $data[0]['title'];
    } else {
        return false;
    }
}

function get_contact_us($type)
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where('type', 'contact_us');
    $query = $CI->db->get();
    $data = $query->result_array();
    if (isset($data[0]['data']) && !empty($data[0]['data'])) {
        $data = json_decode($data[0]['data']);
        $data->$type = str_replace(array('\r\n', '\n\r', '\n', '\r'), '<br>', $data->$type);
        return $data->$type;
    } else {
        return false;
    }
}
function get_tenures($ids)
{
    $CI = &get_instance();
    $query = $CI->db->query('select * from packages_tenures where id IN (' . $ids . ')');
    $data = $query->result_array();
    if (!empty($data)) {
        return $data;
    } else {
        return false;
    }
}
function check_module($user_id, $module)
{
    $CI = &get_instance();
    $query = $CI->db->query("select modules from users_packages where user_id=" . $user_id . " and CURDATE() between from_date and to_date");
    $data = $query->result_array();
    if (!empty($data)) {
        $data = json_decode($data[0]['modules'], 1);
        if (isset($data[$module]) && $data[$module] == 1) {
            return true;
        }
    } else {
        return false;
    }
}
function get_storage_limit($id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select storage_unit,storage_allowed from users_packages where id=" . $id);
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_max_workspaces_by_user_package_id($user_package_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select allowed_workspaces from users_packages where id=" . $user_package_id);
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_max_employees_by_user_package_id($user_package_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select allowed_employees from users_packages where id=" . $user_package_id);
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_user_package_id_by_user_id($user_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select id from users_packages where user_id=" . $user_id . " and CURDATE() between from_date and to_date");
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_package_id_by_user_id($user_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select package_id from users_packages where user_id=" . $user_id . " and CURDATE() between from_date and to_date");
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_admin_id_by_workspace_id($workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select created_by from workspace where id=" . $workspace_id);
    $data = $query->result_array();
    if (!empty($data[0]['created_by'])) {
        return $data[0]['created_by'];
    } else {
        return false;
    }
}
function get_workspace_admins($workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select admin_id from workspace where id=" . $workspace_id);
    $data = $query->result_array();
    if (!empty($data[0]['admin_id'])) {
        return $data[0]['admin_id'];
    } else {
        return false;
    }
}
function update_storage($user_package_id, $size)
{
    $CI = &get_instance();
    $query = $CI->db->query("update users_packages set storage_used = storage_used + " . $size . " where id=" . $user_package_id);
}
function get_storage_used($user_package_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select storage_used from users_packages where id=" . $user_package_id);
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_total_workspaces($admin_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select count(id) as total from workspace where admin_id=" . $admin_id);
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function get_total_employees($workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select count(ug.id) as total from users_groups ug join users u on ug.user_id=u.id where ug.group_id != 3 && ug.group_id != 4 && ug.group_id != 1 AND u.workspace_id IN ($workspace_id)");
    $data = $query->result_array();
    if (!empty($data)) {
        return $data[0];
    } else {
        return false;
    }
}
function reset_default_package()
{
    $CI = &get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type' => 'general']);
    $query = $CI->db->get();
    $config = $query->result_array();
    $config = json_decode($config[0]['data']);
    $config->default_package = "";
    $config->default_tenure = "";
    $data = json_encode($config);
    $query = $CI->db->query("update settings set data = '" . $data . "' where type = 'general'");
}
function get_settings_by_admin_id($admin_id, $type, $is_plain = 0)
{
    $CI = &get_instance();
    if ($type == 'general') {
        $query = $CI->db->query("select company,address,city,state,country,zip_code,phone from users where id=" . $admin_id);
        $company_address = $query->result_array();
        if (isset($company_address[0]) && !empty($company_address[0])) {
            $company_address = $company_address[0];
            $data['company_contact'] = $company_address['phone'];
            if (array_key_exists("phone", $company_address)) {
                unset($company_address['phone']);
            }
            foreach ($company_address as $key => $value) {
                if (is_null($value) || $value == '')
                    unset($company_address[$key]);
            }
            $company_address = implode('-', $company_address);
            $company_address = str_replace('-', ' - ', $company_address);
            $data['company_address'] = $company_address;
            return $data;
        } else {
            return false;
        }
    } elseif ($type == 'email') {
        // echo "test";
        $query = $CI->db->query("select email_config from users where id=" . $admin_id);
        $email_config = $query->result_array();
        if (isset($email_config[0]['email_config']) && !empty($email_config[0]['email_config'])) {
            $email_config = json_decode($email_config[0]['email_config']);
            if ($is_plain == 1) {
                return $email_config;
            }
            if ($email_config->smtp_encryption == 'off') {
                $smtp_encryption = $email_config->smtp_host;
            } else {
                $smtp_encryption = $email_config->smtp_encryption . '://' . $email_config->smtp_host;
            }
            $data = array(
                'mailtype' => $email_config->mail_content_type,
                'protocol' => "smtp",
                'smtp_host' => $smtp_encryption,
                'smtp_port' => $email_config->smtp_port,
                'smtp_user' => $email_config->email,
                'smtp_pass' => $email_config->password,
                'charset' => "utf-8",
            );
            return $data;
        }
    }
}
function escape_array($array)
{
    $t = &get_instance();
    $posts = array();
    if (!empty($array)) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $posts[$key] = $t->db->escape_str($value);
            }
        } else {
            return $t->db->escape_str($array);
        }
    }
    return $posts;
}
function get_current_version()
{
    $t = &get_instance();
    $version = $t->db->select('max(version) as version')->get('updates')->result_array();
    return $version[0]['version'];
}
function is_workspace_admin($user_id, $workspace_id)
{
    $CI = &get_instance();
    $query = $CI->db->query("select id from workspace where id=" . $workspace_id . " and FIND_IN_SET(" . $user_id . ",admin_id)");
    $count = $query->num_rows();
    if ($count == 1) {
        return true;
    } else {
        return false;
    }
}

function verify_payment_transaction($txn_id, $payment_method)
{
    $CI = &get_instance();
    $supported_methods = array('razorpay', 'stripe', 'paypal');

    if (empty(trim($payment_method)) || !in_array($payment_method, $supported_methods)) {
        $response['error'] = true;
        $response['message'] = "Invalid payment method supplied";
        return $response;
    }
    switch ($payment_method) {
        case 'razorpay':
            $CI->load->library("razorpay");
            $payment = $CI->razorpay->fetch_payments($txn_id);
            if (!empty($payment) && isset($payment['status'])) {
                if ($payment['status'] == 'authorized') {

                    /* if the payment is authorized try to capture it using the API */
                    $capture_response = $CI->razorpay->capture_payment($payment['amount'], $txn_id, $payment['currency']);
                    if ($capture_response['status'] == 'captured') {
                        $response['error'] = false;
                        $response['message'] = "Payment captured successfully";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        return $response;
                    } else if ($capture_response['status'] == 'refunded') {
                        $response['error'] = true;
                        $response['message'] = "Payment is refunded.";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        return $response;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Payment could not be captured.";
                        $response['amount'] = (isset($capture_response['amount'])) ? $capture_response['amount'] / 100 : 0;
                        $response['data'] = $capture_response;
                        return $response;
                    }
                } else if ($payment['status'] == 'captured') {
                    $response['error'] = false;
                    $response['message'] = "Payment captured successfully";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['data'] = $payment;
                    return $response;
                } else if ($payment['status'] == 'created') {
                    $response['error'] = true;
                    $response['message'] = "Payment is just created and yet not authorized / captured!";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['data'] = $payment;
                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . ucwords($payment['status']) . "! ";
                    $response['amount'] = (isset($payment['amount'])) ? $payment['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;
    }
}
function output_escaping($array)
{
    $t = &get_instance();

    if (!empty($array)) {
        if (is_array($array)) {
            $data = array();
            foreach ($array as $key => $value) {
                $data[$key] = stripcslashes($value);
            }
            return $data;
        } else if (is_object($array)) {
            $data = new stdClass();
            foreach ($array as $key => $value) {
                $data->$key = stripcslashes($value);
            }
            return $data;
        } else {
            return stripcslashes($array);
        }
    }
}

function relativeTime($time)
{
    if (!ctype_digit($time))
        $time = strtotime($time);
    $d[0] = array(1, "second");
    $d[1] = array(60, "minute");
    $d[2] = array(3600, "hour");
    $d[3] = array(86400, "day");
    $d[4] = array(604800, "week");
    $d[5] = array(2592000, "month");
    $d[6] = array(31104000, "year");

    $w = array();

    $return = "";
    $now = time();
    $diff = ($now - $time);
    $secondsLeft = $diff;

    for ($i = 6; $i > -1; $i--) {
        $w[$i] = intval($secondsLeft / $d[$i][0]);
        $secondsLeft -= ($w[$i] * $d[$i][0]);
        if ($w[$i] != 0) {
            $return .= abs($w[$i]) . " " . $d[$i][1] . (($w[$i] > 1) ? 's' : '') . " ";
        }
    }

    $return .= ($diff > 0) ? "ago" : "left";
    return $return;
}
function get_image_url($path, $image_type = '', $image_size = '', $file_type = 'image')
{
    $path = explode('/', $path);
    $subdirectory = '';
    for ($i = 0; $i < count($path) - 1; $i++) {
        $subdirectory .= $path[$i] . '/';
    }
    $image_name = end($path);

    $file_main_dir = FCPATH . $subdirectory;
    $image_main_dir = base_url() . $subdirectory;
    if ($file_type == 'image') {
        $types = ['thumb', 'cropped'];
        $sizes = ['md', 'sm'];
        if (in_array(trim(strtolower($image_type)), $types) &&  in_array(trim(strtolower($image_size)), $sizes)) {
            $filepath = $file_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
            $imagepath = $image_main_dir . $image_type . '-' . $image_size . '/' . $image_name;
            if (file_exists($filepath)) {
                return  $imagepath;
            } else if (file_exists($file_main_dir . $image_name)) {
                return  $image_main_dir . $image_name;
            } else {
                return  base_url() . NO_IMAGE;
            }
        } else {
            if (file_exists($file_main_dir . $image_name)) {
                return  $image_main_dir . $image_name;
            } else {
                return  base_url() . NO_IMAGE;
            }
        }
    } else {
        $file = new SplFileInfo($file_main_dir . $image_name);
        $ext  = $file->getExtension();

        $media_data =  find_media_type($ext);
        $image_placeholder = $media_data[1];
        $filepath = FCPATH .  $image_placeholder;
        $extensionpath = base_url() .  $image_placeholder;
        if (file_exists($filepath)) {
            return  $extensionpath;
        } else {
            return  base_url() . NO_IMAGE;
        }
    }
}

function find_media_type($extenstion)
{
    $t = &get_instance();
    $t->config->load('eshop');
    $type = $t->config->item('type');
    foreach ($type as $main_type => $extenstions) {
        foreach ($extenstions['types'] as $k => $v) {
            if ($v === strtolower($extenstion)) {
                return array($main_type, $extenstions['icon']);
            }
        }
    }
    return false;
}
function fetch_details($where = NULL, $table, $fields = '*', $limit = '', $offset = '', $sort = '', $order = '', $where_in_key = '', $where_in_value = '')
{
    $t = &get_instance();
    $t->db->select($fields);
    if (!empty($where)) {
        $t->db->where($where);
    }

    if (!empty($where_in_key) && !empty($where_in_value)) {
        $t->db->where_in($where_in_key, $where_in_value);
    }

    if (!empty($limit)) {
        $t->db->limit($limit);
    }

    if (!empty($offset)) {
        $t->db->offset($offset);
    }

    if (!empty($order) && !empty($sort)) {
        $t->db->order_by($sort, $order);
    }

    $res = $t->db->get($table)->result_array();
    // print_r($t->db->last_query());
    return $res;
}
function send_notification($fcmMsg, $registrationIDs_chunks)
{
    $fcmFields = [];
    foreach ($registrationIDs_chunks as $registrationIDs) {
        $fcmFields = array(
            'registration_ids' => $registrationIDs,  // expects an array of ids
            'priority' => 'high',
            'notification' => $fcmMsg,
            'data' => $fcmMsg,
        );
        $fcm_key = get_system_settings('web_fcm_settings');

        $fcm_key = !empty($fcm_key) ? json_decode($fcm_key[0]['data']) : '';

        $fcm_key = !empty($fcm_key->fcm_server_key) ? $fcm_key->fcm_server_key : '';
        $headers = array(
            $headers[] = "Authorization: key = " . $fcm_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
    }
    // print_r($fcmFields);
    return $fcmFields;
}
