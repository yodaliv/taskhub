<!-- desktop nav -->
<?php
$logo = get_full_logo();
?>
<nav class="desktop_nav navbar navbar-expand-lg  navbar-light fixed-top py-3 header" id="nav_header">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url() ?>"><img src="<?= !empty($logo) ? base_url('assets/backend/icons/' . $logo) : base_url('assets/backend/icons/logo.png'); ?>" class="logo"></a>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto" id="nav">
                <li class="nav-item home">
                    <a class="nav-link <?= current_url() == base_url() || current_url() == base_url('home') ? 'activee' : '' ?>" href="<?= base_url('home') ?>"><?= !empty($this->lang->line('label_home')) ? $this->lang->line('label_home') : 'Home'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('plans') ? 'activee' : '' ?>" href="<?= base_url('plans') ?>"><?= !empty($this->lang->line('label_plans')) ? $this->lang->line('label_plans') : 'Plans'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('features') ? 'activee' : '' ?>" href="<?= base_url('features') ?>"><?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('privacy-policy') ? 'activee' : '' ?>" href="<?= base_url('privacy-policy') ?>"><?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('terms-conditions') ? 'activee' : '' ?>" href="<?= base_url('terms-conditions') ?>"><?= !empty($this->lang->line('label_terms_conditions')) ? $this->lang->line('label_terms_conditions') : 'Terms & conditions'; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == base_url('contact-us') ? 'activee' : '' ?>" href="<?= base_url('contact-us') ?>"><?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?></a>
                </li>
            </ul>
            <?php if (!empty($this->session->userdata('user_id'))) { ?>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                    <button id="button" class="button_animation doubletake text-center"><?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?></button>
                </div>
            <?php } else { ?>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                    <button id="button" class="button_animation doubletake text-center"><?= !empty($this->lang->line('label_get_started')) ? $this->lang->line('label_get_started') : 'Get started'; ?></button>
                </div>
            <?php } ?>

        </div>
    </div>
</nav>

<!-- mobile nav -->

<nav class="mobile_nav navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?=base_url();?>"><img src="<?= !empty($logo) ? base_url('assets/backend/icons/' . $logo) : base_url('assets/backend/icons/logo.png'); ?>" class="logo"></a>
        <div class="menu js-menu">
            <span class="menu__line"></span>
            <span class="menu__line"></span>
            <span class="menu__line"></span>
        </div>
        <nav class="mnav js-nav">
            <ul class="navbar-nav nav__list js-nav__list">
                <li class="nav__item home"><a href="<?= base_url() ?>" class="navlinks"><?= !empty($this->lang->line('label_home')) ? $this->lang->line('label_home') : 'Home'; ?></a></li>
                <li class="nav__item"><a href="<?= base_url('plans') ?>" class="navlinks"><?= !empty($this->lang->line('label_plans')) ? $this->lang->line('label_plans') : 'Plans'; ?></a></li>
                <li class="nav__item"><a href="<?= base_url('features') ?>" class="navlinks"><?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></a></li>
                <li class="nav__item"><a href="<?= base_url('privacy-policy') ?>" class="navlinks"><?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?></a></li>
                <li class="nav__item"><a href="<?= base_url('terms-conditions') ?>" class="navlinks"><?= !empty($this->lang->line('label_terms_conditions')) ? $this->lang->line('label_terms_conditions') : 'Terms & conditions'; ?></a></li>
                <li class="nav__item"><a href="<?= base_url('contact-us') ?>" class="navlinks"><?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?></a></li>
                <?php if (!empty($this->session->userdata('user_id'))) { ?>
                    <li class="nav__item button_animation doubletake text-center"><a href="<?= base_url('auth') ?>" class="navlinks"><?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?></a></li>
                <?php } else { ?>
                    <li class="nav__item button_animation doubletake text-center"><a href="<?= base_url('auth') ?>" class="navlinks"><?= !empty($this->lang->line('label_get_started')) ? $this->lang->line('label_get_started') : 'Get started'; ?></a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</nav>