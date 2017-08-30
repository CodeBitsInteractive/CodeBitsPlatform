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
                <!-- PAGE TITLE -->
                <div class="row">
                    <div class="col s12">
                        <div class="page-title"><?=$title?></div>
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
                
                <div class="row">
                    <?php for($i=0;$i<count($list);$i++): ?>
                        <div class="col m6 s12 l3" data-element="media_<?=$i?>">
                            <div class="card">
                                <div class="card-image">
                                    <img src="/media/<?=basename($list[$i])?>" alt="<?=basename($list[$i])?>">
                                    <span class="card-title"><?=basename($list[$i])?></span>
                                </div>
                                <div class="card-action">
                                    <a href="#!" data-action="getlink" data-file="<?=basename($list[$i])?>"><?=$this->lang->line('media_getlink')?></a>
                                    <a href="#!" data-action="remove" data-file="<?=basename($list[$i])?>" data-media="<?=$i?>"><?=$this->lang->line('media_remove')?></a>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <!-- CONTENT -->
            
            <!-- FIXED ACTION -->
            <div class="fixed-action-btn">
                <a id="create_media" class="btn-floating btn-large waves-effect waves-light red" href="#!"><i class="material-icons">add</i></a>
            </div>
            <!-- TUTORIAL -->
            <div class="tap-target" data-activates="create_media">
                <div class="tap-target-content">
                    <h5><?=$this->lang->line('media_editor_tutorial_title')?></h5>
                    <p><?=$this->lang->line('media_editor_tutorial_desc')?></p>
                </div>
            </div>
            <!-- TUTORIAL -->
            
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
        
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM
                // Получить ссылку
                $('a[data-action="getlink"]').on('click', function(e){
                    var _elem = $(this); // Элемент
                    var _file = _elem.attr('data-file'); // Файл
                    swal({
                        title: "<?=$this->lang->line('media_link_ready_title')?>",
                        text: "<b><?=$this->lang->line('media_link_ready_desc')?></b><br/><a href=\"http://<?=DOMAIN?>/media/"+_file+"\">/media/"+_file+"</a>",
                        html: true,
                        closeOnConfirm: true,
                        type: "success"
                    });
                    
                    // Отмена перехода
                    e.preventDefault();
                    return false;
                });
                
                // Удаление
                $('a[data-action="remove"]').on('click', function(e){
                    var _elem = $(this); // Элемент
                    var _file = _elem.attr('data-file'); // Файл
                    swal({ // Вызов модального окна
                        title: "<?=$this->lang->line('media_confirm_remove_title')?>",
                        text: "<?=$this->lang->line('media_confirm_remove_text')?>",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "<?=$this->lang->line('media_confirm_remove_yes')?>",
                        cancelButtonText: "<?=$this->lang->line('media_confirm_remove_no')?>",
                        closeOnConfirm: false,
                        closeOnCancel: false 
                    }, function(isConfirm){ // Результат диалога
                        if (isConfirm) { // Удалить
                            swal("<?=$this->lang->line('media_confirm_removed_title')?>", "<?=$this->lang->line('media_confirm_removed_desc')?>", "success");
                            $('div[data-element="media_'+_elem.attr('data-media')+'"]').remove(); // Удалить элемент
                            CBAPI.getInstance().call('admin.removeMedia',{media_file:_file},function(){},function(e){alert(e.message);});
                        } else { // Отмена
                            swal("<?=$this->lang->line('media_confirm_canceled_title')?>", "<?=$this->lang->line('media_confirm_canceled_desc')?>", "error");
                        }
                    });
                    
                    // Отмена перехода
                    e.preventDefault();
                    return false;
                });
                
                // Кнопка медиа-менеджера
                $('#create_media').media_manager({
                    enable_gallery: false, // Без галлереи
                    title: "<?=$this->lang->line('mm_title')?>",
                    upload_title: "<?=$this->lang->line('mm_upload')?>",
                    gallery_title: "<?=$this->lang->line('mm_from_gallery')?>",
                    on_hidden: function(){
                        var _url = $('#create_media').media_manager('getURL');
                        if(_url!=''){
                            document.location.reload();
                        }
                    }
                });
                
                // Получение обучения
                var _tutorial = <?=$tutorial?>; // Флаг обучения
                if(_tutorial==0){ // Нужно обучение
                    var tm = setTimeout(function(){
                        $('.tap-target').tapTarget('open');
                    }, 1500);
                }
            });
        </script>
        <!-- SCRIPTS -->
    </body>
</html>