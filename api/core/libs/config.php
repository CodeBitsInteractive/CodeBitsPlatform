<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           config.php
//  Назначение:     Класс для работы с конфигами
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  Config
//==============================================================
//  Данный класс служит для обработки файлов конфигураций в
//  JSON-формате. В нашем случае мы используем его для загрузки
//  основных конфигураций из файла conf.main.json
//==============================================================
class Config{
    // Параметры класса
    var $line; // Массив конфигов
    var $params = [ // Массив параметров
        'path'=>'/core/configs/conf.main.json'
    ];
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
    }
    
    // Получить конфигурации
    // При получении новых конфигов, старые будут
    // исключены из объекта
    function get_configs(){
        // Попытка чтения
        $path = ROOT.$this->params['path']; // Путь
        $read = @file_get_contents($path); // Сохранить
        if(!$read){ // Ошибка
            return ['complete'=>false, 'message'=>'Failed to load server configuration'];
        }
        
        // Попытка преобразования
        $json = @json_decode($read, true); // преобразовать
        if(!$json){ // Ошибка преобразования
            return ['complete'=>false, 'message'=>'Failed to convert server configuration from JSON'];
        }else{ // Преобразовано
            $this->line = $json; // Задать конфиги
        }
        
        // Все ок
        return ['complete'=>true, 'configs'=>$this->line];
    }
    
    // Сохранить конфигурации
    function save_configs(){
        // Поптыка преобразования в JSON
        $json = @json_encode($this->line); // преобразовать
        if(!$json){ // Ошибка преобразования
            return ['complete'=>false, 'message'=>'Failed to convert server configuration to JSON'];
        }
        
        // Попытка сохранения
        $path = ROOT.$this->params['path']; // Путь
        $save = @file_put_contents($path, $json); // Сохранить
        if(!$save){ // Ошибка
            return ['complete'=>false, 'message'=>'Failed to save configuraction'];
        }
        
        // Все ок
        return ['complete'=>true];
    }
}
?>