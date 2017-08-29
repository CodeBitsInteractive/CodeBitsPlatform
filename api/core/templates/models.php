<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           {{COMPONENT_NAME}}.php
//  Назначение:     Шаблон модели для CodeBits Platform
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_Model_{{COMPONENT_NAME}} extends BaseModel{
    // Конструктор модели. Вызывается при ее инициализации
    public function __construct($controller, $args = []) {
        parent::__construct($controller, $args); // Запуск конструктора родителя
    }
    
    // Пример работы с моделью
    function doSomething(){
        $_test = $this->CBP->db->query->select("auth"); // Выборка
        echo "Количество строк в таблице: ".$this->CBP->db->query->num_rows($_test);
        exit();
    }
}