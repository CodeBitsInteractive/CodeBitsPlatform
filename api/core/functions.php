<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           functions.php
//  Назначение:     Вспомогательные функции движка
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
//==============================================================
//  Фунция для рендера сообщений
//==============================================================
function render_message($msg){    
    echo (defined('WRAPPER') && WRAPPER)?$msg:json_encode(['complete'=>false, 'message'=>$msg]);
    exit();
}

//==============================================================
//  Фунция определения кодировки
//==============================================================
function detect_encoding(){
    $charset = 'UTF-8';
    ini_set('default_charset', $charset);

    // MBSTRING
    if (extension_loaded('mbstring')){
        define('MB_ENABLED', TRUE);
        @ini_set('mbstring.internal_encoding', $charset);
        mb_substitute_character('none');
    }else{
        define('MB_ENABLED', FALSE);
    }

    // ICONV
    if (extension_loaded('iconv')){
        define('ICONV_ENABLED', TRUE);
        @ini_set('iconv.internal_encoding', $charset);
    }else{
        define('ICONV_ENABLED', FALSE);
    }

    // Для версии PHP 5.6+
    if(version_compare(PHP_VERSION, '5.6', '>=')){
        ini_set('php.internal_encoding', $charset);
    }
}


//==============================================================
//  Фунция определения языка
//==============================================================
function detect_language($default){
    // Определяем язык
    if(defined("WRAPPER") && WRAPPER){ // Для Frontend-а
        if(isset($_GET['lang']) && strlen($_GET['lang'])>0){ // Язык в запросе
            return $_GET['lang']; // Установить язык
        }else if(isset($_COOKIE['lang']) && strlen($_COOKIE['lang'])>0){ // Язык задан в Cookies
            return $_COOKIE['lang']; // Установить язык
        }else{ // Язык не установлен
            return $default; // Установить язык системы
        }
    }else{ // Для API
        if(isset($_POST['lang']) && strlen($_POST['lang'])>0){ // Язык в запросе
            return $_POST['lang']; // Установить язык
        }else if(isset($_COOKIE['lang']) && strlen($_COOKIE['lang'])>0){ // Язык задан в Cookies
            return $_COOKIE['lang']; // Установить язык
        }else{
            return $default; // Установить язык системы
        }
    }
}

//==============================================================
//  Загрузка библиотек
//==============================================================
function load_system_libs(){
    require(ROOT.'/core/libs/config.php'); // Класс конфигураций
    require(ROOT.'/core/libs/controller.php'); // Базовый контроллер (Синглтон)
    require(ROOT.'/core/libs/module.php'); // Базовый модуль
    require(ROOT.'/core/libs/model.php'); // Базовая модель
    require(ROOT.'/core/libs/router.php'); // Роутер
    require(ROOT.'/core/libs/db.php'); // Модуль БД
    require(ROOT.'/core/libs/lang.php'); // Модуль языков
    require(ROOT.'/core/libs/cache.php'); // Класс кеширования
    require(ROOT.'/core/libs/user.php'); // Класс авторизации
}
?>