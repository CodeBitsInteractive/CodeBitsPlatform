<?php
//===============================================
//  Файл:           class.profile.php
//  Назначение:     Контроллер профилей
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_profile extends BaseController{
    var $notify = null;
    
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
        
        // Проверка авторизации
        if(!$this->user->auth['is_auth']){ // Не авторизован
            header("Location: /auth/sign_in/?redirect=http://".DOMAIN."/profile/");
            exit();
        }
        
        // Получаем модель уведомлений
        $this->notify['object'] = $this->load_model("notifications", []); // Нотификации
    }
    
    // Инициализация модуля
    public function init($data){
        // Получаем языковой пакет
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Получаем профиль
        $get = $this->user->getProfile("my"); // Профиль
        if(!$get['complete']){ // Ошибка
            header("Location: /errors/?code=500");
            exit();
        }
        
        // Удалить кеш
        if(isset($_GET['complete']) && $_GET['complete']=="true"){
            $this->cache->removeCache("profile");
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/website/profile.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('profile_title'),
            'desc'=>$this->lang->line('profile_desc'),
            'tags'=>$this->lang->line('profile_keywords'),
            'error'=>$_error,
            'complete'=>(isset($_GET['complete']) && $_GET['complete']=="true")?true:false,
            'profile'=>$this->user->profile
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }
    
    // Редактор
    public function edit(){
        // Получаем языковой пакет
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Получаем профиль
        $get = $this->user->getProfile("my"); // Профиль
        if(!$get['complete']){ // Ошибка
            header("Location: /admin/profile?error=".$get['code']);
            exit();
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/website/profile_editor.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('c_edit_profile_title'),
            'desc'=>$this->lang->line('c_edit_profile_desc'),
            'tags'=>$this->lang->line('c_edit_profile_tags'),
            'error'=>$_error,
            'profile'=>$this->user->profile
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }


    // Смена пароля
    public function change_password(){
        // Получаем языковой пакет
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $_template = FRONTEND.'/view/website/change_password.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('c_edit_password_title'),
            'desc'=>$this->lang->line('c_edit_password_desc'),
            'tags'=>$this->lang->line('c_edit_password_tags'),
            'error'=>$_error,
            'profile'=>$this->user->profile
        ];
        
        // Вывод данных
        $_render = $this->render($_template, false, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }

    // Сохранить профиль
    public function saveProfile(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        $_uid = $this->user->auth['profile_uid']; // UID
        
        // Проверить данные
        $_valid = $this->_validate_profile(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            header("Location: /profile/edit/?error=".$_valid['code']);
            exit();
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
            header("Location: /profile/edit/?error=".$upd['complete']);
            exit();
        }
        
        // Создать уведомление
        $n_text = $this->lang->line("profile_saved_self");
        $this->notify['object']->createNotification($_uid, $n_text, "person");
        
        // Возвращаем ответ
        header("Location: /profile/?complete=true"); // Редирект
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
    
    // Сохранить пароль
    public function savePassword(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        $_uid = $this->user->auth['profile_uid']; // UID
        
        // Проверить данные
        $_valid = $this->_validate_passwords(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            header("Location: /profile/change_password/?error=".$_valid['code']);
            exit();
        }
        
        // Указываем данные
        $r_data = [
            'password'=>$this->db->query->escape($_POST['password']),
            'new_password'=>$this->db->query->escape($_POST['new_password']),
            'repass'=>$this->db->query->escape($_POST['repass']),
        ];
        
        // Обновить пароль
        $upd = $this->user->updatePassword($r_data); // Авторизация
        if(!$upd['complete']){ // Ошибка
            header("Location: /profile/change_password/?error=".$upd['code']);
            exit();
        }
        
        // Создать уведомление
        $n_text = $this->lang->line("password_changed_self");
        $this->notify['object']->createNotification($_uid, $n_text, "security");
        
        // Возвращаем ответ
        header("Location: /profile/?complete=true"); // Редирект
        exit(); // Выход
    }
    
    // Валидация данных (Пароли)
    private function _validate_passwords(){
        // Проверка существования
        if(!isset($_POST['password']) || strlen($_POST['password'])<1){
            return ['complete'=>false, 'code'=>'password_required'];
        }
        
        // Проверка длинны
        if(strlen($_POST['password'])<6 || strlen($_POST['password'])>32){
            return ['complete'=>false, 'code'=>'password_length'];
        }
        
        // Проверка существования
        if(!isset($_POST['new_password']) || strlen($_POST['new_password'])<1){
            return ['complete'=>false, 'code'=>'new_password_required'];
        }
        
        // Проверка длинны
        if(strlen($_POST['new_password'])<6 || strlen($_POST['new_password'])>32){
            return ['complete'=>false, 'code'=>'new_password_length'];
        }
        
        // Проверка существования
        if(!isset($_POST['repass']) || strlen($_POST['repass'])<1){
            return ['complete'=>false, 'code'=>'repass_required'];
        }
        
        // Проверка совпадения
        if($_POST['repass']!=$_POST['new_password']){
            return ['complete'=>false, 'code'=>'pass_no_equal'];
        }
        
        // Проверка другого совпадения
        if($_POST['password']==$_POST['new_password']){
            return ['complete'=>false, 'code'=>'pass_equal'];
        }
        
        // Все ок
        return ['complete'=>true];
    }


    // Выбить ошибку для API
    private function _noAPI(){
        // Для API - выбиваем ошибку
        if(!defined("FRONTEND")){ // Запрос через API
            $return = ['message'=>$this->lang->line('not_api_controller')]; // Задаем ошибку
            $this->render("", false, $return); // Рендер
        }
    }
}