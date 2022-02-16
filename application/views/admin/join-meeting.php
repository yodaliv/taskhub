
<html>

<head>
    <title>Join meeting &mdash; <?= get_compnay_title(); ?></title>
    <?php
    if (is_super()) {
        $data = get_system_settings('general');
        if (!empty($data)) {
            $data = json_decode($data[0]['data']);
        }
    ?>
        <link rel="shortcut icon" href="<?= !empty($data->favicon) ? base_url('assets/backend/icons/' . $data->favicon) : base_url('assets/backend/icons/logo-half.png'); ?>">
    <?php } else {
        $favicon = get_admin_company_favicon($this->data['admin_id']); ?>
        <link rel="shortcut icon" href="<?= base_url('assets/backend/icons/' . $favicon); ?>">
    <?php }
    ?>
</head>
<body><div id="meet" /></body>
<input type="hidden" name="room_name" id="room_name" value="<?=$room_name?>">
<input type="hidden" name="user_name" id="user_name" value="<?=$user_display_name?>">
<input type="hidden" name="user_email" id="user_email" value="<?=$user_email?>">
<input type="hidden" name="meeting_id" id="meeting_id" value="<?=$meeting_id?>">
<input type="hidden" name="is_meeting_admin" id="is_meeting_admin" value="<?=$is_meeting_admin?>">
<input type="hidden" name="base_url" id="base_url" value="<?= base_url(); ?>">
</html>
<script src="<?= base_url('assets/frontend/js/jquery.min.js'); ?>"></script>
<script src='https://8x8.vc/external_api.js'></script>
<script src="<?= base_url('assets/backend/js/page/components-join-meeting.js'); ?>"></script>

