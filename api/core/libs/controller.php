<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           controller.php
//  Назначение:     Базовый контроллер системы
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  Base Controller
//==============================================================
//  Базовый контроллер, от которого мы наследуемся для дальнейшей
//  работы
//==============================================================
class BaseController{
    // Параметры класса
    public static $instance; // Экземпляр объекта
    var $libs = [];
    var $modules = []; // Массив модулей
    var $models = []; // Массив моделей
    var $params = [ // Массив параметров
    ];
    
    // Конструктор класса
    public function __construct($args = [], $libs = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
        
        // Загрузка библиотек
        if(isset($libs) && is_array($libs) && count($libs)>0){ // Либы есть
            for($i=0;$i<count($libs);$i++){ // Перебор библиотек
                if(!isset($this->$libs[$i]['name'])) $this->$libs[$i]['name'] = $libs[$i]['object']; // Вставить библиотеку
            }
        }
        
        // Загрузка модулей
        foreach($this->config->line['system']['modules'] as $key=>$val){
            $this->load_module($val); // Подцепить модуль
        }
        
        // Задаем экземпляр объекта
        self::$instance = $this; // Передача экземпляра
    }
    
    // Получить экземпляр контроллера
    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Рендер данных.
    protected function render($template,$status, $data = []){
        if(defined("WRAPPER") && WRAPPER){ // Враппер включен
            // Проверяем, существует ли шаблон
            if(!file_exists($template)){ // Не существует
                return ['complete'=>false]; // Вывод ответа
            }
            
            // Рендер страницы
            extract($data); // Извлечь данные
            require_once $template; // Подключить файл
            return ['complete'=>true]; // Вывод ответа
        }else{ // Враппер выключен
            // Формируем массив вывода
            $return = (!$status)?['message'=>$data['message']]:$data;
            $return['complete']=$status; // База
            echo json_encode($return); // Оформить и вывести
            exit(); // Выход из скрипта
        }
    }
    
    // Загрузка модуля
    protected function load_module($module, $args = []){
        // Определяем путь к модулю
        $_modpath = ROOT.'/core/modules/'.$module.'/module.php';
        $_modclassname = "CBP_Module_".$module; // Имя класса модуля
        if(!file_exists($_modpath)){ // Модуль не найден
            return false; // Модуль не загружен
        }
        
        // Смотрим, загружен ли модуль
        if(isset($this->modules[$module])){ // Модуль найден
            return $this->modules[$module]; // Модуль не загружен
        }
        
        // Теперь, нам нужно загрузить его
        include($_modpath); // Подключаем модуль
        $this->modules[$module] = new $_modclassname(self::get(), $args); // Создаем модуль
        return $this->modules[$module]; // Вернуть модуль
    }
    
    // Загрузка модели
    protected function load_model($model, $args = []){
        // Определяем путь к модели
        $_modpath = ROOT.'/core/models/class.'.$model.'.php';
        $_modclassname = "CBP_Model_".$model; // Имя класса модели
        if(!file_exists($_modpath)){ // Модель не найдена
            return false; // Модель не загружен
        }
        
        // Смотрим, загружена ли модель
        if(isset($this->models[$model])){ // Модель найдена
            return $this->models[$model]; // Модель не загружена
        }
        
        // Теперь, нам нужно загрузить ее
        include($_modpath); // Подключаем модель
        $this->models[$model] = new $_modclassname(self::get(), $args); // Создаем модель
        return $this->models[$model]; // Вернуть модель
    }
}
?>