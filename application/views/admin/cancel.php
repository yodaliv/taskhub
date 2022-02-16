<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Payment cancelled / failed &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_payment_cancelled_failed')) ? $this->lang->line('label_payment_cancelled_failed') : 'Payment cancelled / failed'; ?></h1>

                    </div>

                    <div class="section-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-has-icon">
                                    <div class="alert-icon"><i class="fa fa-times"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">Sorry!</div>
                                        <span class="custom-font">Your payment was cancelled or failed please try again. <b>Thank you</b> for using our service.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <img src="<?= base_url("assets/backend/img/fail-img.png") ?>" alt="Payment cancelled or failed" width="200">
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