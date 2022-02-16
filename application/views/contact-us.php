<html>

<head>
    <title>Contact Us &mdash; <?= get_compnay_title(); ?></title>
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
                    <h2 class=" pricing_text  mb-4"><?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>
    <section class="contactform" id="content">
        <div class="container box-shadow">
            <div class="image_box">
                <img src="<?= base_url('assets/frontend/img/contact-us.jpg') ?>" alt="" class="contact_box">
            </div>
            <h2 class="contact_form"><?= !empty($this->lang->line('label_get_in_touch')) ? $this->lang->line('label_get_in_touch') : 'Get In Touch'; ?></h2>
            <p class="contact_form">Submit form below to get in touch with us.</p>
            <form id="contact_us_form" action="<?= base_url('contact-us/send-mail') ?>">
                <div class="form-group">
                    <label class="label" for=""><?= !empty($this->lang->line('label_full_name')) ? $this->lang->line('label_full_name') : 'Full name'; ?>:</label>
                    <input type="text" class="form-control" id="fname" placeholder="Enter your full name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label class="label" for="uname"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?>:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required>
                </div>
                <div class="form-group">
                    <label class="label" for=""><?= !empty($this->lang->line('label_mobile_no')) ? $this->lang->line('label_mobile_no') : 'Mobile no'; ?>:</label>
                    <input type="tel" class="form-control" id="mobileno" placeholder="Enter Your Mobile No" name="mobile_no" required>
                </div>
                <div class="form-group">
                    <label class="label" for=""><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?>:</label>
                    <input type="text" class="form-control" id="subject" placeholder="Enter subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label class="label" for="uname"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?>:</label>
                    <textarea class="form-control" id="message" placeholder="Enter Your Message" name="message" required></textarea>
                </div>
                <a href="#" class='butn butn__new contact_button'><span><?= !empty($this->lang->line('label_submit')) ? $this->lang->line('label_submit') : 'Submit'; ?></span></a>
            </form>
            <div class="form-group">
                <div class="d-none" id="result_send_mail"></div>
            </div>
        </div>
    </section>
    <?php require_once(APPPATH . '/views/footer.php'); ?>
</body>
<?php require_once(APPPATH . '/views/footer-scripts.php'); ?>
<script src="<?= base_url('assets/frontend/js/main.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles-js.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/pages/components-contact-us.js'); ?>"></script>

</html>