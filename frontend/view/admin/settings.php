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
                    <div class="col s12">
                        <form id="settings_form" role="form" action="/admin/saveSettings/" method="POST">
                            <div class="card white">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="row">
                                                <div class="col s12">
                                                    <ul class="tabs tab-demo z-depth-1" style="width: 100%;">
                                                        <li class="tab col s3"><a href="#settings1" class=""><?= $this->lang->line('settings_editor_section_1') ?></a></li>
                                                        <li class="tab col s3"><a href="#settings2"><?= $this->lang->line('settings_editor_section_2') ?></a></li>
                                                        <li class="tab col s3"><a href="#settings3"><?= $this->lang->line('settings_editor_section_3') ?></a></li>
                                                        <li class="tab col s3"><a href="#settings4"><?= $this->lang->line('settings_editor_section_4') ?></a></li>
                                                        <li class="tab col s3"><a href="#settings5"><?= $this->lang->line('settings_editor_section_5') ?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            
                                            <div id="settings1" class="col s12">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('settings_editor_section_1') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('settings_editor_section_desc_1') ?></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_driver" name="db[driver]" type="text" value="<?= $settings['db']['driver'] ?>" readonly="true">
                                                        <label for="db_driver"><?= $this->lang->line('t_settings_db_driver') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_host" name="db[host]" type="text" value="<?= $settings['db']['host'] ?>" readonly="true">
                                                        <label for="db_host"><?= $this->lang->line('t_settings_db_host') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_name" name="db[host]" type="text" value="<?= $settings['db']['name'] ?>" readonly="true">
                                                        <label for="db_name"><?= $this->lang->line('t_settings_db_name') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_login" name="db[host]" type="text" value="<?= $settings['db']['login'] ?>" readonly="true">
                                                        <label for="db_login"><?= $this->lang->line('t_settings_db_login') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_password" name="db[host]" type="text" value="<?= $settings['db']['password'] ?>" readonly="true">
                                                        <label for="db_password"><?= $this->lang->line('t_settings_db_password') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_encoding" name="db[host]" type="text" value="<?= $settings['db']['encoding'] ?>" readonly="true">
                                                        <label for="db_encoding"><?= $this->lang->line('t_settings_db_encoding') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="db_prefix" name="db[host]" type="text" value="<?= $settings['db']['prefix'] ?>" readonly="true">
                                                        <label for="db_prefix"><?= $this->lang->line('t_settings_db_prefix') ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="settings2" class="col s12">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('settings_editor_section_2') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('settings_editor_section_desc_2') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <?php $langlist = glob(ROOT.'/core/langs/*',GLOB_ONLYDIR); ?>
                                                        <select name="system[default_language]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <?php foreach($langlist as $key=>$val): ?>
                                                            <option value="<?=basename($val)?>" <?=($settings['system']['default_language']==basename($val))?"selected":""?>><?=basename($val)?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_sys_default_language")?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="system[analytics]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['system']['analytics'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['system']['analytics'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_sys_analytics")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('t_settings_cache_header') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('t_settings_cache_desc') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="system[cache][enabled]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['system']['cache']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['system']['cache']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_sys_cache")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="cache_time" name="system[cache][time]" type="text" value="<?= $settings['system']['cache']['time'] ?>" required="">
                                                        <label for="cache_time"><?= $this->lang->line('t_settings_sys_cache_time') ?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('t_settings_media_header') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('t_settings_media_desc') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="media_max_fsize" name="system[max_upload_filesize]" type="text" value="<?= $settings['system']['max_upload_filesize'] ?>" required="">
                                                        <label for="media_max_fsize"><?= $this->lang->line('t_settings_sys_maxfsize') ?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="media_max_w" name="system[max_upload_width]" type="text" value="<?= $settings['system']['max_upload_width'] ?>" required="">
                                                        <label for="media_max_w"><?= $this->lang->line('t_settings_sys_maxwidth') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="media_max_h" name="system[max_upload_height]" type="text" value="<?= $settings['system']['max_upload_height'] ?>" required="">
                                                        <label for="media_max_h"><?= $this->lang->line('t_settings_sys_maxheight') ?></label>
                                                    </div>
                                                </div>
                                                
                                                
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('t_settings_mod_header') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('t_settings_mod_desc') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="modules" name="system[modules]" type="text" value="<?= implode(",", $settings['system']['modules']) ?>">
                                                        <label for="modules"><?= $this->lang->line('t_settings_sys_modules') ?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('t_settings_secure_header') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('t_settings_secure_desc') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="secret_key" name="system[secret]" type="text" value="<?= $settings['system']['secret'] ?>" readonly="">
                                                        <label for="secret_key"><?= $this->lang->line('t_settings_sys_secret') ?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('t_settings_info_header') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('t_settings_info_desc') ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="app_version" name="system[version]" type="text" value="<?= $settings['system']['version'] ?>" required="">
                                                        <label for="app_version"><?= $this->lang->line('t_settings_sys_version') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="app_build" name="system[build]" type="text" value="<?= $settings['system']['build'] ?>" required="">
                                                        <label for="app_build"><?= $this->lang->line('t_settings_sys_build') ?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="core_version" name="system[platform_version]" type="text" value="<?= $settings['system']['platform_version'] ?>" readonly="">
                                                        <label for="core_version"><?= $this->lang->line('t_settings_sys_core_version') ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="settings3" class="col s12">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('settings_editor_section_3') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('settings_editor_section_desc_3') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="website[enabled]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['website']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['website']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_web_enabled")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="public_mail" name="website[public_email]" type="email" value="<?= $settings['website']['public_email'] ?>" required="">
                                                        <label for="public_mail"><?= $this->lang->line('t_settings_web_email') ?></label>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            
                                            <div id="settings4" class="col s12">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('settings_editor_section_4') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('settings_editor_section_desc_4') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="users[social_login]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['users']['social_login'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['users']['social_login'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_users_social")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="users[registration]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['users']['registration'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['users']['registration'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_users_reg")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="users[unique_email]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['users']['unique_email'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['users']['unique_email'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_users_unique_email")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="users[email_confirm]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['users']['email_confirm'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['users']['email_confirm'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_users_approve_email")?></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="users[notifications]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['users']['notifications'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['users']['notifications'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_users_notifications")?></label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="settings5" class="col s12">
                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 style="margin-top: 0;"><?= $this->lang->line('settings_editor_section_5') ?></h5>
                                                        <p class="hide-on-small-only"><?= $this->lang->line('settings_editor_section_desc_5') ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <select name="api[enabled]" required="">
                                                            <option value="" disabled selected><?= $this->lang->line('t_settings_select') ?></option>
                                                            <option value="true" <?=($settings['api']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_enabled') ?></option>
                                                            <option value="false" <?=(!$settings['api']['enabled'])?"selected":""?>><?= $this->lang->line('t_settings_disabled') ?></option>
                                                        </select>
                                                        <label><?=$this->lang->line("t_settings_api_enabled")?></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <input id="api_dom" name="api[domain]" type="text" value="<?= $settings['api']['domain'] ?>" required="">
                                                        <label for="api_dom"><?= $this->lang->line('t_settings_api_domain') ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button class="waves-effect waves-light btn" type="submit"><i class="material-icons left">save</i><?= $this->lang->line('save') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- CONTENT -->
            
            <!-- FIXED ACTION -->
            <div class="fixed-action-btn">
                <a id="save_settings" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">save</i></a>
            </div>
            <!-- FIXED ACTION -->
            
            <!-- TUTORIAL -->
            <div class="tap-target" data-activates="save_settings">
                <div class="tap-target-content">
                    <h5><?=$this->lang->line('settings_editor_tutorial_title')?></h5>
                    <p><?=$this->lang->line('settings_editor_tutorial_desc')?></p>
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
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){ // Готовность DOM
                // Получение обучения
                var _tutorial = <?=$tutorial?>; // Флаг обучения
                if(_tutorial==0){ // Нужно обучение
                    var tm = setTimeout(function(){
                        $('.tap-target').tapTarget('open');
                    }, 1500);
                }
                
                // СОхранение настроек
                $('#save_settings').click(function(){
                    $('#settings_form').submit(); // Отправить
                });
            });
        </script>
        <!-- SCRIPTS -->
    </body>
</html>