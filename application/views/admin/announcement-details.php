<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Announcement Details &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
    <?php require_once(APPPATH . 'views/include-css.php'); ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require_once(APPPATH . '/views/admin/include-header.php'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_announcement_details')) ? $this->lang->line('label_announcement_details') : 'Announcement Details'; ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-milestone"></div>
                            <div class="col-md-12">
                                <div class="card author-box card-info">
                                    <div class="card-body">
                                        <div class="author-box-name">
                                            <?php $title = $announcement[0]['title']; ?>
                                            <h4><?= $title; ?></h4>
                                        </div>
                                        <div class="announcement-date"><a href="<?= base_url($this->session->userdata('role') . '/users/detail/' . $announcement[0]['user_id']) ?>"><?= $user_name ?></a>
                                            <div class="bullet"></div> <a><?= date("d-M-Y H:i A", strtotime($announcement[0]['date_created'])); ?></a>
                                        </div>
                                        <div class="author-box-description">
                                            <span><?= $announcement[0]['description']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php require_once(APPPATH . 'views/admin/include-footer.php'); ?>
        </div>
    </div>
    <?php require_once(APPPATH . 'views/include-js.php'); ?>
    <!-- Page Specific JS File -->
</body>

</html>