<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Create package &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_add_package')) ? $this->lang->line('label_add_package') : 'Create Package'; ?></h1>
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
                                        <form action="<?= base_url('super-admin/packages/create') ?>" id="create_package_form">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                                                        <input type="text" class="form-control" name="title" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_sequence_no')) ? $this->lang->line('label_sequence_no') : 'Sequence no.'; ?></label><span class="asterisk"> *</span>
                                                        <input type="number" class="form-control" name="sequence_no" placeholder="" min="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_workspaces')) ? $this->lang->line('label_no_of_workspaces') : 'No. of workspaces'; ?></label> <small>(Leave it blank to allow unlimited workspaces)</small>
                                                        <input type="number" class="form-control" name="max_workspaces" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_no_of_employees')) ? $this->lang->line('label_no_of_employees') : 'No. of employees'; ?></label> <small>(Leave it blank to allow unlimited employees)</small>
                                                        <input type="number" class="form-control" name="max_employees" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_storage_limit')) ? $this->lang->line('label_storage_limit') : 'Storage limit'; ?></label> <small>(Leave it blank to allow unlimited storage)</small>
                                                        <input type="number" class="form-control" name="storage_limit" placeholder="" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_unit')) ? $this->lang->line('label_unit') : 'Unit'; ?></label>
                                                        <select name="storage_unit" class="form-control">
                                                            <option value="mb">MB</option>
                                                            <option value="gb">GB</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_plan_type')) ? $this->lang->line('label_plan_type') : 'Plan type'; ?></label><span class="asterisk"> *</span>
                                                        <select name="plan_type" id="plan_type" class="form-control">
                                                            <option value="paid"><?= !empty($this->lang->line('label_paid')) ? $this->lang->line('label_paid') : 'Paid'; ?></option>
                                                            <option value="free"><?= !empty($this->lang->line('label_free')) ? $this->lang->line('label_free') : 'Free'; ?></option>
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

                                                        </div>
                                                        <hr>

                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                                                        <div class="input-group">
                                                            <textarea type="textarea" class="form-control" placeholder="" name="description"></textarea>
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
                                                                        <div class="form-group">
                                                                            <label class="custom-switch mt-2">
                                                                                <input type="checkbox" id="ckbCheckAll" class="custom-switch-input">
                                                                                <span class="custom-switch-indicator"></span>
                                                                                <span class="custom-switch-description">Select all</span>
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>

                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="projects-checkbox">
                                                                            <input type="hidden" id="is-projects-allowed" name="is-projects-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Projects
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="tasks-checkbox">
                                                                            <input type="hidden" id="is-tasks-allowed" name="is-tasks-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Tasks
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="calendar-checkbox">
                                                                            <input type="hidden" id="is-calendar-allowed" name="is-calendar-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Calendar
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="chat-checkbox">
                                                                            <input type="hidden" id="is-chat-allowed" name="is-chat-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Chat
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="finance-checkbox">
                                                                            <input type="hidden" id="is-finance-allowed" name="is-finance-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Finance
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="users-checkbox">
                                                                            <input type="hidden" id="is-users-allowed" name="is-users-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Users
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="clients-checkbox">
                                                                            <input type="hidden" id="is-clients-allowed" name="is-clients-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Clients
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="activity-logs-checkbox">
                                                                            <input type="hidden" id="is-activity-logs-allowed" name="is-activity-logs-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Activity logs
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="leave-requests-checkbox">
                                                                            <input type="hidden" id="is-leave-requests-allowed" name="is-leave-requests-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Leave requests
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="notes-checkbox">
                                                                            <input type="hidden" id="is-notes-allowed" name="is-notes-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Notes
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="mail-checkbox">
                                                                            <input type="hidden" id="is-mail-allowed" name="is-mail-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Mail
                                                                            </label>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="announcements-checkbox">
                                                                            <input type="hidden" id="is-announcements-allowed" name="is-announcements-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Announcements
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="notifications-checkbox">
                                                                            <input type="hidden" id="is-notifications-allowed" name="is-notifications-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                Notifications
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" id="sms-notifications-checkbox">
                                                                            <input type="hidden" id="is-sms-notifications-allowed" name="is-sms-notifications-allowed" value="0">
                                                                            <label class="form-check-label">
                                                                                SMS Notifications
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" name="meetings">                                                                            
                                                                            <label class="form-check-label">
                                                                                Meetings
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input checkBoxClass" type="checkbox" name="support_system">                                                                            
                                                                            <label class="form-check-label">
                                                                                Support system
                                                                            </label>
                                                                        </div>
                                                                    </td>

                                                                </tr>
                                                            </tbody>
                                                        </table>
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
    var max_storage_size = '';
</script>
<script src="<?= base_url('assets/backend/js/page/components-package.js'); ?>"></script>

</html>