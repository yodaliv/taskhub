<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>General Settings &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?>
                    </div>

                    <div class="section-body">

                        <div id="output-status"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_jump_to')) ? $this->lang->line('label_jump_to') : 'Jump To'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="general-tab4" data-toggle="tab" href="#general-settings" role="tab" aria-controls="general" aria-selected="true"><?= !empty($this->lang->line('label_general')) ? $this->lang->line('label_general') : 'General'; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="email-tab4" data-toggle="tab" href="#email-settings" role="tab" aria-controls="email" aria-selected="false"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></a>
                                            </li>                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="tab-content no-padding" id="myTab2Content">
                                    <div class="tab-pane fade show active" id="general-settings" role="tabpanel" aria-labelledby="general-tab4">
                                        <form action="<?= base_url('admin/settings/save_settings'); ?>" method="POST" id="general-setting-form" autocomplete="off">
                                            <div class="card" id="general-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_general_settings')) ? $this->lang->line('label_general_settings') : 'General Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <p class="text-muted">General settings such as, company title and logo.</p>

                                                    <div class="form-group row align-items-center">
                                                        <label for="company_title" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_company_title')) ? $this->lang->line('label_company_title') : 'Company Title'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">

                                                            <input type="hidden" name="setting_type" class="form-control" value="general">

                                                            <input type="text" name="company_title" class="form-control" id="company_title" value="<?= !empty($company_title) ? $company_title : '' ?>" required>

                                                        </div>
                                                    </div>                                                    

                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_logo')) ? $this->lang->line('label_logo') : 'Logo'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" name="full_logo" class="custom-file-input" id="full_logo">
                                                                <input type="hidden" name="full_logo_old" class="custom-file-input" id="full_logo_old" value="<?= !empty($full_logo) ? $full_logo : '' ?>">
                                                                <label class="custom-file-label"><?= !empty($this->lang->line('label_choose_file')) ? $this->lang->line('label_choose_file') : 'Choose File'; ?></label>
                                                            </div>
                                                            <div class="form-text text-muted">The image must have a maximum size of 1MB</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_half_logo')) ? $this->lang->line('label_half_logo') : 'Half Logo'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" name="half_logo" class="custom-file-input" id="half_logo">
                                                                <input type="hidden" name="half_logo_old" class="custom-file-input" id="half_logo_old" value="<?= !empty($half_logo) ? $half_logo : ''; ?>">
                                                                <label class="custom-file-label"><?= !empty($this->lang->line('label_choose_file')) ? $this->lang->line('label_choose_file') : 'Choose File'; ?></label>
                                                            </div>
                                                            <div class="form-text text-muted">The image must have a maximum size of 1MB</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_favicon')) ? $this->lang->line('label_favicon') : 'Favicon'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" name="favicon" class="custom-file-input" id="favicon">
                                                                <input type="hidden" name="favicon_old" class="custom-file-input" id="favicon_old" value="<?= !empty($favicon) ? $favicon : ''; ?>">
                                                                <label class="custom-file-label"><?= !empty($this->lang->line('label_choose_file')) ? $this->lang->line('label_choose_file') : 'Choose File'; ?></label>
                                                            </div>
                                                            <div class="form-text text-muted">The image must have a maximum size of 1MB</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-none setting-result"></div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="general-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <?php $dataemail = get_settings_by_admin_id($this->data['admin_id'],'email',1);
                                    ?>
                                    <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="email-tab4">

                                        <form action="<?= base_url('admin/settings/save_settings'); ?>" id="email-setting-form" autocomplete="off">
                                            <div class="card" id="email-settings-card">
                                                <div class="card-header">
                                                    <h4><?= !empty($this->lang->line('label_email_settings')) ? $this->lang->line('label_email_settings') : 'Email Settings'; ?></h4>
                                                </div>

                                                <div class="card-body">
                                                    <p class="text-muted">Email SMTP settings, notifications and others related to email.</p>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $email = 'YOUR EMAIL';
                                                    }else{
                                                        if(!empty($dataemail->email)){
                                                            $email = $dataemail->email;
                                                        }else{
                                                            $email = 'EMAIL ADDRESS';
                                                        }
                                                    }
                                                    ?>

                                                    <div class="form-group row align-items-center">
                                                        <label for="email-set" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name();
                                                                                        ?>" class="form-control" value="<?= $this->security->get_csrf_hash(); ?>">

                                                            <input type="hidden" name="setting_type" class="form-control" value="email">

                                                            <input type="text" name="email" class="form-control" id="email-set" value="<?= $email ?>" required>
                                                            <div class="form-text text-muted">This is the email address which will be used to send email notifications.</div>
                                                        </div>

                                                    </div>
                                                    <?php
                                                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                                                        $password = 'YOUR PASSWORD';
                                                    }else{
                                                        if(!empty($dataemail->password)){
                                                            $password = $dataemail->password;
                                                        }else{
                                                            $password = 'PASSWORD';
                                                        }
                                                    }
                                                    ?>
                                                    <div class="form-group row align-items-center">
                                                        <label for="password" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="password" class="form-control" id="password" value="<?= $password ?>" required>
                                                            <div class="form-text text-muted">Password of above given email.</div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_host" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_smtp_host')) ? $this->lang->line('label_smtp_host') : 'SMTP Host'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_host" class="form-control" id="smtp_host" value="<?= !empty($dataemail->smtp_host) ? $dataemail->smtp_host : 'SMTP_HOST' ?>" required>
                                                            <div class="form-text text-muted">This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.</div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row align-items-center">
                                                        <label for="smtp_port" class="form-control-label col-sm-3 text-md-right"><?= !empty($this->lang->line('label_smtp_port')) ? $this->lang->line('label_smtp_port') : 'SMTP Port'; ?></label>
                                                        <div class="col-sm-6 col-md-9">
                                                            <input type="text" name="smtp_port" class="form-control" id="smtp_port" value="<?= !empty($dataemail->smtp_port) ? $dataemail->smtp_port : 'SMTP_PORT' ?>" required>
                                                            <div class="form-text text-muted">SMTP port this will provide your service provider.</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_email_content_type')) ? $this->lang->line('label_email_content_type') : 'Email Content Type'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <select class="form-control" name="mail_content_type" id="mail_content_type">
                                                                <?php
                                                                if (!empty($dataemail->mail_content_type)) {

                                                                    if ($dataemail->mail_content_type == 'text') { ?>
                                                                        <option value="text" selected>Text</option>
                                                                        <option value="html">HTML</option>
                                                                    <?php } else { ?>
                                                                        <option value="text">Text</option>
                                                                        <option value="html" selected>HTML</option>
                                                                    <?php }
                                                                } else { ?>
                                                                    <option value="text" selected>Text</option>
                                                                    <option value="html">HTML</option>

                                                                <?php } ?>

                                                            </select>
                                                            <div class="form-text text-muted">Text-plain or HTML content chooser.</div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="form-control-label col-sm-3 mt-3 text-md-right"><?= !empty($this->lang->line('label_smtp_encryption')) ? $this->lang->line('label_smtp_encryption') : 'SMTP Encryption'; ?></label>
                                                        <div class="col-sm-6 col-md-9">

                                                            <select class="form-control" name="smtp_encryption" id="smtp_encryption">
                                                                <?php
                                                                if (!empty($dataemail->smtp_encryption)) {

                                                                    if ($dataemail->smtp_encryption == 'ssl') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl" selected>SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php } elseif ($dataemail->smtp_encryption == 'tls') { ?>
                                                                        <option value="off">off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls" selected>TLS</option>
                                                                    <?php } else {  ?>
                                                                        <option value="off" selected>off</option>
                                                                        <option value="ssl">SSL</option>
                                                                        <option value="tls">TLS</option>
                                                                    <?php   }
                                                                } else { ?>
                                                                    <option value="off" selected>off</option>
                                                                    <option value="ssl">SSL</option>
                                                                    <option value="tls">TLS</option>

                                                                <?php } ?>

                                                            </select>
                                                            <div class="form-text text-muted">If your e-mail service provider supported secure connections, you can choose security method on list.</div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row d-none setting-result"></div>
                                                <div class="card-footer bg-whitesmoke text-md-right">
                                                    <button class="btn btn-primary" id="eamil-save-btn"><?= !empty($this->lang->line('label_save_changes')) ? $this->lang->line('label_save_changes') : 'Save Changes'; ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                </div>
                                <div id="result"></div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

            <?php require_once(APPPATH . 'views/admin/include-footer.php'); ?>

        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>

    <!-- Page Specific JS File -->

    <script src="<?= base_url('assets/backend/js/page/features-setting-detail.js'); ?>"></script>
</body>

</html>