<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Packages &mdash; <?= get_compnay_title(); ?></title>
    <?php
    require_once(APPPATH . '/views/include-css.php');
    ?>
    <!-- /END GA -->
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
                        <div class="section-header-breadcrumb">
                            <div class="btn-group mr-2 no-shadow">
                                <a class="btn btn-primary text-white" href="<?= base_url('super-admin/packages/create-package') ?>" class="btn"><i class="fas fa-plus"></i> <?= !empty($this->lang->line('label_add_package')) ? $this->lang->line('label_add_package') : 'Create Package'; ?></a>
                            </div>

                        </div>

                    </div>
                    <div class="section-body">
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <select id="plan_type" name="plan_type" class="form-control">
                                                        <option value=""><?= !empty($this->lang->line('label_select_type')) ? $this->lang->line('label_select_type') : 'Select type'; ?></option>
                                                        <option value="paid"><?= !empty($this->lang->line('label_paid')) ? $this->lang->line('label_paid') : 'Paid'; ?></option>
                                                        <option value="free"><?= !empty($this->lang->line('label_free')) ? $this->lang->line('label_free') : 'Free'; ?></option>
                                                    </select>

                                                </div>
                                                <div class="form-group col-md-3">
                                                    <select id="status" name="status" class="form-control">
                                                        <option value=""><?= !empty($this->lang->line('label_select')) ? $this->lang->line('label_select') : 'Select'; ?></option>
                                                        <option value="1"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></option>
                                                        <option value="0"><?= !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'Deactive'; ?></option>
                                                    </select>

                                                </div>
                                                <div class="form-group col-md-2">
                                                    <i class="btn btn-primary btn-rounded no-shadow" id="filter_btn"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                                </div>
                                            </div>
                                            <table class='table-striped' id='package_list' data-toggle="table" data-url="<?= base_url('super-admin/packages/get_packages_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "package-list"
                    }' data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                        <th data-field="title" data-sortable="true"><?= !empty($this->lang->line('label_title')) ? $this->lang->line('label_title') : 'Title'; ?></th>
                                                        <th data-field="position_no" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_sequence_no')) ? $this->lang->line('label_sequence_no') : 'Sequence no.'; ?></th>
                                                        <th data-field="max_workspaces" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_workspaces')) ? $this->lang->line('label_workspaces') : 'Workspaces'; ?></th>
                                                        <th data-field="max_employees" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_employees')) ? $this->lang->line('label_employees') : 'Employees'; ?></th>
                                                        <th data-field="max_storage_size" data-sortable="true" data-visible="true"><?= !empty($this->lang->line('label_storage')) ? $this->lang->line('label_storage') : 'Storage'; ?></th>
                                                        <th data-field="plan_type" data-sortable="true"><?= !empty($this->lang->line('label_plan_type')) ? $this->lang->line('label_plan_type') : 'Plan type'; ?></th>
                                                        <th data-field="modules" data-sortable="false"><?= !empty($this->lang->line('label_modules')) ? $this->lang->line('label_modules') : 'Modules'; ?></th>
                                                        <th data-field="status" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                        <th data-field="date_created" data-sortable="false" data-visible="false"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date Created'; ?></th>
                                                        <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>


                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            require_once(APPPATH . '/views/super-admin/include-footer.php');
            ?>
        </div>
    </div>

    <?php
    require_once(APPPATH . 'views/include-js.php');
    ?>
</body>
<script>
    var max_storage_size = '';
</script>
<script src="<?= base_url('assets/backend/js/page/components-package.js'); ?>"></script>

</html>