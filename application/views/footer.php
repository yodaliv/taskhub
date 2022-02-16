<?php
$logo = get_full_logo();
?>|
<footer class="footer_class">
    <div class="container-fluid padding">
        <div class="col-md-12">
            <div class="row">
            <div class="col-md-4">
					<div class="footer_image">
                        <img src="<?= !empty($logo) ? base_url('assets/backend/icons/' . $logo) : base_url('assets/backend/icons/logo.png'); ?>" class="d-block mx-auto mx-md-0 w-50">
                    </div>
                    <div class="address mt-3 text-md-left text-center">
                        <i class="fas fa-map-marker-alt"></i> <?= get_contact_us('address'); ?><br>
                    </div>
                    <div class="text-md-left text-center py-2">
                        <i class="fas fa-phone-alt"></i> <?= get_contact_us('contact_no'); ?>
                    </div>
                    <div class="text-md-left text-center py-2">
                        <i class="fas fa-envelope-square"></i> <?= get_contact_us('email'); ?>
                    </div>
                </div>
                <div class="col-md-4 pt-3 text-md-left text-center">

                    <h3><?= !empty($this->lang->line('label_quick_links')) ? $this->lang->line('label_quick_links') : 'Quick links'; ?></h3>
                    <ul class="footer-ul">
                        <li><a href="<?= base_url('home') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_home')) ? $this->lang->line('label_home') : 'Home'; ?></a></li>
                        <li><a href="<?= base_url('plans') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_plans')) ? $this->lang->line('label_plans') : 'Plans'; ?></a></li>
                        <li><a href="<?= base_url('features') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></a></li>
                        <li><a href="<?= base_url('privacy-policy') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?></a></li>
                        <li><a href="<?= base_url('terms-conditions') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_terms_conditions')) ? $this->lang->line('label_terms_conditions') : 'Terms & conditions'; ?></a></li>
                        <li><a href="<?= base_url('contact-us') ?>"><i class="fas fa-chevron-right"></i> <?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?></a></li>
                    </ul>
                </div>

                <div class="col-md-4 pt-3 pr-3 text-md-left text-center">

                    <h3><?= !empty($this->lang->line('label_quick_enquiry')) ? $this->lang->line('label_quick_enquiry') : 'Quick enquiry'; ?></h3>
                    <h6>If you have a question and would like to contact us, please complete the form below and we will get back to you as soon as possible</h6>

                    <form id="enquiry_form" class="form-subscribe pt-3" action="<?= base_url('contact-us/quick-enquiry') ?>">
                        <div class="input-group">
                            <input type="email" name="email" id="mail" class="form-control input-lg" placeholder="Your email address">
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-lg" type="submit" id="btn"><?= !empty($this->lang->line('label_send')) ? $this->lang->line('label_send') : 'Send'; ?></button>
                            </span>
                        </div>
                    </form>
                    <div class="d-none text-center mt-2" id="enquiry_result"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 bg-dark text-center">
        <hr class="light-100">
        <div class="row">
            <div class="col-md-6">
                <p class="footer-text">&copy; All rights reserved by <?= get_compnay_title(); ?></p>
            </div>
            <div class="col-md-6">
                <p class="footer-text">Designed and developed by <a href='https://infinitietech.com' target="_blank">Infinitie Technologies</a></p>
            </div>
        </div>
    </div>
</footer>
<script>
    base_url = "<?php echo base_url(); ?>";
    csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>