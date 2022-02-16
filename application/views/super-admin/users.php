<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Users &mdash; <?= get_compnay_title(); ?></title>
    <?php require_once(APPPATH . '/views/include-css.php'); ?>

</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">

            <?php require_once(APPPATH . '/views/super-admin/include-header.php'); ?>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= $this->lang->line('label_users') ?></h1>
                        <div class="section-header-breadcrumb d-none">
                            <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-user"><?= !empty($this->lang->line('label_add_user')) ? $this->lang->line('label_add_user') : 'Add User'; ?></i>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-user"></div>
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">

                                        <table class='table-striped' id='users_list' data-toggle="table" data-url="<?= base_url('super-admin/users/get_customers_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams1">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="subscriptions" data-sortable="false"><?= !empty($this->lang->line('label_subscriptions')) ? $this->lang->line('label_subscriptions') : 'Subscriptions'; ?></th>
                                                    <?php if ($this->ion_auth->is_super()) { ?>
                                                        <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
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
            <?= form_open('auth/create_user', 'id="modal-add-user-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?> (If User Already Exits In Other Workspace Than Select Email)</label>
                        <div class="input-group">
                            <?= form_input(['name' => 'email', 'class' => 'demo-default', 'id' => 'email']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="first_name">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'first_name', 'name' => 'first_name', 'placeholder' => 'First Name', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="last_name">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'last_name', 'placeholder' => 'Last Name', 'class' => 'form-control']) ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="password">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password', 'placeholder' => 'Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="password_confirm">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password_confirm', 'placeholder' => 'Confirm Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="address">
                    <div class="form-group">
                        <label for="address"><?= !empty($this->lang->line('label_address')) ? $this->lang->line('label_address') : 'Address'; ?></label>
                        <textarea type="textarea" class="form-control" placeholder="Address" name="address" id="address"></textarea>
                    </div>
                </div>
                <div class="col-md-6" id="city">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_city')) ? $this->lang->line('label_city') : 'City'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'city', 'placeholder' => 'City', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="state">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_state')) ? $this->lang->line('label_state') : 'State'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'state', 'placeholder' => 'State', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="zip_code">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_zip_code')) ? $this->lang->line('label_zip_code') : 'Zip Code'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'zip_code', 'placeholder' => 'Zip Code', 'class' => 'form-control', 'type' => 'number']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="country">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_country')) ? $this->lang->line('label_country') : 'Country'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'country', 'placeholder' => 'Country', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?= form_open('auth/edit_user', 'id="modal-edit-user-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_first_name')) ? $this->lang->line('label_first_name') : 'First Name'; ?></label>
                        <div class="input-group">
                            <input name="id" type="hidden" id="id" value="">
                            <?= form_input(['name' => 'first_name', 'placeholder' => 'First Name', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_last_name')) ? $this->lang->line('label_last_name') : 'Last Name'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'last_name', 'placeholder' => 'Last Name', 'class' => 'form-control']) ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_password')) ? $this->lang->line('label_password') : 'Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password', 'placeholder' => 'Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_confirm_password')) ? $this->lang->line('label_confirm_password') : 'Confirm Password'; ?></label>
                        <div class="input-group">
                            <?= form_input(['name' => 'password_confirm', 'placeholder' => 'Confirm Password', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
            </div>
            </form>
            <?php require_once(APPPATH . 'views/super-admin/include-footer.php'); ?>
        </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>

    <!-- Page Specific JS File -->

    <script src="<?= base_url('assets/backend/js/page/components-user-super.js'); ?>"></script>
</body>

</html>