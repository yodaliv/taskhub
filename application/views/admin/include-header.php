<?php $current_version = get_current_version(); ?>
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li>
                <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
            </li>
            <?php if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) { ?>
                <li class="ml-2">
                    <span class="badge badge-danger">Demo mode</span>
                </li>
            <?php } ?>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <?php
        //if (!empty($workspace)) {
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');

        ?>

        <li class="dropdown">
            <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#"><i class="fas fa-language"></i>
                <span><?= ucfirst($user->lang); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-width">
                <?php
                $languages =  get_languages();
                if (!empty($languages)) {
                    foreach ($languages as $lang) { ?>
                        <a href='<?= base_url("languageswitch/switchlang/" . $lang['language']); ?>' class="dropdown-item has-icon"><?= (($lang['language'] == $user->lang) ? '<i class="fas fa-check"></i>' : ''); ?> <?= ucfirst($lang['language']); ?> </a>
                    <?php }
                }
                if (is_super()) { ?>
                    <div class="dropdown-divider"></div>
                    <a href='<?= base_url("super-admin/languages/change/" . $user->lang); ?>' class="dropdown-item has-icon"> <?= !empty($this->lang->line('label_create_and_customize')) ? $this->lang->line('label_create_and_customize') : 'Create & Customize'; ?> </a>
                <?php } ?>
            </div>

        </li>
        <?php //if (check_module($user_id, 'mail') == 1) { 
        if (is_admin()) {
        ?>
            <li>
                <a href="<?= base_url($role . '/send-mail'); ?>" title="Send Email" class="nav-link nav-link-lg" aria-expanded="true">
                    <i class="fa fa-paper-plane"></i>
                </a>
            </li>
        <?php //}
        } ?>
        <?php //if (check_module($user_id, 'announcements') == 1) { 
        ?>
        <li>
            <a href="<?= base_url($role . '/announcements'); ?>" title="Announcements" class="nav-link nav-link-lg" aria-expanded="true">
                <i class="fa fa-bullhorn"></i>
            </a>
        </li>
        <?php
        //} 
        ?>
        <?php $beep = !empty($notifications) ? 'beep' : ''; ?>
        <?php //if (check_module($user_id, 'notifications') == 1) { 
        ?>
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg <?= $beep; ?>"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">
                    <?= !empty($this->lang->line('label_notifications')) ? $this->lang->line('label_notifications') : 'Notifications'; ?>
                    <?php if (!empty($notifications)) { ?>
                        <div class="float-right">
                            <a href="#" class="mark-all-as-read-alert" data-user-id=<?= $this->session->userdata('user_id'); ?>><?= !empty($this->lang->line('label_mark_all_as_read')) ? $this->lang->line('label_mark_all_as_read') : 'Mark all as read'; ?></a>
                        </div>
                    <?php } ?>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <?php if (!empty($notifications)) {
                        foreach ($notifications as $notification) {
                            $notification_url = '#';
                            switch ($notification['type']) {
                                case 'event':
                                    $notification_url = base_url($role . '/' . 'calendar/' . $notification['type_id']);
                                    break;
                                case 'announcement':
                                    $notification_url = base_url($role . '/announcements/details/' . $notification['type_id']);
                                    break;
                                case 'project':
                                    $notification_url = base_url($role . '/projects/details/' . $notification['type_id']);
                                    break;
                                case 'task':
                                    $notification_url = base_url($role . '/projects/tasks/' . $notification['type_id']);
                                    break;
                                default:
                                    $notification_url = '#';
                                    break;
                            } ?>
                            <a href="<?= $notification_url; ?> " class="dropdown-item notification">
                                <div class="dropdown-item-icon bg-info text-white">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    <?= $notification['title']; ?>
                                    <div class="time"><?= date("d-M-Y H:i:s", strtotime($notification['date_created'])); ?></div>
                                </div>
                            </a>
                        <?php }
                    } else { ?>
                        <div class="dropdown-footer text-center">
                            <p>
                                <?= !empty($this->lang->line('label_no_unread_notifications_found')) ? $this->lang->line('label_no_unread_notifications_found') : 'No Unread Notifications Found!!'; ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="<?= base_url($role . '/notifications') ?>"><?= !empty($this->lang->line('label_view_all')) ? $this->lang->line('label_view_all') : 'View All'; ?> <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <?php //}
        //} 
        ?>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <?php if (isset($user->profile) && !empty($user->profile)) { ?>
                    <img alt="image" src="<?= base_url('assets/backend/profiles/' . $user->profile); ?>" class="rounded-circle mr-1">
                <?php } else { ?>
                    <figure class="avatar mr-1 avatar-sm" data-initial="<?= mb_substr($user->first_name, 0, 1) . '' . mb_substr($user->last_name, 0, 1); ?>"></figure>
                <?php } ?>
                <div class="d-sm-none d-lg-inline-block"><?= !empty($this->lang->line('label_hi')) ? $this->lang->line('label_hi') : 'Hi'; ?>, <?= $user->first_name ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-width">
                <!--workspace code goes here-->
                <a href="<?= base_url($this->session->userdata('role') . '/profile'); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i>
                    <?= !empty($this->lang->line('label_profile')) ? $this->lang->line('label_profile') : 'Profile'; ?>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <?= !empty($this->lang->line('label_logout')) ? $this->lang->line('label_logout') : 'Logout'; ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url($role . '/home'); ?>">
                <img alt="<?= get_admin_company_title($this->data['admin_id']) ?>" src="<?= base_url('assets/backend/icons/' . get_admin_company_logo($this->data['admin_id'])); ?>" width="200px">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url($role . '/home'); ?>">
                <img alt="<?= get_admin_company_title($this->data['admin_id']) ?>" src="<?= base_url('assets/backend/icons/' . get_admin_company_half_logo($this->data['admin_id'])); ?>" width="40px">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li data-toggle="dropdown" class="p-2">
                <a class="workspace-btn nav-link dropdown-toggle" href="#"><i class="fas fa-check"></i> <span>
                        <?php
                        if (!empty($workspace)) {
                            $workspace_id = $this->session->userdata('workspace_id');
                            foreach ($workspace as $row) {
                                if ($row->id == $workspace_id) {
                                    echo $row->title;
                                }
                            }
                        } else {
                            echo 'No Workspace Found.';
                        } ?>
                    </span>
                </a>
            </li>
            <div class="dropdown-menu">
                <?php
                if (!empty($workspace)) {
                    $workspace_id = $this->session->userdata('workspace_id');
                    foreach ($workspace as $row) { ?>
                        <a href="<?= base_url('admin/workspace/change/' . $row->id); ?>" class="dropdown-item has-icon">
                            <?php if ($row->id == $workspace_id) { ?>
                                <i class="fas fa-check"></i>
                            <?php } ?>
                            <?= $row->title ?>
                            <?php if ($row->created_by == $user->id) { ?>
                                <span class="badge badge-info projects-badge">
                                    <?= !empty($this->lang->line('label_owner')) ? $this->lang->line('label_owner') : 'Owner'; ?>
                                </span>
                            <?php } ?>
                        </a>
                <?php }
                } else {
                    echo '<a href="#" class="dropdown-item has-icon">No Workspace Found.</a>';
                } ?>
                <div class="dropdown-divider"></div>
                <?php if (is_admin()) { ?>
                    <a href="#" id="modal-add-workspace" class="dropdown-item has-icon">
                        <i class="fas fa-plus"></i>
                        <?= !empty($this->lang->line('label_create_new_workspace')) ? $this->lang->line('label_create_new_workspace') : 'Create New Workspace'; ?>
                    </a>
                    <?php if (!empty($workspace)) { ?>
                        <a href="#" data-id="<?= $workspace_id ?>" class="dropdown-item has-icon modal-edit-workspace-ajax">
                            <i class="fas fa-edit"></i>
                            <?= !empty($this->lang->line('label_edit_workspace')) ? $this->lang->line('label_edit_workspace') : 'Edit Workspace'; ?>
                        </a>
                        <a href="<?= base_url($role . '/workspace/manage-workspaces') ?>" class="dropdown-item has-icon">
                            <i class="fas fa-chart-bar"></i>
                            <?= !empty($this->lang->line('label_manage_workspaces')) ? $this->lang->line('label_manage_workspaces') : 'Manage Workspaces'; ?>
                        </a>
                <?php }
                } ?>
                <?php if (!empty($this->session->has_userdata('workspace_id'))) { ?>
                    <a href="<?= base_url('admin/users/remove-user-from-workspace/' . $user->id); ?>" class="dropdown-item has-icon">
                        <i class="fas fa-times"></i>
                        <?= !empty($this->lang->line('label_remove_me_from_workspace')) ? $this->lang->line('label_remove_me_from_workspace') : 'Remove Me From Workspace'; ?>
                    </a>
                <?php } ?>
            </div>
            <li <?= (current_url() == base_url($role . '/home')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/home'); ?>"><i class="fas fa-fire text-danger"></i> <span> <?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?> </span></a></li>
            <?php //if (!empty($workspace)) { 
            ?>

            <?php //if (check_module($user_id, 'projects') == 1) {
            ?>
            <li <?= (current_url() == base_url($role . '/projects') || $this->uri->segment(2) == 'projects') ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/projects'); ?>"><i class="fas fa-briefcase text-info"></i> <span> <?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?> </span></a></li>
            <?php //}
            ?>
            <?php //if (check_module($user_id, 'tasks') == 1) {
            ?>
            <li <?= (current_url() == base_url($role . '/tasks')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/tasks'); ?>"><i class="fas fa-newspaper text-warning"></i> <span> <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?> </span></a></li>
            <?php //}
            ?>

            <?php //if (check_module($user_id, 'calendar') == 1) { 
            ?>
            <li <?= (current_url() == base_url($role . '/calendar') || $this->uri->segment(2) == 'calendar') ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/calendar'); ?>"><i class="fas fa-calendar text-danger"></i> <span>
                        <?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?>
                    </span></a>
            </li>
            <li <?= (current_url() == base_url($role . '/meetings') || $this->uri->segment(2) == 'meetings') ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/meetings'); ?>"><i class="fas fa-handshake text-success"></i> <span>
                        <?= !empty($this->lang->line('label_meetings')) ? $this->lang->line('label_meetings') : 'Meetings'; ?>
                    </span></a>
            </li>
            <li class="dropdown<?= (current_url() == base_url($role . '/tickets/ticket-types') || current_url() == base_url($role . '/tickets')) ? ' active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-ticket-alt text-info"></i> <span><?= !empty($this->lang->line('label_support')) ? $this->lang->line('label_support') : 'Support'; ?></span></a>
                <ul class="dropdown-menu">
                    <?php if (!is_member() && !is_client()) { ?>
                        <li <?= (current_url() == base_url($role . '/tickets/ticket-types')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/tickets/ticket-types'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_ticket_types')) ? $this->lang->line('label_ticket_types') : 'Ticket types'; ?>
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                    <li <?= (current_url() == base_url($role . '/tickets')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url($role . '/tickets'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_tickets')) ? $this->lang->line('label_tickets') : 'Tickets'; ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php if (!is_client()) {
            ?>
                <?php //} 
                ?>
                <?php //if (check_module($user_id, 'chat') == 1) {
                ?>
                <li <?= (current_url() == base_url($role . '/chat')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/chat'); ?>"><i class="fas fa-comments text-success"></i>
                        <?php if (!empty($workspace)) {
                            $unread_msg = get_chat_count();
                            if ($unread_msg > 0 && current_url() != base_url($role . '/chat')) { ?>
                                <span class="font-weight-bold" title="<?= $unread_msg ?> unread message(s) "><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?> <i class="fas fa-circle text-danger fa-xs"></i>
                                </span>
                            <?php } else { ?>
                                <span><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?></span><?php }  ?>

                        <?php } else { ?>
                            <span><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?></span>
                        <?php } ?>
                    </a>
                </li>
            <?php //}
            }
            //}
            ?>
            <?php $user_id = $this->session->userdata('user_id');
            $workspace_id = $this->session->userdata('workspace_id');
            if (is_admin() || is_workspace_admin($user_id, $workspace_id)) {

            ?>
                <li <?= (current_url() == base_url($role . '/billing') || current_url() == base_url($role . '/billing/packages') || current_url() == base_url($role . '/checkout')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/billing'); ?>"><i class="fas fa-clipboard-list text-danger"></i> <span>
                            <?= !empty($this->lang->line('label_billing')) ? $this->lang->line('label_billing') : 'Billing'; ?>
                        </span></a>
                </li>
                <?php //if (!empty($workspace)) { 
                ?>
                <?php //if (!is_admin()) { 
                ?>
                <?php //if (check_module($user_id, 'finance') == 1) {
                ?>
                <li class="dropdown <?= (current_url() == base_url($role . '/expenses') || current_url() == base_url($role . '/expenses/expense-types') || current_url() == base_url($role . '/estimates') || current_url() == base_url($role . '/estimates/create-estimate') || current_url() == base_url($role . '/taxes') || current_url() == base_url($role . '/items') || current_url() == base_url($role . '/units') || current_url() == base_url($role . '/invoices') || current_url() == base_url($role . '/payments') || current_url() == base_url($role . '/payments/payment-modes') || current_url() == base_url($role . '/invoices/create-invoice') || $this->uri->segment(2) == $role . '/view-estimate' || $this->uri->segment(2) == $role . '/invoice' || $this->uri->segment(2) == $role . '/edit-estimate') || $this->uri->segment(2) == $role . '/view-invoice' || $this->uri->segment(2) == $role . '/edit-invoice' || $this->uri->segment(2) == $role . '/estimate' ? ' active' : ''; ?>">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-money-bill-alt text-info"></i> <span><?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?></span></a>
                    <ul class="dropdown-menu">
                        <li <?= (current_url() == base_url($role . '/expenses') || current_url() == base_url($role . '/expenses/expense-types')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/expenses'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_expenses')) ? $this->lang->line('label_expenses') : 'Expenses'; ?>
                                </span>
                            </a>
                        </li>

                        <li <?= (current_url() == base_url($role . '/estimates') || current_url() == base_url($role . '/estimates/create-estimate') || $this->uri->segment(2) == $role . '/view-estimate' || $this->uri->segment(2) == $role . '/edit-estimate' || $this->uri->segment(2) == $role . '/estimate') ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/estimates'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_estimates')) ? $this->lang->line('label_estimates') : 'Estimates'; ?>
                                </span>
                            </a>
                        </li>

                        <li <?= (current_url() == base_url($role . '/invoices') || $this->uri->segment(2) == $role . '/invoice' || current_url() == base_url($role . '/invoices/create-invoice') || $this->uri->segment(2) == $role . '/view-invoice' || $this->uri->segment(2) == $role . '/edit-invoice') ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/invoices'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_invoices')) ? $this->lang->line('label_invoices') : 'Invoices'; ?>
                                </span>
                            </a>
                        </li>
                        <li <?= (current_url() == base_url($role . '/items')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/items'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_items')) ? $this->lang->line('label_items') : 'Items'; ?>
                                </span>
                            </a>
                        </li>
                        <li <?= (current_url() == base_url($role . '/payments') || current_url() == base_url($role . '/payments/payment-modes')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/payments'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_payments')) ? $this->lang->line('label_payments') : 'Payments'; ?>
                                </span>
                            </a>
                        </li>
                        <li <?= (current_url() == base_url($role . '/taxes')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/taxes'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_taxes')) ? $this->lang->line('label_taxes') : 'Taxes'; ?>
                                </span>
                            </a>
                        </li>

                        <li <?= (current_url() == base_url($role . '/units')) ? 'class="active"' : ''; ?>>
                            <a class="nav-link" href="<?= base_url($role . '/units'); ?>">
                                <span>
                                    <?= !empty($this->lang->line('label_units')) ? $this->lang->line('label_units') : 'Units'; ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>


                <?php //if (check_module($user_id, 'users') == 1) {
                ?>
                <li <?= (current_url() == base_url($role . '/users')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/users'); ?>"><i class="fas fa-user text-warning"></i> <span>
                            <?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?>
                        </span></a>
                </li>
                <?php //}
                ?>
                <?php //if (check_module($user_id, 'clients') == 1) {
                ?>
                <li <?= (current_url() == base_url($role . '/clients')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/clients'); ?>"><i class="fas fa-users text-info"></i> <span>
                            <?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?>
                        </span></a>
                </li>
            <?php } //}
            ?>
            <?php if (is_admin() || is_workspace_admin($user_id, $workspace_id)) { ?>
                <?php //if (check_module($user_id, 'activity_logs') == 1) {
                ?>
                <li <?= (current_url() == base_url($role . '/activity-logs')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/activity-logs'); ?>"><i class="fas fa-chart-line text-warning"></i><span>
                            <?= !empty($this->lang->line('label_activity_logs')) ? $this->lang->line('label_activity_logs') : 'Activity Logs'; ?>
                        </span></a>
                </li>
                <?php //}
                ?>
            <?php } ?>
            <?php if (!is_client()) { //if (check_module($user_id, 'leave_requests') == 1) {
            ?>
                <li <?= (current_url() == base_url($role . '/leaves')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/leaves'); ?>"><i class="fas fa-check text-success"></i> <span>
                            <?= !empty($this->lang->line('label_leave_requests')) ? $this->lang->line('label_leave_requests') : 'Leave Requests'; ?>
                        </span></a>
                </li>
            <?php } //}
            ?>
            <?php //} 
            ?>
            <?php //if (check_module($user_id, 'notes') == 1) {
            ?>
            <li <?= (current_url() == base_url($role . '/notes')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($role . '/notes'); ?>"><i class="fas fa-clipboard-list text-danger"></i> <span>
                        <?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Notes'; ?>
                    </span></a>
            </li>
            <?php //}
            ?>
            <?php if (is_admin() || is_workspace_admin($user_id, $workspace_id)) { ?>
                <li <?= (current_url() == base_url($role . '/settings/setting-detail')) ? 'class="active mb-5"' : 'class="mb-5"'; ?>><a class="nav-link" href="<?= base_url($role . '/settings/setting-detail'); ?>"><i class="fas fa-cog"></i> <span>
                            <?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings
                        '; ?>
                        </span></a>
                </li>
            <?php } ?>


            <?php //} //}
            ?>
        </ul>
    </aside>
</div>