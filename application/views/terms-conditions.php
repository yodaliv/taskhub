<html>

<head>
    <title>Terms & Conditions &mdash; <?= get_compnay_title(); ?></title>
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
                    <h2 class="pricing_text mb-4"><?= !empty($this->lang->line('label_terms_conditionss')) ? $this->lang->line('label_terms_conditions') : 'Terms & conditions'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>
    <section id="content">
        <div class="container mt-4">
            <p><?= !empty($terms_conditions) ? $terms_conditions : 'Terms conditions goes here' ?></p>
        </div>
    </section>
    <?php include 'footer.php' ?>
</body>
<?php require_once(APPPATH . '/views/footer-scripts.php'); ?>
<script src="<?= base_url('assets/frontend/js/main.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles-js.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/particles.js'); ?>"></script>

</html>