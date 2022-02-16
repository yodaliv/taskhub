<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Contact us &mdash; <?= get_compnay_title(); ?></title>
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
            <?php $data = get_system_settings('contact_us');
            $contact_us = json_decode($data[0]['data']);
            ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?></h1>
                    </div>
                    <div class="section-body">
                        <form action="<?= base_url('super-admin/contact-us/update') ?>" id="contact_us_form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : 'Address'; ?></label><span class="asterisk"> *</span>
                                        <textarea class="form-control" name="address" placeholder="Address"><?= !empty($contact_us->address) ? $contact_us->address : '' ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_contact_no')) ? $this->lang->line('label_contact_no') : 'Contact no.'; ?></label><span class="asterisk"> *</span>
                                        <input type="text" class="form-control" name="contact_no" placeholder="Contact number" value="<?= !empty($contact_us->contact_no) ? $contact_us->contact_no : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label><span class="asterisk"> *</span>
                                        <input type="text" class="form-control" name="email" placeholder="Email" value="<?= !empty($contact_us->email) ? $contact_us->email : '' ?>">
                                    </div>
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
<script src="<?= base_url('assets/backend/js/page/components-contact-us.js'); ?>"></script>

</html>