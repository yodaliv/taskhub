<!-- General JS Scripts -->
<script src="<?= base_url('assets/backend/modules/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/popper.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/tooltip.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/bootstrap/js/' . $rtl . 'bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/nicescroll/jquery.nicescroll.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/select2/dist/js/select2.full.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
<script src="<?= base_url('assets/backend/js/stisla.js'); ?>"></script>

<!-- JS Libraies -->
<script src="<?= base_url('assets/backend/modules/jquery.sparkline.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/chart.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/owlcarousel2/dist/owl.carousel.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/summernote/summernote-bs4.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/chocolat/dist/js/jquery.chocolat.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/dropzonejs/min/dropzone.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/selectize/selectize.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/dragula/dragula.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/pagedown/Markdown.Converter.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/pagedown/Markdown.Sanitizer.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/pagedown/Markdown.Editor.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/izitoast/js/iziToast.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/js/page/modules-toastr.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/sweetalert/sweetalert.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/cropper/cropper.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/bootstrap-table/bootstrap-table.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/bootstrap-table/bootstrap-table-mobile.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/lightbox/lightbox.min.js'); ?>"></script>

<script src="<?= base_url('assets/backend/js/scripts.js'); ?>"></script>
<script src="<?= base_url('assets/backend/js/custom.js'); ?>"></script>
<script src="<?= base_url('assets/backend/js/common.js'); ?>"></script>

<script src="<?= base_url('assets/backend/fullcalendar/core/main.js'); ?>"></script>
<script src="<?= base_url('assets/backend/fullcalendar/interaction/main.js'); ?>"></script>
<script src="<?= base_url('assets/backend/fullcalendar/daygrid/main.js'); ?>"></script>
<script src="<?= base_url('assets/backend/fullcalendar/list/main.js'); ?>"></script>
<script src="<?= base_url('assets/backend/fullcalendar/google-calendar/main.js'); ?>"></script>

<script src="<?= base_url('assets/backend/modules/atwho/jquery.atwho.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/atwho/jquery.caret.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/gchart/loader.js'); ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.4/tinymce.min.js"></script>

<script src="<?= base_url('assets/backend/libs/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/libs/jspdf.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/libs/html2canvas.js'); ?>"></script>

<?php if ($this->session->flashdata('message')) { ?>
    <script>
        iziToast.<?= $this->session->flashdata('message_type'); ?>({
            title: "<?= $this->session->flashdata('message'); ?>",
            message: '',
            position: 'topRight'
        });
    </script>
<?php } ?>