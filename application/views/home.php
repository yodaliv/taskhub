<html>

<head>
    <title>Home &mdash; <?= get_compnay_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once(APPPATH . 'views/frontend-css.php'); ?>
</head>

<body>
    <!-- navigation -->

    <?php require_once(APPPATH . '/views/header.php'); ?>

    <section class="image_wrap" id="home_sec">
        <div class="container-fluid">
            <div class="row custom-section d-flex align-items-center">
                <div class="sec2 col-12 col-lg-4">
                    <h2 class="animation">Project</h2>
                    <h3 class="index_header_text">Management</h3>
                    <p><?= get_compnay_title(); ?> is best project management tool to manage your company's projects and tasks very effectively. it's powerful features helps to boost growth of your company. this powerful tool also helps you to manage human resources so you can increase your productivity.</p>
                    <a href="<?= base_url('plans') ?>" class='butn butn__new'><span><?= !empty($this->lang->line('label_browse_plans')) ? $this->lang->line('label_browse_plans') : 'Browse plans'; ?></span></a>
                </div>
                <div class="col-12 col-lg-8">
                    <img src="<?= base_url('assets/frontend/img/6316.jpg') ?>" alt="home">
                </div>
            </div>
        </div>
    </section>
    <?php
    $default_tenure = default_tenure();
    $default_package = default_package();
    $tenure = $this->db->where('id', $default_tenure)->get('packages_tenures')->row_array();
    $package = $this->db->where('id', $default_package)->get('packages')->row_array();
    if (!empty($default_package) && !empty($default_tenure)) {
        if (!empty($tenure) && !empty($package)) {
            $months = $tenure['months'] > 1 ? 'MONTHS' : 'MONTH';
    ?>
            <section>
                <div class="promo">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                                <p class="mb-0">TRY <?= strtoupper($package['title']) ?> FREE FOR<span> <?= $tenure['months'] . ' ' . $months ?> </span> YOU'LL NEVER REGRET IT!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    <?php }
    } ?>

    <section id="task_sec">
        <div class="container pt-5">
            <div class="sec_header text-center">
                <h2>Discover what makes <span> <?= get_compnay_title(); ?> </span> great.</h2>
                <p>Start working with <?= get_compnay_title(); ?> that can provide everything you need to manage your daily tasks smarterly.</p>
            </div>
            <div class="row">
                <div class="col-md-5 mt-4 pt-2">
                    <ul class="nav-tabs nav nav-justified flex-column mb-0">
                        <li class="sec-nav-item bg-light rounded-md mt-1">
                            <a href="#home" class="sec_nav nav-link active" data-toggle="tab">
                                <div class="p-3 text-left">
                                    <h4 class="fclass"><?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?></h4>
                                    <p>Dashboard shows important information like project status, task overview and task insights. it also provides numeric information like total projects, tasks, users and clients.</p>
                                </div>
                            </a>
                        </li>
                        <li class="sec-nav-item bg-light rounded-md mt-1">
                            <a href="#home1" class="sec_nav nav-link" data-toggle="tab">
                                <div class="p-3 text-left">
                                    <h4 class="fclass"><?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?></h4>
                                    <p>Here you can see all the projects in grid view, list view and calendar view. also projects can be filtered according to it's status and you can manage projects from here.</p>
                                </div>
                            </a>
                        </li>
                        <li class="sec-nav-item  bg-light rounded-md mt-1">
                            <a href="#home2" class="sec_nav nav-link" data-toggle="tab">
                                <div class="p-3 text-left">
                                    <h4 class="fclass"><?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?></h4>
                                    <p>It provides basic information about task also you can filter taksk by project, status, due date, clients and users. you can see detailed information of particular task by clicking view button.</p>
                                </div>
                            </a>
                        </li>
                        <li class="sec-nav-item  bg-light rounded-md mt-1">
                            <a href="#home3" class="sec_nav nav-link" data-toggle="tab">
                                <div class="p-3 text-left">
                                    <h4 class="fclass"><?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?></h4>
                                    <p>You can create events by clicking particular date or selecting multiple dates and can see events in calendar and list view. also you can drag and drop to change start date and end date of event you can change event details by clicking it.</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 col-12 mt-4 pt-2">
                    <div class="tab-content">
                        <div class="tab-pane mt-5 active" id="home">
                            <img src="<?= base_url('assets/frontend/img/ts1.jpg') ?>" alt="" class="ts_image">
                        </div>
                        <div class="tab-pane mt-5" id="home1">
                            <img src="<?= base_url('assets/frontend/img/projects.jpg') ?>" alt="" class="ts_image">
                        </div>
                        <div class="tab-pane mt-5" id="home2">
                            <img src="<?= base_url('assets/frontend/img/tasks.jpg') ?>" alt="" class="ts_image">
                        </div>
                        <div class="tab-pane mt-5" id="home3">
                            <img src="<?= base_url('assets/frontend/img/calendar.jpg') ?>" alt="" class="ts_image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="feature_sec">
        <div class="container">
            <h2 class="text-center pt-5"><?= !empty($this->lang->line('label_great')) ? $this->lang->line('label_great') : 'Great'; ?> <span class="span-color"><?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></span></h2>
            <div class="row pt-4">
                <div class="col-md-4 pt-5">
                    <div class="card card1 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_project_management')) ? $this->lang->line('label_project_management') : 'Project management'; ?></h4>
                            <p>You can assign projects to your employees and manage deadlines and track progress. </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card card2 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="far fa-calendar-check"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_task_scheduling')) ? $this->lang->line('label_task_scheduling') : 'Task scheduling'; ?></h4>
                            <p>Task can be created and updated according to status. it helps to view progress of particular task.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card card4 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?></h4>
                            <p>Calendar helps you to manage events. you can create and manage events for any dates and time. </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card card5 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="fas fa-comments"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?></h4>
                            <p>This feature is for internal chating build with great features like detect typing, message alert, file sharing.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card card6 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="fas fa-money-bill"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?></h4>
                            <p>This module is for creating and managing estimates and invoices also helps to track payments.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card card6 d-flex align-items-end flex-column p-4 border-0 feature-box">
                        <div class="card-body">
                            <span class="sec_icons">
                                <i class="fas fa-language"></i>
                            </span>
                            <h4><?= !empty($this->lang->line('label_multiple_languages')) ? $this->lang->line('label_multiple_languages') : 'Multiple languages'; ?></h4>
                            <p><?= get_compnay_title() ?> supports multiple languages. you can create and manage languages. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="philosophy_sec">
        <!-- two section -->
        <div class="container padding">
            <div class="row padding">
                <div class="col-md-12 col-lg-6 philosophy_section">
                    <h2><?= !empty($this->lang->line('label_why')) ? $this->lang->line('label_why') : 'Why'; ?> <span class="span-color"> <?= get_compnay_title() ?></span> <?= !empty($this->lang->line('label_is')) ? $this->lang->line('label_is') : 'is'; ?> <?= !empty($this->lang->line('label_best')) ? $this->lang->line('label_best') : 'best'; ?>?</h2>
                    <p><?= get_compnay_title() ?> is intended mainly for small and growing businesses includes all the tools you’ll need to track the development of a project. it reduces project development cycles and improves team productivity by combining all of the important features of project management into one tool</p>
                    <p><?= get_compnay_title() ?> project management software offers great features like dashboard, projects, tasks, chat, finance, activity logs, notifications, and mail. you can share and work with this tool with your whole team and save time and increase your productivity.</p>
                    <p></p>

                </div>
                <div class="col-lg-6">
                    <img src="<?= base_url('assets/frontend/img/home.jpg') ?>" class="img-fluid">
                </div>
            </div>
            <hr class="my-4">
        </div>
    </section>
    <section class="home_faq_sec" id="faq_sec">
        <div class="container">
            <div class="row">
                <div class="home_faq col-md-7 offset-lg-2">
                    <h2><span class="span-color"><?= !empty($this->lang->line('label_frequently')) ? $this->lang->line('label_frequently') : 'Frequently'; ?></span> <?= !empty($this->lang->line('label_asked')) ? $this->lang->line('label_asked') : 'Asked'; ?> <?= !empty($this->lang->line('label_questions')) ? $this->lang->line('label_questions') : 'Questions'; ?></h2>
                    <div class="accordion mt-5" id="accordionExample">
                        <?php if (!empty($faqs)) {
                            for ($i = 0; $i < count($faqs); $i++) {
                        ?>
                                <div class="card">
                                    <div class="card-header" id="h<?= $i ?>">
                                        <h2 class="clearfix mb-0">
                                            <a class="home_faq_btn btn btn-link collapsed" data-toggle="collapse" data-target="#c<?= $i ?>" aria-expanded="true" aria-controls="collapseone">
                                                <?= $faqs[$i]['question'] ?><i class="fa fa-angle-down"></i></a>
                                        </h2>
                                    </div>
                                    <div id="c<?= $i ?>" class="collapse" aria-labelledby="h<?= $i ?>" data-parent="#accordionExample">
                                        <div class="card-body"><?= $faqs[$i]['answer'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="card-body alert alert-danger text-center"><?= !empty($this->lang->line('label_no_faqs_found')) ? $this->lang->line('label_no_faqs_found') : 'No Faqs Found'; ?>!!!
                            </div>

                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-5">
                    <img class="faq_image" src="<?= base_url('assets/frontend/img/faq1.png') ?>" alt="faq">
                </div>
            </div>
        </div>
    </section>
    <section id="client_sec" class="d-none">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/1.png') ?>" class="swipper_image" alt=""></div>
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/2.png') ?>" class="swipper_image" alt=""></div>
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/3.png') ?>" class="swipper_image" alt=""></div>
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/4.png') ?>" class="swipper_image" alt=""></div>
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/5.png') ?>" class="swipper_image" alt=""></div>
                <div class="swiper-slide"><img src="<?= base_url('assets/frontend/img/6.png') ?>" class="swipper_image" alt=""></div>
            </div>
        </div>
    </section>
    <!-- footer -->

    <?php require_once(APPPATH . '/views/footer.php'); ?>
</body>
<?php require_once(APPPATH . '/views/footer-scripts.php'); ?>
<script src="<?= base_url('assets/frontend/js/main.js'); ?>"></script>

</html>