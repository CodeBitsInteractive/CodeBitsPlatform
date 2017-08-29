<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           user.php
//  Назначение:     Класс для работы с пользователями
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  User
//==============================================================
class User{
    // Параметры класса
    var $lang, $db, $confs; // Библиотеки БД и языков
    var $params = [ // Массив параметров
        'secret'=>'', // Секретный ключ
        'lang'=>'' // Объект языка
    ];
    
    // Авторизация юзера
    var $auth = [
        'uid'=>0, // UID пользователя
        'is_auth'=>false, // Флаг авторизации
        'login'=>'', // Логин
        'token'=>'', // Токен
        'from'=>'', // Откуда авторизация
        'is_admin'=>false, // Права администратора
        'profile_uid'=>0 // UID профиля
    ];
    
    // Профиль пользователя
    var $profile = [
        'uid'=>0, // UID
        'avatar'=>'',
        'nickname'=>'', // Ник
        'email'=>'', // Email
        'profile_data'=>[], // Дополнительные данные профиля
        'ban_data'=>[ // Бан
            'banned'=>false, // Флаг
            'ban_escape'=>0, // Время истечения бана
            'ban_reason'=>"" // Причина бана
        ],
        'last_login_day'=>0 // Время последнего входа
    ];
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
        
        // Установка объектов
        $this->lang = $this->params['lang']; // Язык
        $this->db = $this->params['db']; // БД
        $this->confs = $this->params['confs']; // Конфиги библиотеки
        
