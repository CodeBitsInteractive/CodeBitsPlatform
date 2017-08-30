<?php
//===============================================
//  Файл:           class.media.php
//  Назначение:     Контроллер обработки медиа
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_media extends BaseController{   
    // Контроллер
    public function __construct($args = [], $libs = []) {
        // Инициализация
        parent::__construct($args, $libs); // Запуск конструктора родителя
    }
    
    // Инициализация модуля
    public function init($data){
        header("Location: /errors/?code=404");
        exit();
    }
    
    // Получить список
    public function getList($fd = []){
        $this->_noFrontend(); // Для API - выбиваем ошибку
        
        // Проверка авторизации
        if(!$this->user->auth['is_admin']){
            $_render = $this->render("", false, ['message'=>$this->lang->line('auth_required')]); // Рендер
        }
        
        // Проверка прав
        if(!$this->user->auth['is_admin']){
            $_render = $this->render("", false, ['message'=>$this->lang->line('admin_required')]); // Рендер
        }
        
        // Получаем список медиа-файлов
        $_filelist = glob($_SERVER['DOCUMENT_ROOT'].'/media/*.{png,jpg,jpeg}', GLOB_BRACE); // Поиск медиа
        foreach($_filelist as $key=>$val){
            $_filelist[$key]=basename($val);
        }
        
        // Настраиваем
        $_data = [ // Данные, передаваемые по API
            'list'=>$_filelist
        ];
        
        // Вывод данных
        $_render = $this->render("", true, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
    }


    // Загрузка файла
    public function upload($fd = []){
        $this->_noFrontend(); // Для API - выбиваем ошибку
        $this->lang->load('errors');
        
        // Проверка авторизации
        if(!$this->user->auth['is_admin']){
            $_render = $this->render("", false, ['message'=>$this->lang->line('auth_required')]); // Рендер
        }
        
        // Проверка существования файла
        if(!isset($_FILES['file'])){
            $_render = $this->render("", false, ['message'=>"Security warning. Please, try again later."]); // Рендер
        }
        
        // Проверка ошибок загрузки
        if ($_FILES['file']['error']>0) {
            $_render = $this->render("", false, ['message'=>$_FILES['file']['error']]); // Рендер
        }
        
        // Проверка расширения
        $file_parts = pathinfo($_FILES['file']["name"]);
        $ext = strtolower($file_parts['extension']);
        if($ext!='jpg' && $ext!='png' && $ext!='jpeg'){
            $_smsg = $this->lang->line('media_wrong_ext');
            $_render = $this->render("", false, ['message'=>$_smsg]); // Рендер
        }
        
        
        // Получение размеров (ширина, высота)
        $_size = getimagesize($_FILES['file']["tmp_name"]); // Получаем данные
        if(!$_size || count($_size)<2){ // Неверный массив
            $_render = $this->render("", false, ['message'=>"Security warning. Please, try again later."]); // Рендер
        }
        
        // Проверка размеров файла
        if($_size[0]>$this->config->line['system']['max_upload_width'] || $_size[1]>$this->config->line['system']['max_upload_height'] || $_FILES['file']['size']>$this->config->line['system']['max_upload_filesize']){
            $_smsg = $this->lang->line('media_size_label1').$this->config->line['system']['max_upload_width'].'x'.$this->config->line['system']['max_upload_height'].$this->lang->line('media_size_label2').($this->config->line['system']['max_upload_filesize']/1024).$this->lang->line('media_size_label3');
            $_render = $this->render("", false, ['message'=>$_smsg]); // Рендер
        }
        
        // Попытка переместить файл
        $_newname = md5($_FILES['file']['tmp_name'])."_".rand(0, 999999999).'.'.$ext;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/media/'.$_newname)){
            $_smsg = $this->lang->line('media_move_error');
            $_render = $this->render("", false, ['message'=>$_smsg]); // Рендер
        }
        
        // Настраиваем
        $_data = [ // Данные, передаваемые по API
            'url'=>'/media/'.$_newname
        ];
        
        // Вывод данных
        $_render = $this->render("", true, $_data); // Рендер
        if(!$_render['complete']){ // Рендеринг обрушился
            echo "<b>Error:</b> Failed to load template. <b>File: \"".$_template."\"</b> not found.";
            exit();
        }
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