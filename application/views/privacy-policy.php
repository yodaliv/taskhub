<html>

<head>
    <title>Privacy policy &mdash; <?= get_compnay_title(); ?></title>
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
                    <h2 class="pricing_text mb-4"><?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?></h2>
                    <a class="titlebar-scroll-link" href="#content" data-localscroll="true"><i class="fa fa-angle-down"></i></a>
                </div>
            </div>
    </section>
    <section id="content">
        <div class="container mt-4">
            <p><?= !empty($privacy_policy) ? $privacy_policy : 'Privacy policy goes here' ?></p>
        </div>
    </section>

    <section id="faqs">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>FAQ's</h1>
                    <div class="accordion mt-5" id="accordionExample">
                        <?php if (!empty($faqs)) {
                            for ($i = 0; $i < count($faqs); $i++) {
                        ?>
                                <div class="card">
                                    <div class="card-header" id="h<?= $i ?>">
                                        <h2 class="clearfix mb-0">
                                            <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#c<?= $i ?>" aria-expanded="true" aria-controls="collapseone">
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
                            <div class="card-body alert alert-danger text-center">No FAQs Found!!!
                            </div>

                        <?php } ?>
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