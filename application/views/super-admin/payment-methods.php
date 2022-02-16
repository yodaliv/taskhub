<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Payment methods &mdash; <?= get_compnay_title(); ?></title>
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
            <?php
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
            $paypal_payment_method = get_system_settings('paypal_payment_method');
            $paypal_payment_method = json_decode($paypal_payment_method[0]['data'], true);

            $razorpay_payment_method = get_system_settings('razorpay_payment_method');
            $razorpay_payment_method = json_decode($razorpay_payment_method[0]['data'], true);

            $stripe_payment_method = get_system_settings('stripe_payment_method');
            $stripe_payment_method = json_decode($stripe_payment_method[0]['data'], true);
            ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= !empty($this->lang->line('label_payment_methods')) ? $this->lang->line('label_payment_methods') : 'Payment methods'; ?></h1>
                    </div>
                    <div class="section-body">
                        <form action="<?= base_url('super-admin/payment-methods/update') ?>" id="payment_methods_form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6><label><?= !empty($this->lang->line('label_paypal_payments')) ? $this->lang->line('label_paypal_payments') : 'Paypal payments'; ?></label></h6>
                                        <hr>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_payment_mode')) ? $this->lang->line('label_payment_mode') : 'Payment mode'; ?></label>
                                        <select name="paypal_mode" class="form-control">
                                            <option value=""><?= !empty($this->lang->line('label_select_mode')) ? $this->lang->line('label_select_mode') : 'Select mode'; ?> </option>
                                            <option value="sandbox" <?= (isset($paypal_payment_method['paypal_mode']) && $paypal_payment_method['paypal_mode'] == 'sandbox') ? "selected" : "" ?>>Sandbox ( Testing )</option>
                                            <option value="production" <?= (isset($paypal_payment_method['paypal_mode']) && $paypal_payment_method['paypal_mode'] == 'production') ? "selected" : "" ?>>Production ( Live )</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Currency Code <small>[ PayPal supported ]</small> <a href="https://developer.paypal.com/docs/api/reference/currency-codes/" target="_BLANK"><i class="fa fa-link"></i></a></label>
                                        <select name="currency_code" class="form-control">
                                            <option value="">Select Currency Code </option>
                                            <option value="INR" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'INR') ? "selected" : "" ?>>Indian rupee </option>
                                            <option value="AUD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'AUD') ? "selected" : "" ?>>Australian dollar </option>
                                            <option value="BRL" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'BRL') ? "selected" : "" ?>>Brazilian real </option>
                                            <option value="CAD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'CAD') ? "selected" : "" ?>>Canadian dollar </option>
                                            <option value="CNY" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'CNY') ? "selected" : "" ?>>Chinese Renmenbi </option>
                                            <option value="CZK" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'CZK') ? "selected" : "" ?>>Czech koruna </option>
                                            <option value="DKK" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'DKK') ? "selected" : "" ?>>Danish krone </option>
                                            <option value="EUR" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'EUR') ? "selected" : "" ?>>Euro </option>
                                            <option value="HKD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'HKD') ? "selected" : "" ?>>Hong Kong dollar </option>
                                            <option value="HUF" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'HUF') ? "selected" : "" ?>>Hungarian forint </option>
                                            <option value="ILS" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'ILS') ? "selected" : "" ?>>Israeli new shekel </option>
                                            <option value="JPY" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'JPY') ? "selected" : "" ?>>Japanese yen </option>
                                            <option value="MYR" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'MYR') ? "selected" : "" ?>>Malaysian ringgit </option>
                                            <option value="MXN" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'MXN') ? "selected" : "" ?>>Mexican peso </option>
                                            <option value="TWD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'TWD') ? "selected" : "" ?>>New Taiwan dollar </option>
                                            <option value="NZD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'NZD') ? "selected" : "" ?>>New Zealand dollar </option>
                                            <option value="NOK" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'NOK') ? "selected" : "" ?>>Norwegian krone </option>
                                            <option value="PHP" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'PHP') ? "selected" : "" ?>>Philippine peso </option>
                                            <option value="PLN" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'PLN') ? "selected" : "" ?>>Polish złoty </option>
                                            <option value="GBP" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'GBP') ? "selected" : "" ?>>Pound sterling </option>
                                            <option value="RUB" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'RUB') ? "selected" : "" ?>>Russian ruble </option>
                                            <option value="SGD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'SGD') ? "selected" : "" ?>>Singapore dollar </option>
                                            <option value="SEK" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'SEK') ? "selected" : "" ?>>Swedish krona </option>
                                            <option value="CHF" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'CHF') ? "selected" : "" ?>>Swiss franc </option>
                                            <option value="THB" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'THB') ? "selected" : "" ?>>Thai baht </option>
                                            <option value="USD" <?= (isset($paypal_payment_method['currency_code']) && $paypal_payment_method['currency_code'] == 'USD') ? "selected" : "" ?>>United States dollar </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_paypal_business_email')) ? $this->lang->line('label_paypal_business_email') : 'Paypal business email'; ?></label>
                                        <input type="text" class="form-control" name="business_email" placeholder="Paypal business email" value="<?= isset($paypal_payment_method['business_email']) && !empty($paypal_payment_method['business_email']) ? $paypal_payment_method['business_email'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Notification URL <small>(Set this as IPN notification URL in you PayPal account)</small></label>
                                        <input type="text" class="form-control" name="notification_url" value="<?= base_url("webhooks/paypal-webhook"); ?>" placeholder="Paypal IPN notification URL" disabled />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="status" id="active" value="1" class="selectgroup-input" <?= (isset($paypal_payment_method['status']) && $paypal_payment_method['status'] == 1) ? "checked" : "" ?>>
                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="status" id="deactive" value="0" class="selectgroup-input" <?= (isset($paypal_payment_method['status']) && $paypal_payment_method['status'] == 0) ? "checked" : "" ?>>
                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'Deactive'; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6><label><?= !empty($this->lang->line('label_razorpay_payments')) ? $this->lang->line('label_razorpay_payments') : 'Razorpay payments'; ?></label></h6>
                                        <hr>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_razorpay_key')) ? $this->lang->line('label_razorpay_key') : 'Razorpay key ID'; ?></label>
                                        <input type="text" class="form-control" name="razorpay_key_id" placeholder="Razorpay Key ID" value="<?= (isset($razorpay_payment_method['razorpay_key_id'])) && !empty($razorpay_payment_method['razorpay_key_id']) ? $razorpay_payment_method['razorpay_key_id'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_razorpay_secret_key')) ? $this->lang->line('label_razorpay_secret_key') : 'Razorpay Secret Key'; ?></label>
                                        <input type="text" class="form-control" name="razorpay_secret_key" placeholder="Razorpay Secret Key" value="<?= (isset($razorpay_payment_method['razorpay_secret_key'])) && !empty($razorpay_payment_method['razorpay_secret_key']) ? $razorpay_payment_method['razorpay_secret_key'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="razorpay_status" id="active" value="1" class="selectgroup-input" <?= (isset($razorpay_payment_method['razorpay_status']) && $razorpay_payment_method['razorpay_status'] == 1) ? "checked" : "" ?>>
                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="razorpay_status" id="deactive" value="0" class="selectgroup-input" <?= (isset($razorpay_payment_method['razorpay_status']) && $razorpay_payment_method['razorpay_status'] == 0) ? "checked" : "" ?>>
                                                <span class="selectgroup-button"><?= !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'Deactive'; ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6><label><?= !empty($this->lang->line('label_stripe_payments')) ? $this->lang->line('label_stripe_payments') : 'Stripe payments'; ?></label></h6>
                                        <hr>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_payment_mode')) ? $this->lang->line('label_payment_mode') : 'Payment mode'; ?></label>
                                        <select name="stripe_payment_mode" class="form-control">
                                            <option value=""><?= !empty($this->lang->line('label_select_mode')) ? $this->lang->line('label_select_mode') : 'Select mode'; ?> </option>
                                            <option value="test" <?= (isset($stripe_payment_method['stripe_payment_mode']) && $stripe_payment_method['stripe_payment_mode'] == 'test') ? "selected" : "" ?>>Test</option>
                                            <option value="live" <?= (isset($stripe_payment_method['stripe_payment_mode']) && $stripe_payment_method['stripe_payment_mode'] == 'live') ? "selected" : "" ?>>Live</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Currency Code <small>[ Stripe supported ]</small> <a href="https://stripe.com/docs/currencies" target="_BLANK"><i class="fa fa-link"></i></a></label>
                                        <select name="stripe_currency_code" class="form-control">
                                            <option value="">Select Currency Code </option>
                                            <option value="INR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'INR') ? "selected" : "" ?>>Indian rupee </option>
                                            <option value="USD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'USD') ? "selected" : "" ?>>United States dollar </option>
                                            <option value="AED" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AED') ? "selected" : "" ?>>United Arab Emirates Dirham </option>
                                            <option value="AFN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AFN') ? "selected" : "" ?>>Afghan Afghani </option>
                                            <option value="ALL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ALL') ? "selected" : "" ?>>Albanian Lek </option>
                                            <option value="AMD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AMD') ? "selected" : "" ?>>Armenian Dram </option>
                                            <option value="ANG" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ANG') ? "selected" : "" ?>>Netherlands Antillean Guilder </option>
                                            <option value="AOA" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AOA') ? "selected" : "" ?>>Angolan Kwanza </option>
                                            <option value="ARS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ARS') ? "selected" : "" ?>>Argentine Peso</option>
                                            <option value="AUD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AUD') ? "selected" : "" ?>> Australian Dollar</option>
                                            <option value="AWG" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AWG') ? "selected" : "" ?>> Aruban Florin</option>
                                            <option value="AZN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'AZN') ? "selected" : "" ?>> Azerbaijani Manat </option>
                                            <option value="BAM" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BAM') ? "selected" : "" ?>> Bosnia-Herzegovina Convertible Mark </option>
                                            <option value="BBD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BBD') ? "selected" : "" ?>> Bajan dollar </option>
                                            <option value="BDT" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BDT') ? "selected" : "" ?>> Bangladeshi Taka</option>
                                            <option value="BGN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BGN') ? "selected" : "" ?>> Bulgarian Lev </option>
                                            <option value="BIF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BIF') ? "selected" : "" ?>>Burundian Franc</option>
                                            <option value="BMD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BMD') ? "selected" : "" ?>> Bermudan Dollar</option>
                                            <option value="BND" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BND') ? "selected" : "" ?>> Brunei Dollar </option>
                                            <option value="BOB" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BOB') ? "selected" : "" ?>> Bolivian Boliviano </option>
                                            <option value="BRL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BRL') ? "selected" : "" ?>> Brazilian Real </option>
                                            <option value="BSD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BSD') ? "selected" : "" ?>> Bahamian Dollar </option>
                                            <option value="BWP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BWP') ? "selected" : "" ?>> Botswanan Pula </option>
                                            <option value="BZD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'BZD') ? "selected" : "" ?>> Belize Dollar </option>
                                            <option value="CAD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CAD') ? "selected" : "" ?>> Canadian Dollar </option>
                                            <option value="CDF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CDF') ? "selected" : "" ?>> Congolese Franc </option>
                                            <option value="CHF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CHF') ? "selected" : "" ?>> Swiss Franc </option>
                                            <option value="CLP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CLP') ? "selected" : "" ?>> Chilean Peso </option>
                                            <option value="CNY" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CNY') ? "selected" : "" ?>> Chinese Yuan </option>
                                            <option value="COP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'COP') ? "selected" : "" ?>> Colombian Peso </option>
                                            <option value="CRC" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CRC') ? "selected" : "" ?>> Costa Rican Colón </option>
                                            <option value="CVE" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CVE') ? "selected" : "" ?>> Cape Verdean Escudo </option>
                                            <option value="CZK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'CZK') ? "selected" : "" ?>> Czech Koruna </option>
                                            <option value="DJF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'DJF') ? "selected" : "" ?>> Djiboutian Franc </option>
                                            <option value="DKK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'DKK') ? "selected" : "" ?>> Danish Krone </option>
                                            <option value="DOP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'DOP') ? "selected" : "" ?>> Dominican Peso </option>
                                            <option value="DZD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'DZD') ? "selected" : "" ?>> Algerian Dinar </option>
                                            <option value="EGP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'EGP') ? "selected" : "" ?>> Egyptian Pound </option>
                                            <option value="ETB" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ETB') ? "selected" : "" ?>> Ethiopian Birr </option>
                                            <option value="EUR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'EUR') ? "selected" : "" ?>> Euro </option>
                                            <option value="FJD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'FJD') ? "selected" : "" ?>> Fijian Dollar </option>
                                            <option value="FKP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'FKP') ? "selected" : "" ?>> Falkland Island Pound </option>
                                            <option value="GBP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GBP') ? "selected" : "" ?>> Pound sterling </option>
                                            <option value="GEL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GEL') ? "selected" : "" ?>> Georgian Lari </option>
                                            <option value="GIP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GIP') ? "selected" : "" ?>> Gibraltar Pound </option>
                                            <option value="GMD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GMD') ? "selected" : "" ?>> Gambian dalasi </option>
                                            <option value="GNF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GNF') ? "selected" : "" ?>> Guinean Franc </option>
                                            <option value="GTQ" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GTQ') ? "selected" : "" ?>> Guatemalan Quetzal </option>
                                            <option value="GYD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'GYD') ? "selected" : "" ?>> Guyanaese Dollar </option>
                                            <option value="HKD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'HKD') ? "selected" : "" ?>> Hong Kong Dollar </option>
                                            <option value="HNL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'HNL') ? "selected" : "" ?>> Honduran Lempira </option>
                                            <option value="HRK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'HRK') ? "selected" : "" ?>> Croatian Kuna </option>
                                            <option value="HTG" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'HTG') ? "selected" : "" ?>> Haitian Gourde </option>
                                            <option value="HUF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'HUF') ? "selected" : "" ?>> Hungarian Forint </option>
                                            <option value="IDR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'IDR') ? "selected" : "" ?>> Indonesian Rupiah </option>
                                            <option value="ILS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ILS') ? "selected" : "" ?>> Israeli New Shekel </option>
                                            <option value="ISK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ISK') ? "selected" : "" ?>> Icelandic Króna </option>
                                            <option value="JMD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'JMD') ? "selected" : "" ?>> Jamaican Dollar </option>
                                            <option value="JPY" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'JPY') ? "selected" : "" ?>> Japanese Yen </option>
                                            <option value="KES" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KES') ? "selected" : "" ?>> Kenyan Shilling </option>
                                            <option value="KGS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KGS') ? "selected" : "" ?>> Kyrgystani Som </option>
                                            <option value="KHR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KHR') ? "selected" : "" ?>> Cambodian riel </option>
                                            <option value="KMF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KMF') ? "selected" : "" ?>> Comorian franc </option>
                                            <option value="KRW" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KRW') ? "selected" : "" ?>> South Korean won </option>
                                            <option value="KYD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KYD') ? "selected" : "" ?>> Cayman Islands Dollar </option>
                                            <option value="KZT" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'KZT') ? "selected" : "" ?>> Kazakhstani Tenge </option>
                                            <option value="LAK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'LAK') ? "selected" : "" ?>> Laotian Kip </option>
                                            <option value="LBP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'LBP') ? "selected" : "" ?>> Lebanese pound </option>
                                            <option value="LKR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'LKR') ? "selected" : "" ?>> Sri Lankan Rupee </option>
                                            <option value="LRD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'LRD') ? "selected" : "" ?>> Liberian Dollar </option>
                                            <option value="LSL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'LSL') ? "selected" : "" ?>>Lesotho loti </option>
                                            <option value="MAD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MAD') ? "selected" : "" ?>> Moroccan Dirham </option>
                                            <option value="MDL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MDL') ? "selected" : "" ?>> Moldovan Leu </option>
                                            <option value="MGA" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MGA') ? "selected" : "" ?>> Malagasy Ariary </option>
                                            <option value="MKD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MKD') ? "selected" : "" ?>> Macedonian Denar </option>
                                            <option value="MMK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MMK') ? "selected" : "" ?>> Myanmar Kyat </option>
                                            <option value="MNT" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MNT') ? "selected" : "" ?>> Mongolian Tugrik </option>
                                            <option value="MOP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MOP') ? "selected" : "" ?>> Macanese Pataca </option>
                                            <option value="MRO" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MRO') ? "selected" : "" ?>> Mauritanian Ouguiya </option>
                                            <option value="MUR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MUR') ? "selected" : "" ?>> Mauritian Rupee</option>
                                            <option value="MVR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MVR') ? "selected" : "" ?>> Maldivian Rufiyaa </option>
                                            <option value="MWK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MWK') ? "selected" : "" ?>> Malawian Kwacha </option>
                                            <option value="MXN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MXN') ? "selected" : "" ?>> Mexican Peso </option>
                                            <option value="MYR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MYR') ? "selected" : "" ?>> Malaysian Ringgit </option>
                                            <option value="MZN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'MZN') ? "selected" : "" ?>> Mozambican metical </option>
                                            <option value="NAD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NAD') ? "selected" : "" ?>> Namibian dollar </option>
                                            <option value="NGN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NGN') ? "selected" : "" ?>> Nigerian Naira </option>
                                            <option value="NIO" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NIO') ? "selected" : "" ?>>Nicaraguan Córdoba </option>
                                            <option value="NOK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NOK') ? "selected" : "" ?>> Norwegian Krone </option>
                                            <option value="NPR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NPR') ? "selected" : "" ?>> Nepalese Rupee </option>
                                            <option value="NZD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'NZD') ? "selected" : "" ?>> New Zealand Dollar </option>
                                            <option value="PAB" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PAB') ? "selected" : "" ?>> Panamanian Balboa </option>
                                            <option value="PEN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PEN') ? "selected" : "" ?>> Sol </option>
                                            <option value="PGK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PGK') ? "selected" : "" ?>> Papua New Guinean Kina </option>
                                            <option value="PHP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PHP') ? "selected" : "" ?>>Philippine peso </option>
                                            <option value="PKR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PKR') ? "selected" : "" ?>> Pakistani Rupee </option>
                                            <option value="PLN" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PLN') ? "selected" : "" ?>> Poland złoty </option>
                                            <option value="PYG" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'PYG') ? "selected" : "" ?>> Paraguayan Guarani </option>
                                            <option value="QAR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'QAR') ? "selected" : "" ?>> Qatari Rial </option>
                                            <option value="RON" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'RON') ? "selected" : "" ?>>Romanian Leu </option>
                                            <option value="RSD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'RSD') ? "selected" : "" ?>> Serbian Dinar </option>
                                            <option value="RUB" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'RUB') ? "selected" : "" ?>> Russian Ruble </option>
                                            <option value="RWF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'RWF') ? "selected" : "" ?>> Rwandan franc </option>
                                            <option value="SAR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SAR') ? "selected" : "" ?>> Saudi Riyal </option>
                                            <option value="SBD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SBD') ? "selected" : "" ?>> Solomon Islands Dollar </option>
                                            <option value="SCR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SCR') ? "selected" : "" ?>>Seychellois Rupee </option>
                                            <option value="SEK" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SEK') ? "selected" : "" ?>> Swedish Krona </option>
                                            <option value="SGD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SGD') ? "selected" : "" ?>> Singapore Dollar </option>
                                            <option value="SHP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SHP') ? "selected" : "" ?>> Saint Helenian Pound </option>
                                            <option value="SLL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SLL') ? "selected" : "" ?>> Sierra Leonean Leone </option>
                                            <option value="SOS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SOS') ? "selected" : "" ?>>Somali Shilling </option>
                                            <option value="SRD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SRD') ? "selected" : "" ?>> Surinamese Dollar </option>
                                            <option value="STD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'STD') ? "selected" : "" ?>> Sao Tome Dobra </option>
                                            <option value="SZL" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'SZL') ? "selected" : "" ?>> Swazi Lilangeni </option>
                                            <option value="THB" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'THB') ? "selected" : "" ?>> Thai Baht </option>
                                            <option value="TJS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TJS') ? "selected" : "" ?>> Tajikistani Somoni </option>
                                            <option value="TOP" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TOP') ? "selected" : "" ?>> Tongan Paʻanga </option>
                                            <option value="TRY" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TRY') ? "selected" : "" ?>> Turkish lira </option>
                                            <option value="TTD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TTD') ? "selected" : "" ?>> Trinidad & Tobago Dollar </option>
                                            <option value="TWD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TWD') ? "selected" : "" ?>> New Taiwan dollar </option>
                                            <option value="TZS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'TZS') ? "selected" : "" ?>> Tanzanian Shilling </option>
                                            <option value="UAH" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'UAH') ? "selected" : "" ?>> Ukrainian hryvnia </option>
                                            <option value="UGX" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'UGX') ? "selected" : "" ?>> Ugandan Shilling </option>
                                            <option value="UYU" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'UYU') ? "selected" : "" ?>> Uruguayan Peso </option>
                                            <option value="UZS" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'UZS') ? "selected" : "" ?>> Uzbekistani Som </option>
                                            <option value="VND" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'VND') ? "selected" : "" ?>> Vietnamese dong </option>
                                            <option value="VUV" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'VUV') ? "selected" : "" ?>> Vanuatu Vatu </option>
                                            <option value="WST" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'WST') ? "selected" : "" ?>> Samoa Tala</option>
                                            <option value="XAF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'XAF') ? "selected" : "" ?>> Central African CFA franc </option>
                                            <option value="XCD" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'XCD') ? "selected" : "" ?>> East Caribbean Dollar </option>
                                            <option value="XOF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'XOF') ? "selected" : "" ?>> West African CFA franc </option>
                                            <option value="XPF" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'XPF') ? "selected" : "" ?>> CFP Franc </option>
                                            <option value="YER" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'YER') ? "selected" : "" ?>> Yemeni Rial </option>
                                            <option value="ZAR" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ZAR') ? "selected" : "" ?>> South African Rand </option>
                                            <option value="ZMW" <?= (isset($stripe_payment_method['stripe_currency_code']) && $stripe_payment_method['stripe_currency_code'] == 'ZMW') ? "selected" : "" ?>> Zambian Kwacha </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Endpoint URL <small>(Set this as IPN notification URL in you Stripe account)</small></label>
                                        <input type="text" class="form-control" name="stripe_webhook_url" value="<?= base_url("webhooks/stripe-webhook"); ?>" disabled />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Stripe Publishable Key</label>
                                        <input type="text" class="form-control" name="stripe_publishable_key" value="<?= isset($stripe_payment_method['stripe_publishable_key']) && !empty($stripe_payment_method['stripe_publishable_key']) ? $stripe_payment_method['stripe_publishable_key'] : '' ?>" placeholder="Stripe Publishable Key" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Stripe Secret Key</label>
                                        <input type="text" class="form-control" name="stripe_secret_key" value="<?= isset($stripe_payment_method['stripe_secret_key']) && !empty($stripe_payment_method['stripe_secret_key']) ? $stripe_payment_method['stripe_secret_key'] : '' ?>" placeholder="Stripe Secret Key" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Stripe Webhook Secret Key</label>
                                        <input type="text" class="form-control" name="stripe_webhook_secret_key" value="<?= isset($stripe_payment_method['stripe_webhook_secret_key']) && !empty($stripe_payment_method['stripe_webhook_secret_key']) ? $stripe_payment_method['stripe_webhook_secret_key'] : '' ?>" placeholder="Stripe Webhook Secret Key" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= !empty($this->lang->line('label_status')) ? $this->lang->line('label_status') : 'Status'; ?></label>
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="stripe_status" id="active" value="1" class="selectgroup-input" <?= (isset($stripe_payment_method['stripe_status']) && $stripe_payment_method['stripe_status'] == 1) ? "checked" : "" ?>>
                                                <span class="selectgroup-button">Active</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="stripe_status" id="deactive" value="0" class="selectgroup-input" <?= (isset($stripe_payment_method['stripe_status']) && $stripe_payment_method['stripe_status'] == 0) ? "checked" : "" ?>>
                                                <span class="selectgroup-button">Deactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-round btn-lg btn-primary" id="submit_button">
                                    <?= !empty($this->lang->line('label_save')) ? $this->lang->line('label_save') : 'Save'; ?>
                                </button>

                            </div>
                        </form>
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
<script src="<?= base_url('assets/backend/js/page/components-payment-methods.js'); ?>"></script>

</html>