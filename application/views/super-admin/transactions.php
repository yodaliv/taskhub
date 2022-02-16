<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Transactions &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_transactions')) ? $this->lang->line('label_transactions') : 'Transactions'; ?></h1>

                    </div>
                    <div class="section-body">
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <div class="card-body">
                                            <table class='table-striped' id='transaction_list' data-toggle="table" data-url="<?= base_url('super-admin/transactions/get_transaction_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "transaction-list"
                    }' data-query-params="queryParams">
                                                <thead>
                                                    <tr>
                                                        <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                        <th data-field="user_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                        <th data-field="user" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_user')) ? $this->lang->line('label_user') : 'User'; ?></th>
                                                        <th data-field="item_id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_package_id')) ? $this->lang->line('label_package_id') : 'Package ID'; ?></th>
                                                        <th data-field="package" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_package')) ? $this->lang->line('label_package') : 'Package'; ?></th>
                                                        <th data-field="type" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_type')) ? $this->lang->line('label_type') : 'Type'; ?></th>
                                                        <th data-field="txn_id" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_transaction_id')) ? $this->lang->line('label_transaction_id') : 'Transaction ID'; ?></th>
                                                        <th data-field="amount" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_amount')) ? $this->lang->line('label_amount') : 'Amount'; ?></th>
                                                        <th data-field="currency_code" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_currency')) ? $this->lang->line('label_currency') : 'Currency'; ?></th>
                                                        <th data-field="message" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_message')) ? $this->lang->line('label_message') : 'Message'; ?></th>
                                                        <th data-field="status" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                        <th data-field="date_created" data-visible="true" data-sortable="true"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date created'; ?></th>
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