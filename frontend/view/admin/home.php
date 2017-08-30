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
                            <div class="card purple darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title white-text"><?=$this->lang->line('welcome_to_cbp')?></span>
                                    <p><?=$this->lang->line('welcome_to_cbp_desc')?></p>
                                </div>
                                <div class="card-action">
                                    <a href="//platform.codebits.xyz/" target="_blank" class="white-text"><?=$this->lang->line('welcome_to_cbp_link')?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($today_analytics) && $today_analytics!==false): ?>
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_hosts_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_hosts')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$today_analytics['data']['hosts']?></span><small><?=$this->lang->line('t_hosts_postfix')?></small></span>
                                    <?php if(isset($prev_analytics) && $prev_analytics!==false): ?>
                                    <div class="percent-info <?=(($today_analytics['data']['hosts']-$prev_analytics['data']['hosts'])>0)?"green-text":"red-text"?>"><?=$today_analytics['data']['hosts']-$prev_analytics['data']['hosts']?> <i class="material-icons"><?=(($today_analytics['data']['hosts']-$prev_analytics['data']['hosts'])>0)?"trending_up":"trending_down"?></i></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_views_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_views')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$today_analytics['data']['views']?></span><small><?=$this->lang->line('t_views_postfix')?></small></span>
                                    <?php if(isset($prev_analytics) && $prev_analytics!==false): ?>
                                    <div class="percent-info <?=(($today_analytics['data']['views']-$prev_analytics['data']['views'])>0)?"green-text":"red-text"?>"><?=$today_analytics['data']['views']-$prev_analytics['data']['views']?> <i class="material-icons"><?=(($today_analytics['data']['views']-$prev_analytics['data']['views'])>0)?"trending_up":"trending_down"?></i></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_effectivity_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_effectivity')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$today_analytics['data']['effectivity']?></span><small><?=$this->lang->line('t_effectivity_postfix')?></small></span>
                                    <?php if(isset($prev_analytics) && $prev_analytics!==false): ?>
                                    <div class="percent-info <?=(($today_analytics['data']['effectivity']-$prev_analytics['data']['effectivity'])>0)?"green-text":"red-text"?>"><?=$today_analytics['data']['effectivity']-$prev_analytics['data']['effectivity']?> <i class="material-icons"><?=(($today_analytics['data']['effectivity']-$prev_analytics['data']['effectivity'])>0)?"trending_up":"trending_down"?></i></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_users_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_users')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$today_analytics['data']['registred_users']?></span><small><?=$this->lang->line('t_users_postfix')?></small></span>
                                    <?php if(isset($prev_analytics) && $prev_analytics!==false): ?>
                                    <div class="percent-info <?=(($today_analytics['data']['registred_users']-$prev_analytics['data']['registred_users'])>0)?"green-text":"red-text"?>"><?=$today_analytics['data']['registred_users']-$prev_analytics['data']['registred_users']?> <i class="material-icons"><?=(($today_analytics['data']['registred_users']-$prev_analytics['data']['registred_users'])>0)?"trending_up":"trending_down"?></i></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_online_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_online')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$online?></span><small><?=$this->lang->line('t_hosts_postfix')?></small></span>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_analytic_media_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_analytic_media')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$media_size?></span><small><?=$this->lang->line('t_media_postfix')?></small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
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

                <!-- FIXED FAB -->
                <div id="tutorial" class="fixed-action-btn vertical click-to-toggle" style="bottom: 45px; right: 24px;">
                    <a class="btn-floating btn-large purple darken-1">
                        <i class="material-icons">menu</i>
                    </a>
                    <ul>
                        <li><a href="/admin/editor/" class="btn-floating purple"><i class="material-icons">code</i></a></li>
                        <li><a href="/admin/pages/" class="btn-floating purple"><i class="material-icons">line_style</i></a></li>
                        <li><a href="/admin/media/" class="btn-floating purple"><i class="material-icons">photo</i></a></li>
                        <li><a href="/admin/analytics/" class="btn-floating purple"><i class="material-icons">timeline</i></a></li>
                        <li><a href="/admin/settings/" class="btn-floating purple"><i class="material-icons">settings</i></a></li>
                    </ul>
                </div>
                <!-- FIXED FAB -->
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
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM
                // Сообщение "Добро пожаловать"
                var _welcome_msg = <?=$show_welcome?>;
                if(_welcome_msg==1){ // Показывать сообщение
                    Materialize.toast("<?=$this->lang->line('welcome_to_cbp')?>", 4000);
                }
                
                // CounterUp Plugin
                $('.counter').each(function () {
                    $(this).prop('Counter',0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 3500,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                        }
                    });
                });
            });
        </script>
        <!-- SCRIPTS -->
    </body>
</html>