<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Subscriptions &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_subscriptions')) ? $this->lang->line('label_subscriptions') : 'Subscriptions'; ?></h1>

                    </div>
                    <div class="section-body">
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <select id="type" name="type" class="form-control">
                                                        <option value=""><?= !empty($this->lang->line('label_all')) ? $this->lang->line('label_all') : 'All'; ?></option>
                                                        <option value="active"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></option>
                                                        <option value="upcoming"><?= !empty($this->lang->line('label_upcoming')) ? $this->lang->line('label_upcoming') : 'Up coming'; ?></option>
                                                        <option value="expired"><?= !empty($this->lang->line('label_expired')) ? $this->lang->line('label_expired') : 'Expired'; ?></option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <i class="btn btn-primary btn-rounded no-shadow" id="filter"><?= !empty($this->lang->line('label_filter')) ? $this->lang->line('label_filter') : 'Filter'; ?></i>
                                                </div>
                                            </div>
                                            <table class='table-striped' id='subscription_list' data-toggle="table" data-url="<?= base_url('super-admin/subscriptions/get_subscription_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "subscription-list"
                    }' data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                        <th data-field="user_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                        <th data-field="user_name" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_user_name')) ? $this->lang->line('label_user_name') : 'User name'; ?></th>
                                                        <th data-field="package_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_package_id')) ? $this->lang->line('label_package_id') : 'Package ID'; ?></th>
                                                        <th data-field="title" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_package')) ? $this->lang->line('label_package') : 'Package'; ?></th>
                                                        <th data-field="plan_type" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                        <th data-field="from_date" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_start_date')) ? $this->lang->line('label_start_date') : 'Start date'; ?></th>
                                                        <th data-field="to_date" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_end_date')) ? $this->lang->line('label_end_date') : 'End date'; ?></th>
                                                        <th data-field="tenure" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_tenure')) ? $this->lang->line('label_tenure') : 'Tenure'; ?></th>
                                                        <th data-field="months" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_months')) ? $this->lang->line('label_months') : 'Months'; ?></th>
                                                        <th data-field="amount" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                        <th data-field="payment_method" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_payment_method')) ? $this->lang->line('label_payment_method') : 'Payment method'; ?></th>
                                                        <th data-field="date_created" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_purchase_date')) ? $this->lang->line('label_purchase_date') : 'Purchase date'; ?></th>
                                                        <th data-field="action" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>


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
<script src="<?= base_url('assets/backend/js/page/components-subscriptions.js'); ?>"></script>

</html>