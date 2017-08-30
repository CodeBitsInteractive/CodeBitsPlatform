<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           {{COMPONENT_NAME}}/module.php
//  Назначение:     Шаблон контроллера для CodeBits Platform
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_Module_{{COMPONENT_NAME}} extends BaseModule{
    // Конструктор модуля. Вызывается при его инициализации
    public function __construct($controller, $args = []) {
        parent::__construct($controller, $args); // Запуск конструктора родителя
    }
    
    // Пример работы с модулем
    function doSomething(){
        echo "Вызов модуля прошел успешно";
        exit();
    }
}