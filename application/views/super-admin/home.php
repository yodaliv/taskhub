<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Home &mdash; <?= get_compnay_title(); ?></title>
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
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <div class="card-stats-title">Subscription Statistics
                                    </div>
                                    <div class="card-stats-items">
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $active_subscriptions ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $upcoming_subscriptions ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_upcoming')) ? $this->lang->line('label_upcoming') : 'Upcoming'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $expired_subscriptions ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_expired')) ? $this->lang->line('label_expired') : 'Expired'; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-primary">
                                    <i class="fas fa-align-center"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_total')) ? $this->lang->line('label_total') : 'Total'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $total_subscriptions ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <div class="card-stats-title">Packages Statistics
                                    </div>
                                    <div class="card-stats-items">
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $active_packages ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_active')) ? $this->lang->line('label_active') : 'Active'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $deactive_packages ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_deactive')) ? $this->lang->line('label_deactive') : 'De-active'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <div class="card-stats-item-count"><?= $subscribed_packages ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_subscribed')) ? $this->lang->line('label_subscribed') : 'Subscribed'; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-primary">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_total')) ? $this->lang->line('label_total') : 'Total'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $total_packages ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="card card-statistic-2">
                                <div class="card-stats">
                                    <div class="card-stats-title">Earning Statistics
                                    </div>
                                    <div class="card-stats-items">
                                        <div class="card-stats-item">
                                            <?php $today_earning = !empty($today_earning) ? $today_earning : 0 ?>
                                            <div class="card-stats-item-count"><?= get_currency_symbol() . ' ' . $today_earning ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_today')) ? $this->lang->line('label_today') : 'Today'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <?php $yesterday_earning = !empty($yesterday_earning) ? $yesterday_earning : 0 ?>
                                            <div class="card-stats-item-count"><?= get_currency_symbol() . ' ' . $yesterday_earning ?></div>
                                            <div class="card-stats-item-label"><?= !empty($this->lang->line('label_yesterday')) ? $this->lang->line('label_yesterday') : 'Yesterday'; ?></div>
                                        </div>
                                        <div class="card-stats-item">
                                            <?php $day_before_yesterday_earning = !empty($day_before_yesterday_earning) ? $day_before_yesterday_earning : 0 ?>
                                            <div class="card-stats-item-count"><?= get_currency_symbol() . ' ' . $day_before_yesterday_earning ?></div>
                                            <div class="card-stats-item-label"><?= date('d-M', strtotime("-2 days")) ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-icon shadow-primary bg-primary">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_total_earning')) ? $this->lang->line('label_total_earning') : 'Total Earning'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <?php $total_earning = !empty($total_earning) ? $total_earning : 0 ?>
                                            <?= get_currency_symbol() . ' ' . $total_earning ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12 d-none">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-mid-dark">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4><?= !empty($this->lang->line('label_total_earning')) ? $this->lang->line('label_total_earning') : 'Total Earning'; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <?php $total_earning = !empty($total_earning) ? $total_earning : 0 ?>
                                        <?= get_currency_symbol() . ' ' . $total_earning ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                            <div class="card">
                            <span class="badge badge-info">Reload the page to change graph colors</span>
                                <div class="card-header">
                                    <h4>Package wise earnings</h4><small>Earnings in (<?= get_currency_symbol() ?>)</small>
                                    
                                </div>
                                <div class="card-body">
                                    <canvas id="myChart" height="100"></canvas>
                                    <div class="statistic-details mt-sm-4">
                                        <div class="statistic-details-item">
                                            <?php $today_earning = !empty($today_earning) ? $today_earning : 0 ?>
                                            <div class="detail-value"><?= get_currency_symbol() . $today_earning ?></div>
                                            <div class="detail-name">Today's Earning</div>
                                        </div>
                                        <div class="statistic-details-item">
                                            <?php $week_earning = !empty($week_earning) ? $week_earning : 0 ?>
                                            <div class="detail-value"><?= get_currency_symbol() . $week_earning ?></div>
                                            <div class="detail-name">This Week's Earning</div>
                                        </div>
                                        <div class="statistic-details-item">
                                            <?php $month_earning = !empty($month_earning) ? $month_earning : 0 ?>
                                            <div class="detail-value"><?= get_currency_symbol() . $month_earning ?></div>
                                            <div class="detail-name">This Month's Earning</div>
                                        </div>
                                        <div class="statistic-details-item">
                                            <?php $year_earning = !empty($year_earning) ? $year_earning : 0 ?>
                                            <div class="detail-value"><?= get_currency_symbol() . $year_earning ?></div>
                                            <div class="detail-name">This Year's Earning</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Pie Chart</h4><small>Earnings in (<?= get_currency_symbol() ?>)</small>
                                </div>
                                <div class="card-body">
                                    <canvas id="piechart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4><?= !empty($this->lang->line('label_earning_summary')) ? $this->lang->line('label_earning_summary') : 'Earning summary'; ?></h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="earning_chart"></canvas>
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
<?php $titles = [];
$earnings = [];
$bg_colors = [];
$ran = array('#63ed7a', '#ffa426', '#fc544b', '#6777ef', '#FF00FF','#53ff1a','#ff3300','#0000ff','#00ffff','#99ff33','#003366','#cc3300','#ffcc00','#ff00ff','#ff9900','#3333cc','#ffff00');
$backgroundColor = array_rand($ran);
$d = $ran[$backgroundColor];
foreach ($package_wise_earnings as $package_wise_earning) {
    $amount = !empty($package_wise_earning['amount']) ? $package_wise_earning['amount'] : 0;
    array_push($titles, "'" . $package_wise_earning['title'] . "'");
    array_push($earnings, $amount);
    $k = array_rand($ran);
    $v = $ran[$k];
    array_push($bg_colors, "'" . $v . "'");
}
$titles = implode(",", $titles);
$earnings = implode(",", $earnings);
?>
<?php $dates = [];
$amounts = [];

foreach ($earning_summary as $summary) {
    $amount = !empty($summary['total_sale']) ? $summary['total_sale'] : 0;
    array_push($dates, "'" . $summary['purchase_date'] . "'");
    array_push($amounts, $amount);
    
}
$bg_colors = implode(",", $bg_colors);
$dates = implode(",", $dates);
$amounts = implode(",", $amounts);
$currency_symbol = get_currency_symbol();
?>
<script>
    var labels = [<?= $titles ?>];
    var data = [<?= $earnings ?>];
    var dates = [<?= $dates ?>];
    var amounts = [<?= $amounts ?>];
    var bg_colors = [<?= $bg_colors ?>];
    var bg_color = <?= "'" . $d . "'" ?>;
    currency_symbol = <?= "'$currency_symbol'"; ?>;
</script>
<script src="<?= base_url('assets/backend/js/page/components-dashboard.js'); ?>"></script>

</html>