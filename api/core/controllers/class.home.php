<?php
//===============================================
//  Файл:           class.home.php
//  Назначение:     Контроллер главной страницы
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_home extends BaseController{
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
    }
    
    // Инициализация модуля
    public function init($data){
        // Получаем языковой пакет
        $this->lang->load("home"); // Языковой пакет главной страницы
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Настраиваем
        $this->cache->getCache("main"); // Получить кеш
        $_template = FRONTEND.'/view/website/home.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('home_title'),
            'desc'=>$this->lang->line('home_desc'),
            'tags'=>$this->lang->line('home_keywords')
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
    
    // Выбить ошибку для API
    private function _noAPI(){
        // Для API - выбиваем ошибку
        if(!defined("FRONTEND")){ // Запрос через API
            $return = ['message'=>$this->lang->line('not_api_controller')]; // Задаем ошибку
            $this->render("", false, $return); // Рендер
        }
    }
}