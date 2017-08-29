<?php
//===============================================
//  Файл:           class.error.php
//  Назначение:     Контроллер ошибок
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_error extends BaseController{
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
    }
    
    // Инициализация модуля
    public function init($data){
        // Получаем языковой пакет
        $this->lang->load("errors"); // Языковой пакет ошибок
        
        // Настраиваем
        $_code = (isset($_GET['code']) && strlen($_GET['code'])>0)?intval($_GET['code']):404; // Код ошибки
        $this->cache->getCache($_code); // Получить кеш
        $_template = (defined("FRONTEND"))?FRONTEND.'/view/common/errors.php':""; // Шаблон
        $return = ['message'=>$this->lang->line('error_'.$_code), 'code'=>$_code]; // Задаем ошибку
        
        // Вывод данных
        $_render = $this->render($_template, false, $return); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load errors template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }else{ // Рендер удался
            $this->cache->saveCache(); // Сохранить кеш
        }
    }
}