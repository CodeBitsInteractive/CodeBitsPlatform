<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           db.php
//  Назначение:     Класс для работы с БД
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  DataBase
//==============================================================
//  Класс для подключения к базе данных. Подключение выполняется
//  согласно драйверу, прописанному в конфигурациях
//==============================================================
class DB{
    // Параметры класса
    var $query; // Здесь будет храниться объект драйвера
    var $params = [ // Массив параметров
        'driver'=>'', // Драйвер DB
        'host'=>'', // Хост
        'name'=>'', // Имя
        'login'=>'', // Логин
        'password'=>'', // Пароль
        'encoding'=>'', // Кодировка
        'prefix'=>'' // Префикс
    ];
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
    }
    
    // Инициализация БД. Здесь мы совершаем поиск драйвера БД 
    // и вгружаем его в состав объекта БД
    function initialize(){
        // Производим поиск драйвера БД
        $_driver = $this->params['driver']; // Наш драйвер
        if(!file_exists(ROOT.'/core/libs/drivers/'.$_driver.'.php')){ // Драйвер не найден
            return ['complete'=>false, 'message'=>'Failed to initialize DB. Database driver "'.$_driver.'" not found.'];
        }else{ // Если драйвер найден - подключаем его
            include(ROOT.'/core/libs/drivers/'.$_driver.'.php'); // Подключить скрипт драйвера
        }
        
        // Теперь, производим поиск имени класса драйвера
        $_driver_class = "driver_".$_driver;
        if(!class_exists($_driver_class)){ // Класс не найден
            return ['complete'=>false, 'message'=>'Driver class "'.$_driver_class.'" not found for database driver "'.$_driver.'".'];
        }else{ // Класс найден
            $this->query = new $_driver_class($this->params); // Инициализируем драйвер
        }
        
        // Если все прошло гладко
        return ['complete'=>true];
    }
    
    // Подключение к БД (Просто обертка функции драйвера
    function connect(){
        $_connect = $this->query->connect(); // Вызов подключения
        return $_connect; // Вернуть результат
    }
}
?>