        // Загрузка языка
        $this->lang->load("users");
    }
    
    // Проверка авторизации
    public function check_auth(){
        $_db = $this->db;
        
        // Проверка токена
        if(isset($_POST['access_token']) && mb_strlen($_POST['access_token'])>0){ // Токен есть в запросе (для API)
            $access_token = $_db->query->escape($_POST['access_token']); // Токен доступа
        }else if(isset($_COOKIE['access_token']) && mb_strlen($_COOKIE['access_token'])>0){ // Токен есть в куках (для API)
            $access_token = $_db->query->escape($_COOKIE['access_token']); // Токен доступа
        }else{ // Токена нет
            $this->logout(); // Сброс авторизации
            return ['complete'=>true, 'is_auth'=>false]; // Флаг авторизации
        }
        
        // Авторизация
        $login = $this->login(['access_token'=>$access_token]); // Попытка входа
        if(!$login['complete']){ // Авторизация не удалась
            $this->logout(); // Сброс авторизации
            return ['complete'=>false, 'code'=>$login['code']];
        }
        
        // Задаем данные авторизации
        $this->auth = [
            'uid'=>$login['uid'], // UID пользователя
            'is_auth'=>true, // Флаг авторизации
            'login'=>$login['login'], // Логин
            'token'=>$login['token'], // Токен
            'from'=>$login['from'], // Откуда авторизация
            'is_admin'=>$login['is_admin'], // Права администратора
            'profile_uid'=>$login['profile_uid'] // UID профиля
        ];
        
        // Возврат данных
        $_return = ['complete'=>true];
        return array_merge_recursive($_return, $this->auth);
    }
    
    // Авторизация
    public function login($fd = []){
        $_db = $this->db;
        
        // Смотрим задан ли логин и пароль
        if(isset($fd['login']) && isset($fd['password'])){ // Логин и пароль заданы
            $_bytoken = false; // Авторизация по токену
            $_login = $_db->query->escape($fd['login']); // Логин
            $_password = $_db->query->escape($fd['password']); // Пароль
        }else if(isset ($fd['access_token'])){
            $_bytoken = true; // Авторизация по токену
            $_token = $fd['access_token']; // Токен
        }
        
        // Валидация данных
        if(!$_bytoken){ // Если авторизируемся не по токену
            $_validate = $this->_validate_login($_login, $_password); // Валидация
            if(!$_validate['complete']){ // Валидация не удалась
                $this->logout(); // Выйти из системы
                return $_validate; // Вернуть данные
            }
            
            // Генерация токена
            $_token = $_db->query->escape(sha1(md5($_login.'|'.$_password).'|'.$this->params['secret'])); // Токен
        }
        
        // Проверка подтверждения email
        if($this->confs['email_confirm'] && !$_bytoken){
            $find = $this->_checkConfirm($_login); // Проверка активации
            if(!$find['complete']){ // Ошибка
                return $find; // Ошибка
            }
        }
        
        // Поиск креденшнолов пользователя
        $find = $this->_findByToken($_token); // Искать
        if(!$find['complete']){ // Не найдено
            return ['complete'=>false, 'code'=>$find['code']]; // Валидация не удалась
        }else{ // Найдено
            $_return = $find;
            setcookie("access_token", $_token, time()+(365*24*60*60), "/");
            $_return['complete'] = true; // База
            return $_return;
        }
    }
    
    // Валидация логина и пароля
    private function _validate_login($login, $password){
        // Проверка длинны логина
        if(mb_strlen($login)<$this->confs['min_login_length'] || mb_strlen($login)>$this->confs['max_login_length']){
            return ['complete'=>false, 'code'=>"login_length"]; // Валидация не удалась
        }
        
        // Проверка символов логина
        if(preg_match('/[^'.$this->confs['allowed_login_symbols'].']+$/u', $login)){
            return ['complete'=>false, 'code'=>"login_symbols"]; // Валидация не удалась
        }
        
        // Проверка длинны пароля
        if(mb_strlen($password)<$this->confs['min_password_length'] || mb_strlen($password)>$this->confs['max_password_length']){
            return ['complete'=>false, 'code'=>"password_length"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Поиск пользователя
    private function _findByToken($access_token){
        $_db = $this->db;
        $get = $_db->query->select_where("auth", "`token`='".$access_token."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Данные не найдены
                return ['complete'=>false, 'code'=>"auth_wrong"]; // Ошибка
            }
            
            // Обработка данных
            while($rw = $_db->query->fetch($get)){
                $this->auth = $rw; // Передать данные
                $this->auth['is_admin'] = ($this->auth['is_admin']==1)?true:false;
                $this->auth['is_auth'] = true;
            }
        }
        
        // Проверка бана
        $get = $_db->query->select_where("profiles", "`uid`=".$this->auth['profile_uid'], 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Данных нет
                return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
            }else{ // Данные есть
                while($rw = $_db->query->fetch($get)){
                    if($rw['ban_data']!=''){
                        $_bandata = unserialize($rw['ban_data']); // Десериализация данных блокировки
                        if($_bandata['banned']==1 || $_bandata['banned']===true){ // Заблокирован
                            if($_bandata['ban_escape']==0){
                                return ['complete'=>false, 'code'=>"account_banned_forever"]; // Ошибка
                            }else{
                                return ['complete'=>false, 'code'=>"account_banned_for"]; // Ошибка
                            }
                        }
                    }
                }
            }
        }
        
        // Вернуть данные
        $_return = $this->auth;
        $_return['complete'] = true; // База
        return $_return;
    }

    // Выход
    public function logout(){
        // Авторизация юзера
        $this->auth = [
            'uid'=>0, // UID пользователя
            'is_auth'=>false, // Флаг авторизации
            'login'=>'', // Логин
            'token'=>'', // Токен
            'from'=>'', // Откуда авторизация
            'is_admin'=>0, // Права администратора
            'profile_uid'=>0 // UID профиля
        ];
        
        // Сброс куков
        setcookie("access_token", "", time()-50, "/"); // Токен
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Базовая регистрация
    public function reg($fd = []){
        $_db = $this->db;
        
        // Задаем данные\
        $_fullname = $_db->query->escape($fd['fullname']); // Имя
        $_login = $_db->query->escape($fd['login']); // Логин
        $_password = $_db->query->escape($fd['password']); // Пароль
        $_repass = $_db->query->escape($fd['repass']); // Повтор пароля
        $_email = $_db->query->escape($fd['email']); // Email
        $_token = $_db->query->escape(sha1(md5($_login.'|'.$_password).'|'.$this->params['secret'])); // Токен
        
        // Если регистрация отключена
        if(!$this->confs['registration']){
            return ['complete'=>false, 'code'=>'registration_disabled'];
        }
        
        // Валидация данных
        $_validate = $this->_validateReg($_fullname, $_login, $_password, $_repass, $_email); // Валидация
        if(!$_validate['complete']){ // Валидация не удалась
            $this->logout(); // Выйти из системы
            return $_validate; // Вернуть данные
        }
        
        // Поиск по логину
        $find = $this->_findByLogin($_login);
        if(!$find['complete']){ // Не найдено
            return ['complete'=>false, 'code'=>$find['code']]; // Валидация не удалась
        }
        
        // Создание учетной записи
        $create = $this->_createAccount($_fullname, $_login, $_token, $_email); // Создать
        if(!$create['complete']){
            return ['complete'=>false, 'code'=>$create['code']]; // Регистрация не удалась
        }
        
        // Поиск креденшнолов пользователя
        if(!$this->confs['email_confirm']){ // Не нужно подтверждения
            $find = $this->_findByToken($_token);
            if(!$find['complete']){ // Не найдено
                return ['complete'=>false, 'code'=>$find['code']]; // Валидация не удалась
            }else{ // Найдено
                $_return = ['complete'=>true]; // База
                setcookie("access_token", $_token, time()+(365*24*60*60), "/");
                return array_merge_recursive($_return, $find); // Возврат
            }
        }else{ // Нужно подтверждение
            $_return = ['complete'=>true]; // База
            return $_return;
        }
    }
    
    // Регистрация через панель управления
    public function reg_admin($fd = []){
        $_db = $this->db;
        
        // Задаем данные
        $_fullname = $_db->query->escape($fd['fullname']); // Имя
        $_login = $_db->query->escape($fd['login']); // Логин
        $_password = $_db->query->escape($fd['password']); // Пароль
        $_repass = $_db->query->escape($fd['repass']); // Повтор пароля
        $_email = $_db->query->escape($fd['email']); // Email
        $_is_admin = intval($fd['is_admin']);
        $_token = $_db->query->escape(sha1(md5($_login.'|'.$_password).'|'.$this->params['secret'])); // Токен
        
        // Валидация данных
        $_validate = $this->_validateReg($_fullname, $_login, $_password, $_repass, $_email); // Валидация
        if(!$_validate['complete']){ // Валидация не удалась
            $this->logout(); // Выйти из системы
            return $_validate; // Вернуть данные
        }
        
        // Поиск по логину
        $find = $this->_findByLogin($_login);
        if(!$find['complete']){ // Не найдено
            return ['complete'=>false, 'code'=>$find['code']]; // Валидация не удалась
        }
        
        // Не отправлять подтверждение
        $this->confs['email_confirm'] = false;
        
        // Создание учетной записи
        $create = $this->_createAccount($_fullname, $_login, $_token, $_email); // Создать
        if(!$create['complete']){
            return ['complete'=>false, 'code'=>$create['code']]; // Регистрация не удалась
        }
        
        // Все ок
        $_return = ['complete'=>true, 'puid'=>$create['puid']]; // База
        return $_return;
    }

    // Валидация данных регистрации
    private function _validateReg($fullname, $login, $password, $repass, $email){
        // Проверка пары логин/пароль
        $_check = $this->_validate_login($login, $password); // Валидация
        if(!$_check['complete']) return $_check; // Возврат ошибки валидации
        
        // Проверка совпадения паролей
        if($password!=$repass){
            return ['complete'=>false, 'code'=>"pass_no_equal"]; // Валидация не удалась
        }
        
        // Проверка Email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return ['complete'=>false, 'code'=>"wrong_email"]; // Валидация не удалась
        }
        
        // Проверка имени
        if(preg_match("/[^0-9a-zA-Zа-яА-Я ]+$/u", $fullname) || mb_strlen($fullname)<2 || mb_strlen($fullname)>40){
            return ['complete'=>false, 'code'=>"wrong_nickname"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Заблокировать аккаунт
    public function banAccount($uid, $ban_time, $ban_reason){
        $_db = $this->db; // Линк на DB
        
        // Проверка UID
        if($uid==$this->auth['profile_uid']){
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Заполнение данных блокировки
        switch ($ban_time){
            case "no":
                $_bandata = ['banned'=>0, "ban_escape"=>0, "ban_reason"=>$ban_reason];
                break;
            case "day":
                $_bandata = ['banned'=>1, "ban_escape"=>(time()+(24*60*60)), "ban_reason"=>$ban_reason];
                break;
            case "week":
                $_bandata = ['banned'=>1, "ban_escape"=>(time()+(7*24*60*60)), "ban_reason"=>$ban_reason];
                break;
            case "month":
                $_bandata = ['banned'=>1, "ban_escape"=>(time()+(30*24*60*60)), "ban_reason"=>$ban_reason];
                break;
            case "forever":
                $_bandata = ['banned'=>1, "ban_escape"=>0, "ban_reason"=>$ban_reason];
                break;
        }
        
        // Запись данных
        $upd = $_db->query->send("UPDATE `".PREFIX."profiles` SET `ban_data`='".serialize($_bandata)."' WHERE `uid`=".$uid);
        if(!$upd){ // Ошибка
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Удалить акккаунт
    public function removeUser($uid){
        $_db = $this->db; // Линк на DB
        
        // Проверка UID
        if($uid==$this->auth['profile_uid']){
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Удаление 
        $remove = $_db->query->delete_where("profiles", "`uid`=".$uid); // Удалить по UID профиль
        if(!$remove){ // Ошибка
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Удаление привязок
        $remove = $_db->query->delete_where("auth", "`profile_uid`=".$uid); // Удалить по UID профиль
        if(!$remove){ // Ошибка
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Все ок
        return ['complete'=>true];
    }

    // Поиск по логину
    private function _findByLogin($login){
        $_db = $this->db;
        $get = $_db->query->select_where("auth", "`login`='".$login."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)>0){ // Данные найдены
                return ['complete'=>false, 'code'=>"login_exists"]; // Ошибка
            }else{ // Данные не найдены
                return ['complete'=>true];
            }
        }
    }
    
    // Создание учетной записи
    private function _createAccount($fullname, $login, $token, $email, $from = 'default'){
        // Данные к отправке
        $_db = $this->db; // Линк на DB
        $_data = [ // Данные профиля
            'avatar'=>'',
            'nickname'=>$fullname, // Ник
            'email'=>$email, // Email
            'profile_data'=>[],
            'ban_data'=>[ // Бан
                'banned'=>false, // Флаг
                'ban_escape'=>0, // Время истечения бана
                'ban_reason'=>"" // Причина бана
            ],
            'last_login_day'=>time() // Время последнего входа
        ];
        
        // Проверка уникальности Email
        if($this->confs['unique_email']){ // Только если выставлено в конфигах
            $find = $this->_findEmail($email); // Поиск Email
            if(!$find['complete']){ // Ошибка
                return $find; // Ошибка
            }
        }
        
        // Запрос на вставку
        $create = $_db->query->insert_array("profiles", $_data); // Создаем профиль
        if(!$create){ // Не удалось создать профиль
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Все ок
            $_puid = $_db->query->insert_id(); // UID профиля
        }
        
        // Создаем привязку
        $_link = [ // Привязка
            'login'=>$login, // Логин
            'token'=>$token, // Токен
            'from'=>$from, // Тип привязки
            'is_admin'=>0, // Администратор
            'profile_uid'=>$_puid // UID профиля
        ];
        
        // Запрос на привязку
        $link = $_db->query->insert_array("auth", $_link); // Создаем линк
        if(!$link){ // Не удалось создать профиль
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Отправка Email
        if($this->confs['email_confirm']){
            $send = $this->_sendConfirm($email); // Поиск Email
            if(!$send['complete']){ // Ошибка
                return $send; // Ошибка
            }
        }
        
        // Выводим данные
        return ['complete'=>true, 'puid'=>$_puid];
    }
    
    // Проверка уникальности Email
    private function _findEmail($email){
        $_db = $this->db;
        $get = $_db->query->select_where("profiles", "`email`='".$email."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)>0){ // Данные найдены
                return ['complete'=>false, 'code'=>"email_exists"]; // Ошибка
            }else{ // Данные не найдены
                return ['complete'=>true];
            }
        }
    }
    
    // Отправка кода подтверждения
    private function _sendConfirm($email){
        // Генерация данных
        $_code = sha1(md5($email.'|'.$this->params['secret']).  rand(0, 10000)); // Генерация кода активации
        $_data = ['email'=>$email, 'code'=>$_code]; // Данные активации
        $_generate = $this->db->query->insert_array("activation", $_data); // Отправка запроса
        if(!$_generate){ // Не удалось сгенерировать данные
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }        
        
        // Поиск шаблона
        $_pfx = strtolower($this->lang->curr_lang);
        if(defined("FRONTEND") && file_exists(FRONTEND.'/view/mail/activation_'.$_pfx.'.html')){ // Есть шаблон
            $_body = file_get_contents(FRONTEND.'/view/mail/activation_'.$_pfx.'.html'); // Получаем письмо
        }else{ // Нет шаблона
            // Генерируем простейшее письмо
            $_body = '<html><head><title>'.$this->lang->line("activation_mail").'</title></head><body><p>'.$this->lang->line('activation_line_1').'<br/>{{activation_link}}</p><p>'.$this->lang->line('activation_line_2').'</p></body>';
        }
        
        // Меняем теги в шаблоне
        $_body = str_replace("{{activation_link}}", "<a href=\"http://".DOMAIN."/auth/approve/?code=".$_code."\">http://".DOMAIN."/auth/approve/?code=".$_code."</a>", $_body); // Заменить тег {{activation_link}}
        $_body = str_replace("{{site_name}}", $this->lang->line("application"), $_body); // Заменить тег {{site_name}}
        $_body = str_replace("{{domain}}", DOMAIN, $_body); // Заменить тег {{domain}}
        
        // Отправка письма
        $_to = $email; // Кому
        $_subject = $this->lang->line("activation_mail"); // Тема письма
        $_headers  = 'MIME-Version: 1.0' . "\r\n";
        $_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $_headers .= 'To: '.$this->lang->line('user').' <'.$email.'>'. "\r\n";
        $_headers .= 'From: '.$this->lang->line('administrator').' <no-reply@'.DOMAIN.'>';
        
        // Отправить
        mail($_to, $_subject, $_body, $_headers);
        return ['complete'=>true];
    }
    
    // Проверка подтверждения email
    private function _checkConfirm($login){
        $_db = $this->db;
        
        // Поиск данных авторизации
        $get = $_db->query->select_where("auth", "`login`='".$login."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Данные получены
            if($this->db->query->num_rows($get)<1){ // Нет ничего
                return ['complete'=>false, 'code'=>"user_notfound"]; // Ошибка
            }
            
            // Данные найдены
            while($rw = $this->db->query->fetch($get)){
                $_puid = $rw['profile_uid']; // Получаем UID профиля
            }
        }
        
        // Запрос профиля для получения email
        $get = $_db->query->select_where("profiles", "`uid`='".$_puid."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Данные получены
            if($this->db->query->num_rows($get)<1){ // Нет ничего
                return ['complete'=>false, 'code'=>"user_notfound"]; // Ошибка
            }
            
            // Данные найдены
            while($rw = $this->db->query->fetch($get)){
                $_email = $rw['email']; // Получаем UID профиля
            }
        }
        
        // Проверяем активацию
        $get = $_db->query->select_where("activation", "`email`='".$_email."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($this->db->query->num_rows($get)>0){ // Нет ничего
                return ['complete'=>false, 'code'=>"account_deactived"]; // Ошибка
            }
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Подтвердить Email
    public function confirmEmail($fd = []){
        $_db = $this->db; // Экземпляр базы
        $code = $_db->query->escape($fd['code']); // Код активации
        $get = $_db->query->select_where("activation", "`code`='".$code."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($this->db->query->num_rows($get)<1){ // Нет ничего
                return ['complete'=>false, 'code'=>"account_already_actived"]; // Ошибка
            }
        }
        
        // Удаляем запись
        $remove = $_db->query->delete_where("activation", "`code`='".$code."'"); // Удаление
        if(!$remove){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Старт сброса пароля
    public function start_reset($fd = []){
        $_db = $this->db;
        $email = $_db->query->escape($fd['email']); // Email
       
        // Проверяем еслть ли Email
        $get = $_db->query->select_where("profiles", "`email`='".$email."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Email не найден
                return ['complete'=>false, 'code'=>"email_not_exists"]; // Ошибка
            }
        }
        
        // Проверяем время отправки
        $get = $_db->query->select_where("reset", "`email`='".$email."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)>1){ // Уже есть в базе
                while($rw = $_db->query->fetch($get)){ // Загрузка данных
                    $_time = $rw['time']; // Время отправки
                }
                
                // Проверяем, сколько прошло времени
                if($_time>time()){ // Время еще не прошло
                    return ['complete'=>false, 'code'=>"reset_time"]; // Ошибка
                }
            }
        }
        
        // Если все хорошо - генерируем код
        // и отправляем наше письмо
        $_code = sha1(md5($email.'|'.$this->params['secret']).  rand(0, 10000)); // Генерация кода сброса пароля
        $_data = ['email'=>$email, 'code'=>$_code, 'time'=>(time()+300)]; // Данные инициализации сброса
        $_generate = $this->db->query->insert_array("reset", $_data); // Отправка запроса
        if(!$_generate){ // Не удалось сгенерировать данные
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        
        // Поиск шаблона
        $_pfx = strtolower($this->lang->curr_lang);
        if(defined("FRONTEND") && file_exists(FRONTEND.'/view/mail/reset_'.$_pfx.'.html')){ // Есть шаблон
            $_body = file_get_contents(FRONTEND.'/view/mail/reset_'.$_pfx.'.html'); // Получаем письмо
        }else{ // Нет шаблона
            // Генерируем простейшее письмо
            $_body = '<html><head><title>'.$this->lang->line("reset_mail").'</title></head><body><p>'.$this->lang->line('reset_line_1').'<br/>{{reset_link}}</p><p>'.$this->lang->line('activation_line_2').'</p></body>';
        }
        
        // Меняем теги в шаблоне
        $_body = str_replace("{{reset_link}}", "<a href=\"http://".DOMAIN."/auth/reset/?code=".$_code."\">http://".DOMAIN."/auth/reset/?code=".$_code."</a>", $_body); // Заменить тег {{reset_link}}
        $_body = str_replace("{{site_name}}", $this->lang->line("application"), $_body); // Заменить тег {{site_name}}
        $_body = str_replace("{{domain}}", DOMAIN, $_body); // Заменить тег {{domain}}
        
        // Отправка письма
        $_to = $email; // Кому
        $_subject = $this->lang->line("reset_mail"); // Тема письма
        $_headers  = 'MIME-Version: 1.0' . "\r\n";
        $_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $_headers .= 'To: '.$this->lang->line('user').' <'.$email.'>'. "\r\n";
        $_headers .= 'From: '.$this->lang->line('administrator').' <no-reply@'.DOMAIN.'>';
        
        // Отправить
        mail($_to, $_subject, $_body, $_headers);
        return ['complete'=>true];
    }

    // Окончание сброса пароля
    public function complete_reset($fd = []){
        $_db = $this->db;
        
        // Задаем данные
        $_password = $_db->query->escape($fd['password']); // Пароль
        $_repass = $_db->query->escape($fd['repass']); // Повтор пароля
        $_code = $_db->query->escape($fd['code']); // Код сброса
        
        // Валидация данных
        $_validate = $this->_validateReset($_password, $_repass); // Валидация
        if(!$_validate['complete']){ // Валидация не удалась
            $this->logout(); // Выйти из системы
            return $_validate; // Вернуть данные
        }
        
        // Проверяем код подтверждения
        $get = $_db->query->select_where("reset", "`code`='".$_code."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){
                return ['complete'=>false, 'code'=>"reset_code_not_found"]; // Ошибка
            }
            
            // Получаем Email пользоваетля
            while($rw = $_db->query->fetch($get)){
                $_email = $rw['email']; // Email
            }
        }
        
        // Проверяем еслть ли Email
        $get = $_db->query->select_where("profiles", "`email`='".$_email."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Email не найден
                return ['complete'=>false, 'code'=>"email_not_exists"]; // Ошибка
            }
            
            // Получаем UID пользователя
            while($rw = $_db->query->fetch($get)){
                $_puid = $rw['uid']; // UID
            }
        }
        
        // Ищем данные авторизации
        $get = $_db->query->select_where("auth", "`profile_uid`='".$_puid."' AND `from`='default'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Email не найден
                return ['complete'=>false, 'code'=>"auth_data_not_found"]; // Ошибка
            }
            
            // Получаем данные
            while($rw = $_db->query->fetch($get)){
                $_login = $rw['login']; // Получить логин
            }
        }
        
        // Меняем пароль
        $_token = $_db->query->escape(sha1(md5($_login.'|'.$_password).'|'.$this->params['secret'])); // Генерируем токен
        $r_data = ['token'=>$_token];
        $update = $_db->query->update_array("auth",$r_data,"`login`='".$_login."'"); // Запрос
        if(!$update){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error_"]; // Ошибка
        }
        
        // Удаляем заявку
        $remove = $_db->query->delete_where("reset", "`code`='".$_code."'"); // Удаление
        if(!$remove){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Все ок 
        return ['complete'=>true];
    }
    
    // Валидация данных регистрации
    private function _validateReset($password, $repass){
        // Проверка длинны пароля
        if(mb_strlen($password)<$this->confs['min_password_length'] || mb_strlen($password)>$this->confs['max_password_length']){
            return ['complete'=>false, 'code'=>"password_length"]; // Валидация не удалась
        }
        
        // Проверка совпадения паролей
        if($password!=$repass){
            return ['complete'=>false, 'code'=>"pass_no_equal"]; // Валидация не удалась
        }
        
        // Все ок
        return ['complete'=>true]; // Валидация пройдена
    }
    
    // Получить IP
    public function getIP(){
        // Перебор IP адресов
        $ip = false;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        // Возвращаем IP адрес
        return $ip;
    }

    // Получить профиль игрока
    public function getProfile($uid){
        // Валидация UID
        $_valid = $this->_validateUID($uid); // Запрос валидации
        if(!$_valid['complete']){ // Ничего не вышло
            return ['complete'=>false, 'code'=>$_valid['code']];
        }else{ // Все ок
            $_uid = ($uid=="my")?$this->auth['profile_uid']:$uid; // UID
        }
        
        // Получение профиля по ID
        $_profile = $this->_getProfileByUID($_uid); // Попытка получения
        if(!$_profile['complete']){ // Ошибка получения
            return ['complete'=>false, 'code'=>$_profile['code']];
        }
        
        // Все ок - выдаем данные профиля
        return $_profile;
    }
    
    // Получить профиль по ID
    private function _getProfileByUID($uid){
        $_db = $this->db; // Линк на DB
        
        // Запрос профиля
        $get = $_db->query->select_where("profiles", "`uid`='".$uid."'", 1); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }else{ // Запрос удался
            if($_db->query->num_rows($get)<1){ // Данные найдены
                return ['complete'=>false, 'code'=>"login_exists"]; // Ошибка
            }else{ // Данные не найдены
                $_profile = [];
                while($rw = $_db->query->fetch($get)){
                    $_profile = $rw;
                }
                
                // Десериализуем данные
                $_profile['profile_data'] = unserialize($_profile['profile_data']); // Доп. данные профиля
                $_profile['ban_data'] = unserialize($_profile['ban_data']); // Данные бана
                $_profile['ban_data']['banned']=($_profile['ban_data']['banned']==1)?true:false; // Конверсия в Boolean
            }
        }
        
        // Если это наш профиль то задаем его
        if($uid==$this->auth['profile_uid']) $this->profile = $_profile;
        
        // Возврат данных
        $_return = $_profile; // Данные профиля
        $_return['complete']=true; // Флаг
        return $_return; // Вернуть данные
    }
    
    // Получить количество пользователей
    public function getUsersCount(){
        $_db = $this->db; // Линк на DB
        
        // Запрос счетчика
        $get = $_db->query->send("SELECT COUNT(*) FROM `".PREFIX."profiles`"); // Выборка
        if(!$get){ // Запрос не удался
            return ['complete'=>false, 'code'=>"db_request_error"]; // Ошибка
        }
        
        // Считаем результат
        $_users = $_db->query->result($get); // Получить результат
        
        // ВЫдаем результат
        return ['complete'=>true, 'count'=>$_users];
    }

    // Получить список пользователей
    public function getUsersList($curr_page = 0, $search = false){
        // Получаем параметры
        $num = 25; // Вывод на страницу
        $page = $curr_page; // Навигатор
        
        // Параметр поиска
        $_like = ($search!==false && strlen($search)>0)?" WHERE `nickname` LIKE '%".$search."%' OR `email` LIKE '%".$search."%'":"";
        
        // Получаем количество профилей
        $q = $this->db->query->send("SELECT COUNT(*) FROM `".PREFIX."profiles`".$_like." ORDER BY `uid` DESC"); // Запрос к БД
        if(!$q){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Считаем количество страниц
        $posts = $this->db->query->result($q); // Получить результат
        $total = intval(($posts - 1) / $num) + 1;  // Высчитываем общее количество страниц
        $page = intval($page); // Начало сообщений для страницы
        if(empty($page) or $page < 0) $page = 1; // Если значение слишком маленькое - ставим первую страницу
        if($page > $total) $page = $total; // Если значение слишком большое - ставим последнюю страницу
        $start = $page * $num - $num; // Определяем откуда начинать лимиты
        
        // Делаем запрос списка профилей
        $q = $this->db->query->send("SELECT * FROM `".PREFIX."profiles`".$_like." ORDER BY `uid` DESC LIMIT $start,$num");
        if(!$q){ // Ошибка запроса
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Все окей
            $list = []; // Массив страниц
            $cnt = 0;
            while($rw = $this->db->query->fetch($q)){
                $list[$cnt] = $rw; // Добавить в список
                $list[$cnt]['profile_data'] = unserialize($list[$cnt]['profile_data']);
                $list[$cnt]['ban_data'] = unserialize($list[$cnt]['ban_data']);
                $cnt++;
            }
        }
        
        // Все окей
        return ['complete'=>true, 'list'=>$list, 'page'=>$curr_page, 'total'=>$total, 'posts'=>$posts, 'num'=>$num];
    }
    
    // Обновить профиль
    public function updateProfile($uid, $data){
        // Валидация
        $_uid = ($uid=="my")?$this->auth['profile_uid']:$uid; // UID
        $_profile = $this->getProfile("my");
        
        // Валидация UID
        $_valid = $this->_validateUID($uid); // Запрос валидации
        if(!$_valid['complete']){ // Ничего не вышло
            return ['complete'=>false, 'code'=>$_valid['code']];
        }else if($this->auth['profile_uid']!=$_uid && !$this->auth['is_admin']){ // Все ок
            return ['complete'=>false, 'code'=>"no_access"];
        }
        
        // Валидация полей профиля
        $_valid = $this->_validateProfileFields($data);
        if(!$_valid['complete']){ // Ошибка
            return ['complete'=>false, 'code'=>$_valid['code']];
        }
        
        // Получаем старую аву
        $old_ava = $this->profile['avatar'];
        
        // Обновляем профиль пользователя
        $q = $this->db->query->send("UPDATE `".PREFIX."profiles` SET `avatar`='".$data['avatar']."', `nickname`='".$data['nickname']."', `email`='".$data['email']."', `profile_data`='".serialize($data['profile_data'])."' WHERE `uid`=".$_uid);
        if(!$q){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Проверка существования старой авы
        if(file_exists($_SERVER['DOCUMENT_ROOT'].$old_ava) && $old_ava!=""){
            unlink($_SERVER['DOCUMENT_ROOT'].$old_ava);
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Задать новый пароль
    public function updatePassword($data){
        $_db = $this->db;
        
        // Генерация токенов
        $_token = $_db->query->escape(sha1(md5($this->auth['login'].'|'.$data['password']).'|'.$this->params['secret'])); // Токен
        $_new_token = $_db->query->escape(sha1(md5($this->auth['login'].'|'.$data['new_password']).'|'.$this->params['secret'])); // Токен
        
        // Не с веба
        if($this->auth['from']!='default'){
            return ['complete'=>false, 'code'=>'wrong_auth_method'];
        }
        
        // Проверка токена
        if($_token!=$this->auth['token'] && $this->auth['from']=='default'){
            return ['complete'=>false, 'code'=>'wrong_current_password'];
        }
        
        // Обновление информации
        $q = $_db->query->send("UPDATE `".PREFIX."auth` SET `token`='".$_new_token."' WHERE `login`='".$this->auth['login']."'");
        if(!$q){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Задаем новый токен
        $this->auth['token']=$_new_token;
        setcookie("access_token", $_new_token, time()+(365*24*60*60), "/");
        
        // Все ок
        return ['complete'=>true];
    }

    // Валидация полей профиля
    private function _validateProfileFields($data){
        // Проверка аватара (если задан)
        if(isset($data['avatar']) && strlen($data['avatar'])>0 && !file_exists($_SERVER['DOCUMENT_ROOT'].$data['avatar'])){
            return ['complete'=>false, 'code'=>'avatar_not_found'];
        }
        
        // Проверка Email
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            return ['complete'=>false, 'code'=>"wrong_email"]; // Валидация не удалась
        }
        
        // Проверка имени
        if(preg_match("/[^0-9a-zA-Zа-яА-Я ]+$/u", $data['nickname']) || mb_strlen($data['nickname'])<2 || mb_strlen($data['nickname'])>40){
            return ['complete'=>false, 'code'=>"wrong_nickname"]; // Валидация не удалась
        }
        
        // Проверка телефона (если задано)
        if(isset($data['profile_data']['phone']) && mb_strlen($data['profile_data']['phone'])>0 && (preg_match("/[^0-9\+\-\(\)\. ]+$/u", $data['profile_data']['phone']) || mb_strlen($data['profile_data']['phone'])>20)){
            return ['complete'=>false, 'code'=>'wrong_phone'];
        }
        
        // Проверка дня рождения (если задано)
        if(isset($data['profile_data']['birthday']) && mb_strlen($data['profile_data']['birthday'])>0){
            $_explode = explode(".",$data['profile_data']['birthday']);
            if(!is_array($_explode) || count($_explode)<3){ // Массив
                return ['complete'=>false, 'code'=>'wrong_birthday'];
            }
            
            // Доп. валидация
            if(!checkdate($_explode[1], $_explode[0], $_explode[2])){
                return ['complete'=>false, 'code'=>'wrong_birthday'];
            }
            
            if(mb_strlen($data['profile_data']['birthday'])>12){
                return ['complete'=>false, 'code'=>'wrong_birthday'];
            }
        }
        
        // Все ок
        return ['complete'=>true];
    }

    // Проверка валидности UID
    private function _validateUID($uid){
        if($uid=="my"){ // Мой UID
            return ['complete'=>true]; // UID не мой
        }else if(preg_match('/[^0-9]/',$uid)){ // UID не содержит цифры
            return ['complete'=>false, 'code'=>"uid_not_valid"];
        }else{ // Numeric
            return ['complete'=>true]; // UID верный
        }
    }
}
?>