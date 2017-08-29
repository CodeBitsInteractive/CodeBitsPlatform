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
        
        <!-- MODAL CREATION -->
        <div id="create_user" class="modal modal-fixed-footer">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <h4><?=$this->lang->line('user_creation')?></h4>
                        <p><?=$this->lang->line('user_creation_desc')?></p>
                    </div>
                </div>
                <form id="account_creation" role="form" action="/admin/createAccount/" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="fullname" name="fullname" type="text" value="" maxlength="30" required="">
                            <label for="fullname"><?= $this->lang->line('fullname') ?></label>
                        </div>
                        <div class="input-field col s12">
                            <input id="login" name="login" type="text" value="" maxlength="30" required="">
                            <label for="login"><?= $this->lang->line('login') ?></label>
                        </div>
                        <div class="input-field col s12">
                            <input id="password" name="password" type="password" value="" maxlength="32" required="">
                            <label for="password"><?= $this->lang->line('password') ?></label>
                        </div>
                        <div class="input-field col s12">
                            <input id="repass" name="repass" type="password" value="" maxlength="32" required="">
                            <label for="repass"><?= $this->lang->line('repass') ?></label>
                        </div>
                        <div class="input-field col s12">
                            <input id="email" name="email" type="email" value="" required="">
                            <label for="email"><?= $this->lang->line('email') ?></label>
                        </div>
                        <div class="input-field col s12">
                            <select name="user_role">
                                <option value="" disabled selected><?= $this->lang->line('choose_user_role') ?></option>
                                <option value="user"><?= $this->lang->line('user_role_user') ?></option>
                                <option value="admin"><?= $this->lang->line('user_role_admin') ?></option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action waves-effect waves-green btn-flat" data-action="create-account"><?=$this->lang->line('save')?></a>
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat "><?=$this->lang->line('cancel')?></a>
            </div>
        </div>
        <!-- MODAL CREATION -->
        
        <!-- MODAL BAN -->
        <div id="account_banning" class="modal modal-fixed-footer">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <h4><?=$this->lang->line('user_banning')?></h4>
                        <p><?=$this->lang->line('user_banning_desc')?></p>
                    </div>
                </div>
                <form id="account_ban" role="form" action="/admin/banAccount/" method="POST">
                    <div class="row">
                        <input type="hidden" name="puid" value="" />
                        <div class="input-field col s12">
                            <select name="ban_time">
                                <option value="" disabled selected><?= $this->lang->line('choose_ban_time') ?></option>
                                <option value="no"><?= $this->lang->line('ban_clear') ?></option>
                                <option value="day"><?= $this->lang->line('ban_day') ?></option>
                                <option value="week"><?= $this->lang->line('ban_week') ?></option>
                                <option value="month"><?= $this->lang->line('ban_month') ?></option>
                                <option value="forever"><?= $this->lang->line('ban_type_forever') ?></option>
                            </select>
                        </div>
                        <div class="input-field col s12">
                            <input id="ban_reason" name="ban_reasoned" type="text" value="" maxlength="100" length="100" required="">
                            <label for="ban_reason"><?= $this->lang->line('ban_reason') ?></label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action waves-effect waves-green btn-flat" data-action="ban-account"><?=$this->lang->line('save')?></a>
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat "><?=$this->lang->line('cancel')?></a>
            </div>
        </div>
        <!-- MODAL BAN -->
        
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
                
                <!-- USERS LIST -->
                <?php if(isset($list) && !$editor): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel white darken-1">
                            <div class="card-content" style="padding: 0;">
                                <div class="card-options">
                                    <input id="find" type="text" name="search" class="expand-search" placeholder="<?=$this->lang->line('page_search')?>" value="<?=($search!==false)?$search:""?>" autocomplete="off" />
                                </div>
                                <span class="card-title"><?=$title?></span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?=$this->lang->line('avatar')?></th>
                                            <th><?=$this->lang->line('nickname_table')?></th>
                                            <th><?=$this->lang->line('email')?></th>
                                            <th><?=$this->lang->line('ban')?></th>
                                            <th><?=$this->lang->line('reg_date')?></th>
                                            <th><?=$this->lang->line('action')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $uud=>$profile): ?>
                                        <tr>
                                            <td><?=$profile['uid']?></td>
                                            <td><div class="user_photo small" style="background-image:url('<?=(isset($profile['avatar']) && $profile['avatar']!="")?$profile['avatar']:"/frontend/assets/img/user.png"?>');"></div></td>
                                            <td><?=(isset($profile['nickname']) && $profile['nickname']!="")?$profile['nickname']:$this->lang->line('unknown')?></td>
                                            <td><?=$profile['email']?></td>
                                            <?php if(!$profile['ban_data']['banned']): ?>
                                            <td class="green-text"><?=$this->lang->line('user_noban')?></td>
                                            <?php elseif($profile['ban_data']['banned'] && $profile['ban_data']['ban_escape']==0): ?>
                                            <td class="red-text"><?=$this->lang->line('user_ban_forever')?></td>
                                            <?php else: ?>
                                            <td class="red-text"><?=$this->lang->line('user_ban_for').': '.date("d.m.Y", $profile['ban_data']['ban_escape'])?></td>
                                            <?php endif; ?>
                                            <td><?=date("d.m.Y", $profile['last_login_day'])?></td>
                                            <td>
                                                <a href="/admin/users/?edit=<?=$profile['uid']?>" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_user_edit')?>" class="tooltipped btn-floating waves-effect waves-light purple"><i class="material-icons">create</i></a>
                                                <?php if($this->user->profile['uid']!=$profile['uid']):?>
                                                <a href="#account_banning" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_user_ban')?>" class="tooltipped btn-floating waves-effect waves-light orange modal-trigger" data-action="ban" data-uid="<?=$profile['uid']?>"><i class="material-icons">block</i></a>
                                                <a href="#" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_user_remove')?>" class="tooltipped btn-floating waves-effect waves-light red" data-action="remove" data-uid="<?=$profile['uid']?>"><i class="material-icons">delete_sweep</i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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
                                        <a href="/admin/users/?nav=<?=($page+1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_users_next') ?></a>
                                        <?php endif?>
                                        <?php if($page!=1 && $total>1): ?>
                                        <a href="/admin/users/?nav=<?=($page-1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_users_prev') ?></a>
                                        <?php endif?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel white darken-1">
                            <form id="saving_form" role="form" action="/admin/saveProfile/" method="POST">
                                <div class="card-content" style="padding: 0;">
                                    <span class="card-title"><?=$this->lang->line('user_editor')?></span>
                                    <input type="hidden" name="uid" value="<?=$_GET['edit']?>" />
                                    <ul class="collapsible" data-collapsible="accordion">
                                        <li>
                                            <div class="collapsible-header active"><?= $this->lang->line('user_accordion_main') ?></div>
                                            <div class="collapsible-body">
                                                <div style="margin: 20px;">
                                                    <div class="row">
                                                        <div class="col s12 m3 l2">
                                                            <input name="avatar" type="hidden" value="<?= $profile['avatar'] ?>" />
                                                            <div class="photo-picker" data-model="photo_picker" style="background-image:url('<?=(isset($profile['avatar']) && $profile['avatar']!="")?$profile['avatar']:""?>'); height: 140px;">
                                                                <div class="centrize">
                                                                    <h3 style="margin: 0;"><i class="material-icons">photo_camera</i></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col s12 m9 l10">
                                                            <div class="row">
                                                                <div class="input-field col s12">
                                                                    <input id="fullname" name="nickname" type="text" value="<?= $profile['nickname'] ?>" required="">
                                                                    <label for="fullname"><?= $this->lang->line('fullname') ?></label>
                                                                </div>
                                                                <div class="input-field col s12">
                                                                    <input id="email" name="email" type="email" value="<?= $profile['email'] ?>" required="">
                                                                    <label for="email"><?= $this->lang->line('email') ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="collapsible-header"><?= $this->lang->line('user_accordion_data') ?></div>
                                            <div class="collapsible-body">
                                                <div style="margin: 20px;">
                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <input id="phone" name="profile_data[phone]" type="text" value="<?=(isset($profile['profile_data']['phone']))?$profile['profile_data']['phone']:""?>">
                                                            <label for="phone"><?=$this->lang->line('phone_number')?></label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <input id="birthday" name="profile_data[birthday]" type="text" value="<?=(isset($profile['profile_data']['birthday']))?$profile['profile_data']['birthday']:""?>">
                                                            <label for="birthday"><?= $this->lang->line('birthday')?> (DD.MM.YYYY)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="collapsible-header"><?= $this->lang->line('user_accordion_more') ?></div>
                                            <div class="collapsible-body">
                                                <div style="margin: 20px;">
                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <input id="regdate" name="last_login_day" type="text" value="<?= date("d.m.Y", $profile['last_login_day']) ?>" readonly="">
                                                            <label for="regdate"><?= $this->lang->line('reg_date') ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-action">
                                    <button id="save_profile" class="waves-effect waves-light btn" type="submit"><i class="material-icons left">save</i><?= $this->lang->line('save') ?></button>
                                    <a class="waves-effect waves-light btn red" href="/admin/users/"><?= $this->lang->line('cancel') ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- USERS LIST -->
                
                <!-- FIXED ACTION -->
                <div class="fixed-action-btn">
                    <a id="tutorial" class="btn-floating btn-large waves-effect waves-light red modal-trigger" href="#create_user"><i class="material-icons">add</i></a>
                </div>
                <!-- FIXED ACTION -->
                
                <!-- TUTORIAL -->
                <div class="tap-target" data-activates="tutorial">
                    <div class="tap-target-content">
                        <h5><?=$this->lang->line('user_editor_tutorial_title')?></h5>
                        <p><?=$this->lang->line('user_editor_tutorial_desc')?></p>
                    </div>
                </div>
                <!-- TUTORIAL -->
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
        
        <script type="text/javascript">
            $(document).ready(function(){
                // Получение обучения
                var _tutorial = <?=$tutorial?>; // Флаг обучения
                if(_tutorial==0){ // Нужно обучение
                    var tm = setTimeout(function(){
                        $('.tap-target').tapTarget('open');
                    }, 1500);
                }
                
                // Поиск
                $("#find").keypress(function(e) {
                    if(e.which == 13) {
                        var _val = $(this).val();
                        document.location.href = "/admin/users/?s="+_val;
                    }
                });
                
                // Отправка формы
                $('#save_profile').on('click', function(){
                    $('#saving_form').submit();
                });
                
                // Создание аккаунта
                $('a[data-action="create-account"]').off('click').on('click', function(){
                    $('select[name="user_role"]').material_select();
                    $('#account_creation').submit();
                });
                
                // Блокировка пользователя
                $('a[data-action="ban"]').on('click', function(){
                    var _elem = $(this); // Элемент
                    var _uid = _elem.attr('data-uid'); // UID
                    $('#account_ban').find('input[name="puid"]').val(_uid);
                });
                
                // Бан
                $('a[data-action="ban-account"]').off('click').on('click', function(){
                    $('select[name="ban_time"]').material_select();
                    $('#account_ban').submit();
                });
                
                // Фото пикер
                $('div[data-model="photo_picker"]').media_manager({
                    enable_gallery: true,
                    title: "<?=$this->lang->line('mm_title')?>",
                    upload_title: "<?=$this->lang->line('mm_upload')?>",
                    gallery_title: "<?=$this->lang->line('mm_from_gallery')?>",
                    on_hidden: function(self){
                        var _elem = $('div[data-model="photo_picker"]');
                        var _url = _elem.media_manager('getURL');
                        if(_url!=''){
                            $('input[name="avatar"]').val(_url);
                            _elem.css('background-image', 'url("'+_url+'")');
                        }
                    }
                });
                
                // Удаление
                $('a[data-action="remove"]').on('click', function(e){
                    var _elem = $(this); // Элемент
                    var _uid = _elem.attr('data-uid'); // UID
                    swal({ // Вызов модального окна
                        title: "<?=$this->lang->line('user_confirm_remove_title')?>",
                        text: "<?=$this->lang->line('user_confirm_remove_text')?>",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "<?=$this->lang->line('user_confirm_remove_yes')?>",
                        cancelButtonText: "<?=$this->lang->line('user_confirm_remove_no')?>",
                        closeOnConfirm: false,
                        closeOnCancel: false 
                    }, function(isConfirm){ // Результат диалога
                        if (isConfirm) { // Удалить
                            document.location.href="/admin/removeAccount/?uid="+_uid;
                        } else { // Отмена
                            swal("<?=$this->lang->line('user_confirm_canceled_title')?>", "<?=$this->lang->line('user_confirm_canceled_desc')?>", "error");
                        }
                    });
                    
                    // Отмена перехода
                    e.preventDefault();
                    return false;
                });
            });
        </script>
        <!-- SCRIPTS -->
    </body>
</html>