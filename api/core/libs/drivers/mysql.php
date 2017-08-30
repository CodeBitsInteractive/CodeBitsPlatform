<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           mysql.php
//  Назначение:     Драйвер работы с MySQL
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//==============================================================
//  MySQL Diver
//==============================================================
//  Драйвер для работы с MySQL базой данных. См. документацию
//  по работе с моделями.
//==============================================================
class driver_mysql{
    var $last_query = ""; // Последний запрос
    var $params = [ // Массив параметров
        'host'=>'', // Хост
        'name'=>'', // Имя
        'login'=>'', // Логин
        'password'=>'', // Пароль
        'encoding'=>'', // Кодировка
        'prefix'=>'' // Префикс
    ];
    
    // Конструктор драйвера
    function __construct($args = []) {
        // Поиск аргументов функции
        if(isset($args) && is_array($args) && count($args)>0){ // Аргументы есть
            $this->params = array_merge($this->params, $args); // Объединить массивы
        }
    }
    
    // Подключение к базе данных
    function connect(){
        // Параметры подключения
        $_db = $this->params;
        
        // Создаем подключение
        $connect = mysql_connect($_db['host'], $_db['login'], $_db['password']); // Подключение
        if(!$connect){ // Ошибка
            return ['complete'=>false, 'message'=>'Failed to connect with DB: '.  mysql_error()];
        }
        
        // Задаем кодировку
        $setchar = mysql_set_charset($_db['encoding']); // Задать кодировку
        if(!$setchar){ // Ошибка
            return ['complete'=>false, 'message'=>'Failed to set DB encoding: '.  mysql_error()];
        }
        
        // Выбираем базу
        $select = mysql_select_db($_db['name']); // Выбрать БД
        if(!$select){ // Ошибка
            return ['complete'=>false, 'message'=>'Failed to select DB: '.  mysql_error()];
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Стандартный запрос к базе данных.
    // SQL запрос
    function send($sql){
        // Записываем последний запрос
        $this->last_query = $sql; // Сохранить
        
        // Выполняем запрос
        $q = mysql_query($sql); // Запрос
        if(!$q){ // Ошибка запроса
            return false;
        }else{ // Все ок
            return $q;
        }
    }
    
    // Вывод ошибки
    function error(){
        return mysql_error();
    }
    
    // Escape String
    function escape($string){
        return mysql_real_escape_string($string); // Возврат
    }
    
    // Fetch
    function fetch($result){
        mysql_fetch_assoc($result);
    }
    
    // Fetch Array
    function fetch_array($result){
        mysql_fetch_array($result);
    }
    
    // Вывод результата
    function result($res, $row = 0){
        mysql_result($res, $row);
    }
    
    // Количество строк
    function num_rows($result){
        mysql_num_rows($result);
    }
    
    // Вывод последнего ID
    function insert_id(){
        return mysql_insert_id();
    }
    
    // Вставка данных
    function insert_array($table, $data){
        // Формируем запрос
        $sql = "INSERT INTO `".PREFIX.$table."` SET "; // Базовый SQL
        $sql += $this->_arrToSring($data); // Объединяем данные в запрос
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Обновление данных
    function update_array($table, $data, $where = ""){
        // Формируем запрос
        $sql = "UPDATE `".PREFIX.$table."` SET "; // Базовый SQL
        $sql += $this->_arrToSring($data); // Объединяем данные в запрос
        $sql += ($where!="")?" WHERE ".$where:""; // Добавить фильтрацию
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Выборка данных (простая)
    function select($table){
        // Формируем запрос
        $sql = "SELECT * FROM `".PREFIX.$table."`"; // Базовый SQL
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Выборка данных с фильтром
    function select_where($table, $where, $from = null, $to = null){
        // Формируем запрос
        $sql = "SELECT * FROM `".PREFIX.$table."`"; // Базовый SQL
        $sql += ($where!="")?" WHERE ".$where:""; // Добавить фильтрацию
        $sql += ($from!=null)?" LIMIT ".$from:""; // Добавить старт LIMIT-а
        $sql += ($to!=null)?",".$to:""; // Добавить конец LIMIT-а
        
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Удалить
    function delete_where($table, $where){
        // Формируем запрос
        $sql = "DELETE FROM `".PREFIX.$table."`"; // Базовый SQL
        $sql += ($where!="")?" WHERE ".$where:""; // Добавить фильтрацию
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Выборка количества (простая)
    function num($table){
        // Формируем запрос
        $sql = "SELECT COUNT(*) FROM `".PREFIX.$table."`"; // Базовый SQL
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Выборка количества с фильтром
    function num_where($table, $where, $from = null, $to = null){
        // Формируем запрос
        $sql = "SELECT * FROM `".PREFIX.$table."`"; // Базовый SQL
        $sql += ($where!="")?" WHERE ".$where:""; // Добавить фильтрацию
        $sql += ($from!=null)?" LIMIT ".$from:""; // Добавить старт LIMIT-а
        $sql += ($to!=null)?",".$to:""; // Добавить конец LIMIT-а
        
        
        // Выполняем запрос
        $request = $this->send($sql); // Отправить запрос БД
        return $request; // Вернуть ответ
    }
    
    // Вспомогательная функция формата данных
    private function _format_data($data){
        if(is_array($data)){ // Данные являются массивом
            return "'".serialize($data)."'"; // Сериализовать
        }else if($data==""){
            return "''";
        }else if(!preg_match("/[^0-9]/", $data)){ // Является числом
            return intval($data); // Вернуть число
        }else{ // Является строкой
            return "'".$data."'";
        }
    }
    
    // Конверсия массива в строку
    private function _arrToSring($data){
        foreach($data as $key=>$val){ // Перебор данных
            $data[$key]="`".$key."`=".$this->_format_data($val); // Переформируем данные
        }
        
        return implode(",", $data); // Объединяем данные в запрос
    }
}