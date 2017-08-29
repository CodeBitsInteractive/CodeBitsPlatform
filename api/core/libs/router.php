<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           router.php
//  Назначение:     Роутер запросов
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class Router{
    // Параметры класса
    var $query = []; // Массив данных запроса
    var $params = [ // Массив параметров
    ];
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
        
        // Загрузка данных запроса
        $this->query = explode('/', URL); // Разрубить запрос
    }
    
    // Начало работы роутера
    public function route(){
        // Определяем существует ли контроллер
        $controller = (!defined("WRAPPER") || !WRAPPER)?$this->query[2]:$this->query[1]; // Контроллер
        // Смотрим данные запроса
        if(!isset($controller) || strlen($controller)<2 || strpos($controller, '?')===0){ // Нет данных
            $controller = 'home'; // Контроллер главной
        }
        
        // Смотрим, существует ли данный контроллер
        $cname = 'CBP_'.$controller; // Имя контроллера
        $cpath = ROOT.'/core/controllers/class.'.$controller.'.php'; // 
        if(!file_exists($cpath)){ // Контроллер не существует
            $resp = $this->error('404'); // Получить данные
            return $resp; // Вернуть данные
        }else{ // Контроллер существует
            $resp = ['complete'=>true, 'controller'=>$cname, 'path'=>$cpath, 'data'=>(!defined("WRAPPER") || !WRAPPER)?$this->query[2]:$this->query[1]];
        }
        
        // Вернуть данные
        return $resp; // Возврат
    }
    
    // Если контроллер не найден
    public function error($code){
        // Поиск контроллера ошибок
        $cname = 'CBP_error'; // Имя контроллера
        $cpath = ROOT.'/core/controllers/class.error.php'; // Контроллер ошибок
        if(!file_exists($cpath)){ // Контроллер не существует
            $resp = ['complete'=>false, 'message'=>'Errors controller is not found'];
        }else{ // Контроллер существует
            $resp = ['complete'=>true, 'controller'=>$cname, 'path'=>$cpath, 'data'=>['code'=>$code]];
        }
        
        // Вернуть данные
        return $resp; // Возврат
    }
}
?>