<html>

<head>
    <title>Login &mdash; <?= get_compnay_title(); ?></title>
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
                <h2 class=" pricing_text  mb-4"><?= !empty($this->lang->line('label_login')) ? $this->lang->line('label_login') : 'Login'; ?></h2>
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
                <h2 class="pt-5"><?= !empty($this->lang->line('label_login')) ? $this->lang->line('label_login') : 'Login'; ?></h2>
                <p class="pb-3">Please fill in this form to Login an account.</p>
                <form action=<?=base_url("auth/login")?> id="login_form">
                    <div class="form-group">
                        <label for="inputEmail4"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
                        <input type="email" name="identity" class="form-control" id="identity" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="inputPassword4"><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label><label class="form-check-label text-right">
                        </label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group text-right">
                        <label class="form-check-label">
                            <a href="<?= base_url('auth/forgot') ?>"><?= !empty($this->lang->line('label_forgot_password')) ? $this->lang->line('label_forgot_password') : 'Forgot password'; ?>?</a>
                        </label>

                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="gridCheck">
                            <?= !empty($this->lang->line('label_keep_me_logged_in')) ? $this->lang->line('label_keep_me_logged_in') : 'Keep me logged in'; ?>
                            </label>

                        </div>

                    </div>



                    <a href="#" class='butn butn__new premium_button submit_btn'><?= !empty($this->lang->line('label_login')) ? $this->lang->line('label_login') : 'Login'; ?></a>
                </form>
                <div class="form-group">
                    <div class="d-none" id="login_result"></div>
                </div>
                <?php if (ALLOW_MODIFICATION == 0) { ?>
                    <div class="form-group">
                        <button data-login_as="super-admin" class="btn btn-success btn-lg btn-block login-as"> Login as Super admin </button>
                        <button data-login_as="admin" class="btn btn-danger btn-lg btn-block login-as"> Login as Admin </button>
                        <button data-login_as="team" class="btn btn-info btn-lg btn-block login-as"> Login as Team Member </button>
                        <button data-login_as="client" class="btn btn-warning btn-lg btn-block login-as"> Login as Client </button>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <!-- <div class="form-check"> -->
                    <label class="form-check-label">
                        Don't have an account? <a href="<?= base_url('signup') ?>">Create account now</a>
                    </label>
                    <!-- </div> -->
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
<script src="<?= base_url('assets/frontend/js/pages/components-login.js'); ?>"></script>

</html>