<html>

<head>
    <title>Reset password &mdash; <?= get_compnay_title(); ?></title>
    <?php require_once(APPPATH . 'views/frontend-css.php'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<!-- navigation -->

<?php require_once(APPPATH . '/views/header.php'); ?>
<section id="page-title" class="header_section page-title-parallax page-title-center page-title-dark include-header" data-bottom-top="background-position:0px 300px;" data-top-bottom="background-position:0px -300px;">
    <div id="particles-line"></div>
    <div class="container clearfix mt-4">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class=" pricing_text  mb-4"><?= !empty($this->lang->line('label_reset_password')) ? $this->lang->line('label_reset_password') : 'Reset password'; ?></h2>
                <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
            </div>
        </div>
</section>
<section id="content" class="pt-5 pb-5">
    <div class="container box-shadow">
        <div class="row">
            <div class="col-md-7">
                <div class="sign-up-image">
                    <img src="<?= base_url('assets/frontend/img/mobile_login.png') ?>" alt="">
                </div>
            </div>
            <div class="col-md-5">
                <h2 class="pt-5"><?= !empty($this->lang->line('label_reset_password')) ? $this->lang->line('label_reset_password') : 'Reset password'; ?></h2>                
                <form action=<?=base_url("forgot_password/recover_password")?> id="recover_password_form">
                <input type="hidden" id="code" name="code" value="<?=$code?>" />
                    <div class="form-group">
                        <label for=""><?= !empty($this->lang->line('label_new_password')) ? $this->lang->line('label_new_password') : 'New password'; ?></label>
                        <input type="password" name="password" id="psw" class="form-control" placeholder="New password">
                    </div>
                    <div class="form-group">
                        <label for=""><?= !empty($this->lang->line('label_confirm_new_password')) ? $this->lang->line('label_confirm_new_password') : 'Confirm new password'; ?></label>
                        <input type="password" name="confirm_password" id="confirm_psw" class="form-control" placeholder="Confirm new password">
                    </div>
                    <a href="#" class='butn butn__new premium_button submit_btn'><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></a>
                </form>
                <div class="form-group">
                    <div class="d-none" id="result"></div>
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
<script src="<?= base_url('assets/frontend/js/pages/components-reset-password.js'); ?>"></script>

</html>