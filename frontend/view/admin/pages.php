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
        <link href="/frontend/assets/css/editor/trumbowyg.min.css" rel="stylesheet" type="text/css"/>
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
                
                <!-- PAGES LIST/DATA -->
                <?php if(isset($list) && $list!==false && !$editor): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel white darken-1">
                            <div class="card-content" style="padding: 0;">
                                <div class="card-options">
                                    <input id="find" type="text" name="search" class="expand-search" placeholder="<?=$this->lang->line('user_search')?>" value="<?=($search!==false)?$search:""?>" autocomplete="off" />
                                </div>
                                <span class="card-title"><?=$title?></span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?=$this->lang->line('a_page_photo')?></th>
                                            <th><?=$this->lang->line('a_page_title')?></th>
                                            <th><?=$this->lang->line('a_page_desc')?></th>
                                            <th><?=$this->lang->line('a_page_views')?></th>
                                            <th><?=$this->lang->line('a_page_time')?></th>
                                            <th><?=$this->lang->line('action_thead')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $uud=>$page_data): ?>
                                        <tr>
                                            <td><?=$page_data['uid']?></td>
                                            <td><div class="user_photo small" style="background-image: url('<?=(isset($page_data['image']) && $page_data['image']!="")?$page_data['image']:"/frontend/assets/img/user.png"?>');"></div></td>
                                            <td><?=$page_data['title']?></td>
                                            <td><?=$page_data['desc']?></td>
                                            <td><?=$page_data['views']?></td>
                                            <td><?=date("d.m.Y H:i:s",$page_data['time'])?></td>
                                            <td>
                                                <a href="/pages/view/<?=$page_data['slug']?>/" target="_blank" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_page_view')?>" class="tooltipped btn-floating waves-effect waves-light green"><i class="material-icons">remove_red_eye</i></a>
                                                <a href="/admin/pages/?editor=true&page=<?=$page_data['slug']?>" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_page_edit')?>" class="tooltipped btn-floating waves-effect waves-light purple"><i class="material-icons">create</i></a>
                                                <a href="#" data-position="bottom" data-delay="50" data-tooltip="<?=$this->lang->line('h_page_remove')?>" class="tooltipped btn-floating waves-effect waves-light red" data-action="remove" data-uid="<?=$page_data['slug']?>"><i class="material-icons">delete_sweep</i></a>
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
                                        <a href="/admin/pages/?nav=<?=($page+1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_more') ?></a>
                                        <?php endif?>
                                        <?php if($page!=1 && $total>1): ?>
                                        <a href="/admin/pages/?nav=<?=($page-1)?><?=(isset($search) && $search!==false)?"&s=".$search:""?>" class="waves-effect waves-light btn"><?= $this->lang->line('t_prev') ?></a>
                                        <?php endif?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                    
                    <!-- FIXED ACTION -->
                    <div class="fixed-action-btn">
                        <a id="tutorial" class="btn-floating btn-large waves-effect waves-light red" href="/admin/pages/?editor=true"><i class="material-icons">add</i></a>
                    </div>

                    <!-- TUTORIAL -->
                    <div class="tap-target" data-activates="tutorial">
                        <div class="tap-target-content">
                            <h5><?=$this->lang->line('pages_editor_tutorial_title')?></h5>
                            <p><?=$this->lang->line('pages_editor_tutorial_desc')?></p>
                        </div>
                    </div>
                    <!-- TUTORIAL -->
                </div>
                <?php elseif($editor!==false): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card white">
                            <form id="code_man" role="form" action="/admin/savePage/" method="POST">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s12">
                                            <div class="row">
                                                <div class="col s12">
                                                    <h5 style="margin-top: 0;"><?=(isset($page_data) && $page_data!==false)?$this->lang->line('page_editing_title'):$this->lang->line('page_creation_title')?></h5>
                                                    <p class="hide-on-small-only"><?=(isset($page_data) && $page_data!==false)?$this->lang->line('page_editing_desc'):$this->lang->line('page_creation_desc')?></p>
                                                </div>
                                            </div>
                                            <input type="hidden" name="new" value="<?=(isset($page_data) && $page_data!==false)?"false":"true"?>" />
                                            <input data-cross="page_cover" type="hidden" name="image" value="<?=(isset($page_data[$default_language]['image']))?$page_data[$default_language]['image']:""?>" />
                                            <div class="row">
                                                <div class="col s12">
                                                    <div class="photo-picker" data-model="photo_picker" style="background-image: url('<?=(isset($page_data[$default_language]['image']))?$page_data[$default_language]['image']:""?>');">
                                                        <div class="centrize">
                                                            <h3><i class="material-icons">photo_camera</i></h3>
                                                            <p><?=$this->lang->line('mm_pick_photo')?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col s12">
                                                    <ul class="tabs tab-demo z-depth-1" style="width: 100%;">
                                                        <?php foreach($langs as $key=>$val): ?>
                                                        <li class="tab col s3"><a href="#page_edit_<?=$key?>"><?=$val?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear: both; height: 20px;"></div>
                                        <?php foreach($langs as $key=>$val): ?>
                                        <div id="page_edit_<?=$key?>" class="col s12">
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input id="page_title_<?=$key?>" name="data[<?=$key?>][title]" type="text" value="<?=(isset($page_data[$key]['title']))?$page_data[$key]['title']:""?>" maxlength="50" data-action="slug" data-for="page_slug" length="50">
                                                    <label for="page_title_<?=$key?>"><?= $this->lang->line('t_page_title') ?> (<?=$val?>)</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input id="page_slug_<?=$key?>" name="data[<?=$key?>][slug]" type="text" value="<?=(isset($page_data[$key]['slug']))?$page_data[$key]['slug']:""?>" maxlength="50" length="50" data-type="page_slug" <?=(isset($page_data[$key]['slug']))?'readonly="true"':""?>>
                                                    <label for="page_slug_<?=$key?>" data-type="page_slug"><?= $this->lang->line('t_page_slug') ?> (<?=$val?>)</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea id="page_desc_<?=$key?>" name="data[<?=$key?>][desc]" class="materialize-textarea" length="200" maxlength="200"><?=(isset($page_data[$key]['desc']))?$page_data[$key]['desc']:""?></textarea>
                                                    <label for="page_desc_<?=$key?>"><?= $this->lang->line('t_page_desc')?> (<?=$val?>)</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea id="page_tags_<?=$key?>" name="data[<?=$key?>][tags]" class="materialize-textarea" length="250" maxlength="250"><?=(isset($page_data[$key]['tags']))?$page_data[$key]['tags']:""?></textarea>
                                                    <label for="page_tags_<?=$key?>"><?= $this->lang->line('t_page_tags')?> (<?=$val?>)</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea id="page_body_<?=$key?>" class="materialize-textarea" name="data[<?=$key?>][body]" placeholder="<?= $this->lang->line('t_page_body')?> (<?=$val?>)"><?=(isset($page_data[$key]['body']))?$page_data[$key]['body']:""?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <button class="waves-effect waves-light btn" type="submit"><i class="material-icons left">save</i><?=$this->lang->line('save')?></button>
                                    <a class="waves-effect waves-light btn red" href="/admin/pages/"><?=$this->lang->line('cancel')?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card white">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12">
                                        <h5 style="margin-top: 0;"><?=$this->lang->line('manage_pages_title')?></h5>
                                        <p class="hide-on-small-only"><?=$this->lang->line('manage_pages_desc')?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <a href="/admin/pages/?editor=true" class="waves-effect waves-light btn"><i class="material-icons left">add</i><?= $this->lang->line('create_page') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FIXED ACTION -->
                <div class="fixed-action-btn">
                    <a id="tutorial" class="btn-floating btn-large waves-effect waves-light red" href="/admin/pages/?editor=true"><i class="material-icons">add</i></a>
                </div>

                <!-- TUTORIAL -->
                <div class="tap-target" data-activates="tutorial">
                    <div class="tap-target-content">
                        <h5><?=$this->lang->line('pages_editor_tutorial_title')?></h5>
                        <p><?=$this->lang->line('pages_editor_tutorial_desc')?></p>
                    </div>
                </div>
                <!-- TUTORIAL -->
                <?php endif; ?>
                <!-- PAGES LIST/DATA -->
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
        <script src="/frontend/assets/js/editor/trumbowyg.min.js"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.base64.js?2"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.cleanpaste.js?2"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.colors.js?2"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.pasteimage.js?2"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.table.js?2"></script>
        <script src="/frontend/assets/js/editor/plugins/trumbowyg.upload.js?2"></script>
        <script src="/frontend/assets/js/editor/langs/<?=strtolower($this->lang->curr_lang)?>.min.js"></script>
        <script src="/frontend/view/admin/assets/js/prettify.js"></script>
        <script src="/frontend/view/admin/assets/js/sweetalert.js"></script>
        <script src="/frontend/view/admin/assets/js/custom.js"></script>
        
        <script type="text/javascript">
            // Функция транслитерации
            function translit(str) {
                var space = '-'; // Замена пробелов
                var link = ''; // Выводное значение
                var transl = { // Массив транслита
                    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
                    'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                    'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
                    'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space,
                    'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya'
                };
                
                if (str != '') str = str.toLowerCase();

                for (var i = 0; i < str.length; i++){
                    if (/[а-яё]/.test(str.charAt(i))){ // заменяем символы на русском
                        link += transl[str.charAt(i)];
                    } else if (/[a-z0-9]/.test(str.charAt(i))){ // символы на анг. оставляем как есть
                        link += str.charAt(i);
                    } else {
                        if (link.slice(-1) !== space) link += space; // прочие символы заменяем на space
                    }
                }
                
                return link;
            }
            
            $(document).ready(function(){ // Готовность DOM               
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
                        document.location.href = "/admin/pages/?s="+_val;
                    }
                });
                
                // Инициализация редактора контента
                $.trumbowyg.svgPath = '/frontend/assets/css/editor/icons.svg';
                <?php foreach($langs as $key=>$val): ?>
                    $('#page_body_<?=$key?>').trumbowyg({ // Инициализация
                        lang: '<?=strtolower($this->lang->curr_lang)?>', // Язык
                        autogrow: true, // Авто-перенос
                        btnsDef: {
                            image: {
                                dropdown: ['insertImage', 'upload', 'base64'],
                                ico: 'insertImage'
                            }
                        },
                        btns: [ // КНопки редактора
                            ['viewHTML'],
                            ['formatting'],
                            'btnGrp-semantic',
                            ['superscript', 'subscript'],
                            ['backColor','foreColor'],
                            ['link'],
                            ['image'],
                            ['table'],
                            'btnGrp-justify',
                            'btnGrp-lists',
                            ['horizontalRule'],
                            ['removeformat'],
                            ['fullscreen']
                        ],
                        removeformatPasted: true, // Убрать форматирование
                        plugins:{
                            upload: {
                                serverPath: '/api/media/upload/',
                                fileFieldName: 'file',
                                urlPropertyName: 'data.link'
                            }
                        }
                    });
                <?php endforeach; ?>
                
                // Инициализация медиа-менеджера
                $('div[data-model="photo_picker"]').media_manager({
                    enable_gallery: true, // Без галлереи
                    title: "<?=$this->lang->line('mm_title')?>",
                    upload_title: "<?=$this->lang->line('mm_upload')?>",
                    gallery_title: "<?=$this->lang->line('mm_from_gallery')?>",
                    on_hidden: function(self){
                        var _elem = $('div[data-model="photo_picker"]');
                        var _url = _elem.media_manager('getURL');
                        if(_url!=''){
                            $('input[data-cross="page_cover"]').val(_url);
                            _elem.css('background-image', 'url("'+_url+'")');
                        }
                    }
                });
                
                // Транслитерация Input
                <?php if(!isset($page_data[$default_language]['slug'])): ?>
                $('input[data-action="slug"]').keyup(function(){
                    var _elem = $('input[data-type="'+$(this).attr('data-for')+'"]'); // Элемент куда транслируем
                    var _label = $('label[data-type="'+$(this).attr('data-for')+'"]');
                    var _val = $(this).val(); // Захватываем значение
                    _elem.val(translit(_val)); // Устанавливаем значение
                    
                    // Обновление лейбла
                    if(_elem.val().length<1){ _label.removeClass('active'); }else{ _label.addClass('active'); }
                });
                <?php endif; ?>
                    
                // Удаление
                $('a[data-action="remove"]').on('click', function(e){
                    var _elem = $(this); // Элемент
                    var _uid = _elem.attr('data-uid'); // UID
                    swal({ // Вызов модального окна
                        title: "<?=$this->lang->line('page_confirm_remove_title')?>",
                        text: "<?=$this->lang->line('page_confirm_remove_text')?>",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "<?=$this->lang->line('page_confirm_remove_yes')?>",
                        cancelButtonText: "<?=$this->lang->line('page_confirm_remove_no')?>",
                        closeOnConfirm: false,
                        closeOnCancel: false 
                    }, function(isConfirm){ // Результат диалога
                        if (isConfirm) { // Удалить
                            document.location.href="/admin/removePage/?uid="+_uid;
                        } else { // Отмена
                            swal("<?=$this->lang->line('page_confirm_canceled_title')?>", "<?=$this->lang->line('page_confirm_canceled_desc')?>", "error");
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