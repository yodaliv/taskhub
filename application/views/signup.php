<html>

<head>
    <title>Sign Up</title>
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
                    <h2 class=" pricing_text  mb-4"><?= !empty($this->lang->line('label_sign_up')) ? $this->lang->line('label_sign_up') : 'Sign up'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>
    <section id="content" class="pt-5 pb-5">
        <div class="container box-shadow">
            <div class="row">
                <div class="col-md-7">
                    <div class="sign-up-image">
                        <img src="<?= base_url('assets/frontend/img/signup.png') ?>" alt="">
                    </div>
                </div>
                <div class="col-md-5">
                    <h2 class="pt-3"><?= !empty($this->lang->line('label_sign_up')) ? $this->lang->line('label_sign_up') : 'Sign up'; ?></h2>
                    <p class="pb-3">Please fill in this form to SignUp an account.</p>
                    <form action="auth/create_user" id="signup_form">
                        <input type="hidden" name="user_type" value="admin">
                        <input type="hidden" name="group_id" value="1">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                                <?= form_input(['name' => 'first_name', 'name' => 'first_name', 'placeholder' => 'First Name', 'class' => 'form-control']) ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                                <?= form_input(['name' => 'last_name', 'placeholder' => 'Last Name', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
                            <?= form_input(['name' => 'email', 'placeholder' => 'Email', 'class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                            <?= form_password(['name' => 'password', 'id' => 'password', 'placeholder' => 'Password', 'class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                            <?= form_password(['name' => 'password_confirm', 'placeholder' => 'Confirm Password', 'class' => 'form-control']) ?>
                        </div>
                        <a href="#" class='butn butn__new premium_button submit_btn'>Sign Up</a>
                        <div class="form-group mt-2">
                            <div id="result" class="d-none"></div>
                            <!-- <div id="result_success" class="d-none"></div> -->
                        </div>
                        <div class="form-group mt-2">
                            <!-- <div class="form-check"> -->
                            <label class="form-check-label">
                                Already have an account? <a href="<?= base_url('login') ?>">Login now</a>
                            </label>
                            <!-- </div> -->
                        </div>
                    </form>
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
<script src="<?= base_url('assets/frontend/js/pages/components-signup.js'); ?>"></script>

</html>