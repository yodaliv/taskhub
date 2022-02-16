<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Edit package &mdash; <?= get_compnay_title(); ?></title>
    <?php require_once(APPPATH . 'views/include-css.php'); ?>
</head>

<body>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php require_once(APPPATH . '/views/super-admin/include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_edit_package')) ? $this->lang->line('label_edit_package') : 'Edit Package'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('super-admin/packages') ?>" class="btn"><i class="fas fa-list"></i> <?= !empty($this->lang->line('label_packages')) ? $this->lang->line('label_packages') : 'Packages'; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row mt-sm-4">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="<?= base_url('super-admin/packages/edit') ?>" id="edit_package_form">
                                            <input type="hidden" name="package_id" value="<?= $package_id ?>">
                                            <input type="hidden" name="deleted_tenure_ids" id="deleted_tenure_ids">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                                                        <input type="text" class="form-control" name="title" value="<?= $package['title'] ?>" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_sequence_no')) ? $this->lang->line('label_sequence_no') : 'Sequence no.'; ?></label><span class="asterisk"> *</span>
                                                        <input type="number" class="form-control" name="sequence_no" value="<?= $package['position_no'] ?>" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_workspaces')) ? $this->lang->line('label_no_of_workspaces') : 'No. of workspaces'; ?></label> <small>(Leave it blank to allow unlimited workspaces)</small>
                                                        <input type="number" class="form-control" name="max_workspaces" value="<?= $package['max_workspaces'] ?>" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_employees')) ? $this->lang->line('label_no_of_employees') : 'No. of employees'; ?></label> <small>(Leave it blank to allow unlimited employees)</small>
                                                        <input type="number" class="form-control" name="max_employees" value="<?= $package['max_employees'] ?>" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_storage_limit')) ? $this->lang->line('label_storage_limit') : 'Storage limit'; ?></label> <small>(Leave it blank to allow unlimited storage)</small>
                                                        <input type="number" class="form-control" name="storage_limit" value="<?= $package['max_storage_size'] ?>" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_unit')) ? $this->lang->line('label_unit') : 'Unit'; ?></label>
                                                        <select name="storage_unit" class="form-control">
                                                            <option value="mb" <?= $package['storage_unit'] == 'mb' ? 'selected' : '' ?>>MB</option>
                                                            <option value="gb" <?= $package['storage_unit'] == 'gb' ? 'selected' : '' ?>>GB</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_plan_type')) ? $this->lang->line('label_plan_type') : 'Plan type'; ?></label><span class="asterisk"> *</span>
                                                        <select name="plan_type" id="plan_type" class="form-control">
                                                            <option value="paid" <?= $package['plan_type'] == 'paid' ? 'selected' : '' ?>><?= !empty($this->lang->line('label_paid')) ? $this->lang->line('label_paid') : 'Paid'; ?></option>
                                                            <option value="free" <?= $package['plan_type'] == 'free' ? 'selected' : '' ?>><?= !empty($this->lang->line('label_free')) ? $this->lang->line('label_free') : 'Free'; ?></option>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="container-fluid">
                                                        <div class="row custom-table-header">

                                                            <div class="col-md-4 custom-col">
                                                                <?= !empty($this->lang->line('label_tenure')) ? $this->lang->line('label_tenure') : 'Tenure'; ?><span class="asterisk"> *</span>
                                                            </div>
                                                            <div class="col-md-4 custom-col">
                                                                <?= !empty($this->lang->line('label_months')) ? $this->lang->line('label_months') : 'Month(s)'; ?>
                                                            </div>
                                                            <div class="col-md-3 custom-col">
                                                                <?= !empty($this->lang->line('label_rate')) ? $this->lang->line('label_rate') : 'Rate'; ?>(<?= get_currency_symbol() ?>)<span class="asterisk"> *</span>
                                                            </div>
                                                            <div class="col-md-1 custom-col">
                                                                <?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?>
                                                            </div>
                                                        </div><br>

                                                        <div id="tenure_items">
                                                            <div class="tenure-item py-1">
                                                                <div class="row">
                                                                    <div class="col-md-4 custom-col">
                                                                        <input type="text" class="form-control" id="item_0_tenure" placeholder="Ex. Weekly, Quarterly, Monthly, Yearly">
                                                                    </div>
                                                                    <div class="col-md-4 custom-col">
                                                                        <select class="form-control" id="item_0_months" required>
                                                                            <?php for ($i = 1; $i <= 36; $i++) { ?>
                                                                                <option value="<?= $i ?>"><?= $i ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-3 custom-col">
                                                                        <input type="number" class="form-control" id="item_0_rate" min="0" placeholder="Rate">
                                                                    </div>
                                                                    <div class="col-md-1 custom-col">
                                                                        <a href="#" class="btn btn-icon btn-success add-tenure-item"><i class="fas fa-plus"></i></a>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <?php
                                                            for ($j = 0; $j < count($package_tenures); $j++) { ?>
                                                                <input type="hidden" name="tenure_id[]" id="tenure_<?= $j ?>_id" value="<?= $package_tenures[$j]['id'] ?>">
                                                                <div class="tenure-item py-1">
                                                                    <div class="row">
                                                                        <div class="col-md-4 custom-col">
                                                                            <input type="text" class="form-control" id="item_<?= $j ?>_tenure" name="tenure[]" value="<?= $package_tenures[$j]['tenure'] ?>" placeholder="Ex. Weekly, Quarterly, Monthly, Yearly">
                                                                        </div>
                                                                        <div class="col-md-4 custom-col">
                                                                            <select class="form-control" id="item_<?= $j ?>_months" name="months[]" required>
                                                                                <?php for ($k = 1; $k <= 36; $k++) { ?>
                                                                                    <option value="<?= $k ?>" <?= $package_tenures[$j]['months'] == $k ? 'selected' : '' ?>><?= $k ?></option>
                                                                                <?php } ?>

                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 custom-col">
                                                                            <input type="number" class="form-control" id="item_<?= $j ?>_rate" name="rate[]" value="<?= $package_tenures[$j]['rate'] ?>" min="0" placeholder="Rate">
                                                                        </div>
                                                                        <div class="col-md-1 custom-col">
                                                                            <a href="#" class="btn btn-icon btn-danger remove-tenure-item" data-id='<?= $package_tenures[$j]['id'] ?>'><i class="fas fa-trash"></i></a>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <hr>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                                                        <div class="input-group">
                                                            <textarea type="textarea" class="form-control" placeholder="" name="description"><?= $package['description'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_modules')) ? $this->lang->line('label_modules') : 'Modules'; ?></label>
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        $all_checked = 1;
                                                                        foreach ($modules as $module) {
                                                                            if ($module == 0) {
                                                                                $all_checked = 0;
                                                                            }
                                                                        }

                                                                        ?>
                                                                        <div class="form-group">
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" id="editckbCheckAll" class="custom-switch-input" <?= $all_checked == 1 ? 'checked' : '' ?>>
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description">Select all</span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>

                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-projects-checkbox" <?= $modules['projects'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-projects-allowed" name="is-projects-allowed" value="<?= $modules['projects'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Projects
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-tasks-checkbox" <?= $modules['tasks'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-tasks-allowed" name="is-tasks-allowed" value="<?= $modules['tasks'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Tasks
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-calendar-checkbox" <?= $modules['calendar'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-calendar-allowed" name="is-calendar-allowed" value="<?= $modules['calendar'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Calendar
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-chat-checkbox" <?= $modules['chat'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-chat-allowed" name="is-chat-allowed" value="<?= $modules['chat'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Chat
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-finance-checkbox" <?= $modules['finance'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-finance-allowed" name="is-finance-allowed" value="<?= $modules['finance'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Finance
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-users-checkbox" <?= $modules['users'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-users-allowed" name="is-users-allowed" value="<?= $modules['users'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Users
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-clients-checkbox" <?= $modules['clients'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-clients-allowed" name="is-clients-allowed" value="<?= $modules['clients'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Clients
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-activity-logs-checkbox" <?= $modules['activity_logs'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-activity-logs-allowed" name="is-activity-logs-allowed" value="<?= $modules['activity_logs'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Activity logs
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-leave-requests-checkbox" <?= $modules['leave_requests'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-leave-requests-allowed" name="is-leave-requests-allowed" value="<?= $modules['leave_requests'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Leave requests
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-notes-checkbox" <?= $modules['notes'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-notes-allowed" name="is-notes-allowed" value="<?= $modules['notes'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Notes
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-mail-checkbox" <?= isset($modules['mail']) && $modules['mail'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-mail-allowed" name="is-mail-allowed" value="<?= isset($modules['mail']) && $modules['mail'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Mail
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-announcements-checkbox" <?= isset($modules['announcements']) && $modules['announcements'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-announcements-allowed" name="is-announcements-allowed" value="<?= isset($modules['announcements']) && $modules['announcements'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Announcements
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-notifications-checkbox" <?= isset($modules['notifications']) && $modules['notifications'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-notifications-allowed" name="is-notifications-allowed" value="<?= isset($modules['notifications']) && $modules['notifications'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                Notifications
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-sms-notifications-checkbox" <?= isset($modules['sms_notifications']) && $modules['sms_notifications'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-sms-notifications-allowed" name="is-sms-notifications-allowed" value="<?= isset($modules['sms_notifications']) && $modules['sms_notifications'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                SMS Notifications
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" name="meetings" <?= isset($modules['meetings']) && $modules['meetings'] == 1 ? 'checked' : '' ?>>
                                                                            <label class="form-check-label">
                                                                                Meetings
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" name="support_system" <?= isset($modules['support_system']) && $modules['support_system'] == 1 ? 'checked' : '' ?>>
                                                                            <label class="form-check-label">
                                                                                Support system
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                                            <div class="selectgroup w-100">
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" name="status" value="1" class="selectgroup-input" <?= $package['status'] == 1 ? 'checked' : '' ?>>
                                                                    <span class="selectgroup-button">Active</span>
                                                                </label>
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" name="status" value="0" class="selectgroup-input" <?= $package['status'] == 0 ? 'checked' : '' ?>>
                                                                    <span class="selectgroup-button">Deactive</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="card-footer col-md-6">
                                                            <button class="btn btn-primary mb-2" id="submit_button">Submit</button>
                                                            <div id="result" class="disp-none"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                </section>


                <?php require_once(APPPATH . 'views/super-admin/include-footer.php'); ?>

            </div>
        </div>
        <?php require_once(APPPATH . 'views/include-js.php'); ?>
</body>
<script>
    j = <?= count($package_tenures) ?>;
</script>
<script src="<?= base_url('assets/backend/js/page/components-edit-package.js'); ?>"></script>

</html>