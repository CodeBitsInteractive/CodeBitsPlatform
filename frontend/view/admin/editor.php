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
        <link href="/frontend/view/admin/assets/css/codemirror.css" rel="stylesheet" type="text/css"/>
        <link href="/frontend/view/admin/assets/css/material-editor.css" rel="stylesheet" type="text/css"/>
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
                    <div class="col s12 m4 l3">
                        <div class="collection with-header">
                            <li class="collection-header" style="list-style-type: none;"><h5><?=$this->lang->line('choose_edit_type')?></h5></li>
                            <?php foreach($types as $key=>$val): ?>
                                <a href="/admin/editor/?type=<?=$val?>" class="collection-item <?=($val==$type)?'active':''?>"><?=$this->lang->line('nav_core_editor_line_'.($key+1))?></a>
                            <?php endforeach; ?>
                        </div>
                        <?php if($type!==false && $type!='bootstrap'): ?>
                        <div class="collection with-header">
                            <li class="collection-header" style="list-style-type: none;"><h5><?=$this->lang->line('choose_edit_module')?></h5></li>
                            <?php foreach($files as $key=>$val): ?>
                                <a href="/admin/editor/?type=<?=$type?>&filename=<?=basename($val)?>" class="collection-item <?=(basename($val)==$filename)?'active':''?>"><?=basename($val)?></a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if($code!==false): ?>
                    <div class="col s12 m8 l9">
                        <div class="card white">
                            <form id="code_man" role="form" action="/admin/saveComponent/" method="POST">
                                <input type="hidden" name="component_type" value="<?=$type?>" />
                                <input type="hidden" name="component_file" value="<?=$filename?>" />
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12">
                                            <h5 style="margin-top: 0;"><?=$filename?></h5>
                                            <p class="hide-on-small-only"><?=$path?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <textarea id="code_editor" name="component_code"><?=$code?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button class="waves-effect waves-light btn" type="submit"><i class="material-icons left">save</i><?=$this->lang->line('save')?></button>
                                    <a class="waves-effect waves-light btn red" href="/admin/editor/"><?=$this->lang->line('cancel')?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col s12 m8 l9">
                        <div class="card white">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12">
                                        <h5 style="margin-top: 0;"><?=$this->lang->line('component_get_started')?></h5>
                                        <p class="hide-on-small-only"><?=$this->lang->line('component_get_started_desc')?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <a class="waves-effect waves-light btn modal-trigger" href="#component_creation"><i class="material-icons left">add</i><?=$this->lang->line('create_component')?></a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- CONTENT -->
            
            <!-- FIXED ACTION -->
            <div class="fixed-action-btn">
                <a id="tutorial" class="btn-floating btn-large waves-effect waves-light red modal-trigger" href="#component_creation"><i class="material-icons">add</i></a>
            </div>
            
            <!-- TUTORIAL -->
            <div class="tap-target" data-activates="tutorial">
                <div class="tap-target-content">
                    <h5><?=$this->lang->line('component_editor_tutorial_title')?></h5>
                    <p><?=$this->lang->line('component_editor_tutorial_desc')?></p>
                </div>
            </div>
            <!-- TUTORIAL -->
            
            <!-- COMPONENT CREATION -->
            <div id="component_creation" class="modal modal-fixed-footer">
                <div class="modal-content">
                    <div class="row">
                        <div class="col s12">
                            <h4><?=$this->lang->line('component_creation')?></h4>
                            <p><?=$this->lang->line('component_creation_desc')?></p>
                        </div>
                    </div>
                    <form id="com_creator" role="form" action="/admin/createComponent/" method="POST">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="comname" name="component_name" type="text" class="validate" value="" maxlength="25">
                                <label for="comname"><?=$this->lang->line('component_name')?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <select name="component_type">
                                    <option value="" disabled selected><?=$this->lang->line('choose_edit_type')?></option>
                                    <?php foreach($types as $key=>$val): ?>
                                        <?php if($key!=0): ?>
                                        <option value="<?=$val?>"><?=$this->lang->line('nav_core_editor_line_'.($key+1))?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <p class="p-v-xs">
                                    <input type="checkbox" id="comtemplate" name="component_template" />
                                    <label for="comtemplate"><?=$this->lang->line('component_from_template')?></label>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action waves-effect waves-green btn-flat" data-action="create_component"><i class="material-icons left">save</i><?=$this->lang->line('create_component')?></a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat"><?=$this->lang->line('cancel')?></a>
                </div>
            </div>
            <!-- COMPONENT CREATION -->
            
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
        <script src="/frontend/view/admin/assets/js/codemirror.js"></script>
        <script src="/frontend/view/admin/assets/js/html.js"></script>
        <script src="/frontend/view/admin/assets/js/javascript.js"></script>
        <script src="/frontend/view/admin/assets/js/css.js"></script>
        <script src="/frontend/view/admin/assets/js/xml.js"></script>
        <script src="/frontend/view/admin/assets/js/clike.js"></script>
        <script src="/frontend/view/admin/assets/js/php.js"></script>
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        <!-- SCRIPTS -->
        
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM
                // Создание компонента
                $('a[data-action="create_component"]').click(function(){
                    $('select[name="component_type"]').material_select();
                    $('#com_creator').submit(); // Отправить форму
                });
                
                // Инициализация редактора
                if($('#code_editor').length>0){
                    var editor = CodeMirror.fromTextArea(document.getElementById("code_editor"), {
                        lineNumbers: true,
                        matchBrackets: true,
                        styleActiveLine: true,
                        mode: "application/x-httpd-php",
                        theme:"material"
                    });
                }
                
                if($('#code_man').length>0){
                    $('#code_man').submit(function(e){
                        var _val = editor.getValue(_val);
                        $('#code_editor').html(_val);
                    });
                }
                
                // Получение обучения
                var _tutorial = <?=$tutorial?>; // Флаг обучения
                if(_tutorial==0){ // Нужно обучение
                    var tm = setTimeout(function(){
                        $('.tap-target').tapTarget('open');
                    }, 1500);
                }
            });            
        </script>
        <style>
            .CodeMirror {height: auto;}
        </style>
    </body>
</html>