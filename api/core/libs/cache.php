<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           cache.php
//  Назначение:     Класс для работы с кешированием
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  Определяем класс кеширования. Не работает в режиме API
//==============================================================
class Cache{
    // Параметры
    var $lang,$confs;
    var $enabled = false;
    var $cachetime = 0;
    var $params = [];
    var $name = '';
    
    // Конструктор класса
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
        
        // Установка объектов
        $this->lang = $this->params['lang']; // Язык
        $this->confs = $this->params['confs']; // Конфиги библиотеки
        $this->enabled = $this->confs['enabled']; // Включен ли кеш
        $this->cachetime = $this->confs['time']; // Время кеша
    }
    
    // Получить кеш
    public function getCache($name){
        if(defined("WRAPPER") && WRAPPER && $this->enabled){ // Режим Frontend-а
            $_url_break = implode("-",array_slice(explode('/', URL), 1)); // Разрубить URL
            $_url_break = ($_url_break=="")?"home":$_url_break; // Для главной страницы
            $_filename = GLOBALROOT."/cache/cache-".md5($_url_break).'-'.$this->lang->curr_lang.'-'.$name.'.cache'; // Путь к кешу
            $this->name = $_filename; // Задаем имя

            // Если кеш не истек - выдаем его
            if (file_exists($_filename) && time() - $this->cachetime <filemtime($_filename)) {
                echo "<!-- Cached copy, generated ".date('H:i', filemtime($_filename))." by CodeBits Platform -->\n";
                include($_filename);
                exit;
            }
            
            // Начало кеширования
            ob_start(); // Запуск буфера вывода
        }else{ // Режим API
            return false;
        }
    }
    
    // Удалить кеш
    public function removeCache($name){
        $_url_break = implode("-",array_slice(explode('/', URL), 1)); // Разрубить URL
        $_url_break = ($_url_break=="")?"home":$_url_break; // Для главной страницы
        $_filename = GLOBALROOT."/cache/cache-".md5($_url_break).'-'.$this->lang->curr_lang.'-'.$name.'.cache'; // Путь к кешу
        if (file_exists($_filename)){
            unlink($_filename);
        }
    }
    
    // Удалить кеш по имени файла
    public function removeByName($filename){
        $_filename = GLOBALROOT."/cache/".$filename; // Путь к кешу
        if (file_exists($_filename)){
            unlink($_filename);
        }
        
        return true;
    }

    // Сохранить кеш
    public function saveCache(){
        if(defined("WRAPPER") && WRAPPER && $this->enabled){ // Режим Frontend-а
            $cached = fopen($this->name, 'w'); // Открыть файл кеша
            fwrite($cached, ob_get_contents()); // Сохранить контент
            fclose($cached); // Закрыть файл
            ob_end_flush(); // Закончить запись
        }
    }
    
    // Очистить кеш
    public function clearCache(){
        foreach (glob(GLOBALROOT.'/cache/*') as $filename){ // Собрать список файлов
            unlink($filename); // Удалить
        }
        
        // Вернуть ответ
        return true;
    }
}
?>