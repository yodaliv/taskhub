<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Packages &mdash; <?= get_compnay_title(); ?></title>
    <?php
    require_once(APPPATH . 'views/include-css.php');
    ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php
            require_once(APPPATH . '/views/super-admin/include-header.php');
            ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_packages')) ? $this->lang->line('label_packages') : 'Packages'; ?></h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card of-auto">
                                    <div class="card-body">
                                        <?php if (!empty($packages)) { ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"><?= !empty($this->lang->line('label_packages')) ? $this->lang->line('label_packages') : 'Packages'; ?> <i class="fas fa-arrow-right"></i></th>
                                                        <?php foreach ($packages as $package) { ?>

                                                            <th scope="col"><?= strtoupper($package['title']) ?></th><?php } ?>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row"><?= !empty($this->lang->line('label_price')) ? $this->lang->line('label_price') : 'Price'; ?></th>

                                                        <?php

                                                        $j = 0;
                                                        foreach ($packages as $package) {
                                                            $tenures = get_tenures($package['tenure_ids']); ?>

                                                            <td>
                                                                <h6><?= get_currency_symbol() . '<span class="tenure_' . $j . '_price">' . $tenures[0]['rate'] . '</span> <small>For <span class="tenure_' . $j . '_months">' . $tenures[0]['months'] . '</span> month(s)</small>' ?></h6>
                                                                <?php if (!empty($tenures)) { ?>

                                                                    <select class="custom-select" id="tenure_<?= $j ?>_price" onchange="update_amount(<?= $j ?>)"><?php for ($i = 0; $i < count($tenures); $i++) { ?><option value="<?= $tenures[$i]['id'] ?>"><?= $tenures[$i]['tenure'] ?></option><?php } ?></select>
                                                            </td>
                                                    <?php }
                                                                $j++;
                                                            } ?>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row"><?= !empty($this->lang->line('label_features')) ? $this->lang->line('label_features') : 'Features'; ?></th>

                                                        <?php

                                                        $j = 0;
                                                        foreach ($packages as $package) {
                                                        ?>

                                                            <td>
                                                                <div class="feature-area">
                                                                    <i class="fas fa-check text-success"></i> <?= $package['max_storage_size'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_storage_size'] . ' ' . strtoupper($package['storage_unit']) . '</b>' ?> Storage
                                                                    <i class="fas fa-check text-success"></i> <b><?= $package['max_employees'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_employees'] . '</b>' ?></b> Employee(s)
                                                                    <i class="fas fa-check text-success"></i> <b><?= $package['max_workspaces'] == 0 ? '<b>Unlimited</b>' : '<b>' . $package['max_workspaces'] . '</b>' ?></b> Workspaces(s)
                                                                </div>
                                                            </td>
                                                        <?php }
                                                        $j++;
                                                        ?>
                                                    </tr>
                                                    <?php
                                                    $m = array('projects', 'tasks', 'calendar', 'chat', 'finance', 'users', 'clients', 'activity_logs', 'leave_requests', 'notes', 'mail', 'announcements', 'notifications', 'sms_notifications','support_system','meetings');
                                                    for ($k = 0; $k < count($m); $k++) {
                                                    ?>
                                                        <tr>
                                                            <td scope="row"><?= ucfirst(str_replace('_', ' ', $m[$k])) ?></td>
                                                            <?php
                                                            for ($l = 0; $l < count($packages); $l++) {
                                                                $modules = json_decode($packages[$l]['modules'], 1);
                                                            ?>
                                                                <td><?= isset($modules[$m[$k]]) && $modules[$m[$k]] == 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
                                                            <?php } ?>
                                                        </tr><?php  } ?>
                                                    <tr>
                                                        <td scope="row"></td>
                                                        <?php for ($j = 0; $j < count($packages); $j++) {
                                                        ?>

                                                            <td><a href="#" class="btn btn-primary submit_btn add-subscription-alert-1" data-id="<?= $packages[$j]['id'] ?>" data-count="<?= $j ?>" data-user-id="<?= $user_id ?>">Choose</a></td>

                                                        <?php } ?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            <div class="simple-footer mt-5">
                                                <div id="error_msg" class="alert alert-danger disp-none"></div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="alert alert-info">No packages found!</div>
                                        <?php

                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            require_once(APPPATH . 'views/admin/include-footer.php');
            ?>
        </div>
    </div>
    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
    <script>
        var max_storage_size = '';
    </script>
    <script src="<?= base_url('assets/backend/js/page/components-package.js'); ?>"></script>
    <!-- Page Specific JS File -->
</body>

</html>