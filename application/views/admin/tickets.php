<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Manage Tickets &mdash; <?= get_admin_company_title($this->data['admin_id']); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_manage_tickets')) ? $this->lang->line('label_manage_tickets') : 'Manage tickets' ?></h1>
                        <?php if (is_member() || is_client()) { ?>
                            <div class="section-header-breadcrumb">
                                <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-ticket">
                                    <?= !empty($this->lang->line('label_create_ticket')) ? $this->lang->line('label_create_ticket') : 'Create Ticket'; ?></i>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="modal-edit-ticket"></div>
                            <div class="modal-view-ticket"></div>
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='tickets_list' data-toggle="table" data-url="<?= base_url('admin/tickets/get_tickets_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="title" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "tickets-list"
                    }' data-query-params="queryParamss">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="workspace_id" data-visible="false" data-sortable="false"><?= !empty($this->lang->line('label_workspace_id')) ? $this->lang->line('label_workspace_id') : 'Workspace ID'; ?></th>
                                                    <th data-field="ticket_type_id" data-visible="false" data-sortable="false"><?= !empty($this->lang->line('label_ticket_type_id')) ? $this->lang->line('label_ticket_type_id') : 'Ticket type ID'; ?></th>
                                                    <th data-field="user_id" data-visible="false" data-sortable="false"><?= !empty($this->lang->line('label_user_id')) ? $this->lang->line('label_user_id') : 'User ID'; ?></th>
                                                    <th data-field="ticket_type" data-sortable="false"><?= !empty($this->lang->line('label_ticket_type')) ? $this->lang->line('label_ticket_type') : 'Ticket type'; ?></th>
                                                    <th data-field="username" data-sortable="true"><?= !empty($this->lang->line('label_created_by')) ? $this->lang->line('label_created_by') : 'Created by'; ?></th>
                                                    <th data-field="users" data-sortable="false"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>
                                                    <th data-field="clients" data-sortable="false"><?= !empty($this->lang->line('label_clients')) ? $this->lang->line('label_clients') : 'Clients'; ?></th>
                                                    <th data-field="subject" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?></th>
                                                    <th data-field="email" data-sortable="true"><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></th>
                                                    <th data-field="description" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></th>
                                                    <th data-field="status" data-sortable="true"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <th data-field="last_updated" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_last_updated')) ? $this->lang->line('label_last_updated') : 'Last updated'; ?></th>
                                                    <th data-field="date_created" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_date_created')) ? $this->lang->line('label_date_created') : 'Date created'; ?></th>
                                                    <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="ticket_id" id="ticket_id">
                        <input type="hidden" name="type" id="type">
                        <div class="modal-ticket-users"></div>
                        <div class="row" id="modal-ticket-users-part">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">
                                        <table class='table-striped' id='ticket_users_list' data-toggle="table" data-url="<?= base_url('admin/tickets/get_ticket_participants_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams2">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="assigned" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                                                    <?php if ($this->ion_auth->is_admin()) { ?>
                                                        <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <?php } ?>

                                                    <?php if ($this->ion_auth->is_admin()) { ?>
                                                        <th data-field="action" data-sortable="false"><?= !empty($this->lang->line('label_action')) ? $this->lang->line('label_action') : 'Action'; ?></th>
                                                    <?php } ?>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-ticket-clients"></div>
                        <div class="row" id="modal-ticket-clients-part">
                            <div class='col-md-12'>
                                <div class="card">
                                    <div class="card-body">

                                        <table class='table-striped' id='ticket_clients_list' data-toggle="table" data-url="<?= base_url('admin/tickets/get_ticket_participants_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="false" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="first_name" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "users-list",
                      "ignoreColumn": ["state"] 
                    }' data-query-params="queryParams2">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-visible="false" data-sortable="true"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>

                                                    <th data-field="first_name" data-sortable="true"><?= !empty($this->lang->line('label_users')) ? $this->lang->line('label_users') : 'Users'; ?></th>

                                                    <th data-field="role" data-sortable="false"><?= !empty($this->lang->line('label_role')) ? $this->lang->line('label_role') : 'Role'; ?></th>
                                                    <th data-field="assigned" data-sortable="false"><?= !empty($this->lang->line('label_assigned')) ? $this->lang->line('label_assigned') : 'Assigned'; ?></th>
                                                    <?php if ($this->ion_auth->is_admin()) { ?>
                                                        <th data-field="active" data-sortable="false"><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></th>
                                                    <?php } ?>

                                                    <?php if ($this->ion_auth->is_admin()) { ?>
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
            <?php if (is_member() || is_client()) { ?>
                <?= form_open('admin/tickets/create_ticket', 'id="modal-add-ticket-part"', 'class="modal-part"'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_ticket_type')) ? $this->lang->line('label_ticket_type') : 'Ticket Type'; ?></label><span class="asterisk"> *</span>
                            <select class="form-control select2" name="ticket_type_id">
                                <option value="" selected>Choose...</option>
                                <?php foreach ($ticket_types as $ticket_type) {
                                ?>
                                    <option value="<?= $ticket_type['id'] ?>"><?= $ticket_type['title'] ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <?= form_input(['name' => 'subject', 'placeholder' => 'Subject', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label><span class="asterisk"> *</span>
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                            <textarea type="textarea" class="form-control" placeholder="description" name="description" id="description"></textarea>

                        </div>
                        <div class="form-group">
                            <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                            <select class="form-control select2" multiple="" name="users[]">
                                <?php foreach ($all_user as $all_users) {
                                    if (!is_client($all_users->id)) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                            <select name="clients[]" class="form-control select2" multiple="">
                                <?php foreach ($all_user as $all_users) {
                                    if (is_client($all_users->id)) { ?>
                                        <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                </form>
            <?php } ?>


            <?= form_open('admin/tickets/edit_ticket', 'id="modal-edit-ticket-part"', 'class="modal-part"'); ?>
            <input type="hidden" name="id" id="update_id">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_ticket_type')) ? $this->lang->line('label_ticket_type') : 'Ticket Type'; ?></label><span class="asterisk"> *</span>
                        <select class="form-control select2" name="ticket_type_id" id="ticket_type_id">
                            <option value="" selected>Choose...</option>
                            <?php foreach ($ticket_types as $ticket_type) {
                            ?>
                                <option value="<?= $ticket_type['id'] ?>"><?= $ticket_type['title'] ?></option>
                            <?php
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_subject')) ? $this->lang->line('label_subject') : 'Subject'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['name' => 'subject', 'id' => 'subject', 'placeholder' => 'Subject', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_email')) ? $this->lang->line('label_email') : 'Email'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <input type="email" name="email" id="emaill" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_description')) ? $this->lang->line('label_description') : 'Description'; ?></label><span class="asterisk"> *</span>
                        <textarea type="textarea" class="form-control" placeholder="description" name="description" id="edit_ticket_description"></textarea>

                    </div>
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_select_users')) ? $this->lang->line('label_select_users') : 'Select Users'; ?></label>
                        <select class="form-control select2" multiple="" name="users[]" id="update_users">
                            <?php foreach ($all_user as $all_users) {
                                if (!is_client($all_users->id)) { ?>
                                    <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clients"><?= !empty($this->lang->line('label_select_clients')) ? $this->lang->line('label_select_clients') : 'Select Clients'; ?></label>
                        <select id="update_clients" name="clients[]" class="form-control select2" multiple="">
                            <?php foreach ($all_user as $all_users) {
                                if (is_client($all_users->id)) { ?>
                                    <option value="<?= $all_users->id ?>"><?= $all_users->first_name ?> <?= $all_users->last_name ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
            </form>
            <!-- <div id="modal-view-ticket-part" class="modal-part"> -->
            <form method="POST" id="modal-view-ticket-part" class="modal-part">
                <div class="row">
                    <div class="col-md-12">
                        <hr>

                        <div class="row">
                        <div class="col-md-12">
                                <p><b>Subject : <span id="subjectt"></span></b></p>
                            </div>
                            <div class="col-md-5">
                                <p><b>Type : <span id="ticket_type"></span></b></p>
                            </div>
                            

                            <div class="col-md-4">
                                <p><b>Created on : <span id="date_createdd"></span></b></p>
                            </div>
                            <?php if (is_member() || is_client()) { ?>
                                <div class="col-md-3">
                                    <p><b>Status : <span id="status"></span></b></p>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12">
                                    <select class="form-control w-100 change_ticket_status">
                                        <option value="">Change Ticket Status</option>
                                        <option value=<?= PENDING ?>>PENDING</option>
                                        <option value=<?= OPENED ?>>OPEN</option>
                                        <option value=<?= RESOLVED ?>>RESOLVE</option>
                                        <option value=<?= CLOSED ?>>CLOSE</option>
                                        <option value=<?= REOPEN ?>>REOPEN</option>
                                    </select>

                                </div>
                            <?php } ?>



                            <?php
                            $offset = 0;
                            $limit = 15;
                            ?>


                            <div class="card-body">
                                <hr>
                                <div class="direct-chat-messages" id="element">
                                    <div class="ticket_msg" data-limit="<?= $limit ?>" data-offset="<?= $offset ?>" data-max-loaded="false">
                                    </div>
                                    <div class="scroll_div"></div>
                                </div>

                                <input type="hidden" name="ticket_id_" id="id">
                                <div class="input-group mt-5">
                                    <input type="text" name="message" id="msg" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-append">
                                        <div class="form-group">
                                            <a class="btn btn-primary text-white custom-btn" id="modal-upload-ticket-mediaa"> <i class="fa fa-paperclip"></i></a>
                                        </div>
                                        <div class="form-group"><button class="btn btn-primary custom-btn" id="ticket_send_msg_btn">Send</button></div>
                                    </span>
                                </div>
                                <div class="media_div" style="display: none;">

                                    <div class="author-box-name mb-4">
                                        <?= !empty($this->lang->line('label_upload_files')) ? $this->lang->line('label_upload_files') : 'Upload Files'; ?>
                                    </div>
                                    <div class="dropzone dz-clickable" id="ticket-files-dropzone">
                                        <div class="dz-default dz-message">
                                            <span>
                                                <?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop files here to upload'; ?>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                            </div>




                            <!-- <div class="card-footer"> -->
                            <!-- <form id="ticket_send_msg_form" enctype="multipart/form-data"> -->

                            <!-- <div id="modal-upload-media-part" class="modal-part"> -->
                            <!-- <div class="row"> -->


                            <!-- </div> -->
                            <!-- </div> -->

                            <!-- </form> -->
                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </form>


        </div>

        <!-- </form> -->
        <?php require_once(APPPATH . 'views/admin/include-footer.php'); ?>
    </div>
    </div>

    <?php require_once(APPPATH . 'views/include-js.php'); ?>

    <!-- Page Specific JS File -->
    <script>
        dictDefaultMessage = "<?= !empty($this->lang->line('label_drop_files_here_to_upload')) ? $this->lang->line('label_drop_files_here_to_upload') : 'Drop Files Here To Upload'; ?>";
    </script>
    <script src="<?= base_url('assets/backend/js/page/components-tickets.js'); ?>"></script>

</body>

</html>