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
            <div class="mn-inner">            
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
                            <li class="collection-header" style="list-style-type: none;"><h5><?=$this->lang->line('choose_langpack')?></h5></li>
                            <?php foreach($lang_files as $key=>$val): ?>
                                <a href="/admin/localization/?lng=<?=$lang_name?>&package=<?=basename($val, '.json')?>" class="collection-item <?=(basename($val, '.json')==$curr_package)?'active':''?>"><?=basename($val)?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col s12 m8 l9">
                        <div class="card white">
                            <form role="form" action="/admin/saveLanguage/" method="POST">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12 m8 l10">
                                            <h5 style="margin-top: 0;"><?=(isset($package['packname']) && mb_strlen($package['packname'])>0)?$package['packname']:$curr_package?></h5>
                                            <p class="hide-on-small-only"><?=$this->lang->line('manage_langpack_desc')?></p>
                                        </div>
                                        <div class="col s12 m4 l2">
                                            <select id="choose_lang">
                                                <?php foreach($langs as $val):?>
                                                    <option value="<?=strtoupper(basename($val, '.json'))?>" <?=(strtoupper(basename($val, '.json'))==$lang_name)?'selected':''?>><?=strtoupper(basename($val, '.json'))?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" name="pack_name" value="<?= $curr_package ?>" />
                                    <input type="hidden" name="pack_lang" value="<?= $lang_name ?>" />
                                    <?php foreach ($package as $key => $val): ?>
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input id="<?= $key ?>" name="pack_val[<?= $key ?>]" type="text" class="validate" value="<?= stripslashes($val) ?>">
                                                <label for="<?= $key ?>"><?= $key ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="card-action">
                                    <button class="waves-effect waves-light btn" type="submit"><i class="material-icons left">save</i><?=$this->lang->line('save')?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        <!-- SCRIPTS -->
        
        <!-- LANGUAGE SELECTOR -->
        <script type="text/javascript">
            $('#choose_lang').change(function(){
                var _lng = $(this).val(); // Язык
                document.location.href = '/admin/localization/?lng='+_lng+'&package=<?=$curr_package?>';
            });
        </script>
        <!-- LANGUAGE SELECTOR -->
    </body>
</html>