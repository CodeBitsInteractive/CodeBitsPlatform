<!DOCTYPE html>
<html lang="<?=$this->lang->curr_lang?>">
    <head>
        <!-- BASE META -->
        <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$this->lang->line('application')?> » <?=$title?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?=$desc?>" />
	<meta name="author" content="CodeBits" />
        <meta name="generator" content="CodeBits Platform" />
        <meta name="robots" content="NOINDEX,NOFOLLOW" />
        <!-- BASE META -->
        
        <!-- ICONS -->
        <link rel="shortcut icon" href="/frontend/assets/img/favicon.ico" />
        <!-- ICONS -->
        
        <!-- THEME -->
	<?php include(FRONTEND.'/view/admin/parts/app_colors.php'); ?>
        <?php include(FRONTEND.'/view/admin/parts/base_styles.php'); ?>
        <link href="/frontend/view/admin/assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- THEME -->
        
        <!-- HTML5 SUPPORT -->
        <?php include(FRONTEND.'/view/admin/parts/html5.php'); ?>
        <!-- HTML5 SUPPORT -->
    </head>
    <body>
        <!-- PRELOADER -->
        <?php include(FRONTEND.'/view/admin/parts/preloader.php'); ?>
        <!-- PRELOADER -->
        
        <!-- CONTAINER -->
        <div class="mn-content fixed-sidebar">
            <!-- SIDEBAR -->
            <?php include(FRONTEND.'/view/admin/parts/sidebar.php'); ?>
            <!-- SIDEBAR -->
            
            <!-- CONTENT -->
            <main class="mn-inner inner-active-sidebar">
                <div class="middle-content">
                    <!-- PAGE TITLE -->
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="page-title"><?=$title?></div>
                        </div>
                        <div class="col s12 m6" style="text-align: right;">
                            <?php if($page<$total && $total>1): ?>
                            <a href="/admin/analytics/?nav=<?=($page+1)?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_analytics_next') ?></a>
                            <?php endif?>
                            <?php if($page!=1 && $total>1): ?>
                            <a href="/admin/analytics/?nav=<?=($page-1)?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_analytics_prev') ?></a>
                            <?php endif?>
                        </div>
                    </div>
                    <!-- PAGE TITLE -->

                    <!-- MESSAGES -->
                    <?php if($complete): ?>
                    <div class="row">
                        <div class="col s12">
                            <div class="card-panel green darken-1">
                                <span class="white-text"><?= $this->lang->line('save_complete') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($error!==false): ?>
                    <div class="row">
                        <div class="col s12">
                            <div class="card-panel red darken-1">
                                <span class="white-text"><?=$error?></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- MESSAGES -->
                    
                    <div class="row no-m-t no-m-b">
                        <div class="col s12">
                            <div class="card visitors-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_chart_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_chart')?><span class="secondary-title"><?=$this->lang->line('t_analytic_chart_help')?></span></span>
                                    <ul class="tabs tab-demo z-depth-1" style="width: 100%;">
                                        <li class="tab col s3"><a href="#atype1" class=""><?= $this->lang->line('t_analytics_tab_chart') ?></a></li>
                                        <li class="tab col s3"><a href="#atype2"><?= $this->lang->line('t_analytics_tab_list') ?></a></li>
                                    </ul>
                                    <div style="clear: both; height: 20px;"></div>
                                    <div id="atype1">
                                        <div id="flotchart1"></div>
                                        <div style="clear: both; height: 20px;"></div>
                                        <div class="row" style="text-align: center;">
                                            <div class="col s12 m4">
                                                <p><i class="material-icons" style="color: #388E3C;">timeline</i> - <?= $this->lang->line('t_analytic_hosts') ?></p>
                                            </div>
                                            <div class="col s12 m4">
                                                <p><i class="material-icons" style="color: #E65100;">timeline</i> - <?= $this->lang->line('t_analytic_views') ?></p>
                                            </div>
                                            <div class="col s12 m4">
                                                <p><i class="material-icons" style="color: #01579B;">timeline</i> - <?= $this->lang->line('t_analytic_effectivity') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="atype2">
                                        <table class="responsive-table bordered">
                                            <thead>
                                                <tr>
                                                    <th data-field="id"><?=$this->lang->line('t_last_visitors_id')?></th>
                                                    <th data-field="day"><?=$this->lang->line('t_analytics_day')?></th>
                                                    <th data-field="hosts"><?= $this->lang->line('t_analytic_hosts') ?></th>
                                                    <th data-field="views"><?= $this->lang->line('t_analytic_views') ?></th>
                                                    <th data-field="effect"><?= $this->lang->line('t_analytic_effectivity') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($list as $uud=>$date): ?>
                                                <tr>
                                                    <td><?=$date['uid']?></td>
                                                    <td><?=$date['day']?></td>
                                                    <td><?=number_format($date['data']['hosts'], 0)?> <?=$this->lang->line('t_hosts_postfix')?></td>
                                                    <td><?=number_format($date['data']['views'], 0)?> <?=$this->lang->line('t_views_postfix')?></td>
                                                    <td><?=number_format($date['data']['effectivity'], 0)?> <?=$this->lang->line('t_effectivity_postfix')?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(isset($last_visitors) && $last_visitors!==false): ?>
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l12">
                            <div class="card invoices-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_last_visitors_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_last_visitors')?></span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th data-field="id"><?=$this->lang->line('t_last_visitors_id')?></th>
                                            <th data-field="ip"><?=$this->lang->line('t_last_visitors_ip')?></th>
                                            <th data-field="login"><?=$this->lang->line('t_last_visitors_login')?></th>
                                            <th data-field="online"><?=$this->lang->line('t_last_visitors_online')?></th>
                                            <th data-field="url"><?=$this->lang->line('t_last_visitors_url')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($last_visitors as $uud=>$visitor): ?>
                                        <tr>
                                            <td><?=$visitor['uid']?></td>
                                            <td><?=$visitor['ip']?></td>
                                            <td><?=(isset($visitor['login']) && mb_strlen($visitor['login'])>0)?$visitor['login']:$this->lang->line('t_last_visitors_non_registred')?></td>
                                            <td><?=($visitor['time']>(time()-600))?'<i class="material-icons green-text" style="float: left; margin: 2px 5px 0 0;">visibility</i><span>'.$this->lang->line('t_last_visitors_status_online').'</span>':'<i class="material-icons red-text" style="float: left; margin: 2px 5px 0 0;">visibility</i><span>'.$this->lang->line('t_last_visitors_status_offline').date("d.m.Y H:i", $visitor['time']).'</span>'?></td>
                                            <td><span class="pie"><a href="<?=$visitor['data'][count($visitor['data'])-1]['url']?>" target="_blank"><?=$visitor['data'][count($visitor['data'])-1]['url']?></a></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
            <!-- CONTENT -->
            
            <div class="page-footer">
                <div class="footer-grid container">
                    <div class="footer-l white">&nbsp;</div>
                    <div class="footer-grid-l white">
                        <a class="footer-text" href="//codebits.xyz/" target="_blank">
                            <span class="direction">Developed by</span>
                            <div>CodeBits Interactive</div>
                        </a>
                    </div>
                    <div class="footer-r white">&nbsp;</div>
                    <div class="footer-grid-r white">
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER -->
        
        <!-- SCRIPTS -->
        <?php include(FRONTEND.'/view/admin/parts/base_scripts.php'); ?>
        <script src="/frontend/assets/js/flot.js"></script>
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        
        <script type="text/javascript">
            var flot1 = function () {
                var data = [];
                var data2 = [];
                var data3 = [];
                <?php foreach($list as $key=>$val): ?>
                data[<?=$key?>]=[<?=$key?>,<?=$val['data']['hosts']?>];
                data2[<?=$key?>]=[<?=$key?>,<?=$val['data']['views']?>];
                data3[<?=$key?>]=[<?=$key?>,<?=$val['data']['effectivity']?>];
                <?php endforeach; ?>
            var dataset =  [
                {
                    data: data,
                    color: "#388E3C",
                    lines: {
                        show: true,
                        fill: 0.4,
                    },
                    shadowSize: 0,
                }, {
                    data: data,
                    color: "#388E3C",
                    lines: {
                        show: false,
                    },
                    points: {
                        show: true,
                        fill: true,
                        radius: 4,
                        fillColor: "#fff",
                        lineWidth: 2
                    },
                    curvedLines: {
                        apply: false,
                    },
                    shadowSize: 0
                }, {
                    data: data2,
                    color: "#E65100",
                    lines: {
                        show: true,
                        fill: 0.4,
                    },
                    shadowSize: 0,
                },{
                    data: data2,
                    color: "#E65100",
                    lines: {
                        show: false,
                    },
                    curvedLines: {
                        apply: false,
                    },
                    points: {
                        show: true,
                        fill: true,
                        radius: 4,
                        fillColor: "#fff",
                        lineWidth: 2
                    },
                    shadowSize: 0
                }, {
                    data: data3,
                    color: "#01579B",
                    lines: {
                        show: true,
                        fill: 0.4,
                    },
                    shadowSize: 0,
                },{
                    data: data3,
                    color: "#01579B",
                    lines: {
                        show: false,
                    },
                    curvedLines: {
                        apply: false,
                    },
                    points: {
                        show: true,
                        fill: true,
                        radius: 4,
                        fillColor: "#fff",
                        lineWidth: 2
                    },
                    shadowSize: 0
                }
            ];

            var ticks = [];
            <?php foreach($list as $key=>$val): ?>
            ticks[<?=$key?>]=[<?=$key?>,"<?=$val['day']?>"];
            <?php endforeach; ?>

            var plot1 = $.plot("#flotchart1", dataset, {
                series: {
                    color: "#14D1BD",
                    lines: {
                        show: true,
                        fill: 0.2
                    },
                    shadowSize: 0,
                    curvedLines: {
                        apply: true,
                        active: true
                    }
                },
                xaxis: {
                    ticks: ticks,
                },
                legend: {
                    show: false
                },
                grid: {
                    color: "#AFAFAF",
                    hoverable: true,
                    borderWidth: 0,
                    backgroundColor: '#FFF'
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y",
                    defaultTheme: false
                }
            });
            
            function update() {
                plot1.draw();
                setTimeout(update, 2000);
            }

            update();
        };

        flot1();
        </script>
        <!-- SCRIPTS -->
    </body>
</html>