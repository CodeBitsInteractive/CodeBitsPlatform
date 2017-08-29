<?php
//===============================================
//  Файл:           class.auth.php
//  Назначение:     Контроллер авторизации
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_auth extends BaseController{
    var $redirect = ""; // Редирект при успешном входе
    
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
        
        // Если есть параметр редиректа
        if(isset($_SESSION['rdr'])) $this->redirect=$_SESSION['rdr'];
    }
    
    // Инициализация модуля
    public function init($fd = []){
        // Для API - выбиваем ошибку
        if(!defined("FRONTEND")){ // Запрос через API
            $return = ['message'=>$this->lang->line('not_api_controller')]; // Задаем ошибку
            $this->render("", false, $return); // Рендер
        }
        
        // Перенаправляем на форму входа
        header("Location: /auth/sign_in/");
        exit();
    }
    
    // Метод отображения формы входа
    public function sign_in($fd = []){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Поиск флага редиректа
        if(isset($_GET['redirect']) && strlen($_GET['redirect']) && filter_var(urldecode($_GET['redirect']), FILTER_VALIDATE_URL)){
            $this->redirect = $_GET['redirect']; // URL редиректа
            $_SESSION['rdr']=$this->redirect; // Редирект
        }
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $this->cache->getCache("signin"); // Получить кеш
        $_template = FRONTEND.'/view/common/auth.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('signin_title'),
            'desc'=>$this->lang->line('signin_desc'),
            'error'=>$_error
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
    
    // Метод отображения формы регистрации
    public function sign_up(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Если регистрация отключена
        if(!$this->config->line['users']['registration']){
            header("Location: /error/?code=404"); // Выполняем редирект на ошибку
            exit();
        }
        
        // Поиск флага редиректа
        if(isset($_GET['redirect']) && strlen($_GET['redirect']) && filter_var(urldecode($_GET['redirect']), FILTER_VALIDATE_URL)){
            $this->redirect = $_GET['redirect']; // URL редиректа
            $_SESSION['rdr']=$this->redirect; // Редирект
        }
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $this->cache->getCache("signup"); // Получить кеш
        $_template = FRONTEND.'/view/common/reg.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('signup_title'),
            'desc'=>$this->lang->line('signup_desc'),
            'error'=>$_error
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

    // Метод отображения формы сброса пароля
    public function forgot(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $this->cache->getCache("forgot"); // Получить кеш
        $_template = FRONTEND.'/view/common/forgot.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('forgot_title'),
            'desc'=>$this->lang->line('forgot_desc'),
            'error'=>$_error
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
    
    // Метод отображения страницы подтверждения
    public function confirm(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Смотрим есть ли флаг сообщения
        $_message = (isset($_GET['complete']) && $_GET['complete']=="true")?"confirm_message_2":"confirm_message_1";
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Настраиваем
        $this->cache->getCache("confirm"); // Получить кеш
        $_template = FRONTEND.'/view/common/confirm.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('confirm_title'),
            'desc'=>$this->lang->line('confirm_desc'),
            'message'=>$this->lang->line($_message),
            'error'=>$_error
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
    
    // Метод отображения страницы сброса пароля
    public function reset(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Поиск флага ошибки
        if(isset($_GET['error']) && strlen($_GET['error'])>0 && $this->lang->line($_GET['error'])!==false){ // Есть ошибка
            $_error = $this->lang->line($_GET['error']); // Ошибка
        }else{ // Ошибки нет
            $_error = false; // Ошибка
        }
        
        // Поиск кода
        if(isset($_GET['code']) && strlen($_GET['code'])>0){ // Код есть
            $_code = strip_tags($_GET['code']);
        }else{ // Кода нет
            $_code = false;
        }
        
        // Настраиваем
        $this->cache->getCache("reset"); // Получить кеш
        $_template = FRONTEND.'/view/common/reset.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('reset_title'),
            'desc'=>$this->lang->line('reset_desc'),
            'error'=>$_error,
            'code'=>$_code
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
    
    // Метод отображения страницы завершения сброса
    public function reset_complete(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Уже авторизован
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Настраиваем
        $this->cache->getCache("reset_complete"); // Получить кеш
        $_template = FRONTEND.'/view/common/reset_complete.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('reset_title'),
            'desc'=>$this->lang->line('reset_desc')
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

    // Вход
    public function login($fd = []){
        // Проверка авторизации
        if($this->user->auth['is_auth']){ // Есть авторизация
            if(defined("WRAPPER") && WRAPPER){ // Для веба
                $this->_doRedirect(); // Сделать редирект
            }else{ // Для API
                $_render = $this->render("", true, $this->user->auth); // Рендер
            }
        }
        
        // Проверить данные
        $_valid = $this->_validate_login(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code']);
        }
        
        // Задаем данные авторизации
        $a_data = [
            'login'=>$_POST['login'], 
            'password'=>$_POST['password']
        ];
        
        // Пройти авторизацию
        $auth = $this->user->login($a_data); // Авторизация
        if(!$auth['complete']){ // Ошибка
            $this->_doError($auth['code']);
        }
        
        // Все прошло успешно
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            $this->_doRedirect(); // Сделать редирект
        }else{ // Для API
            $_render = $this->render("", true, $this->user->auth); // Рендер
        }
    }
    
    // Валидация данных (Авторизация)
    private function _validate_login(){
        // Проверка существования
        if(!isset($_POST['login']) || strlen($_POST['login'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"login_required"]; // Валидация не удалась
        }
        
        // Проверка сущестования пароля
        if(!isset($_POST['password']) && strlen($_POST['password'])<1){ // Пароль задан
            return ['complete'=>false, 'code'=>"password_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Начать сброс пароля
    public  function start_reset($fd = []){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Проверка авторизации
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Проверить данные
        $_valid = $this->_validate_forgot(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "forgot");
        }
        
        // Задаем данные сброса
        $r_data = [
            'email'=>$_POST['email']
        ];
        
        // Инициализировать сброс
        $reset = $this->user->start_reset($r_data); // Сброс
        if(!$reset['complete']){ // Ошибка
            $this->_doError($reset['code'], "forgot");
        }
        
        // Все прошло успешно
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            $this->_doRedirect("/auth/reset/"); // Сделать редирект
        }
    }
    
    // Завершить сброс пароля
    public function complete_reset($fd = []){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Проверка авторизации
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Проверить данные
        $_valid = $this->_validate_reset(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            header("Location: /auth/reset/?error=".$_valid['code']."&code=".$fd['code']); // Выполняем редирект
            exit();
        }
        
        // Задаем данные сброса
        $r_data = [
            'password'=>$_POST['password'],
            'repass'=>$_POST['repass'],
            'code'=>$_POST['code']
        ];
        
        // Пройти сброс пароля
        $reset = $this->user->complete_reset($r_data); // Сброс
        if(!$reset['complete']){ // Ошибка
            header("Location: /auth/reset/?error=".$reset['code']."&code=".$fd['code']); // Выполняем редирект
            exit();
        }
        
        // Все прошло успешно
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            $this->_doRedirect("/auth/reset_complete/"); // Сделать редирект
        }
    }
    
    // Валидация данных (сброс пароля)
    private function _validate_reset(){
        // Проверка существования
        if(!isset($_POST['code']) || strlen($_POST['code'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"code_required"]; // Валидация не удалась
        }
        
        // Проверка сущестования пароля
        if(!isset($_POST['password']) && strlen($_POST['password'])<1){ // Пароль задан
            return ['complete'=>false, 'code'=>"password_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['repass']) || strlen($_POST['repass'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"repass_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Валидация данных (забыли пароль)
    private function _validate_forgot(){
        // Проверка существования
        if(!isset($_POST['email']) || strlen($_POST['email'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"email_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Регистрация
    public function registration(){
        // Проверка авторизации
        if($this->user->auth['is_auth']){ // Есть авторизация
            if(defined("WRAPPER") && WRAPPER){ // Для веба
                $this->_doRedirect(); // Сделать редирект
            }else{ // Для API
                $_render = $this->render("", true, $this->user->auth); // Рендер
            }
        }
        
        // Проверить данные
        $_valid = $this->_validate_reg(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "sign_up");
        }
        
        // Задаем данные регистрации
        $r_data = [
            'fullname'=>$_POST['fullname'],
            'login'=>$_POST['login'], 
            'password'=>$_POST['password'],
            'repass'=>$_POST['repass'],
            'email'=>$_POST['email']
        ];
        
        // Пройти регистрацию
        $reg = $this->user->reg($r_data); // Авторизация
        if(!$reg['complete']){ // Ошибка
            $this->_doError($reg['code'], "sign_up");
        }
        
        // Все прошло успешно
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            if($this->config->line['users']['email_confirm']){ // Нужно подтверждение email
                header("Location: /auth/confirm/");
                exit();
            }else{ // Не нужно подтверждение Email
                $this->_doRedirect(); // Сделать редирект
            }
        }else{ // Для API
            if($this->config->line['users']['email_confirm']){ // Нужно подтверждение email
                $_render = $this->render("", true, ['confirm'=>true]); // Рендер
            }else{ // Не нужно подтверждение Email
                $_render = $this->render("", true, $this->user->auth); // Рендер
            }
        }
    }
    
    // Валидация данных (Регистрация)
    private function _validate_reg(){
        // Проверка существования
        if(!isset($_POST['fullname']) || strlen($_POST['fullname'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"fullname_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['login']) || strlen($_POST['login'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"login_required"]; // Валидация не удалась
        }
        
        // Проверка сущестования пароля
        if(!isset($_POST['password']) && strlen($_POST['password'])<1){ // Пароль задан
            return ['complete'=>false, 'code'=>"password_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['email']) || strlen($_POST['email'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"email_required"]; // Валидация не удалась
        }
        
        // Проверка существования
        if(!isset($_POST['repass']) || strlen($_POST['repass'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"repass_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Подтверждение аккаунта
    public function approve(){
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Проверка авторизации
        if($this->user->auth['is_auth']){ // Есть авторизация
            $this->_doRedirect(); // Сделать редирект
        }
        
        // Проверить данные
        $_valid = $this->_validate_code(); // Валидация
        if(!$_valid['complete']){ // Ошибка
            $this->_doError($_valid['code'], "confirm");
        }
        
        // Задаем данные активации
        $a_data = [
            'code'=>$_GET['code']
        ];
        
        // Попытка подтверждения пароля
        $auth = $this->user->confirmEmail($a_data); // Авторизация
        if(!$auth['complete']){ // Ошибка
            $this->_doError($auth['code'], "confirm");
        }
        
        // Все прошло успешно
        header("Location: /auth/confirm/?complete=true");
        exit();
    }
    
    // Валидация данных (Код подтверждения)
    private function _validate_code(){
        // Проверка существования
        if(!isset($_GET['code']) || strlen($_GET['code'])<1){ // Нет данных
            return ['complete'=>false, 'code'=>"code_required"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Выход из системы
    public function logout(){
        // Поиск флага редиректа
        if(isset($_GET['redirect']) && strlen($_GET['redirect']) && filter_var(urldecode($_GET['redirect']), FILTER_VALIDATE_URL)){
            $this->redirect = $_GET['redirect']; // URL редиректа
            $_SESSION['rdr']=$this->redirect; // Редирект
        }
        
        // Выход из системы
        $_logout = $this->user->logout(); // Выйти
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            $this->_doRedirect("/auth/sign_in/"); // Сделать редирект
        }else{ // Для API
            $_render = $this->render("", true, []); // Рендер
        }
    }

    // Отправить ошибку
    private function _doError($code, $module = "sign_in"){
        if(defined("WRAPPER") && WRAPPER){ // Для веба
            header("Location: /auth/".$module."/?error=".$code); // Выполняем редирект
            exit();
        }else{ // Для API
            $_render = $this->render("", false, ['message'=>$this->lang->line($code)]); // Рендер
        }
    }
    
    // Сделать редирект
    private function _doRedirect($default = "/profile/"){
        if ($this->redirect != "") { // Есть редирект
            header("Location: " . urldecode($this->redirect)); // Выполняем редирект
            unset($_SESSION['rdr']);
            exit();
        } else { // Нет редиректа
            header("Location: ".$default); // Выполняем редирект на профиль
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
}
?>