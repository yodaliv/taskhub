<html>

<head>
    <title>Plans &mdash; <?= get_compnay_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once(APPPATH . 'views/frontend-css.php'); ?>
</head>

<body>


    <!-- navigation -->
    <?php require_once(APPPATH . '/views/header.php'); ?>
    <section id="page-title" class="header_section page-title-parallax page-title-center page-title-dark include-header" data-bottom-top="background-position:0px 300px;" data-top-bottom="background-position:0px -300px;">
        <div id="particles-line"></div>

        <div class="container clearfix mt-4">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class=" pricing_text  mb-4"><?= !empty($this->lang->line('label_plans')) ? $this->lang->line('label_plans') : 'Plans'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>

    <section id="content">
        <div class="pricing-area">
            <div class="container">
                <div class="row">
                    <?php if (!empty($packages)) {
                        $j = 0;
                        foreach ($packages as $package) { ?>
                            <div class="col-lg-4 col-md-6">

                                <div class="single-pricing best-deal m-2">
                                    <?php $tenures = get_tenures($package['tenure_ids']);
                                    if (!empty($tenures)) {
                                    ?>
                                        <!-- <div class="" > -->
                                        <select id="tenure_<?= $j ?>_price" onchange="update_amount(<?= $j ?>)" class="custom-select">
                                            <?php for ($i = 0; $i < count($tenures); $i++) { ?>
                                                <option value="<?= $tenures[$i]['id'] ?>"><?= $tenures[$i]['tenure'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <!-- </div> -->
                                    <?php } ?>
                                    <div class="<?= $package['plan_type'] == 'free' ? 'label-area-green' : 'label-area' ?>">
                                        <span><?= strtoupper($package['plan_type']) ?></span>
                                    </div>

                                    <div class="head-text">
                                        <h3><?= $package['title'] ?></h3>
                                    </div>
                                    <div class="price-area">
                                        <?= get_currency_symbol() ?><span class="tenure_<?= $j ?>_price"><?= $tenures[0]['rate'] ?></span><span class="duration">For <span class="tenure_<?= $j ?>_months"><?= $tenures[0]['months'] ?></span> Month(s)</span>
                                    </div><?php $j++ ?>
                                    <div class="feature-area">
                                        <ul>
                                            <li><?= $package['max_storage_size'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_storage_size'] . ' ' . strtoupper($package['storage_unit']) . '</b>' ?> Storage</li>
                                            <li><b><?= $package['max_employees'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_employees'] . '</b>' ?></b> Employee(s)</li>
                                            <li><b><?= $package['max_workspaces'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_workspaces'] . '</b>' ?></b> Workspaces(s)</li>
                                            <li><b><?= $package['description'] ?></b></li>
                                        </ul>
                                    </div>
                                    <?php
                                    $temp = '<ul>';
                                    $modules = json_decode($package['modules'], true);
                                    if (isset($modules['projects']) && $modules['projects'] == 1) {
                                        $temp .= '<li>Projects</li>';
                                    }
                                    if (isset($modules['tasks']) && $modules['tasks'] == 1) {
                                        $temp .= '<li>Tasks</li>';
                                    }
                                    if (isset($modules['calendar']) && $modules['calendar'] == 1) {
                                        $temp .= '<li>Calendar</li>';
                                    }
                                    if (isset($modules['chat']) && $modules['chat'] == 1) {
                                        $temp .= '<li>Chat</li>';
                                    }
                                    if (isset($modules['finance']) && $modules['finance'] == 1) {
                                        $temp .= '<li>Finance</li>';
                                    }
                                    if (isset($modules['users']) && $modules['users'] == 1) {
                                        $temp .= '<li>Users</li>';
                                    }
                                    if (isset($modules['clients']) && $modules['clients'] == 1) {
                                        $temp .= '<li>Clients</li>';
                                    }
                                    if (isset($modules['activity_logs']) && $modules['activity_logs'] == 1) {
                                        $temp .= '<li>Activity Logs</li>';
                                    }
                                    if (isset($modules['leave_requests']) && $modules['leave_requests'] == 1) {
                                        $temp .= '<li>Leave Requests</li>';
                                    }
                                    if (isset($modules['notes']) && $modules['notes'] == 1) {
                                        $temp .= '<li>Notes</li>';
                                    }
                                    if (isset($modules['mail']) && $modules['mail'] == 1) {
                                        $temp .= '<li>Mail</li>';
                                    }
                                    if (isset($modules['announcements']) && $modules['announcements'] == 1) {
                                        $temp .= '<li>Announcements</li>';
                                    }
                                    if (isset($modules['notifications']) && $modules['notifications'] == 1) {
                                        $temp .= '<li>Notifications</li>';
                                    }
                                    if (isset($modules['sms_notifications']) && $modules['sms_notifications'] == 1) {
                                        $temp .= '<li>SMS Notifications</li>';
                                    }
                                    if (isset($modules['support_system']) && $modules['support_system'] == 1) {
                                        $temp .= '<li>Support system</li>';
                                    }
                                    if (isset($modules['meetings']) && $modules['meetings'] == 1) {
                                        $temp .= '<li>Meetings</li>';
                                    }
                                    $temp .= '</ul>'; ?>
                                    <b><?= !empty($this->lang->line('label_modules')) ? $this->lang->line('label_modules') : 'Modules'; ?></b>
                                    <div class="feature-area">
                                        <?= $temp; ?>

                                    </div>
                                    <div class="btn-area">
                                        <a href="<?= base_url('admin/billing/packages') ?>" class='butn butn__new'><span><?= !empty($this->lang->line('label_proceed')) ? $this->lang->line('label_proceed') : 'Proceed'; ?></span></a>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="card-body alert alert-danger text-center">No plans found!!!
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <?php require_once(APPPATH . '/views/footer.php'); ?>
    <script src="<?= base_url('assets/frontend/js/pages/components-plans.js'); ?>"></script>
</body>
<?php require_once(APPPATH . '/views/footer-scripts.php'); ?>
<script src="<?= base_url('assets/frontend/js/main.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles-js.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles.js'); ?>"></script>

</html>