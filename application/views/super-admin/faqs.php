<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Faqs &mdash; <?= get_compnay_title(); ?></title>
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
                        <h1><?= !empty($this->lang->line('label_faqs')) ? $this->lang->line('label_faqs') : 'FAQs'; ?></h1>
                        <div class="section-header-breadcrumb">
                            <i class="btn btn-primary btn-rounded no-shadow" id="modal-add-faq">
                                <?= !empty($this->lang->line('label_add_faq')) ? $this->lang->line('label_add_faq') : 'Add FAQ'; ?></i>
                        </div>

                    </div>
                    <div class="section-body">
                        <div class="section-body">
                            <div class="row">
                                <div class='col-md-12'>
                                    <div class="card">
                                        <table class='table-striped' id='faqs_list' data-toggle="table" data-url="<?= base_url('super-admin/faqs/get_faq_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                      "fileName": "faq-list"
                    }' data-query-params="queryParams">
                                            <thead>
                                                <tr>
                                                    <th data-field="id" data-sortable="true" data-visible="false"><?= !empty($this->lang->line('label_id')) ? $this->lang->line('label_id') : 'ID'; ?></th>
                                                    <th data-field="question" data-sortable="true"><?= !empty($this->lang->line('label_question')) ? $this->lang->line('label_question') : 'Question'; ?></th>
                                                    <th data-field="answer" data-sortable="true"><?= !empty($this->lang->line('label_answer')) ? $this->lang->line('label_answer') : 'Answer'; ?></th>
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
            <?= form_open('super-admin/faqs/create', 'id="modal-add-faq-part"', 'class="modal-part"'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_question')) ? $this->lang->line('label_question') : 'Question'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['name' => 'question', 'placeholder' => 'Question', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_answer')) ? $this->lang->line('label_answer') : 'Answer'; ?></label><span class="asterisk"> *</span>
                        <div class="input-group">
                            <?= form_input(['name' => 'answer', 'id' => 'answer', 'placeholder' => 'Answer', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                </div>

            </div>
            </form>
            <div class="modal-edit-faq"></div>
            <?= form_open('super-admin/faqs/edit', 'id="modal-edit-faq-part"', 'class="modal-part"'); ?>
            <input name="id" type="hidden" id="id" value="">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_question')) ? $this->lang->line('label_question') : 'Question'; ?></label><span class="asterisk"> *</span>
                        <input type="text" name="question" id="edit_question" class="form-control" placeholder="Question">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_answer')) ? $this->lang->line('label_answer') : 'Answer'; ?></label><span class="asterisk"> *</span>
                        <input type="text" name="answer" id="edit_answer" class="form-control" placeholder="Answer">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" id="active" value="1" class="selectgroup-input">
                                <span class="selectgroup-button">Active</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="status" id="deactive" value="0" class="selectgroup-input">
                                <span class="selectgroup-button">Deactive</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            </form>
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
<script src="<?= base_url('assets/backend/js/page/components-faqs.js'); ?>"></script>

</html>