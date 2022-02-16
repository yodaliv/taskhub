<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Manage ticket types &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
    <?php require_once(APPPATH . 'views/include-css.php'); ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php require_once(APPPATH . '/views/admin/include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_manage_ticket_types')) ? $this->lang->line('label_manage_ticket_types') : 'Manage ticket types' ?></h1>
                        <div class="section-header-breadcrumb">
                            <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-ticket-type">
                                <?= !empty($this->lang->line('label_add_ticket_type')) ? $this->lang->line('label_add_ticket_type') : 'Add Ticket Type'; ?></i>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-ticket-type"></div>
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='ticket_types_list' data-toggle="table" data-url="<?= base_url('admin/tickets/get_ticket_types_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="title" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "ticket-types-list"
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                                                    <th data-field="date_created" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date created'; ?></th>
                                                    <?php if ($this->ion_auth->is_admin() || is_workspace_admin($this->session->userdata('user_id'), $this->session->userdata('workspace_id'))) { ?>
                                                        <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?= form_open('admin/tickets/create_ticket_type', 'id="modal-add-ticket-type-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-12" id="title">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['name' => 'title', 'placeholder' => 'Title', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?= form_open('admin/tickets/edit_ticket_type', 'id="modal-edit-ticket-type-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <input name="update_id" type="hidden" id="update_id" value="">
                            <?= form_input(['name' => 'title', 'placeholder' => 'Title', 'class' => 'form-control', 'id' => 'update_title']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?php require_once(APPPATH . 'views/admin/include-footer.php'); ?>
        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>
    <!-- Page Specific JS File -->
    <script src="<?= base_url('assets/backend/js/page/components-ticket-types.js'); ?>"></script>

</body>

</html>