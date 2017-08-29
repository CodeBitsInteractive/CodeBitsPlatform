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
                            <div class="card orange">
                                <div class="card-content white-text">
                                    <span class="card-title white-text"><?=$this->lang->line('fast_cache_title')?></span>
                                    <p><?=$this->lang->line('fast_cache_desc')?></p>
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
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_cache_enabled_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_cache_enabled')?></span>
                                    <span class="stats-counter <?=($this->config->line['system']['cache']['enabled'])?'green-text':'red-text'?>"><span><?=($this->config->line['system']['cache']['enabled'])?$this->lang->line('enabled'):$this->lang->line('disabled')?></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_cache_count_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_cache_count')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$count?></span><small><?=$this->lang->line('t_cache_count_postfix')?></small></span>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m12 l4">
                            <div class="card stats-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_cache_size_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_cache_size')?></span>
                                    <span class="stats-counter"><span class="counter"><?=$size?></span><small><?=$this->lang->line('t_media_postfix')?></small></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l12">
                            <div class="card invoices-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <ul>
                                            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('t_cache_files_help')?>"><i class="material-icons">help</i></a></li>
                                        </ul>
                                    </div>
                                    <span class="card-title"><?=$this->lang->line('t_cache_files')?></span>
                                    <table class="responsive-table bordered">
                                        <thead>
                                            <tr>
                                                <th data-field="name"><?=$this->lang->line('t_cache_file')?></th>
                                                <th data-field="action"><?=$this->lang->line('t_cache_action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0;$i<$list_count;$i++): ?>
                                            <tr>
                                                <td><?=basename($list[$i])?></td>
                                                <td><a href="/admin/removeCache/?file=<?=basename($list[$i])?>"><?=$this->lang->line('t_cache_remove')?></a></td>
                                            </tr>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-action">
                                    <a href="/admin/clearCache/"><?=$this->lang->line('t_clear_cache')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
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
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM                
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