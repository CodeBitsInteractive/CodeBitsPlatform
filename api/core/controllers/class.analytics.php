<?php
//===============================================
//  Файл:           class.analytics.php
//  Назначение:     Контроллер аналитики
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_analytics extends BaseController{
    // Модель аналитики
    var $a_model; // Здесь будет модель
    
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
        
        // Загрузка модели
        $this->a_model = $this->load_model("analytics", []); // Получаем модель
    }
    
    // Инициализация модуля
    public function init($data){
        header("Location: /errors/?code=404");
        exit();
    }
    
    // Обновление аналитики
    public function update(){
        // Проверка времени установки
        if(isset($_SESSION['analytics_time']) && intval($_SESSION['analytics_time'])>time()){
            $_render = $this->render("", true, []); // Рендер
        }
        
        // Проверка страницы
        if(isset($_POST['page']) && mb_strlen($_POST['page'])>0 && filter_var($_POST['page'], FILTER_VALIDATE_URL)){
            $_page = $_POST['page'];
        }else{
            $_page = URL;
        }
        
        // Все ок - обновляем данные
        $_upd = $this->a_model->updateAnalytics($_page); // Обновляем данные
        if(!$_upd['complete']){ // Ошибка
            $_render = $this->render("", false, ['message'=>$this->lang->line($_upd['code'])]); // Рендер
        }
        
        // Все прошло удачно
        $_SESSION['analytics_time']=time()+60; // Не чаще, чем раз в минуту
        $_render = $this->render("", true, []); // Рендер
    }

    // Выбить ошибку для Front-end
    private function _noFrontend(){
        // Для API - выбиваем ошибку
        if(defined("FRONTEND")){ // Запрос через API
            header("Location: /errors/?code=404");
            exit();
        }
    }
}