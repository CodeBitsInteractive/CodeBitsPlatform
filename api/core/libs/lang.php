<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           lang.php
//  Назначение:     Библиотека для работы с языками
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class Lang{
    var $curr_lang = "EN";
    var $langpack = [];
    
    // Конструктор класса
    function __construct($lang) {
        $this->curr_lang = strtoupper($lang); // Set Current Lang
    }
    
    // Инициализация
    public function load($pack = ""){
        // Устанавливаем имя пакета
        $_packname = ($pack=="")?"main":$pack;
        
        // Проверяем существование языкового пакета
        if(file_exists(ROOT.'/core/langs/'.$this->curr_lang.'/'.$_packname.'.json')){ // Существует
            $_path = ROOT.'/core/langs/'.$this->curr_lang.'/'.$_packname.'.json';
        }else{ // Пакет не найден
            return ['complete'=>false, 'message'=>'Failed to load language pack'];
        }
        
        // Загрузить пакет
        $_load = @file_get_contents($_path);
        if(!$_load){ // Failed
            return ['complete'=>false, 'message'=>'Failed to load language pack file'];
        }
        
        // Декодировать
        $_load = @json_decode($_load, TRUE); // Decode
        if(!$_load){
            return ['complete'=>false, 'message'=>'Failed to convert language pack'];
        }
        
        // Пакет загружен
        $this->langpack = array_merge_recursive($this->langpack, $_load);
        return ['complete'=>true];
    }
    
    // Получить строку
    public function line($str){
        if(isset($this->langpack[$str])){ // Has
            return $this->langpack[$str]; // Set
        }else{ // No
            return false; // Set
        }
    }
}
?>