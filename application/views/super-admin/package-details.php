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
                                                        <input type="number" class="form-control" name="sequence_no" value="<?= $package['position_no'] ?>" placeholder="" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_workspaces')) ? $this->lang->line('label_no_of_workspaces') : 'No. of workspaces'; ?></label><span class="asterisk"> *</span>
                                                        <input type="number" class="form-control" name="max_workspaces" value="<?= $package['max_workspaces'] ?>" placeholder="" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_employees')) ? $this->lang->line('label_no_of_employees') : 'No. of employees'; ?></label><span class="asterisk"> *</span>
                                                        <input type="number" class="form-control" name="max_employees" value="<?= $package['max_employees'] ?>" placeholder="" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_storage_limit')) ? $this->lang->line('label_storage_limit') : 'Storage limit'; ?></label> <small>(Leave it blank to allow unlimited storage)</small>
                                                        <input type="number" class="form-control" name="storage_limit" value="<?= $package['max_storage_size'] ?>" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_storage_unit')) ? $this->lang->line('label_storage_unit') : 'Storage unit'; ?></label>
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
                                                <div class="col-md-4" id="monthly_price">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_monthly_price')) ? $this->lang->line('label_monthly_price') : 'Monthly price'; ?></label><span class="asterisk"> *</span>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                                            <input class="form-control" type="number" min="1" name="monthly_price" value="<?= $package['monthly_price'] ?>" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="yealy_price">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_yearly_price')) ? $this->lang->line('label_yearly_price') : 'Yearly price'; ?></label><span class="asterisk"> *</span>
                                                        <div class="input-group">
                                                            <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend"><span class="input-group-text"><?= get_currency_symbol(); ?></span></span>
                                                            <input class="form-control" type="number" min="1" name="yealy_price" value="<?= $package['annual_price'] ?>" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label>
                                                        <div class="input-group">
                                                            <textarea type="textarea" class="form-control" placeholder="" name="description" value="<?= $package['description'] ?>"></textarea>
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
                                                                                <span class="custom-switch-description"><?= !empty($this->lang->line('label_select_all')) ? $this->lang->line('label_select_all') : 'Select all'; ?></span>
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
                                                                                <?= !empty($this->lang->line('label_projects')) ? $this->lang->line('label_projects') : 'Projects'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-tasks-checkbox" <?= $modules['tasks'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-tasks-allowed" name="is-tasks-allowed" value="<?= $modules['tasks'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_tasks')) ? $this->lang->line('label_tasks') : 'Tasks'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-calendar-checkbox" <?= $modules['calendar'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-calendar-allowed" name="is-calendar-allowed" value="<?= $modules['calendar'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_calendar')) ? $this->lang->line('label_calendar') : 'Calendar'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-chat-checkbox" <?= $modules['chat'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-chat-allowed" name="is-chat-allowed" value="<?= $modules['chat'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_chat')) ? $this->lang->line('label_chat') : 'Chat'; ?>
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
                                                                                <?= !empty($this->lang->line('label_finance')) ? $this->lang->line('label_finance') : 'Finance'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-users-checkbox" <?= $modules['users'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-users-allowed" name="is-users-allowed" value="<?= $modules['users'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-clients-checkbox" <?= $modules['clients'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-clients-allowed" name="is-clients-allowed" value="<?= $modules['clients'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-activity-logs-checkbox" <?= $modules['activity_logs'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-activity-logs-allowed" name="is-activity-logs-allowed" value="<?= $modules['activity_logs'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_activity_logs')) ? $this->lang->line('label_activity_logs') : 'Activity logs'; ?>
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
                                                                                <?= !empty($this->lang->line('label_leave_requests')) ? $this->lang->line('label_leave_requests') : 'Leave requests'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-notes-checkbox" <?= $modules['notes'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-notes-allowed" name="is-notes-allowed" value="<?= $modules['notes'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_notes')) ? $this->lang->line('label_notes') : 'Notes'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="d-none">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-settings-checkbox" <?= $modules['settings'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-settings-allowed" name="is-settings-allowed" value="<?= $modules['settings'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_settings')) ? $this->lang->line('label_settings') : 'Settings'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="d-none">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-languages-checkbox" <?= $modules['languages'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-languages-allowed" name="is-languages-allowed" value="<?= $modules['languages'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                                <?= !empty($this->lang->line('label_languages')) ? $this->lang->line('label_languages') : 'Languages'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-mail-checkbox" <?= $modules['mail'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-mail-allowed" name="is-mail-allowed" value="<?= $modules['mail'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_mail')) ? $this->lang->line('label_mail') : 'Mail'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-announcements-checkbox" <?= $modules['announcements'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-announcements-allowed" name="is-announcements-allowed" value="<?= $modules['announcements'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_announcements')) ? $this->lang->line('label_announcements') : 'Announcements'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="edit-notifications-checkbox" <?= $modules['notifications'] == 1 ? 'checked' : '' ?>>
                                                                            <input type="hidden" id="edit-is-notifications-allowed" name="is-notifications-allowed" value="<?= $modules['notifications'] == 1 ? '1' : '0' ?>">
                                                                            <label class="form-check-label">
                                                                            <?= !empty($this->lang->line('label_notifications')) ? $this->lang->line('label_notifications') : 'Notifications'; ?>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    <div class="row text-center">
                                                        <div class="col-md-3"></div>
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
    var max_storage_size = '';
</script>
<script src="<?= base_url('assets/backend/js/page/components-package.js'); ?>"></script>

</html>