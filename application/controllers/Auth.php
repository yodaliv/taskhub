<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(['users_model', 'workspace_model', 'packages_model']);
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    /**
     * Redirect if needed, otherwise display the user list
     */
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            $this->load->view('login');
        } else {
            $role = $this->users_model->get_role_by_user_id($this->session->userdata('user_id'));
            if ($role == 'superadmin') {
                $role = 'super-admin';
            }
            $this->session->set_userdata('role', $role);
            if ($role == 'super-admin') {
                redirect(base_url() . 'super-admin/home', 'refresh');
            } elseif ($role == 'admin') {
                redirect(base_url() . 'admin/home', 'refresh');
            } elseif ($role == 'members') {
                redirect(base_url() . 'members/home', 'refresh');
            } elseif ($role == 'clients') {
                redirect(base_url() . 'clients/home', 'refresh');
            } else {
                $this->load->view('login');
            }
        }
    }

    public function forgot()
    {

        if ($this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect($this->session->userdata('role') . '/home', 'refresh');
        } else {
            $this->load->view('forgot-password');
        }
    }

    /**
     * Log the user in
     */
    public function login()
    {
        if ($this->input->post('remember')) {
            $this->config->set_item('sess_expiration', '0');
        } else {
            $this->config->set_item('sess_expiration', '7200');
        }
        $this->data['title'] = $this->lang->line('login_heading');

        // validate form input
        $this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
        $this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

        if ($this->form_validation->run() === TRUE) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                $role = $this->users_model->get_role_by_user_id($this->session->userdata('user_id'));
                if ($role == 'superadmin') {
                    $role = 'super-admin';
                }
                if (!$this->session->userdata('role')) {
                    $this->session->set_userdata('role', $role);
                }

                $response['error'] = false;
                $response['role'] = $role;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = $this->ion_auth->messages();
                $this->session->set_flashdata('message', 'Logged in successfully.');
                $this->session->set_flashdata('message_type', 'success');
                echo json_encode($response);
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                // use redirects instead of loading views for compatibility with MY_Controller libraries
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = $this->ion_auth->errors();
                echo json_encode($response);
            }
        } else {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];

            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
            ];

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
        }
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $this->ion_auth->logout();

        // redirect them to the login page
        redirect('home', 'refresh');
    }

    /**
     * Change password
     */
    public function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() === FALSE) {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = [
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            ];
            $this->data['new_password'] = [
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['new_password_confirm'] = [
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            ];
            $this->data['user_id'] = [
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            ];

            // render
            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    /**
     * Forgot password
     */
    public function forgot_password()
    {
        $this->data['title'] = $this->lang->line('forgot_password_heading');

        // setting validation rules by checking whether identity is username or email
        if ($this->config->item('identity', 'ion_auth') != 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }


        if ($this->form_validation->run() === FALSE) {
            $this->data['type'] = $this->config->item('identity', 'ion_auth');
            // setup the input
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
            ];

            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();

            $response['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            echo json_encode($response);

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
        } else {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgot_password", 'refresh');

                $response['error'] = true;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = $this->ion_auth->errors();
                echo json_encode($response);
                return false;
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                // if there were no errors

                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = $this->ion_auth->messages();
                echo json_encode($response);
                //we should display a confirmation page here instead of the login page
            } else {

                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = $this->ion_auth->errors();
                echo json_encode($response);
            }
        }
    }

    /**
     * Reset password - final step for forgotten password
     *
     * @param string|null $code The reset code
     */
    public function reset_password($code = NULL)
    {
        if (!$code) {
            show_404();
        }

        $this->data['title'] = $this->lang->line('reset_password_heading');

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === FALSE) {
                // display the form

                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = [
                    'name' => 'new',
                    'class' => 'form-control',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['new_password_confirm'] = [
                    'name' => 'new_confirm',
                    'class' => 'form-control',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // render
                $this->_render_page('reset-password', $this->data);
            } else {
                $identity = $user->{$this->config->item('identity', 'ion_auth')};

                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($identity);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("auth/", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('reset-password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * Activate the user
     *
     * @param int         $id   The user ID
     * @param string|bool $code The activation code
     */
    public function activate($id, $code = FALSE)
    {
        $activation = FALSE;

        if ($code !== FALSE) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * Deactivate the user
     *
     * @param int|string|null $id The user ID
     */
    public function deactivate($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            show_error('You must be an administrator to view this page.');
        }

        $id = (int)$id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    /**
     * Create a new user
     */
    public function create_user()
    {
        if (isset($_POST['user_type']) && $_POST['user_type'] == 'member') {

            if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !is_editor())) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Either you have no admin rights or session expired!";
                echo json_encode($response);
                return false;
            }
            $workspace_id = $this->session->userdata('workspace_id');
            if (is_admin()) {
                $user_id = $this->session->userdata('user_id');
            } else {
                $user_id = get_admin_id_by_workspace_id($workspace_id);
            }
            $user_package_id = get_user_package_id_by_user_id($user_id);
            $max_employees = get_max_employees_by_user_package_id($user_package_id['id']);
            $max_employees = $max_employees['allowed_employees'];
            $total_employees = get_total_employees($workspace_id);
            $total_employees = $total_employees['total'];
            if ($total_employees >= $max_employees && $_POST['group_id'] != 3 && $max_employees != 0 && !empty($max_employees)) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = 'Max employees limit reached';
                echo json_encode($response);
                return false;
            }
        } else if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response['error'] = true;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();
            $response['message'] = ERR_ALLOW_MODIFICATION;
            echo json_encode($response);
            return false;
        }
        $this->data['title'] = $this->lang->line('create_user_heading');

        if (!empty($this->input->post('email')) && empty($this->input->post('first_name')) && empty($this->input->post('last_name')) && empty($this->input->post('password')) && empty($this->input->post('password_confirm'))) {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required');
            if ($this->form_validation->run() === FALSE) {
                $response['error'] = true;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                echo json_encode($response);
                return false;
            }
            $my_data = $this->users_model->get_user_by_id($this->input->post('email'));

            if (!empty($my_data)) {

                $workspace = get_workspace($this->session->userdata('workspace_id'));
                if (isset($_POST['user_type']) && $_POST['user_type'] == 'member' || $_POST['user_type'] == 'client') {
                    $this->users_model->add_users_ids_to_workspace($this->session->userdata('workspace_id'), $my_data[0]['id']);
                    $this->workspace_model->add_workspace_ids_to_users($this->session->userdata('workspace_id'), $my_data[0]['id']);
                }
                $to_email = $my_data[0]['email'];
                $this->email->clear(TRUE);
                $admin_email_settings = get_settings_by_admin_id($this->input->post('admin_id'), 'email');
                if ($admin_email_settings) {
                    $this->email->initialize($admin_email_settings);
                    $this->email->set_newline("\r\n");
                    $this->email->to($to_email);
                    $this->email->subject('Added in new workspace');
                    $from_email = get_admin_company_email($this->input->post('admin_id'));
                    $this->email->from($from_email, get_admin_company_title($this->input->post('admin_id')));
                    $data['logo'] = base_url('assets/backend/icons/') . get_admin_company_logo($this->input->post('admin_id'));
                    $data['company_title'] = get_admin_company_title($this->input->post('admin_id'));
                    $data['heading'] = "<h1>Added in new workspace</h1>";
                    $data['message'] = "<p>Admin just added you in new workspace <b>" . $workspace . "</b>.</p>
                <p>Go To Workspace <a href='" . base_url() . "' target='_blank'>Click Here</a></p>
                <p>Thanks & regards <b>" . get_admin_company_title($this->input->post('admin_id')) . "</b></p>";
                    $this->email->message($this->load->view('create-admin-user-email-template.php', $data, true));
                    $this->email->send();
                }
                $this->session->set_flashdata('message', 'User Created successfully.');
                $this->session->set_flashdata('message_type', 'success');
                $response['error'] = false;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = 'User Created successfully.';
                echo json_encode($response);
                return false;
            }
        }

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required|strip_tags');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]', array('is_unique' => 'Email already registered please login.'));
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('address', $this->lang->line('create_user_validation_address_label'), 'trim');
        $this->form_validation->set_rules('city', $this->lang->line('create_user_validation_city_label'), 'trim');
        $this->form_validation->set_rules('state', $this->lang->line('create_user_validation_state_label'), 'trim');
        $this->form_validation->set_rules('zip_code', $this->lang->line('create_user_validation_zip_code_label'), 'trim');
        $this->form_validation->set_rules('country', $this->lang->line('create_user_validation_country_label'), 'trim');
        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');
            if (isset($_POST['user_type']) && $_POST['user_type'] == 'member' || $_POST['user_type'] == 'client') {
                $workspace_id = !empty($this->session->userdata('workspace_id')) ? $this->session->userdata('workspace_id') : '';
            } else {
                $workspace_id = '';
            }
            $additional_data = [
                'workspace_id' => $workspace_id,
                'first_name' => strip_tags($this->input->post('first_name', true)),
                'last_name' => strip_tags($this->input->post('last_name', true)),
                'address' => strip_tags($this->input->post('address', true)),
                'city' => strip_tags($this->input->post('city', true)),
                'state' => strip_tags($this->input->post('state', true)),
                'zip_code' => strip_tags($this->input->post('zip_code', true)),
                'country' => strip_tags($this->input->post('country', true)),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            ];
            $group = (!empty($this->input->post('group_id'))) ? array($this->input->post('group_id')) : '';
        }
        if ($this->form_validation->run() === TRUE && $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {
            // check to see if we are creating the user
            // redirect them back to the admin page

            if ($group[0] == 1) {
                $package_id = default_package();
                $tenure_id = default_tenure();
                if (!empty($tenure_id)) {
                    $package = $this->packages_model->get_package_by_id($package_id);
                    if (!empty($package) && isset($package[0])) {
                        $tenure = $this->packages_model->get_tenure_by_id($tenure_id);
                        if (!empty($tenure) && isset($tenure[0])) {
                            $date = date('Y-m-d');
                            $to_date = new DateTime("+" . $tenure[0]['months'] . " months");
                            $to_date = $to_date->format("Y-m-d");
                            $package_data = [
                                'user_id' => $user_id,
                                'package_id' => $package_id,
                                'title' => $package[0]['title'],
                                'tenure_id' => $tenure_id,
                                'from_date' => $date,
                                'to_date' => $to_date,
                                'amount' => $tenure[0]['rate'],
                                'months' => $tenure[0]['months'],
                                'plan_type' => $package[0]['plan_type'],
                                'modules' => $package[0]['modules'],
                                'tenure' => $tenure[0]['tenure'],
                                'storage_unit' => $package[0]['storage_unit'],
                                'storage_allowed' => $package[0]['max_storage_size'],
                                'allowed_workspaces' => $package[0]['max_workspaces'],
                                'allowed_employees' => $package[0]['max_employees']
                            ];
                            $this->packages_model->add_user_package($package_data);
                            $this->send_package_purchased_email($user_id, $package[0]['title'], $date, $to_date);
                        }
                    }
                }
                if (isset($_POST['user_type']) && $_POST['user_type'] == 'member' || $_POST['user_type'] == 'client') {
                    $workspace_data = [
                        'title' => 'Main Workspace',
                        'user_id' => $user_id,
                        'admin_id' => $user_id,
                        'leave_editors' => $user_id,
                        'created_by' => $user_id
                    ];

                    $workspace_id = $this->workspace_model->create_workspace($workspace_data);
                    if ($workspace_id != false) {
                        $this->workspace_model->add_workspace_ids_to_users($workspace_id, $user_id);
                        // $this->session->set_userdata('workspace_id', $workspace_id);
                    }
                }
            }

            if ($group[0] == 2 || $group[0] == 3) {
                $this->users_model->add_users_ids_to_workspace($this->session->userdata('workspace_id'), $user_id);
                $admin_email_settings = get_settings_by_admin_id($this->input->post('admin_id'), 'email');
                if ($admin_email_settings) {
                    $to_email = $this->input->post('email');
                    $this->email->clear(TRUE);
                    $this->email->initialize($admin_email_settings);
                    $this->email->set_newline("\r\n");
                    $this->email->to($to_email);
                    $this->email->subject('Added in' . get_admin_company_title($this->input->post('admin_id')));
                    $from_email = get_admin_company_email($this->input->post('admin_id'));
                    $this->email->from($from_email, get_admin_company_title($this->input->post('admin_id')));
                    $data['logo'] = base_url('assets/backend/icons/') . get_admin_company_logo($this->input->post('admin_id'));
                    $data['company_title'] = get_admin_company_title($this->input->post('admin_id'));
                    $data['heading'] = "<h1>Added in " . get_admin_company_title($this->input->post('admin_id')) . " company.</h1>";
                    $data['message'] = "<p>You are added in <b>" . get_admin_company_title($this->input->post('admin_id')) . "</b> company. Please login using below credential.</p>
                <p><b>Email:</b>" . $this->input->post('email') . "</p>
                <p><b>Password:</b>" . $this->input->post('password') . "</p>
                <p>Go To Dashboard <a href='" . base_url() . "' target='_blank'>Click Here</a></p>";
                    $this->email->message($this->load->view('create-admin-user-email-template.php', $data, true));
                    $this->email->send();
                }
            }
            if ($group[0] == 1) {
                $to_email = $this->input->post('email');
                $this->email->clear(TRUE);
                $this->load->library('email');
                $config = $this->config->item('email_config');
                if (isset($config['smtp_user']) && !empty($config['smtp_user'])) {
                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                    $from_email = get_admin_email();
                    $this->email->from($from_email, get_compnay_title());
                    $this->email->to($to_email);
                    $this->email->subject('Company registered successfully');
                    $data['logo'] = base_url('assets/backend/icons/') . get_compnay_logo();
                    $data['company_title'] = get_compnay_title();
                    $data['heading'] = "<h1>Company registered successfully.</h1>";
                    $data['message'] = "<p>Please login to admin account using below credential.</p>
                    <p>Email:</b>" . $this->input->post('email') . "</p>
                    <p>Password:</b>" . $this->input->post('password') . "</p>
                    <p>Go To Dashboard <a href='" . base_url() . "'>Click Here</a></p>";
                    $this->email->message($this->load->view('create-admin-user-email-template.php', $data, true));
                    $this->email->send();
                }
            }
            if ($group[0] == 1) {
                $this->session->set_flashdata('message', 'Account Created successfully.');
            } else {
                $this->session->set_flashdata('message', 'User Created successfully.');
            }

            $this->session->set_flashdata('message_type', 'success');
            $response['error'] = false;
            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();

            $response['message'] = 'Successful';
            echo json_encode($response);
        } else {

            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();

            $response['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            echo json_encode($response);
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = [
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            ];
            $this->data['last_name'] = [
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            ];
            $this->data['identity'] = [
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            ];
            $this->data['email'] = [
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            ];
            $this->data['company'] = [
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            ];
            $this->data['phone'] = [
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            ];
            $this->data['password'] = [
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            ];
            $this->data['password_confirm'] = [
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            ];

            // $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
        }
    }
    /**
     * Redirect a user checking if is admin
     */
    public function redirectUser()
    {
        if ($this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }
        redirect('/', 'refresh');
    }

    /**
     * Edit a user
     *
     * @param int|string $id
     */
    public function edit_user($id)
    {
        $response['error'] = true;
        $response['csrfName'] = $this->security->get_csrf_token_name();
        $response['csrfHash'] = $this->security->get_csrf_hash();
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response['message'] = ERR_ALLOW_MODIFICATION;
            $this->session->set_flashdata('message', ERR_ALLOW_MODIFICATION);
            $this->session->set_flashdata('message_type', 'error');
            echo json_encode($response);
            return false;
            exit();
        }

        $id = !empty($id) ? $id : $this->input->post('id');
        $this->data['title'] = $this->lang->line('edit_user_heading');
        if (!is_super()) {
            if (!$this->ion_auth->logged_in() || ((!$this->ion_auth->is_admin() && !is_editor()) && !($this->ion_auth->user()->row()->id == $id))) {
                $response['error'] = true;
                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();
                $response['message'] = "Either you have no admin rights or session expired!";
                echo json_encode($response);
                return false;
            }
        }

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //USAGE NOTE - you can do more complicated queries like this
        //$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();


        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('address', $this->lang->line('edit_user_validation_address_label'), 'trim');
        $this->form_validation->set_rules('city', $this->lang->line('edit_user_validation_city_label'), 'trim');
        $this->form_validation->set_rules('state', $this->lang->line('edit_user_validation_state_label'), 'trim');
        $this->form_validation->set_rules('zip_code', $this->lang->line('edit_user_validation_zip_code_label'), 'trim');
        $this->form_validation->set_rules('country', $this->lang->line('edit_user_validation_country_label'), 'trim');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');

        if ($this->form_validation->run() === FALSE) {
            $response['error'] = true;

            $response['csrfName'] = $this->security->get_csrf_token_name();
            $response['csrfHash'] = $this->security->get_csrf_hash();

            $response['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            echo json_encode($response);
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->session->set_flashdata('message', 'Some thing went wrong please try again!');
            $this->session->set_flashdata('message_type', 'error');
            return false;
            exit();
        }
        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            // if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
            // {
            // 	show_error($this->lang->line('error_csrf'));
            // }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = [
                    'first_name' => strip_tags($this->input->post('first_name', true)),
                    'last_name' => strip_tags($this->input->post('last_name', true)),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'zip_code' => $this->input->post('zip_code'),
                    'country' => $this->input->post('country'),
                    'chat_theme' => $this->input->post('chat_theme_preference')
                ];
                $data = escape_array($data);

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                if ($this->input->post('profile')) {
                    $img = $this->input->post('profile');
                    $data_photo = $img;
                    $img_dir = './assets/backend/profiles/';
                    list($type, $data_photo) = explode(';', $data_photo);
                    list(, $data_photo) = explode(',', $data_photo);
                    $data_photo = base64_decode($data_photo);
                    $filename = microtime(true) . '.jpg';
                    if (!is_dir($img_dir)) {
                        mkdir($img_dir, 0777, true);
                    }
                    if (file_put_contents($img_dir . $filename, $data_photo)) {
                        $data['profile'] = $filename;
                        if ($this->input->post('old_profile')) {
                            unlink($img_dir . $this->input->post('old_profile'));
                        }
                    } else {
                        $data['profile'] = $this->input->post('old_profile');
                    }

                    $source_path = $img_dir . $filename;
                    $thumb_path = $img_dir . $filename;
                    $thumb_width = 512;
                    $thumb_height = 512;
                    $config['image_library']  = 'gd2';
                    $config['source_image']   = $source_path;
                    $config['new_image']   = $thumb_path;
                    $config['create_thumb']   = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width']          = $thumb_width;
                    $config['height']         = $thumb_height;
                    $configs['thumb_marker'] = '';
                    $this->load->library('image_lib', $config);
                    if (!$this->image_lib->resize()) {
                        // $this->image_lib->display_errors();
                    }
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    $response['error'] = false;

                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();

                    $response['message'] = $this->session->set_flashdata('message', $this->ion_auth->messages());
                    $this->session->set_flashdata('message', 'Profile Updated Successfully');
                    $this->session->set_flashdata('message_type', 'success');
                    echo json_encode($response);
                } else {
                    $response['error'] = true;

                    $response['csrfName'] = $this->security->get_csrf_token_name();
                    $response['csrfHash'] = $this->security->get_csrf_hash();

                    $response['message'] = $this->session->set_flashdata('message', $this->ion_auth->errors());
                    $this->session->set_flashdata('message', 'Something went wrong');
                    $this->session->set_flashdata('message_type', 'error');
                    echo json_encode($response);
                }
            } else {

                $response['error'] = true;

                $response['csrfName'] = $this->security->get_csrf_token_name();
                $response['csrfHash'] = $this->security->get_csrf_hash();

                $response['message'] = $this->session->set_flashdata('message', 'Not updated!');
                echo json_encode($response);
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = [
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        ];
        $this->data['last_name'] = [
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        ];
        $this->data['company'] = [
            'name'  => 'company',
            'id'    => 'company',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        ];
        $this->data['phone'] = [
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password'
        ];
        $this->data['password_confirm'] = [
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password'
        ];
    }

    /**
     * Create a new group
     */
    public function create_group()
    {
        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

        if ($this->form_validation->run() === TRUE) {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth", 'refresh');
            }
        } else {
            // display the create group form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = [
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            ];
            $this->data['description'] = [
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('description'),
            ];

            $this->_render_page('auth/create_group', $this->data);
        }
    }

    /**
     * Edit a group
     *
     * @param int|string $id
     */
    public function edit_group($id)
    {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'trim|required|alpha_dash');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
                    'description' => $_POST['group_description']
                ));

                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("auth", 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['group'] = $group;

        $this->data['group_name'] = [
            'name'    => 'group_name',
            'id'      => 'group_name',
            'type'    => 'text',
            'value'   => $this->form_validation->set_value('group_name', $group->name),
        ];
        if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
            $this->data['group_name']['readonly'] = 'readonly';
        }

        $this->data['group_description'] = [
            'name'  => 'group_description',
            'id'    => 'group_description',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        ];

        $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
    }

    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return [$key => $value];
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param string     $view
     * @param array|null $data
     * @param bool       $returnhtml
     *
     * @return mixed
     */
    public function _render_page($view, $data = NULL, $returnhtml = FALSE) //I think this makes more sense
    {

        $viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $viewdata, $returnhtml);

        // This will return html on 3rd argument being true
        if ($returnhtml) {
            return $view_html;
        }
    }

    public function send_package_purchased_email($user_id, $title, $from_date, $to_date)
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
            $this->email->subject('Default plan added to your subscription successfully');
            $data['heading'] = 'Default plan added to your subscription successfully';
            $data['message'] = "<p>Hello Dear <b>" . $user_data[0]['first_name'] . " " . $user_data[0]['last_name'] . "</b>, On successfull registration default free plan <b>" . $title . "</b> added to your subscription successfully.</p>
                    <p><b>Start Date : </b>" . $from_date . " <b>End date : </b>" . $to_date . "</p>
                    <p>Thanks & regards <b>" . get_compnay_title() . "</b></p>";
            $data['logo'] = base_url('assets/backend/icons/') . get_compnay_logo();
            $data['company_title'] = get_compnay_title();

            $this->email->message($this->load->view('admin/plan-purchased-email-template.php', $data, true));
            $this->email->send();
        }
    }
}
