<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Summary &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
    <?php
    require_once(APPPATH . 'views/include-css.php');
    ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php
            require_once(APPPATH . '/views/admin/include-header.php');
            ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_summary')) ? $this->lang->line('label_summary') : 'Summary'; ?></h1>
                    </div>

                    <div class="section-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (!empty($package)) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="pricing">
                                                        <div class="pricing-title">
                                                            <?= $package['title'] ?>
                                                        </div>
                                                        <div>
                                                            <?= $package['id'] == default_package() ? '<span class="badge badge-info projects-badge">Default</span>' : '' ?>
                                                            <?= $package['plan_type'] == 'free' ? '<span class="badge badge-success projects-badge">FREE</span>' : '<span class="badge badge-danger projects-badge">PAID</span>' ?>
                                                        </div>
                                                        <div class="pricing-padding">

                                                            <div class="pricing-price">
                                                                <div><?= get_currency_symbol() . ' ' . $tenure['rate'] ?></div>
                                                                <div>For <?= $tenure['months'] ?> <?= !empty($this->lang->line('label_month')) ? $this->lang->line('label_month') : 'Month'; ?>(s)</div>
                                                            </div>
                                                            <div class="pricing-details">
                                                                <div class="pricing-item">
                                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                    <div class="pricing-item-label"><?= $package['max_storage_size'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_storage_size'] . ' ' . strtoupper($package['storage_unit']) . '</b>' ?> <?= !empty($this->lang->line('label_storage')) ? $this->lang->line('label_storage') : 'Storage'; ?></div>
                                                                </div>
                                                                <div class="pricing-item">
                                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                    <div class="pricing-item-label"><?= $package['max_employees'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_employees'] . '</b>' ?></b> <?= !empty($this->lang->line('label_employee')) ? $this->lang->line('label_employee') : 'Employee'; ?>(s)</div>
                                                                </div>
                                                                <div class="pricing-item">
                                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                                    <div class="pricing-item-label"><?= $package['max_workspaces'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_workspaces'] . '</b>' ?></b> <?= !empty($this->lang->line('label_workspace')) ? $this->lang->line('label_workspace') : 'Workspace'; ?>(s)</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $modules = json_decode($package['modules'], 1);
                                                ?>
                                                <div class="col-md-8">
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
                                                                    <td class="d-none">

                                                                        <?= isset($modules['settings']) && $modules['settings'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?>
                                                                        </label>
                                                                    </td>
                                                                    <td class="d-none">

                                                                        <?= isset($modules['languages']) && $modules['languages'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_languages')) ? $this->lang->line('label_languages') : 'Languages'; ?>
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
                                                                    <td>

                                                                        <?= isset($modules['support_system']) && $modules['support_system'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_support_system')) ? $this->lang->line('label_support_system') : 'Support System'; ?>
                                                                        </label>
                                                                    </td>

                                                                    <td>

                                                                        <?= isset($modules['meetings']) && $modules['meetings'] == 1 ? '<i class="far fa-check-circle text-success"></i>' : '<i class="far fa-times-circle text-danger"></i>' ?> <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_meetings')) ? $this->lang->line('label_meetings') : 'Meetings'; ?>
                                                                        </label>
                                                                    </td>


                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                                <?php if ($tenure['rate'] != 0) { ?>
                                                    <form id="checkout_form">
                                                        <div class="col-md-12">
                                                            <?php
                                                            $paypal_payment_method = get_system_settings('paypal_payment_method');
                                                            $paypal_payment_method = json_decode($paypal_payment_method[0]['data'], true);

                                                            ?>
                                                            <div class="section-title">Select payment method</div>
                                                            <?php
                                                            if (isset($paypal_payment_method['status']) && $paypal_payment_method['status'] == 1) {
                                                            ?>
                                                                <div class="custom-control custom-radio mt-3">
                                                                    <input id="paypal" name="payment_method" type="radio" class="custom-control-input" value="Paypal" required>
                                                                    <label class="custom-control-label" for="paypal">
                                                                        <img src="<?= base_url('assets/backend/img/paypal.png') ?>" alt="Paypal" class="w-30">
                                                                        <b>Paypal</b>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>

                                                            <?php
                                                            $razorpay_payment_method = get_system_settings('razorpay_payment_method');
                                                            $razorpay_payment_method = json_decode($razorpay_payment_method[0]['data'], true);
                                                            ?>
                                                            <?php
                                                            if (isset($razorpay_payment_method['razorpay_status']) && $razorpay_payment_method['razorpay_status'] == 1) {
                                                            ?>
                                                                <div class="custom-control custom-radio mt-3">
                                                                    <input id="razorpay" name="payment_method" type="radio" class="custom-control-input" value="Razorpay" required>
                                                                    <label class="custom-control-label" for="razorpay">
                                                                        <img src="<?= base_url('assets/backend/img/razorpay.png') ?>" alt="Razorpay" class="w-30">
                                                                        <b>Razorpay</b>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>

                                                            <?php
                                                            $stripe_payment_method = get_system_settings('stripe_payment_method');
                                                            $stripe_payment_method = json_decode($stripe_payment_method[0]['data'], true);
                                                            ?>
                                                            <?php
                                                            if (isset($stripe_payment_method['stripe_status']) && $stripe_payment_method['stripe_status'] == 1) {
                                                            ?>
                                                                <div class="custom-control custom-radio mt-3">
                                                                    <input id="stripe" name="payment_method" type="radio" class="custom-control-input" value="Stripe" required>
                                                                    <label class="custom-control-label" for="stripe">
                                                                        <img src="<?= base_url('assets/backend/img/stripe.png') ?>" alt="Stripe" class="w-30">
                                                                        <b>Stripe</b>
                                                                    </label>
                                                                </div>
                                                                <div id="stripe_div" class="mt-5 disp-none">
                                                                    <div id="stripe-card-element">
                                                                        <!--Stripe.js injects the Card Element-->
                                                                    </div>
                                                                    <p id="card-error" role="alert"></p>
                                                                    <p class="result-message hidden"></p>
                                                                </div>
                                                            <?php } ?>
                                                            <input type="hidden" name="app_name" id="app_name" value="<?= get_admin_company_title($this->data['admin_id']) ?>" />
                                                            <input type="hidden" name="username" id="username" value="<?= $user->first_name . ' ' . $user->last_name ?>" />
                                                            <input type="hidden" name="user_email" id="user_email" value="<?= $user->email ?>" />
                                                            <input type="hidden" name="user_contact" id="user_contact" value="<?= $user->phone ?>" />
                                                            <input type="hidden" name="logo" id="logo" value="<?= base_url('assets/backend/icons/' . get_admin_company_logo($this->data['admin_id'])) ?>" />
                                                            <input type="hidden" name="order_amount" id="order_amount" value="<?= $tenure['rate'] ?>" />
                                                            <input type="hidden" name="tenure_id" id="tenure_id" value="<?= $tenure['id'] ?>" />


                                                            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="" />
                                                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="" />
                                                            <input type="hidden" name="razorpay_signature" id="razorpay_signature" value="" />

                                                            <input type="hidden" name="stripe_client_secret" id="stripe_client_secret" value="" />
                                                            <input type="hidden" name="stripe_payment_id" id="stripe_payment_id" value="" />
                                                        <?php } ?>
                                                        <button class="btn btn-primary mt-5" id="submit_btn">Proceed</button>
                                                        <div id="checkout_result" class="d-none mt-2"></div>
                                                    </form>

                                                    <input type="hidden" name="stripe_key_id" id="stripe_key_id" value="<?= isset($stripe_payment_method['stripe_publishable_key']) && !empty($stripe_payment_method['stripe_publishable_key']) ? $stripe_payment_method['stripe_publishable_key'] : '' ?>" />
                                                    <input type="hidden" name="razorpay_key_id" id="razorpay_key_id" value="<?= isset($razorpay_payment_method['razorpay_key_id']) && !empty($razorpay_payment_method['razorpay_key_id']) ? $razorpay_payment_method['razorpay_key_id'] : '' ?>" />

                                                    <form action="<?= base_url('admin/payment/paypal') ?>" id="paypal_form" method="POST">
                                                        <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                        <input type="hidden" name="p_id" id="pid" value="<?= $package['id'] ?>" />
                                                        <input type="hidden" name="t_id" id="tid" value="<?= $tenure['id'] ?>" />

                                                    </form>
                                                    <form id="razorpay_form" action="<?= base_url('admin/payment/success') ?>" method="POST">
                                                        <input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                        <input type="hidden" name="payment_method" value="Razorpay" />
                                                        <input type="hidden" name="package_title" id="p_title" value="" />
                                                        <input type="hidden" name="from_date" id="f_date" value="" />
                                                        <input type="hidden" name="to_date" id="t_date" value="" />

                                                    </form>




                                                <?php } else { ?>
                                                    <div class="alert alert-info"><?= !empty($this->lang->line('label_data_not_found')) ? $this->lang->line('label_data_not_found') : 'Data not found!'; ?></div>
                                                <?php } ?>
                                            </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
        <?php
        require_once(APPPATH . 'views/admin/include-footer.php');
        ?>
    </div>
    </div>
    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?= base_url('assets/backend/js/page/components-checkout.js'); ?>"></script>
    <!-- Page Specific JS File -->
</body>

</html>