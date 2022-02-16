<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Terms conditions &mdash; <?= get_compnay_title(); ?></title>
    <?php
    require_once(APPPATH . '/views/include-css.php');
    ?>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php
            require_once(APPPATH . '/views/super-admin/include-header.php');
            ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_terms_conditions')) ? $this->lang->line('label_terms_conditions') : 'Terms conditions'; ?></h1>
                    </div>
                    <?php $terms_conditions = get_system_settings('terms_conditions');
                    ?>
                    <div class="section-body">
                        <form action="<?= base_url('super-admin/terms-conditions/update') ?>" id="terms_conditions_form">
                            <div class="form-group">
                                <div class="input-group">
                                    <textarea name="terms_conditions" id="terms_conditions" type="textarea" class="form-control" placeholder="Terms conditions" name="terms_conditions"><?= !empty($terms_conditions[0]['data']) ? $terms_conditions[0]['data'] : '' ?></textarea>

                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-round btn-lg btn-primary" id="submit_button">
                                    <?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?>
                                </button>

                            </div>
                        </form>
                    </div>
                </section>
            </div>
            <?php
            require_once(APPPATH . '/views/super-admin/include-footer.php');
            ?>
        </div>
    </div>

    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
</body>
<script src="<?= base_url('assets/backend/js/page/components-terms-conditions.js'); ?>"></script>

</html>