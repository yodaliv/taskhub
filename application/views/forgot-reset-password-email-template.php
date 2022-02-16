<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center" style="padding:40px 0 30px 0;background:#70bbd9;">
                            <img src="<?= $logo ?>" alt="" width="300" style="height:auto;display:block;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <?php $heading = $type == 'forgot_password' ? 'Password reset link' : 'Password changed' ?>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;"><?= $heading ?></h1>
                                        <?php if ($type == 'forgot_password') { ?>
                                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Dear, <b><?= $user_name ?></b> here is your password reset link <a href="<?= $full_link ?>" target="_blank"><?= $full_link ?></a> click it to proceed further.<br>Thanks & regards <b><?= $company_title ?></b></p>
                                        <?php } elseif($type=='reset_password_ack'){ ?>
                                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Dear, <b><?= $user_name ?></b> your account password changes successfully. Here is new password <b><?=$password?></b> please take note of it.<br>Thanks & regards <b><?= $company_title ?></b></p>
                                        <?php }?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;background:#ee4c50;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                    <td style="padding:0;width:50%;" align="left">
                                        <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                            &reg; <?= $company_title . ' ' . date('Y') ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>