<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Payment success &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_payment_success')) ? $this->lang->line('label_payment_success') : 'Payment success'; ?></h1>

                    </div>

                    <div class="section-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-has-icon">
                                    <div class="alert-icon"><i class="fa fa-check"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Congratulations</div>
                                        <?php if (!empty($package_title) && !empty($from_date) && !empty($to_date)) { ?>
                                            <span class="custom-font">You have purchased <b><?= $package_title ?></b> plan, activation date : <b><?= $from_date ?></b>, expiry date : <b><?= $to_date ?></b>. <b>Thank you</b> for using our service.</span>
                                        <?php } else { ?>
                                            <span class="custom-font">Your payment was successfull. <b>Thank you</b> for using our service.</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <img src="<?= base_url("assets/backend/img/success-img.png") ?>" alt="Payment success" width="200">
                            </div>
                            <div class="col-md-12 text-center mt-1">
                                <a href="<?= base_url('admin/home') ?>" class="btn btn-primary mt-5"><?= !empty($this->lang->line('label_goto_dashboard')) ? $this->lang->line('label_goto_dashboard') : 'Goto dashboard'; ?></a>
                            </div>


                        </div>
                    </div>
                </section>
            </div>



            <?php require_once(APPPATH . '/views/admin/include-footer.php'); ?>

        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>

    <!-- Page Specific JS File -->


</body>

</html>