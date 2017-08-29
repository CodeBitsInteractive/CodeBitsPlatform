<?php
//===============================================
//  Файл:           class.admin.php
//  Назначение:     Контроллер панели управления
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_admin extends BaseController{
    // Параметры
    var $notify = null;
    
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
        
        // Получаем языковой пакет
        $this->lang->load("control_panel"); // Языковой пакет главной страницы
        $this->lang->load("notifications"); // Языковой пакет уведомлений
        
        // Проверка авторизации
        if(!$this->user->auth['is_auth']){ // Не авторизован
            header("Location: /auth/sign_in/?redirect=http://".DOMAIN."/admin/");
            exit();
        }
        
        // Проверка прав
        if(!$this->user->auth['is_admin']){
            header("Location: /error/?code=403");
            exit();
        }
        
        // Получаем модель уведомлений
        $this->notify['object'] = $this->load_model("notifications", []); // Нотификации
        $get = $this->notify['object']->getNotificationsList(0, 10); // Получить список
        if($get['complete']){ // Все ок
            $this->notify['list'] = $get['list'];
            $this->notify['new'] = $get['new'];
        }else{
            $this->notify['list'] = [];
            $this->notify['new'] = 0;
        }
        
    }
    
    // Инициализация модуля
    public function init($data){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Сообщение "Добро пожаловать"
        if(isset($_COOKIE['welcome_cbp']) && strlen($_COOKIE['welcome_cbp'])>0){ $_show_welcome = 0;}else{
            $_show_welcome = 1;
            setcookie("welcome_cbp", "true", time()+(24*60*60), "/");
        }
        
        // Получаем данные аналитики
        $_amodel = $this->load_model("analytics", []); // Получаем модель
        $_getToday = $_amodel->getAnalyticsToday(); // Получить данные
        if(!$_getToday['complete']){ // Ошибка
            if(!$_error){ // Нет ошибки
                $this->_doError($_getToday["code"]); // Ошибка
            }
        }else{ // Все ок
            $_a_data = $_getToday['a_data'];
            $_online = $_getToday['online'];
            $_media_size = $_getToday['media_size'];
        }
        
        // Обзор данных
        $_today_analytics = false;
        $_prev_analytics = false;
        if(isset($_a_data) && is_array($_a_data)){
            foreach($_a_data as $key=>$val){
                if($val['day']==date("d.m.Y")){
                    $_today_analytics = $val;
                }else{
                    $_prev_analytics = $val;
                }
            }
        }
        
        // Получаем данные о последних посетителях за сегодня
        $_last_visitors = false;
        $_getVisitors = $_amodel->getVisitorsToday(); // Получить данные
        if(!$_getVisitors['complete']){ // Ошибка
            if(!$_error){ // Нет ошибки
                $this->_doError($_getVisitors["code"]); // Ошибка
            }
        }else{ // Все ок
            $_last_visitors = $_getVisitors['v_data'];
        }
        
        // Настраиваем
        $this->cache->getCache("admin_home"); // Получить кеш
        $_template = FRONTEND.'/view/admin/home.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_home_title'),
            'desc'=>$this->lang->line('admin_home_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'today_analytics'=>$_today_analytics,
            'prev_analytics'=>$_prev_analytics,
            'last_visitors'=>$_last_visitors,
            'show_welcome'=>$_show_welcome,
            'media_size'=>$_media_size,
            'online'=>$_online
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Все ок
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
    
    // Модуль настроек (View)
    public function settings(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Удалить кеш
        if(isset($_GET['complete']) && $_GET['complete']=="true"){
            $this->cache->removeCache("admin_settings");
        }
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $this->cache->getCache("admin_settings"); // Получить кеш
        $_template = FRONTEND.'/view/admin/settings.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_settings_title'),
            'desc'=>$this->lang->line('admin_settings_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'settings'=>$this->config->line,
            'tutorial'=>$this->_getTutorial("admin_settings")
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Все ок
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
    
    // Сохранение настроек
    public function saveSettings(){
        // Проверка данных на существования
        if(!isset($_POST['system']) || !is_array($_POST['system']) || !isset($_POST['website']) || !is_array($_POST['website']) || !isset($_POST['users']) || !is_array($_POST['users']) || !isset($_POST['api']) || !is_array($_POST['api'])){
            $this->_doError("settings_error", "settings");
        }
        
        // Задаем массив
        $_settings['system'] = $_POST['system']; // Системные параметры
        $_settings['website'] = $_POST['website']; // Параметры сайта
        $_settings['users'] = $_POST['users']; // Параметры пользователей
        $_settings['api'] = $_POST['api']; // Параметры API
        
        // Валидация настроек
        $_valid = $this->_validateSettings($_settings); // Валдиация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "settings");
        }else{
            $_settings = $_valid['settings']; // Обновить
        }
        
        // Дополнительное преобразование
        $_settings['system']['modules']=explode(",", $_settings['system']['modules']);
        if(count($_settings['system']['modules'])>0 && $_settings['system']['modules'][0]!=""){ // Есть модули
            $_find = $this->_findModules($_settings['system']['modules']);
            if(!$_find['complete']){ // Ошибка
                $this->_doError($_find['code'], "settings");
            }
        }
        
        // Удаление лишних параметров
        if(isset($_settings['system']['secret'])) unset($_settings['system']['secret']);
        if(isset($_settings['system']['platform_version'])) unset($_settings['system']['platform_version']);
        
        // Сохранение параметров
        $this->config->line['system']=  array_replace_recursive($this->config->line['system'], $_settings['system']);
        $this->config->line['website']=  array_replace_recursive($this->config->line['website'], $_settings['website']);
        $this->config->line['users']=  array_replace_recursive($this->config->line['users'], $_settings['users']);
        $this->config->line['api']=  array_replace_recursive($this->config->line['api'], $_settings['api']);
        
        // Сохранить параметры
        $_json = json_encode($this->config->line);
        $_save = file_put_contents(ROOT.'/core/configs/conf.main.json', $_json);
        
        // Все ок
        header("Location: /admin/settings/?complete=true");
        exit();
    }
    
    // Валидация настроек
    private function _validateSettings($settings){
        //*****************************************
        //  Система
        //*****************************************
        if(!isset($settings['system']['default_language']) || !isset($settings['system']['analytics']) || !isset($settings['system']['cache']) || !isset($settings['system']['cache']['enabled']) 
                || !isset($settings['system']['cache']['time']) || !isset($settings['system']['max_upload_filesize']) || !isset($settings['system']['max_upload_width']) 
                || !isset($settings['system']['max_upload_height']) || !isset($settings['system']['modules']) || !isset($settings['system']['version']) || !isset($settings['system']['build'])){
            return ['complete'=>false, 'code'=>'s_wrong_params'];
        }
        
        // Преобразование данных (Boolean)
        $settings['system']['analytics']=($settings['system']['analytics']=="false")?false:true;
        $settings['system']['cache']['enabled']=($settings['system']['cache']['enabled']=="false")?false:true;
        
        // Преобразование данных (Int)
        $settings['system']['cache']['time'] = intval($settings['system']['cache']['time']);
        $settings['system']['max_upload_filesize'] = intval($settings['system']['max_upload_filesize']);
        $settings['system']['max_upload_width'] = intval($settings['system']['max_upload_width']);
        $settings['system']['max_upload_height'] = intval($settings['system']['max_upload_height']);
        $settings['system']['build'] = intval($settings['system']['build']);
        
        // Дополнительное преобразование
        $settings['system']['cache']['time'] = ($settings['system']['cache']['time']<60)?60:$settings['system']['cache']['time'];
        $settings['system']['max_upload_filesize'] = ($settings['system']['max_upload_filesize']<(1024*1024))?(1024*1024):$settings['system']['max_upload_filesize'];
        $settings['system']['max_upload_height'] = ($settings['system']['max_upload_height']<200)?200:$settings['system']['max_upload_height'];
        $settings['system']['max_upload_width'] = ($settings['system']['max_upload_width']<200)?200:$settings['system']['max_upload_width'];
        $settings['system']['build'] = ($settings['system']['build']<1000)?1000:$settings['system']['build'];
        
        // Дополнительные проверки данных
        if(preg_match('/[^0-9a-zA-Z\-\,]+$/u', $settings['system']['modules'])){
            return ['complete'=>false, 'code'=>'s_wrong_modules'];
        }
        
        if(preg_match('/[^0-9\.]+$/u', $settings['system']['version'])){
            return ['complete'=>false, 'code'=>'s_wrong_version'];
        }
        
        // Поиск языка
        if(!file_exists(ROOT.'/core/langs/'.$settings['system']['default_language'].'/')){
            return ['complete'=>false, 'code'=>'s_lang_notfound'];
        }
        
        //*****************************************
        //  Веб-сайт
        //*****************************************
        // Проверка существования
        if(!isset($settings['website']['public_email']) || !isset($settings['website']['enabled'])){
            return ['complete'=>false, 'code'=>'s_wrong_params'];
        }
        
        // Если не указан email
        if(mb_strlen($settings['website']['public_email'])<1 || !filter_var($settings['website']['public_email'], FILTER_VALIDATE_EMAIL)){
            return ['complete'=>false, 'code'=>'s_public_email'];
        }
        
        // Флаг доступности
        $settings['website']['enabled']=($settings['website']['enabled']=="false")?false:true;
        
        
        //*****************************************
        //  Пользователи
        //*****************************************
        // Проверка существования
        if(!isset($settings['users']['social_login']) || !isset($settings['users']['registration']) || !isset($settings['users']['unique_email']) || !isset($settings['users']['email_confirm']) || !isset($settings['users']['notifications'])){
            return ['complete'=>false, 'code'=>'s_wrong_params'];
        }
        
        // Установка флагов
        $settings['users']['social_login']=($settings['users']['social_login']=="false")?false:true;
        $settings['users']['registration']=($settings['users']['registration']=="false")?false:true;
        $settings['users']['unique_email']=($settings['users']['unique_email']=="false")?false:true;
        $settings['users']['email_confirm']=($settings['users']['email_confirm']=="false")?false:true;
        $settings['users']['notifications']=($settings['users']['notifications']=="false")?false:true;
        
        //*****************************************
        //  API
        //*****************************************
        // Проверка существования
        if(!isset($settings['api']['domain']) || !isset($settings['api']['enabled'])){
            return ['complete'=>false, 'code'=>'s_wrong_params'];
        }
        
        // Проверка флага доступности
        $settings['api']['enabled']=($settings['api']['enabled']=="false")?false:true;
        
        // Проверка домена
        if($settings['api']['domain']!="*" && !filter_var($settings['api']['domain'], FILTER_VALIDATE_URL)){
            return ['complete'=>false, 'code'=>'s_wrong_api_domain'];
        }
        
        // Все окей
        return ['complete'=>true, 'settings'=>$settings];
    }
    
    // Поиск модулей
    private function _findModules($modules){
        foreach($modules as $key=>$module){
            if(!file_exists(ROOT.'/core/modules/'.$module.'/module.php')){
                return ['complete'=>false, 'code'=>'settings_module_not_found'];
            }
        }
        
        // Все окей
        return ['complete'=>true];
    }

    // Модуль локализации (View)
    public function localization(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Получаем флаг языка
        $_langname = (isset($_GET['lng']) && strlen($_GET['lng'])>0 && file_exists(ROOT.'/core/langs/'.strtoupper($_GET['lng']).'/'))?strtoupper($_GET['lng']):strtoupper($this->lang->curr_lang);
        
        // Получаем файлы локализаций
        $_locfiles = glob(ROOT.'/core/langs/'.$_langname.'/*.json'); // Листинг языковых файлов
        $_langpacks = glob(ROOT.'/core/langs/*', GLOB_ONLYDIR); // Листинг языковых пакетов
        $_curr_langpack = (isset($_GET['package']) && strlen($_GET['package'])>0 && file_exists(ROOT.'/core/langs/'.$_langname.'/'.$_GET['package'].'.json'))?$_GET['package']:basename($_locfiles[0], '.json');
        
        // Открываем первый файл
        $_package = json_decode(file_get_contents(ROOT.'/core/langs/'.$_langname.'/'.$_curr_langpack.'.json'), true); // Получить языковой пакет
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Удалить кеш
        if(isset($_GET['complete']) && $_GET['complete']=="true"){
            $this->cache->removeCache("admin_localization");
        }
        
        // Настраиваем
        $this->cache->getCache("admin_localization"); // Получить кеш
        $_template = FRONTEND.'/view/admin/localization.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_localization_title'),
            'desc'=>$this->lang->line('admin_localization_desc'),
            'user'=>$this->user->profile,
            'lang_name'=>$_langname,
            'langs'=>$_langpacks,
            'lang_files'=>$_locfiles,
            'curr_package'=>$_curr_langpack,
            'package'=>$_package,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Все ок
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
    
    // Сохранить языковой пакет
    public function saveLanguage(){
        $this->_noAPI(); // Не для API
        
        // Проверка существования данных
        if(isset($_POST['pack_val']) && is_array($_POST['pack_val'])){ // Это массив
            $_newlangs = $_POST['pack_val']; // Новые значения
        }else{ // Это не массив
            $this->_doError("error_no_lang_array", "localization"); // Ошибка
        }
        
        // Проверка существования пакета
        if(isset($_POST['pack_lang']) && strlen($_POST['pack_lang'])>0 && file_exists(ROOT.'/core/langs/'.strtoupper($_POST['pack_lang']).'/')){
            $_langname = strtoupper($_POST['pack_lang']); // Имя языка
        }else{
            $this->_doError("error_no_lang_name", "localization"); // Ошибка
        }
        
        // Проверка существования языкового параметра
        if(isset($_POST['pack_name']) && strlen($_POST['pack_name'])>0 && file_exists(ROOT.'/core/langs/'.$_langname.'/'.$_POST['pack_name'].'.json')){
            $_langpack = $_POST['pack_name']; // Имя пакета
        }else{
            $this->_doError("error_no_lang_pack", "localization"); // Ошибка
        }
        
        // Обработка значений языка
        foreach($_newlangs as $key=>$val){
            $_newlangs[$key] = $this->_convertLangVal($val);
        }
        
        // Преобразовать в JSON
        $_data = json_encode($_newlangs); // Закодировать в JSON
        if(!$_data){ // Не удалось преобразовать
            $this->_doError("error_convert_lang_pack", "localization"); // Ошибка
        }
        
        // Сохранить в качестве пакета
        $_save = @file_put_contents(ROOT.'/core/langs/'.$_langname.'/'.$_langpack.'.json', $_data);
        if(!$_save){ // Не удалось сохранить
            $this->_doError("error_save_lang_pack", "localization"); // Ошибка
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_language_save")." <a href=\"/admin/localization/?lng=".$_langname."&package=".$_langpack."&complete=true\" target=\"_blank\">".$_langpack." (".$_langname.")</a>";
        $this->notify['object']->createNotification(-1, $n_text, "translate");
        
        // Вывод
        header("Location: /admin/localization/?lng=".$_langname."&package=".$_langpack."&complete=true"); // Выполняем редирект
        exit();
    }

    // Конвертация для сохранения в JSON
    private function _convertLangVal($text){
        $_newtext = addslashes($text);
        return $_newtext;
    }

    // Модуль редактора ядра (View)
    public function editor(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Базовые значения
        $_code = false; // Данные
        $_filename = false; // Имя файла
        $_path = false;
        $_filelist = []; // Список файлов
        
        // Поиск типа редактора
        if(isset($_GET['type']) && strlen($_GET['type'])>0){ // Есть тип
            $_type = strtolower($_GET['type']); // Указать тип
        }else{ // Ошибки нет
            $_type = false; // Тип не указан
        }
        
        // Поиск имени файла
        if(isset($_GET['filename']) && strlen($_GET['filename'])>0){ // Есть имя файла
            $_filename = $_GET['filename']; // Указать имя файла
        }else{ // Ошибки нет
            $_filename = false; // Нет имени файла
        }
        
        // Проверка правильности типа
        $_type = ($_type=='bootstrap' || $_type=='controllers' || $_type=='models' || $_type=='modules' || $_type=='views' || $_type=='core')?$_type:false;
        $_types = ['bootstrap','controllers','models','modules','core'];
        
        // Загрузка данных
        if($_type=='bootstrap'){ // Bootstrap
            $_path = ROOT.'/core/bootstrap.php'; // Путь к файлу
            $_filename = basename($_path); // Имя файла
            $_code = file_get_contents($_path); // Получить контент
        }else if($_type=='controllers'){
            $_filelist = glob(ROOT.'/core/controllers/*.php'); // Список контроллеров
            if($_filename!==false && file_exists(ROOT.'/core/controllers/'.$_filename)){ // Есть имя файла
                $_path = ROOT.'/core/controllers/'.$_filename;
                $_code = file_get_contents($_path); // Получить контент
            }
        }else if($_type=='models'){
            $_filelist = glob(ROOT.'/core/models/*.php'); // Список контроллеров
            if($_filename!==false && file_exists(ROOT.'/core/models/'.$_filename)){ // Есть имя файла
                $_path = ROOT.'/core/models/'.$_filename;
                $_code = file_get_contents($_path); // Получить контент
            }
        }else if($_type=='modules'){
            $_filelist = glob(ROOT.'/core/modules/*', GLOB_ONLYDIR); // Список контроллеров
            if($_filename!==false && file_exists(ROOT.'/core/modules/'.$_filename.'/module.php')){ // Есть имя файла
                $_path = ROOT.'/core/modules/'.$_filename.'/module.php';
                $_code = file_get_contents($_path); // Получить контент
            }
        }else if($_type=='core'){
            $_filelist = glob(ROOT.'/core/libs/*.php'); // Список контроллеров
            if($_filename!==false && file_exists(ROOT.'/core/libs/'.$_filename)){ // Есть имя файла
                $_path = ROOT.'/core/libs/'.$_filename;
                $_code = file_get_contents($_path); // Получить контент
            }
        }
        
        // Удалить кеш
        if(isset($_GET['complete']) && $_GET['complete']=="true"){
            $this->cache->removeCache("admin_editor");
        }
        
        // Настраиваем
        $this->cache->getCache("admin_editor"); // Получить кеш
        $_template = FRONTEND.'/view/admin/editor.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_editor_title'),
            'desc'=>$this->lang->line('admin_editor_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'type'=>$_type,
            'types'=>$_types,
            'filename'=>$_filename,
            'files'=>$_filelist,
            'path'=>$_path,
            'code'=>$_code,
            'tutorial'=>$this->_getTutorial("admin_editor")
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Все ок
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
    
    // Сохранить компонент
    public function saveComponent(){
        $this->_noAPI(); // Не для API
        
        // Поиск типа компонента
        if(isset($_POST['component_type']) && strlen($_POST['component_type'])>0){ // Есть тип
            $_type = strtolower($_POST['component_type']); // Указать тип
        }else{ // Ошибки нет
            $this->_doError("error_comtype_notfound", "editor"); // Ошибка
        }
        
        // Сравнение типа компонента
        if($_type!='bootstrap' && $_type!='controllers' && $_type!='models' && $_type!='modules' && $_type!='core'){
            $this->_doError("error_comtype_wrong", "editor"); // Ошибка
        }
        
        // Поиск имени компонента
        if($_type!='bootstrap'){ // Не для Bootstrap
            if(isset($_POST['component_file']) && strlen($_POST['component_file'])>0){ // Есть тип
                $_filename = $_POST['component_file']; // Указать тип
            }else{ // Ошибки нет
                $this->_doError("error_comfile_notfound", "editor"); // Ошибка
            }
        }
        
        // Поиск контента
        if(isset($_POST['component_code'])){ // Код найден
            $_content = $_POST['component_code']; // Код
        }else{ // Код не найден
            $this->_doError("error_comcode_notfound", "editor"); // Ошибка
        }
        
        // Сохраняем
        if($_type=='controllers'){ // Контроллер
            $_done_url = '/admin/editor/?type=controllers&filename='.$_filename.'&complete=true';
            file_put_contents(ROOT.'/core/controllers/'.$_filename, $_content);
        }else if($_type=='models'){ // Модель
            $_done_url = '/admin/editor/?type=models&filename='.$_filename.'&complete=true';
            file_put_contents(ROOT.'/core/models/'.$_filename, $_content);
        }else if($_type=='modules'){ // Модуль
            $_done_url = '/admin/editor/?type=modules&filename='.$_filename.'&complete=true';
            file_put_contents(ROOT.'/core/modules/'.$_filename.'/module.php', $_content);
        }else if($_type=='core'){ // Ядро
            $_done_url = '/admin/editor/?type=core&filename='.$_filename.'&complete=true';
            file_put_contents(ROOT.'/core/libs/'.$_filename, $_content);
        }else if($_type=='bootstrap'){
            $_done_url = '/admin/editor/?type=bootstrap&complete=true';
            file_put_contents(ROOT.'/core/bootstrap.php', $_content);
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_component_edited")." <a href=\"".$_done_url."\" target=\"_blank\">".(($_type=='bootstrap')?"bootstrap.php":$_filename)."</a>";
        $this->notify['object']->createNotification(-1, $n_text, "extension");
        
        // Возвращаем ответ
        header("Location: ".$_done_url); // Редирект
        exit(); // Выход
    }
    
    // Добавить компонент
    public function createComponent(){
        $this->_noAPI(); // Не для API
        
        // Поиск типа компонента
        if(isset($_POST['component_type']) && strlen($_POST['component_type'])>0){ // Есть тип
            $_type = strtolower($_POST['component_type']); // Указать тип
        }else{ // Ошибки нет
            $this->_doError("error_comtype_notfound", "editor"); // Ошибка
        }
        
        // Сравнение типа компонента
        if($_type!='controllers' && $_type!='models' && $_type!='modules' && $_type!='core'){
            $this->_doError("error_comtype_wrong", "editor"); // Ошибка
        }
        
        // Поиск имени компонента
        if(isset($_POST['component_name']) && strlen($_POST['component_name'])>0){ // Есть тип
            $_name = strtolower($_POST['component_name']); // Указать тип
        }else{ // Ошибки нет
            $this->_doError("error_comname_notfound", "editor"); // Ошибка
        }
        
        // Проверка имени компонента
        if(preg_match("/[^a-zA-Z\_]/", $_name) || strlen($_name)<3 || strlen($_name)>25){
            $this->_doError("error_comname_wrong", "editor"); // Ошибка
        }
        
        // Проверка шаблонизатора
        $_from_template = (isset($_POST['component_template']) && strlen($_POST['component_template'])>0)?true:false;
        
        // Если грузим из шаблона - загружаем шаблон
        if($_from_template){ // Из шаблона
            if(file_exists(ROOT.'/core/templates/'.$_type.'.php')){ // Есть шаблон
                $_content = file_get_contents(ROOT.'/core/templates/'.$_type.'.php'); // Загрузить шаблон
                $_content = str_replace("{{COMPONENT_NAME}}", $_name, $_content); // Замена
            }else{ // Шаблона нет
                $this->_doError("error_comtpl_notfound", "editor"); // Ошибка
            }
        }else{ // Нет
            $_content = "";
        }
        
        // Сохраняем
        if($_type=='controllers'){ // Контроллер
            $_newfm = 'class.'.$_name.'.php';
            $_done_url = '/admin/editor/?type=controllers&filename='.$_newfm.'&complete=true';
            if(file_exists(ROOT.'/core/controllers/'.$_newfm)){ // Уже существует
                $this->_doError("error_com_exists", "editor"); // Ошибка
            }
            
            // Сохранить файл
            file_put_contents(ROOT.'/core/controllers/'.$_newfm, $_content);
        }else if($_type=='models'){ // Модель
            $_newfm = 'class.'.$_name.'.php';
            $_done_url = '/admin/editor/?type=models&filename='.$_newfm.'&complete=true';
            if(file_exists(ROOT.'/core/models/'.$_newfm)){ // Уже существует
                $this->_doError("error_com_exists", "editor"); // Ошибка
            }
            
            // Сохранить файл
            file_put_contents(ROOT.'/core/models/'.$_newfm, $_content);
        }else if($_type=='modules'){ // Модуль
            $_newfm = $_name;
            $_done_url = '/admin/editor/?type=modules&filename='.$_newfm.'&complete=true';
            if(file_exists(ROOT.'/core/modules/'.$_newfm.'/module.php')){ // Уже существует
                $this->_doError("error_com_exists", "editor"); // Ошибка
            }
            
            // СОздать директорию модуля
            if(!file_exists(ROOT.'/core/modules/'.$_newfm.'/')){
                mkdir(ROOT.'/core/modules/'.$_newfm.'/'); // Создать директорию
            }
            
            // Сохранить файл
            file_put_contents(ROOT.'/core/modules/'.$_newfm.'/module.php', $_content);
        }else if($_type=='core'){ // Библиотека
            $_newfm = $_name.'.php';
            $_done_url = '/admin/editor/?type=core&filename='.$_newfm.'&complete=true';
            if(file_exists(ROOT.'/core/libs/'.$_newfm)){ // Уже существует
                $this->_doError("error_com_exists", "editor"); // Ошибка
            }
            
            // Сохранить файл
            file_put_contents(ROOT.'/core/libs/'.$_newfm, $_content);
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_component_created")." <a href=\"".$_done_url."\" target=\"_blank\">".$_newfm."</a>";
        $this->notify['object']->createNotification(-1, $n_text, "extension");
        
        // Возвращаем ответ
        header("Location: ".$_done_url); // Редирект
        exit(); // Выход
    }

    // Модуль пользователей (View)
    public function users(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Задаем базовые переменные
        $_currpage = (isset($_GET['nav']) && intval($_GET['nav'])>0)?$_GET['nav']:1; // Навигатор
        
        // Проверка флага
        $profile = false;
        if(isset($_GET['edit']) && strlen($_GET['edit'])>0){ // Редактор
            $_editor = true;
            $profile = $this->user->getProfile(intval($_GET['edit'])); // Все ок
            if(!$profile['complete']){ // Не получилось
                $_editor = false;
                $_error = $this->lang->line($get['code']); // Ошибка
            }
        }else{ // Не редактор
            $_editor = false;
        }
        
        // Определяем поисковый запрос
        $_search = false;
        if(isset($_GET['s']) && strlen($_GET['s'])>0){
            $_search = $this->db->query->escape($_GET['s']);
        }
        
        // Получение списка пользователей
        $_list = false;
        $_total = 1;
        if(!$_editor){ // Не редактор
            $_getList = $this->user->getUsersList($_currpage, $_search); // Получить данные
            if(!$_getList['complete']){ // Не удалось получить
                if(!$_error){ // Нет ошибки
                    $this->_doError($_getList["code"]); // Ошибка
                }
            }else{ // Все ок
                $_list = $_getList['list'];
                $_total = $_getList['total'];
            }
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/admin/users.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_users_title'),
            'desc'=>$this->lang->line('admin_users_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'page'=>$_currpage,
            'total'=>$_total,
            'list'=>$_list,
            'editor'=>(isset($_GET['edit']) && strlen($_GET['edit'])>0)?true:false,
            'tutorial'=>$this->_getTutorial("admin_user_editor"),
            'profile'=>$profile,
            'search'=>$_search
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Создание аккаунта
    public function createAccount(){
        // Проверить данные
        $_valid = $this->_validate_reg(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "users");
        }
        
        // Задаем данные регистрации
        $r_data = [
            'fullname'=>$_POST['fullname'],
            'login'=>$_POST['login'], 
            'password'=>$_POST['password'],
            'repass'=>$_POST['repass'],
            'email'=>$_POST['email'],
            'is_admin'=>($_POST['user_role']=="user")?0:1
        ];
        
        // Пройти регистрацию
        $reg = $this->user->reg_admin($r_data); // Авторизация
        if(!$reg['complete']){ // Ошибка
            $this->_doError($reg['code'], "users");
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_user_created")." <a href=\"/admin/users/?edit=".$reg['puid']."\" target=\"_blank\">".$this->db->query->escape($_POST['fullname'])."</a>";
        $this->notify['object']->createNotification(-1, $n_text, "person");
        
        // Возвращаем ответ
        header("Location: /admin/users/?edit=".$reg['puid']); // Редирект
        exit(); // Выход
    }
    
    // Блокировка пользователей
    public function banAccount(){
        // Проверка UID
        if(!isset($_POST['puid']) || intval($_POST['puid'])<1){
            $this->_doError("user_uid_required", "users");
        }
        
        // Проверка времени
        if(!isset($_POST['ban_time']) || ($_POST['ban_time']!="no" && $_POST['ban_time']!="day" && $_POST['ban_time']!="week" && $_POST['ban_time']!="month" && $_POST['ban_time']!="forever")){
            $this->_doError("ban_time_required", "users");
        }
        
        // Проверка причины
        if($_POST['ban_time']!="no"){
            if(!isset($_POST['ban_reasoned']) || (mb_strlen($_POST['ban_reasoned'])<10 || mb_strlen($_POST['ban_reasoned'])>100)){
                $this->_doError("ban_reason_required", "users");
            }else{
                $_reason = $this->db->query->escape($_POST['ban_reasoned']);
            }
        }else{
            $_reason = "";
        }
        
        // Удалить пользователя
        $ban = $this->user->banAccount(intval($_POST['puid']), $_POST['ban_time'], $_POST['ban_reasoned']); // Удалить аккаунт
        if(!$ban['complete']){ // Ошибка
            $this->_doError($ban['code'], "users");
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".(($_POST['ban_time']=="no")?$this->lang->line("noty_user_unbanned"):$this->lang->line("noty_user_banned"))." #".$_POST['puid'];
        $this->notify['object']->createNotification(-1, $n_text, "person");
        
        // Возвращаем ответ
        header("Location: /admin/users/?complete=true"); // Редирект
        exit(); // Выход
    }

    // Удалить пользователя
    public function removeAccount(){
        // Проверка UID
        if(!isset($_GET['uid']) || intval($_GET['uid'])<1){
            $this->_doError("user_uid_required", "users");
        }
        
        // Удалить пользователя
        $remove = $this->user->removeUser(intval($_GET['uid'])); // Удалить аккаунт
        if(!$remove['complete']){ // Ошибка
            $this->_doError($remove['code'], "users");
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_user_removed")." #".$_GET['uid'];
        $this->notify['object']->createNotification(-1, $n_text, "person");
        
        // Возвращаем ответ
        header("Location: /admin/users/?complete=true"); // Редирект
        exit(); // Выход
    }
    
    // Сохранить профиль
    public function saveProfile(){
        // Проверить UID
        if(!isset($_POST['uid']) || mb_strlen($_POST['uid'])<1){ // UID не задан
            $this->_doError("user_uid_required", "users");
        }else{ // Все ок
            $_uid = intval($_POST['uid']);
        }
        
        
        // Проверить данные
        $_valid = $this->_validate_profile(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "users", "edit=".$_uid);
        }
        
        // Задаем данные профиля
        $r_data = [
            'avatar'=>(isset($_POST['avatar']) && strlen($_POST['avatar'])>0)?$this->db->query->escape($_POST['avatar']):"",
            'nickname'=>$this->db->query->escape($_POST['nickname']),
            'email'=>$this->db->query->escape($_POST['email']),
            'profile_data'=>[
                'phone'=>(isset($_POST['profile_data']['phone']) && strlen($_POST['profile_data']['phone'])>0)?$this->db->query->escape($_POST['profile_data']['phone']):"",
                'birthday'=>(isset($_POST['profile_data']['birthday']) && strlen($_POST['profile_data']['birthday'])>0)?$this->db->query->escape($_POST['profile_data']['birthday']):"",
            ]
        ];
        
        // Обновить профиль
        $upd = $this->user->updateProfile($_uid, $r_data); // Авторизация
        if(!$upd['complete']){ // Ошибка
            $this->_doError($upd['code'], "users", "edit=".$_uid);
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_user_saved")." <a href=\"/admin/users/?edit=".$_uid."\" target=\"_blank\">".$this->db->query->escape($_POST['nickname'])."</a>";
        $this->notify['object']->createNotification(-1, $n_text, "person");
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_user_saved_self");
        $this->notify['object']->createNotification($_uid, $n_text, "person");
        
        // Возвращаем ответ
        header("Location: /admin/users/?edit=".$_uid."&complete=true"); // Редирект
        exit(); // Выход
    }
    
    // Валидация данных (Сохранение профиля)
    private function _validate_profile(){
        // Проверка существования
        if(!isset($_POST['nickname']) || strlen($_POST['nickname'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"fullname_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['email']) || strlen($_POST['email'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"email_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }

    // Валидация данных (Регистрация)
    private function _validate_reg(){
        // Проверка существования
        if(!isset($_POST['fullname']) || strlen($_POST['fullname'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['login']) || strlen($_POST['login'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Проверка сущестования пароля
        if(!isset($_POST['password']) && strlen($_POST['password'])<1){ // Пароль задан
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['email']) || strlen($_POST['email'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['repass']) || strlen($_POST['repass'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Проверка роли
        if(!isset($_POST['user_role']) || ($_POST['user_role']!="admin" && $_POST['user_role']!="user")){
            return ['complete'=>false, 'code'=>"user_creation_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }

    // Модуль аналитики (View)
    public function analytics(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Задаем базовые переменные
        $_currpage = (isset($_GET['nav']) && intval($_GET['nav'])>0)?$_GET['nav']:1; // Навигатор
        
        // Получаем данные
        $_amodel = $this->load_model("analytics", []); // Получаем модель
        $_getList = $_amodel->getAnalyticsList($_currpage); // Получить данные
        if(!$_getList['complete']){ // Не удалось получить
            if(!$_error){ // Нет ошибки
                $this->_doError($_getList["code"]); // Ошибка
            }
        }else{ // Все ок
            $_list = $_getList['list'];
            $_total = $_getList['total'];
        }
        
        // Получаем данные о последних посетителях за сегодня
        $_last_visitors = false;
        $_getVisitors = $_amodel->getVisitorsToday(); // Получить данные
        if(!$_getVisitors['complete']){ // Ошибка
            if(!$_error){ // Нет ошибки
                $this->_doError($_getVisitors["code"]); // Ошибка
            }
        }else{ // Все ок
            $_last_visitors = $_getVisitors['v_data'];
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/admin/analytics.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_analytics_title'),
            'desc'=>$this->lang->line('admin_analytics_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'page'=>$_currpage,
            'total'=>$_total,
            'last_visitors'=>$_last_visitors,
            'list'=>$_list
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Модуль уведомлений (View)
    public function notifications(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Чтение уведомлений
        $read = $this->notify['object']->readAll(); // Чтение
        if(!$read['complete']){
            header("Location: /errors/?code=500");
            exit();
        }
        
        // Определяем страницу
        $_currpage = (isset($_GET['nav']) && intval($_GET['nav'])>0)?$_GET['nav']:1; // Навигатор
        
        // Получаем уведомления
        $get = $this->notify['object']->getNotificationsList($_currpage, 20);
        if(!$get['complete']){
            header("Location: /errors/?code=500");
            exit();
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/admin/notifications.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_notifications_title'),
            'desc'=>$this->lang->line('admin_notifications_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'page'=>$_currpage,
            'total'=>$get['total'],
            'list'=>$get['list']
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Чтение уведомлений
    public function readAllNotifications(){
        $this->_noFrontend(); // Не для Frontend
        $this->_noProfile(); // Нет профиля
        
        // Читаем уведомления
        $read = $this->notify['object']->readAll(); // Чтение
        if(!$read['complete']){ // Ошибка
            $_render = $this->render("", false, ['message'=>$this->lang->line($read['code'])]); // Рендер
        }
        
        // Все ок
        $_render = $this->render("", true, []); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Удалить уведомление
    public function removeNotification(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        if(!isset($_GET['uid']) || mb_strlen($_GET['uid'])<1){
            header("Location: /errors/?code=500");
            exit();
        }
        
        // Удалить уведомление
        $remove = $this->notify['object']->remove($_GET['uid']); // Удалить
        if(!$remove['complete']){ // Ошибка
            $this->_doError($remove['code'], "notificaions");
        }
        
        // Возвращаем ответ
        header("Location: /admin/notifications/?complete=true"); // Редирект
        exit(); // Выход
    }

    // Модуль упрваления страницами (View)
    public function pages(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Задаем базовые переменные
        $_pagelist = false; // Список страниц
        $_pagedata = false; // Данные страницы
        $_currpage = 0; // Текущая страница
        $_langname = (isset($_GET['lng']) && strlen($_GET['lng'])>0 && file_exists(ROOT.'/core/langs/'.strtoupper($_GET['lng']).'/'))?strtoupper($_GET['lng']):strtoupper($this->lang->curr_lang);
        
        // Поиск флага просмотра страницы
        if(isset($_GET['page']) && strlen($_GET['page'])>0){ // Есть UID страницы
            $_page_uid = $this->db->query->escape($_GET['page']); // UID страницы
            if(preg_match("/[^0-9a-zA-Z\-\_]/", $_page_uid)){
                $_page_uid = false;
            }
        }else{ // Нет UID
            $_page_uid = false;
        }

        // Инициализируем модель
        $pages = $this->load_model("static", [ // Получаем модель
            'num'=>(isset($_GET['num']) && intval($_GET['num'])>0)?$_GET['num']:20, // Количество на страницу
            'lang'=>$_langname // Название языка
        ]);
        
        // Получаем языки
        $_langs = json_decode(file_get_contents(ROOT.'/core/langs/list.json'), true);
        if(!$_langs){
            header("Location: /errors/?code=500");
            exit();
        }
        
        // В зависимости от UID
        $_search = false;
        $_total = 1;
        $_list = false; // Сброс списка
        $_default_language = false;
        if(!$_page_uid){ // UID не задан
            // Определяем поисковый запрос
            if(isset($_GET['s']) && strlen($_GET['s'])>0){
                $_search = $this->db->query->escape($_GET['s']);
            }
            
            $_currpage = (isset($_GET['nav']) && intval($_GET['nav'])>0)?$_GET['nav']:1; // Навигатор
            $_pagelist = $pages->getPagesList($_currpage, $_search); // Получить список страниц
            if(!$_pagelist['complete']){
                $_error = $this->lang->line($_pagelist['code']); // Ошибка
                $_list = false; // Сброс списка
            }else if(count($_pagelist['list'])<1){
                $_list = []; // Сброс списка
            }else{
                $_list = $_pagelist['list'];
                $_total = $_pagelist['total'];
            }
        }else{ // UID задан
            // Запрос контента страницы
            $_getContent = $pages->getPageContent($_page_uid);
            if(!$_getContent['complete']){
                $this->_doError($_getContent['code'], "pages"); // Ошибка
            }
            
            // Данные страницы
            $_pagedata = $_getContent['data'];
            $_default_language = $_getContent['default_language'];
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/admin/pages.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_pages_title'),
            'desc'=>$this->lang->line('admin_pages_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'page_uid'=>$_page_uid,
            'list'=>$_list,
            'page_data'=>$_pagedata,
            'langs'=>$_langs,
            'total'=>$_total,
            'editor'=>(isset($_GET['editor']) && $_GET['editor']=='true')?true:false,
            'page'=>$_currpage,
            'tutorial'=>$this->_getTutorial("admin_page_editor"),
            'search'=>$_search,
            'default_language'=>$_default_language
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Сохранить страницу
    public function savePage($fd = []){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        $_new = (isset($_POST['new']) && $_POST['new']=="true")?true:false;
        
        // Инициализируем модель
        $pages = $this->load_model("static", [ // Получаем модель
        ]);
        
        // Получаем языки
        $_langs = json_decode(file_get_contents(ROOT.'/core/langs/list.json'), true);
        if(!$_langs){
            header("Location: /errors/?code=500");
            exit();
        }
        
        // Валидация данных (Существование данных)
        if(!isset($_POST['data']) || !is_array($_POST['data']) || count($_POST['data'])<0){
            header("Location: /errors/?code=500");
            exit();
        }else{
            $_data = $_POST['data'];
        }
        
        // Валидация данных (массив данных)
        $_rndlang = '';
        foreach($_data as $lng=>$inner){ // Поиск данных
            $_rndlang = $lng;
            if(!isset($inner['uid']) || strlen($inner['uid'])<1) $_data[$lng]['uid']=false; // Нужно создать страницу
            
            // Проверка заголовка
            if(!isset($inner['title']) || mb_strlen($inner['title'])<1){
                $this->_doError("error_page_title", "pages", "editor=true");
            }else{
                $_data[$lng]['title'] = addslashes(strip_tags($inner['title']));
            }
            
            // Проверка длинны
            if(mb_strlen($inner['title'])<3 || mb_strlen($inner['title'])>50){
                $this->_doError("error_page_title_length", "pages", "editor=true");
            }
            
            // Проверка системного имени
            if(!isset($inner['slug']) || mb_strlen($inner['slug'])<1){
                $this->_doError("error_page_slug", "pages", "editor=true");
            }else{
                $_data[$lng]['slug']=strtolower($inner['slug']);
            }
            
            // Проверка символов
            if(preg_match("/[^0-9a-z\-\_]+$/u", $inner['slug'])){
                $this->_doError("error_page_slug_symbols", "pages", "editor=true");
            }
            
            // Проверка длинны
            if(mb_strlen($inner['slug'])<3 || mb_strlen($inner['slug'])>50){
                $this->_doError("error_page_slug_length", "pages", "editor=true");
            }
            
            // Проверка краткого описания страницы
            if(!isset($inner['desc']) || mb_strlen($inner['desc'])<1){
                $this->_doError("error_page_desc", "pages", "editor=true");
            }else{
                $_data[$lng]['desc'] = addslashes(strip_tags($inner['desc']));
            }
            
            // Проверка длинны
            if(mb_strlen($inner['desc'])<10 || mb_strlen($inner['desc'])>250){
                $this->_doError("error_page_desc_length", "pages", "editor=true");
            }
            
            // Проверка ключевых слов страницы
            if(!isset($inner['tags']) || mb_strlen($inner['tags'])<1){
                $_data[$lng]['tags'] = '';
            }else{
                $_data[$lng]['tags'] = addslashes(strip_tags($inner['tags']));
            }
            
            // Проверка длинны
            if(mb_strlen($inner['tags'])>250){
                $this->_doError("error_page_tags_length", "pages", "editor=true");
            }
            
            // Проверка тела страницы
            if(!isset($inner['body']) || mb_strlen($inner['body'])<1){
                $this->_doError("error_page_body", "pages", "editor=true");
            }
            
            // Проверка URL фотографии страницы
            if(!isset($_POST['image']) || !file_exists($_SERVER['DOCUMENT_ROOT'].$_POST['image'])){
                $_data[$lng]['image'] = "";
            }else{
                $_data[$lng]['image'] = $_POST['image'];
            }
        }
        
        // Сохранение данных
        $_save = $pages->savePage($_data, $_data[$_rndlang]['slug'], $_new); // Сохранить
        if(!$_save['complete']){ // Сохранение не удалось
            $this->_doError($_save['code'], "pages", "editor=true");
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_page_saved")." <a href=\"/admin/pages/?editor=true&page=".$_data[$_rndlang]['slug']."\" target=\"_blank\">".$_data[$lng]['title']."</a>";
        $this->notify['object']->createNotification(-1, $n_text, "insert_drive_file");
        
        // Все хорошо
        header("Location: /admin/pages/?complete=true");
        exit();
    }
    
    // Удалить страницу
    public function removePage(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        if(!isset($_GET['uid']) || mb_strlen($_GET['uid'])<1){
            $this->_doError("page_uid_required", "pages");
        }
        
        // Инициализируем модель
        $pages = $this->load_model("static", [ // Получаем модель
        ]);
        
        // Удалить пользователя
        $remove = $pages->removePage($_GET['uid']); // Удалить аккаунт
        if(!$remove['complete']){ // Ошибка
            $this->_doError($remove['code'], "pages");
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_page_removed")." #".$_GET['uid'];
        $this->notify['object']->createNotification(-1, $n_text, "insert_drive_file");
        
        // Возвращаем ответ
        header("Location: /admin/pages/?complete=true"); // Редирект
        exit(); // Выход
    }

    // Модуль управления медиа-файлами (View)
    public function media(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Получить файлы
        $_filelist = glob(GLOBALROOT.'/media/*.{png,jpg,jpeg}', GLOB_BRACE); // Поиск медиа
        
        // Удалить кеш
        if(isset($_GET['complete']) && $_GET['complete']=="true"){
            $this->cache->removeCache("admin_media");
        }
        
        // Настраиваем
        $this->cache->getCache("admin_media"); // Получить кеш
        $_template = FRONTEND.'/view/admin/media.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_media_title'),
            'desc'=>$this->lang->line('admin_media_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'tutorial'=>$this->_getTutorial("admin_media_manager"),
            'list'=>$_filelist
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Все ок
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
    
    // Удалить Media
    public function removeMedia(){
        $this->_noFrontend(); // Редирект для фронтенда
        $this->_noProfile(); // Профиль не загружен
        
        // Проверка имени
        if(isset($_POST['media_file']) && strlen($_POST['media_file']) && file_exists($_SERVER['DOCUMENT_ROOT'].'/media/'.$_POST['media_file']) && is_file($_SERVER['DOCUMENT_ROOT'].'/media/'.$_POST['media_file'])){ // Файл существует
            unlink($_SERVER['DOCUMENT_ROOT'].'/media/'.$_POST['media_file']); // Удалить файл
        }else{ // Ошибка удаления
            $_render = $this->render("", false, ['message'=>'Failed to remove file: '.$_POST['media_file']]); // Рендер
        }
        
        // Создать уведомление
        $n_user = '<a href="/admin/users/?edit='.$this->user->auth['profile_uid'].'" target="_blank">'.($this->user->profile['nickname']!=""?$this->user->profile['nickname']:$this->user->auth['login'])."</a>";
        $n_text = $n_user." ".$this->lang->line("noty_media_removed")." ".$_POST['media_file'];
        $this->notify['object']->createNotification(-1, $n_text, "image");
        
        // Все ок
        $_render = $this->render("", true, []); // Рендер
    }
    
    // Модуль упрваления кешем (View)
    public function cache(){
        $this->_noAPI(); // Не для API
        $this->_noProfile(); // Профиль не загружен
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Определяем размер кеша
        $_size = 0; // Начальный размер
        $_size = $this->getDirectorySize($_SERVER['DOCUMENT_ROOT'].'/cache/'); // Получить размер директории
        $_size = $_size/1024/1024; // Конверсия в МБ
        $_size = round($_size); // Округляем
        $_size = number_format($_size, 0); // Преобразовать
        
        // Определяем количество
        $_list = glob($_SERVER['DOCUMENT_ROOT'].'/cache/*.cache'); // Считываем список
        $_count = 0; // Создаем счетчик
        foreach($_list as $val){ // Перебор списка
            $_count++; // Подсчет
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/admin/cache_manager.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('admin_cache_title'),
            'desc'=>$this->lang->line('admin_cache_desc'),
            'user'=>$this->user->profile,
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'size'=>$_size,
            'count'=>$_count,
            'list_count'=>($_count>20)?20:$_count,
            'list'=>$_list
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Очистка кеша
    public function clearCache(){
        $this->cache->clearCache(); // Очистить кеш
        
        // Все ок
        header("Location: /admin/cache/?complete=true");
        exit();
    }
    
    // Удалить кеш
    public function removeCache(){
        // Если есть имя файла
        if(isset($_GET['file']) && strlen($_GET['file'])>0 && file_exists($_SERVER['DOCUMENT_ROOT'].'/cache/'.$_GET['file'])){ // Ок
            $this->cache->removeByName($_GET['file']); // Удалить кеш
        }else{ // Кеш не найден
            $this->_doError("cache_file_not_found", "cache");
        }
        
        // Все ок
        header("Location: /admin/cache/?complete=true");
        exit();
    }

    // Проверка загрузки профиля
    private function _noProfile(){
        // Получаем профиль пользователя
        $_get_user = $this->user->getProfile("my"); // Получить профиль
        if(!$_get_user['complete']){ // Профиль не получен
            header("Location: /error/?code=500");
            exit();
        }
    }

    // Выбить ошибку для API
    private function _noAPI(){
        // Для API - выбиваем ошибку
        if(!defined("FRONTEND")){ // Запрос через API
            $return = ['message'=>$this->lang->line('not_api_controller')]; // Задаем ошибку
            $this->render("", false, $return); // Рендер
        }
    }
    
    // Не Frontend
    private function _noFrontend(){
        // Для API - выбиваем ошибку
        if(defined("FRONTEND")){ // Запрос через API
            header("Location: /errors/?code=404");
            exit();
        }
    }

    // Отправить ошибку
    private function _doError($code, $module = "init", $args = ""){
        $_end = (!isset($args) || $args=="")?$code:$code."&".$args;
        header("Location: /admin/".$module."/?error=".$_end); // Выполняем редирект
        exit();
    }
    
    // Получить флаг туториала
    private function _getTutorial($name){
        $_tutorial = (isset($_COOKIE['tutorials_'.$name]) && strlen($_COOKIE['tutorials_'.$name])>0)?1:0; // Флаг обучения
        if($_tutorial==0) setcookie('tutorials_'.$name, "complete", time()+(365*24*60*60), "/"); // Установить флаг
        return $_tutorial; // Вернуть флаг
    }
    
    // Получить размер директории
    private function getDirectorySize($path){
        $bytestotal = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }
}
?>