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
        <link href="/frontend/view/admin/assets/css/prettify.css" rel="stylesheet" type="text/css"/>
        <link href="/frontend/view/admin/assets/css/sweetalert.css" rel="stylesheet" type="text/css"/>
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
            <div class="mn-inner grid-showcase">
                <!-- MESSAGES -->
                <?php if($complete): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel green darken-1">
                            <span class="white-text"><?= $this->lang->line('noty_removed') ?></span>
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
                
                <!-- PAGES LIST -->
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel white darken-1">
                            <div class="card-content" style="padding: 0;">
                                <span class="card-title"><?=$title?></span>
                                <?php if(count($list)>0): ?>
                                <ul class="collection">
                                    <?php foreach($list as $notification): ?>
                                    <li class="collection-item avatar">
                                        <i class="material-icons circle purple"><?=$notification['icon']?></i>
                                        <span class="title"><?=date("d.m.Y H:i", $notification['time'])?></span>
                                        <p><?=$notification['text']?></p>
                                        <a href="#!" data-position="left" data-delay="50" data-tooltip="<?=$this->lang->line('h_noty_remove')?>" data-action="remove" data-uid="<?=$notification['uid']?>" class="secondary-content tooltipped"><i class="material-icons red-text">delete</i></a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <p><?= $this->lang->line("notifications_empty") ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(($page<$total && $total>1) || ($page!=1 && $total>1)): ?>
                    <div class="col s12">
                        <div class="card-panel white darken-1">
                            <div class="card-content" style="padding: 0;">
                                <div class="row">
                                    <div class="col s12">
                                        <?php if($page<$total && $total>1): ?>
                                        <a href="/admin/notifications/?nav=<?=($page+1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_more') ?></a>
                                        <?php endif?>
                                        <?php if($page!=1 && $total>1): ?>
                                        <a href="/admin/notifications/?nav=<?=($page-1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_prev') ?></a>
                                        <?php endif?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                </div>
                <!-- PAGES LIST -->
            </div>
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
        <script src="/frontend/view/admin/assets/js/prettify.js"></script>
        <script src="/frontend/view/admin/assets/js/sweetalert.js"></script>
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        <!-- SCRIPTS -->
        
        <!-- SCRIPT -->
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM    
                // Удаление
                $('a[data-action="remove"]').on('click', function(e){
                    var _elem = $(this); // Элемент
                    var _uid = _elem.attr('data-uid'); // UID
                    swal({ // Вызов модального окна
                        title: "<?=$this->lang->line('noty_confirm_remove_title')?>",
                        text: "<?=$this->lang->line('noty_confirm_remove_text')?>",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "<?=$this->lang->line('noty_confirm_remove_yes')?>",
                        cancelButtonText: "<?=$this->lang->line('noty_confirm_remove_no')?>",
                        closeOnConfirm: false,
                        closeOnCancel: false 
                    }, function(isConfirm){ // Результат диалога
                        if (isConfirm) { // Удалить
                            document.location.href="/admin/removeNotification/?uid="+_uid;
                        } else { // Отмена
                            swal("<?=$this->lang->line('noty_confirm_canceled_title')?>", "<?=$this->lang->line('noty_confirm_canceled_desc')?>", "error");
                        }
                    });
                    
                    // Отмена перехода
                    e.preventDefault();
                    return false;
                });
            });
        </script>
        <!-- SCRIPT -->
    </body>
</html>