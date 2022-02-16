<?php
if (is_super()) {
    $data = get_system_settings('general');
    if (!empty($data)) {
        $data = json_decode($data[0]['data']);
    }
?>
    <link rel="shortcut icon" href="<?= !empty($data->favicon) ? base_url('assets/backend/icons/' . $data->favicon) : base_url('assets/backend/icons/logo-half.png'); ?>">
<?php } else {
    $favicon = get_admin_company_favicon($this->data['admin_id']);?>
    <link rel="shortcut icon" href="<?= base_url('assets/backend/icons/' . $favicon); ?>">
<?php }
$rtl = is_rtl() ? 'rtl/' : '';
?>





<!-- General CSS Files -->

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/bootstrap/css/' . $rtl . 'bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/fontawesome/css/all.min.css'); ?>">

<!-- CSS Libraries -->
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/dragula/dragula.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/select2/dist/css/select2.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/jqvmap/dist/jqvmap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/summernote/summernote-bs4.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/owlcarousel2/dist/assets/owl.carousel.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/owlcarousel2/dist/assets/owl.theme.default.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/chocolat/dist/css/chocolat.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/dropzonejs/dropzone.css'); ?>">

<!-- Template CSS -->
<link rel="stylesheet" href="<?= base_url('assets/backend/css/' . $rtl . 'style.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/css/' . $rtl . 'components.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/css/' . $rtl . 'custom.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/izitoast/css/iziToast.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/modules/selectize/selectize.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/cropper/cropper.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/bootstrap-table/bootstrap-table.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/lightbox/lightbox.min.css'); ?>">

<link rel="stylesheet" href="<?= base_url('assets/backend/fullcalendar/core/main.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/fullcalendar/daygrid/main.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/fullcalendar/list/main.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/backend/modules/atwho/jquery.atwho.min.css'); ?>">

<?php $my_system_fonts = get_system_fonts();
if ($my_system_fonts != 'default' && !empty($my_system_fonts->id) && !empty($my_system_fonts->font_cdn) && !empty($my_system_fonts->font_name) && !empty($my_system_fonts->font_family) && !empty($my_system_fonts->class)) { ?>
    <link rel="stylesheet" href="<?= $my_system_fonts->font_cdn ?>">
    <style>
        body {
            font-family: <?= $my_system_fonts->font_family ?>;
        }
    </style>
<?php } ?>