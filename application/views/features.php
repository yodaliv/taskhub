<html>

<head>
    <title>Features &mdash; <?= get_compnay_title(); ?></title>
    <?php require_once(APPPATH . 'views/frontend-css.php'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- navigation -->
    <?php require_once(APPPATH . '/views/header.php'); ?>
    <section id="page-title" class="header_section page-title-parallax page-title-center page-title-dark include-header" data-bottom-top="background-position:0px 300px;" data-top-bottom="background-position:0px -300px;">
        <div id="particles-line"></div>

        <div class="container clearfix mt-4">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="pricing_text mb-4"><?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>
    <section id="content">
        <div class="container">
            <div class="feature">
                <div class="cen">
                    <div class="service">
                        <i class="fas fa-briefcase"></i>
                        <h2><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?></h2>
                        <p>Create, manage and track projects.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-newspaper"></i>
                        <h2><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></h2>
                        <p>Schedule and manage tasks for projects.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-calendar"></i>
                        <h2><?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?></h2>
                        <p>Create and manage important events.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-comments"></i>
                        <h2><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?></h2>
                        <p>For internal communication between members.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-money-bill-alt"></i>
                        <h2><?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?></h2>
                        <p>Create and manage expenses, estimates and incoices.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-user"></i>
                        <h2><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></h2>
                        <p>View list of company employees and manage profile.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-users"></i>
                        <h2><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></h2>
                        <p>View list of company clients and manage profile.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-chart-line"></i>
                        <h2><?= !empty($this->lang->line('label_activity_logs')) ? $this->lang->line('label_activity_logs') : 'Activity logs'; ?></h2>
                        <p>To keep track who did which activity like project creation.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-check"></i>
                        <h2><?= !empty($this->lang->line('label_leave_requests')) ? $this->lang->line('label_leave_requests') : 'Leave requests'; ?></h2>
                        <p>To view and take action like approve or disapprove leave request.</p>
                    </div>
                    <div class="service">
                        <i class="fas fa-clipboard-list"></i>
                        <h2><?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Notes'; ?></h2>
                        <p>Notes feature is for managing some important notes related to company.</p>
                    </div>
                    <div class="service">
                        <i class="fa fa-paper-plane"></i>
                        <h2><?= !empty($this->lang->line('label_mail')) ? $this->lang->line('label_mail') : 'Mail'; ?></h2>
                        <p>Send mail to employees and clients with custom font settings and attachment.</p>
                    </div>
                    <div class="service">
                        <i class="fa fa-bullhorn"></i>
                        <h2><?= !empty($this->lang->line('label_announcements')) ? $this->lang->line('label_announcements') : 'Announcements'; ?></h2>
                        <p>Send and manage important announcements to company staff.</p>
                    </div>
                    <div class="service">
                        <i class="far fa-bell"></i>
                        <h2><?= !empty($this->lang->line('label_notifications')) ? $this->lang->line('label_notifications') : 'Notifications'; ?></h2>
                        <p>Employess will get notified if task or project assigned.</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php require_once(APPPATH . '/views/footer.php'); ?>
</body>
<?php require_once(APPPATH . '/views/footer-scripts.php'); ?>
<script src="<?= base_url('assets/frontend/js/main.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles-js.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles.js'); ?>"></script>

</html>