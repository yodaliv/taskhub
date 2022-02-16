<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>User Details &mdash; <?= get_compnay_title(); ?></title>

    <?php require_once(APPPATH . 'views/include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require_once(APPPATH . '/views/super-admin/include-header.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_user_detail')) ? $this->lang->line('label_user_detail') : 'User Detail'; ?></h1>
                    </div>

                    <div class="section-body">
                        <div class="row">


                            <div class="col-md-12">
                                <div class="card profile-widget">

                                    <div class="profile-widget-header">
                                        <?php if (isset($user_detail->profile) && !empty($user_detail->profile)) { ?>
                                            <img src="<?= base_url('assets/backend/profiles/' . $user_detail->profile); ?>" class="rounded-circle profile-widget-picture">
                                        <?php } else { ?>
                                            <div class="user-avtar rounded-circle"><?= mb_substr($user_detail->first_name, 0, 1) . '' . mb_substr($user_detail->last_name, 0, 1); ?></div>
                                        <?php } ?>
                                        <?php if (!empty($current_package)) { ?>
                                            <div class="profile-widget-items">
                                                <div class="profile-widget-item">
                                                    <div class="profile-widget-item-label"><?= !empty($this->lang->line('label_current_package')) ? $this->lang->line('label_current_package') : 'Current package'; ?></div>
                                                    <div class="profile-widget-item-value">
                                                        <?php if (!empty($current_package)) { ?>
                                                            <?= $current_package['plan_type'] == 'free' ? '<span class="badge badge-success">' . $current_package['title'] . '</span>' : '<span class="badge badge-warning">' . $current_package['title'] . '</span>' ?> <?= $current_package['package_id'] == default_package() ? '<span class="badge badge-info projects-badge">Default</span>' : '' ?>
                                                        <?php } else { ?>
                                                            <p>N/A</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="profile-widget-item">
                                                    <div class="profile-widget-item-label"><?= !empty($this->lang->line('label_from_date')) ? $this->lang->line('label_from_date') : 'From date'; ?></div>
                                                    <div class="profile-widget-item-value">
                                                        <?php if (!empty($current_package)) { ?>
                                                            <div class="profile-widget-item-value"><?= date("d-M-Y", strtotime($current_package['from_date'])); ?></div>
                                                        <?php } else { ?>
                                                            <p>N/A</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="profile-widget-item">
                                                    <div class="profile-widget-item-label"><?= !empty($this->lang->line('label_to_date')) ? $this->lang->line('label_to_date') : 'To date'; ?></div>
                                                    <div class="profile-widget-item-value">
                                                        <?php if (!empty($current_package)) { ?>
                                                            <div class="profile-widget-item-value"><?= date("d-M-Y", strtotime($current_package['to_date'])); ?></div>
                                                        <?php } else { ?>
                                                            <p>N/A</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="profile-widget-description pb-0">

                                        <div class="profile-widget-name">
                                            <?= !empty($this->lang->line('label_name')) ? $this->lang->line('label_name') : 'Name'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->first_name ?> <?= $user_detail->last_name ?></div>

                                            <?= !empty($this->lang->line('label_user_type')) ? $this->lang->line('label_user_type') : 'User Type'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5">
                                                <?php
                                                if ($this->ion_auth->is_admin($user_detail->id)) {
                                                    echo  "Admin";
                                                } elseif (is_editor($user_detail->id)) {
                                                    echo  "Admin";
                                                } elseif (is_client($user_detail->id)) {
                                                    echo  "Client";
                                                } else {
                                                    echo  "Team Member";
                                                }
                                                ?>
                                            </div>

                                            <?= !empty($this->lang->line('label_contact')) ? $this->lang->line('label_contact') : 'Contact'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->email ?></div>

                                            <?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?>:
                                            <div class="text-muted d-inline font-weight-normal">
                                                <div class="badge badge-success projects-badge"><?= ($user_detail->active == 1) ? 'Active' : 'Deactive' ?></div>
                                            </div>

                                            <?php if (is_client($user_detail->id)) { ?>
                                                <br>
                                                <?= !empty($this->lang->line('label_company')) ? $this->lang->line('label_company') : 'Company'; ?>:
                                                <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->company ?></div>

                                                <?= !empty($this->lang->line('label_phone')) ? $this->lang->line('label_phone') : 'Phone'; ?>:
                                                <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->phone ?></div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                    <div class="profile-widget-description pb-0">

                                        <div class="profile-widget-name">
                                            <?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : 'Address'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->address ?></div>

                                            <?= !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->city ?></div>

                                            <?= !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->state ?></div>

                                            <?= !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->zip_code ?></div>

                                            <?= !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country'; ?>:
                                            <div class="text-muted d-inline font-weight-normal mr-5"><?= $user_detail->country ?></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_package_summary')) ? $this->lang->line('label_package_summary') : 'Package summary'; ?></h4>
                                                </div>
                                                <div class="card-body">
                                                    <?php if (!empty($current_package)) { ?>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="pricing">
                                                                    <div class="pricing-title">
                                                                        <?= $current_package['title'] ?>
                                                                    </div>
                                                                    <div>
                                                                        <?= $current_package['package_id'] == default_package() ? '<span class="badge badge-info projects-badge">Default</span>' : '' ?>
                                                                        <?= $current_package['plan_type'] == 'free' ? '<span class="badge badge-success projects-badge">FREE</span>' : '<span class="badge badge-danger projects-badge">PAID</span>' ?>
                                                                    </div>
                                                                    <div class="pricing-padding">
                                                                        <div class="pricing-price">
                                                                            <div><?= get_currency_symbol() . ' ' . $current_package['amount'] ?></div>
                                                                            <div>For <?= $current_package['months'] ?> Month(s)</div>
                                                                        </div>
                                                                        <div class="pricing-details">
                                                                            <div class="pricing-item">
                                                                                <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                                <div class="pricing-item-label"><?= $current_package['storage_allowed'] == 0 ? '<b>Unlimited</b>' : '<b>' . $current_package['storage_allowed'] . ' ' . strtoupper($current_package['storage_unit']) . '</b>' ?> <?= !empty($this->lang->line('label_storage')) ? $this->lang->line('label_storage') : 'Storage'; ?></div>
                                                                            </div>
                                                                            <div class="pricing-item">
                                                                                <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                                <div class="pricing-item-label"><?= $current_package['allowed_employees'] == 0 ? '<b>Unlimited</b>' : '<b>' . $current_package['allowed_employees'] . '</b>' ?></b> <?= !empty($this->lang->line('label_employee')) ? $this->lang->line('label_employee') : 'Employee'; ?>(s)</div>
                                                                            </div>
                                                                            <div class="pricing-item">
                                                                                <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                                <div class="pricing-item-label"><?= $current_package['allowed_workspaces'] == 0 ? '<b>Unlimited</b>' : '<b>' . $current_package['allowed_workspaces'] . '</b>' ?></b> <?= !empty($this->lang->line('label_workspace')) ? $this->lang->line('label_workspace') : 'Workspace'; ?>(s)</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $modules = json_decode($current_package['modules'], 1);
                                                            ?>
                                                            <div class="col-md-8 of-auto">
                                                                <div class="form-group">
                                                                    <label><?= !empty($this->lang->line('label_modules')) ? $this->lang->line('label_modules') : 'Modules'; ?></label>
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <?= isset($modules['projects']) && $modules['projects'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['tasks']) && $modules['tasks'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['calendar']) && $modules['calendar'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['chat']) && $modules['chat'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?= isset($modules['finance']) && $modules['finance'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['users']) && $modules['users'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['clients']) && $modules['clients'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['activity_logs']) && $modules['activity_logs'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_activity_logs')) ? $this->lang->line('label_activity_logs') : 'Activity logs'; ?>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?= isset($modules['leave_requests']) && $modules['leave_requests'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_leave_requests')) ? $this->lang->line('label_leave_requests') : 'Leave requests'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['notes']) && $modules['notes'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Notes'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['mail']) && $modules['mail'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_mail')) ? $this->lang->line('label_mail') : 'Mail'; ?>
                                                                                    </label>
                                                                                </td>

                                                                                <td>
                                                                                    <?= isset($modules['announcements']) && $modules['announcements'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_announcements')) ? $this->lang->line('label_announcements') : 'Announcements'; ?>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <?= isset($modules['notifications']) && $modules['notifications'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_notifications')) ? $this->lang->line('label_notifications') : 'Notifications'; ?>
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?= isset($modules['sms_notifications']) && $modules['sms_notifications'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                                    <?= !empty($this->lang->line('label_sms_notifications')) ? $this->lang->line('label_sms_notifications') : 'SMS Notifications'; ?>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <?php
                                                                if (!empty($current_package) && $current_package['storage_allowed'] != 0 && !empty($current_package['storage_allowed'])) { ?>
                                                                    <div class="form-group text-center mt-5">
                                                                        <?php $unit = $current_package['storage_unit'] == 'gb' ? 'GB' : 'MB';
                                                                        $decimal = 2;
                                                                        if ($unit == 'GB') {
                                                                            $current_package['storage_used'] = $current_package['storage_used'] / 1024;
                                                                            $decimal = 3;
                                                                        }
                                                                        $free_space = $current_package['storage_allowed'] - $current_package['storage_used'];
                                                                        ?>
                                                                        <label><label><?= !empty($this->lang->line('label_storage_used')) ? $this->lang->line('label_storage_used') : 'Storage used'; ?> : <?= number_format($current_package['storage_used'], $decimal) .' ' . $unit ?> out of <?= $current_package['storage_allowed'] . ' ' . $unit ?></label><br>
                                                                        <label><?= !empty($this->lang->line('label_free_storage')) ? $this->lang->line('label_free_storage') : 'Free storage'; ?> : <?= number_format($free_space,$decimal) .' ' . $unit ?></label>
                                                                    </div>
                                                                    <canvas id="piechart" height="190"></canvas>
                                                                <?php  }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="alert alert-info text-center"><?= !empty($this->lang->line('label_no_active_plan_found')) ? $this->lang->line('label_no_active_plan_found') : 'No active plan found!'; ?></div>
                                                        <div class="text-center">
                                                            <a class="btn btn-primary" href="<?= base_url('super-admin/packages/package-list/' . $user_detail->id) ?>"><?= !empty($this->lang->line('label_add_plan')) ? $this->lang->line('label_add_plan') : 'Add plan'; ?> <i class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="row">
                                                        <div class='col-md-12'>
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h4><?= !empty($this->lang->line('label_subscription_list')) ? $this->lang->line('label_subscription_list') : 'Subscription list'; ?></h4>
                                                                </div>

                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="form-group col-md-3">
                                                                            <select id="type" name="type" class="form-control">
                                                                                <option value=""><?= !empty($this->lang->line('label_all')) ? $this->lang->line('label_all') : 'All'; ?></option>
                                                                                <option value="active"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></option>
                                                                                <option value="upcoming"><?= !empty($this->lang->line('label_upcoming')) ? $this->lang->line('label_upcoming') : 'Up coming'; ?></option>
                                                                                <option value="expired"><?= !empty($this->lang->line('label_expired')) ? $this->lang->line('label_expired') : 'Expired'; ?></option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-2">
                                                                            <i class="btn btn-primary btn-rounded no-shadow" id="filter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                                                        </div>
                                                                    </div>
                                                                    <table class='table-striped' id='subscription_list' data-toggle="table" data-url="<?= base_url('super-admin/users/get_subscription_list/' . $user_detail->id) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "subscription-list"
                    }' data-query-params="queryParams1">
                                                                        <thead>
                                                                            <tr>
                                                                                <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                                                <th data-field="package_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_package_id')) ? $this->lang->line('label_package_id') : 'Package ID'; ?></th>
                                                                                <th data-field="title" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_package')) ? $this->lang->line('label_package') : 'Package'; ?></th>
                                                                                <th data-field="plan_type" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                                                <th data-field="from_date" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start date'; ?></th>
                                                                                <th data-field="to_date" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End date'; ?></th>
                                                                                <th data-field="tenure" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_tenure')) ? $this->lang->line('label_tenure') : 'Tenure'; ?></th>
                                                                                <th data-field="months" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_months')) ? $this->lang->line('label_months') : 'Months'; ?></th>
                                                                                <th data-field="amount" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                                                <th data-field="payment_method" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_payment_method')) ? $this->lang->line('label_payment_method') : 'Payment method'; ?></th>
                                                                                <th data-field="date_created" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_purchase_date')) ? $this->lang->line('label_purchase_date') : 'Purchase date'; ?></th>
                                                                                <th data-field="action" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>


                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class='col-md-12'>
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h4><?= !empty($this->lang->line('label_transaction_list')) ? $this->lang->line('label_transaction_list') : 'Transaction list'; ?></h4>
                                                                </div>

                                                                <div class="card-body">
                                                                    <table class='table-striped' id='transaction_list' data-toggle="table" data-url="<?= base_url('super-admin/transactions/get_transaction_list/' . $user_detail->id) ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "transaction-list"
                    }' data-query-params="queryParams">
                                                                        <thead>
                                                                            <tr>
                                                                                <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                                                <th data-field="user_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                                                <th data-field="user" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_user')) ? $this->lang->line('label_user') : 'User'; ?></th>
                                                                                <th data-field="item_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_package_id')) ? $this->lang->line('label_package_id') : 'Package ID'; ?></th>
                                                                                <th data-field="package" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_package')) ? $this->lang->line('label_package') : 'Package'; ?></th>
                                                                                <th data-field="type" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                                                <th data-field="txn_id" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_transaction_id')) ? $this->lang->line('label_transaction_id') : 'Transaction ID'; ?></th>
                                                                                <th data-field="amount" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                                                <th data-field="currency_code" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_currency')) ? $this->lang->line('label_currency') : 'Currency'; ?></th>
                                                                                <th data-field="message" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?></th>
                                                                                <th data-field="status" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                                                <th data-field="date_created" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date created'; ?></th>
                                                                                <th data-field="action" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>

                                                                            </tr>
                                                                        </thead>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php require_once(APPPATH . 'views/super-admin/include-footer.php'); ?>

        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>
    <?php
    if (!empty($current_package) && $current_package['storage_allowed'] != 0 && !empty($current_package['storage_allowed'])) {
    ?>
        <script>
            var max_storage_size = <?= $current_package['storage_allowed'] ?>;
            var storageFree = <?= $current_package['storage_allowed'] - $current_package['storage_used'] ?>;
            var storageUsed = <?= number_format($current_package['storage_used'], $decimal); ?>;
            var storageUnit = <?= '"' . $unit . '"' ?>;
        </script>
    <?php } else { ?>
        <script>
            var max_storage_size = 0;
        </script>
    <?php } ?>
    <script src="<?= base_url('assets/backend/js/page/components-user-detail.js'); ?>"></script>

</body>

</html>