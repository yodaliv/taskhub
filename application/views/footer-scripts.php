<script src="<?= base_url('assets/frontend/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/swiper-bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/custom.js'); ?>"></script>
<script src="<?= base_url('assets/backend/libs/jquery.validate.min.js'); ?>"></script>
<script src="<?= base_url('assets/backend/modules/izitoast/js/iziToast.min.js'); ?>"></script>
<?php if ($this->session->flashdata('message')) { ?>
    <script>
        iziToast.<?= $this->session->flashdata('message_type'); ?>({
            title: "<?= $this->session->flashdata('message'); ?>",
            message: '',
            position: 'topRight'
        });
    </script>
<?php } ?>