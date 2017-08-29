<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           {{COMPONENT_NAME}}.php
//  Назначение:     Шаблон библиотеки для CodeBits Platform
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  Определяем класс библиотеки
//==============================================================
class {{COMPONENT_NAME}}{
    var $params = [];
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
    }
    
    // Пример функции
    public function someFunction(){
        return ['complete'=>true];
    }
}