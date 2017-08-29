<?php
//===============================================
//  Файл:           class.pages.php
//  Назначение:     Контроллер страниц
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_pages extends BaseController{
    // Модель страницы
    var $page_model; // Здесь будет модель
    
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
        
        // Загрузка модели
        $this->page_model = $this->load_model("static", [
            'lang'=>$this->lang->curr_lang,
            'num'=>20
        ]); // Получаем модель
    }
    
    // Инициализация модуля
    public function init($data){
        // Получаем языковой пакет
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Получаем список страниц
        $_pagelist = false; // Список страниц
        $_currpage = 0; // Текущая страница
        $_search = false; // Поиск
        $_total = 1; // Всего
        
        // Определяем поисковый запрос
        if (isset($_GET['s']) && strlen($_GET['s']) > 0) {
            $_search = $this->db->query->escape($_GET['s']);
        }
        
        $_currpage = (isset($_GET['nav']) && intval($_GET['nav'])>0)?$_GET['nav']:1; // Навигатор
        $_pagelist = $this->page_model->getPagesList($_currpage, $_search); // Получить список страниц
        if (!$_pagelist['complete']) { // Ошибка
            header("Location: /errors/?code=500");
            exit();
        } else if (count($_pagelist['list']) < 1) {
            $_list = []; // Сброс списка
        } else {
            $_list = $_pagelist['list'];
            $_total = $_pagelist['total'];
        }
        
        // Настраиваем
        $this->cache->getCache("pages"); // Получить кеш
        $_template = FRONTEND.'/view/website/pages.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$this->lang->line('c_pages_title'),
            'desc'=>$this->lang->line('c_pages_desc'),
            'tags'=>$this->lang->line('c_pages_tags'),
            'list'=>$_list,
            'total'=>$_total,
            'page'=>$_currpage,
            'search'=>$_search
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
    
    // Просмотр определенной страницы
    public function view(){
        // Получаем языковой пакет
        $this->lang->load("navigation"); // Языковой пакет навигации
        $this->_noAPI(); // Для API - выбиваем ошибку
        
        // Определяем SLUG
        $_rtr = $this->router->query; // URL разбитый на массив
        if(!isset($_rtr[3]) || strlen($_rtr[3])<1 || preg_match('/[^0-9a-z\-\_]+$/u', $_rtr[3]) || strpos($_rtr[3], '?')===0){
            header("Location: /pages/");
            exit();
        }else{
            $_slug = $_rtr[3];
        }
        
        // Задаем переменные
        $_pagedata = false; // Данные страницы
        // Запрос контента страницы
        $_getContent = $this->page_model->getPageContent($_slug);
        if (!$_getContent['complete'] || !isset($_getContent['data'][$this->lang->curr_lang])) {
            header("Location: /errors/?code=404");
            exit();
        }

        // Данные страницы
        $_pagedata = $_getContent['data'][$this->lang->curr_lang];
        $this->cache->getCache("page-".$_slug); // Получить кеш
        $_template = FRONTEND.'/view/website/signle_page.php'; // Шаблон
        $_data = [ // Данные, передаваемые в шаблон
            'title'=>$_pagedata['title'],
            'desc'=>$_pagedata['desc'],
            'tags'=>$_pagedata['tags'],
            'data'=>$_pagedata
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