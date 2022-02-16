<?php $current_version = get_current_version(); ?>
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li>
                <span class="badge badge-success">v <?= (isset($current_version) && !empty($current_version)) ? $current_version : '1.0' ?></span>
            </li>
            <?php if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) { ?>
                <li class="ml-2">
                    <span class="badge badge-danger">Demo mode</span>
                </li>
            <?php } ?>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <?php if (isset($user->profile) && !empty($user->profile)) { ?>
                    <img alt="image" src="<?= base_url('assets/backend/profiles/' . $user->profile); ?>" class="rounded-circle mr-1">
                <?php } else { ?>
                    <figure class="avatar mr-1 avatar-sm" data-initial="<?= mb_substr($user->first_name, 0, 1) . '' . mb_substr($user->last_name, 0, 1); ?>"></figure>
                <?php } ?>
                <div class="d-sm-none d-lg-inline-block"><?= !empty($this->lang->line('label_hi')) ? $this->lang->line('label_hi') : 'Hi'; ?>, <?= $user->first_name ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-width">
                <!--workspace code goes here-->
                <a href="<?= base_url($this->session->userdata('role') . '/profile'); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i>
                    <?= !empty($this->lang->line('label_profile')) ? $this->lang->line('label_profile') : 'Profile'; ?>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <?= !empty($this->lang->line('label_logout')) ? $this->lang->line('label_logout') : 'Logout'; ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url($this->session->userdata('role') . '/home'); ?>">
                <img alt="Task Hub" src="<?= !empty($data->full_logo) ? base_url('assets/backend/icons/' . $data->full_logo) : base_url('assets/backend/icons/logo.png'); ?>" width="200px">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url($this->session->userdata('role') . '/home'); ?>">
                <img alt="Task Hub" src="<?= !empty($data->half_logo) ? base_url('assets/backend/icons/' . $data->half_logo) : base_url('assets/backend/icons/logo-half.png'); ?>" width="40px">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li <?= (current_url() == base_url($this->session->userdata('role') . '/home')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($this->session->userdata('role') . '/home'); ?>"><i class="fas fa-fire text-danger"></i> <span> <?= !empty($this->lang->line('label_dashboard')) ? $this->lang->line('label_dashboard') : 'Dashboard'; ?> </span></a></li>
            <li <?= (current_url() == base_url($this->session->userdata('role') . '/packages') || current_url() == base_url($this->session->userdata('role') . '/packages' . '/create-package') || $this->uri->segment(3) == 'edit-package') ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($this->session->userdata('role') . '/packages'); ?>"><i class="fas fa-box-open text-success"></i> <span> <?= !empty($this->lang->line('label_packages')) ? $this->lang->line('label_packages') : 'Packages'; ?> </span></a></li>
            <li <?= (current_url() == base_url($this->session->userdata('role') . '/subscriptions')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($this->session->userdata('role') . '/subscriptions'); ?>"><i class="fas fa-align-center text-warning"></i> <span> <?= !empty($this->lang->line('label_subscriptions')) ? $this->lang->line('label_subscriptions') : 'Subscriptions'; ?> </span></a></li>
            <li <?= (current_url() == base_url($this->session->userdata('role') . '/transactions')) ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url($this->session->userdata('role') . '/transactions'); ?>"><i class="fas fa-money-bill text-success"></i> <span> <?= !empty($this->lang->line('label_transactions')) ? $this->lang->line('label_transactions') : 'Transactions'; ?> </span></a></li>
            <li <?= (current_url() == base_url('super-admin/users') || $this->uri->segment(2) == 'users') ? 'class="active"' : ''; ?>><a class="nav-link" href="<?= base_url('super-admin/users'); ?>"><i class="fas fa-user text-warning"></i> <span>
                        <?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?>
                    </span></a>
            </li>
            <li <?= (current_url() == base_url('super-admin/privacy-policy')) ? 'class="active"' : ''; ?>>
            <li class="dropdown <?= (current_url() == base_url('super-admin/privacy-policy') || current_url() == base_url('super-admin/terms-conditions') || current_url() == base_url('super-admin/faqs') || current_url() == base_url('super-admin/contact-us') || current_url() == base_url('super-admin/payment-methods')) ? ' active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs text-info"></i> <span><?= !empty($this->lang->line('label_front_settings')) ? $this->lang->line('label_front_settings') : 'Front settings'; ?></span></a>
                <ul class="dropdown-menu">
                    <li <?= (current_url() == base_url('super-admin/privacy-policy')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url('super-admin/privacy-policy'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_privacy_policy')) ? $this->lang->line('label_privacy_policy') : 'Privacy policy'; ?>
                            </span>
                        </a>
                    </li>
                    <li <?= (current_url() == base_url('super-admin/terms-conditions')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url('super-admin/terms-conditions'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_terms_conditions')) ? $this->lang->line('label_terms_conditions') : 'Terms conditions'; ?>
                            </span>
                        </a>
                    </li>
                    <li <?= (current_url() == base_url('super-admin/faqs')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url('super-admin/faqs'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_faqs')) ? $this->lang->line('label_faqs') : 'Faqs'; ?>
                            </span>
                        </a>
                    </li>
                    <li <?= (current_url() == base_url('super-admin/contact-us')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url('super-admin/contact-us'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_contact_us')) ? $this->lang->line('label_contact_us') : 'Contact us'; ?>
                            </span>
                        </a>
                    </li>
                    <li <?= (current_url() == base_url('super-admin/payment-methods')) ? 'class="active"' : ''; ?>>
                        <a class="nav-link" href="<?= base_url('super-admin/payment-methods'); ?>">
                            <span>
                                <?= !empty($this->lang->line('label_payment_methods')) ? $this->lang->line('label_payment_methods') : 'Payment methods'; ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li <?= (current_url() == base_url('super-admin/settings/setting-detail')) ? 'class="active mb-5"' : 'class="mb-5"'; ?>><a class="nav-link" href="<?= base_url('super-admin/settings/setting-detail'); ?>"><i class="fas fa-cog"></i> <span>
                        <?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Setting'; ?>
                    </span></a>
            </li>
            <li class="language-btn dropup">
                <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#"><i class="fas fa-language"></i>
                    <span><?= ucfirst($user->lang); ?></span>
                </a>
                <div class="dropdown-menu">
                    <?php
                    $languages =  get_languages();
                    if (!empty($languages)) {
                        foreach ($languages as $lang) { ?>
                            <a href='<?= base_url("languageswitch/switchlang/" . $lang['language']); ?>' class="dropdown-item has-icon"><?= (($lang['language'] == $user->lang) ? '<i class="fas fa-check"></i>' : ''); ?> <?= ucfirst($lang['language']); ?> </a>
                        <?php }
                    }
                    if (is_super()) { ?>
                        <div class="dropdown-divider"></div>
                        <a href='<?= base_url("super-admin/languages/change/" . $user->lang); ?>' class="dropdown-item has-icon"> <?= !empty($this->lang->line('label_create_and_customize')) ? $this->lang->line('label_create_and_customize') : 'Create & Customize'; ?> </a>
                    <?php } ?>
                </div>
            </li>

        </ul>
    </aside>
</div>