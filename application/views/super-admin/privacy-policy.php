<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Privacy policy &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?></h1>
                    </div>
                    <?php $privacy_policy = get_system_settings('privacy_policy');
                    ?>
                    <div class="section-body">
                        <form action="<?= base_url('super-admin/privacy-policy/update') ?>" id="privacy_policy_form">
                            <div class="form-group">
                                <div class="input-group">
                                    <textarea id="privacy_policy" type="textarea" class="form-control" placeholder="Privacy policy" name="privacy_policy"><?= !empty($privacy_policy[0]['data']) ? $privacy_policy[0]['data'] : '' ?></textarea>
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
<script src="<?= base_url('assets/backend/js/page/components-privacy-policy.js'); ?>"></script>

</html>