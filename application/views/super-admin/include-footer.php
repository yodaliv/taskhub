
<footer class="main-footer">
    <div class="footer-left">
    </div>
    <div class="footer-right">
        Copyright &copy; <?= date('Y'); ?> <div class="bullet"></div> Design & Developed By <a href="https://www.infinitietech.com/" target="_blank">Infinitie Technologies</a>
    </div>
</footer>

<script>
    base_url = "<?php echo base_url(); ?>";
    role = "<?= $this->session->userdata('role'); ?>"
    csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>