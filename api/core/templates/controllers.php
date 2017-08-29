<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           {{COMPONENT_NAME}}.php
//  Назначение:     Шаблон контроллера для CodeBits Platform
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  Определяем класс
//==============================================================
class CBP_{{COMPONENT_NAME}} extends BaseController{
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
    }
    
    // Инициализация модуля
    public function init($fd = []){
        if($this->_is_api()){ // Для запросов API
            $_datas = ['message'=>'CodeBits Platform Controller Example']; // Данные для вывода
            $_render = $this->render("", true, $_datas); // Рендер
        }else{
            echo "CodeBits Platform Controller Example";
            exit();
        }
    }
    
    // Определяем режим работы
    private function _is_api(){
        $_flag = (defined("WRAPPER") && WRAPPER)?false:true; // Флаг API
        return $_flag;
    }
}
?>