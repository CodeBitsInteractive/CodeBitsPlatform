<?php
//===============================================
//  Файл:           class.static.php
//  Назначение:     Модель для работы со страницами
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_Model_static extends BaseModel{
    var $params = [
        'num'=>20,
        'lang'=>""
    ];
    
    // Конструктор модели. Вызывается при ее инициализации
    public function __construct($controller, $args = []) {
        parent::__construct($controller, $args); // Запуск конструктора родителя
        $this->params = array_merge($args,  $this->params); // Передать параметры
        
        // Если язык не задан
        if($this->params['lang']=="" || !file_exists(ROOT.'/core/langs/'.$this->params['lang'].'/')) $this->params['lang']=$this->CBP->lang->curr_lang;
        if(intval($this->params['num'])<1) $this->params['num']=1;
    }
    
    // Пример работы с моделью
    public function getPagesList($curr_page = 0, $search = false){
        // Получаем параметры
        $num = $this->params['num']; // Вывод на страницу
        $lang = $this->params['lang']; // Язык для вывода
        $page = intval($curr_page); // Навигатор
        
        // Параметр поиска
        $_like = ($search!==false && strlen($search)>0)?" AND (`title` LIKE '%".$search."%' OR `desc` LIKE '%".$search."%')":"";
        
        // Получаем количество страниц
        $q = $this->CBP->db->query->send("SELECT COUNT(*) FROM `".PREFIX."static_pages` WHERE `language`='".$lang."'".$_like); // Запрос к БД
        if(!$q){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Считаем количество страниц
        $posts = $this->CBP->db->query->result($q); // Получить результат
        $total = intval(($posts - 1) / $num) + 1;  // Высчитываем общее количество страниц
        $page = intval($page); // Начало сообщений для страницы
        if(empty($page) or $page < 0) $page = 1; // Если значение слишком маленькое - ставим первую страницу
        if($page > $total) $page = $total; // Если значение слишком большое - ставим последнюю страницу
        $start = $page * $num - $num; // Определяем откуда начинать лимиты
        
        // Делаем запрос списка страниц
        $q = $this->CBP->db->query->send("SELECT `uid`,`language`,`slug`,`title`,`desc`,`tags`,`views`,`time`,`image` FROM `".PREFIX."static_pages` WHERE `language`='".$lang."'".$_like." LIMIT $start,$num");
        if(!$q){ // Ошибка запроса
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Все окей
            $list = []; // Массив страниц
            while($rw = $this->CBP->db->query->fetch($q)){
                $list[$rw['slug']] = $rw; // Добавить в список
            }
        }
        
        // Все окей
        return ['complete'=>true, 'list'=>$list, 'page'=>$curr_page, 'language'=>$lang, 'total'=>$total, 'posts'=>$posts, 'num'=>$num];
    }
    
    // Получить контент страницы
    public function getPageContent($page_slug){
        $request = $this->CBP->db->query; // Линк на БД
        
        // Получаем параметры
        $lang = $this->params['lang']; // Язык для вывода
        $slug = $page_slug; // Системное имя
        
        // Проверка контента
        $q = $request->send("SELECT * FROM `".PREFIX."static_pages` WHERE `slug`='".$page_slug."'");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Запрос удался
            if($request->num_rows($q)<1){ // Ничего не найдено, сохранение уже существующей
                return ['complete'=>false, 'code'=>'failed_to_found_page'];
            }else{ // Загрузка данных
                $_data = [];
                $default_language = '';
                while($rw = $request->fetch($q)){
                    if($default_language=='') $default_language=$rw['language'];
                    $_data[$rw['language']] = $rw;
                }
            }
        }
        
        // Если включена аналитика
        if($this->CBP->config->line['system']['analytics']){
            $views = $_data[$lang]['views']+1;
            $q = $request->send("UPDATE `" . PREFIX . "static_pages` SET `views`='".$views."' WHERE `uid`=".$_data[$lang]['uid']);
            if (!$q) { // Запрос не удался
                return ['complete' => false, "code" => "db_request_error"];
            }
        }
        
        // Все ок
        return ['complete'=>true, 'data'=>$_data, 'default_language'=>$default_language];
    }
    
    // Сохранить контент страницы
    public function savePage($data, $page_slug, $new){
        $request = $this->CBP->db->query; // Линк на БД
        
        // Проверка существования страницы
        $q = $request->send("SELECT * FROM `".PREFIX."static_pages` WHERE `slug`='".$page_slug."'");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Запрос удался
            if($request->num_rows($q)<1 && !$new){ // Ничего не найдено, сохранение уже существующей
                return ['complete'=>false, 'code'=>'failed_to_found_page'];
            }else if($request->num_rows($q)>0 && $new){ // Что-то найдено, создание новой
                return ['complete'=>false, 'code'=>'page_already_exists'];
            }else{ // Что-то найдено при обновлении или ничего не найдено при создании новой :D
                $_create_new = (!$new)?false:true; // Флаг создания
            }
        }
        
        // Получаем UID-ы страниц для обновления
        if(!$_create_new){ // Не для новой страницы
            foreach($data as $lng=>$val){ // Поиск данных
                $q = $request->send("SELECT `uid`,`slug`,`language` FROM `".PREFIX."static_pages` WHERE `slug`='".$page_slug."'");
                if(!$q){ // Запрос не удался
                    return ['complete'=>false, "code"=>"db_request_error"];
                }
            }
        }
        
        // Обработка данных
        foreach($_dt as $key=>$val){
            $_dt[$key]=$request->escape($val);
        }
        
        // Обновление / добавление контента
        if(!$_create_new){ // Не для новой страницы
            foreach($data as $lng=>$val){ // Перебор языков
                $_dt = $data[$lng]; // Данные
                $q = $request->send("UPDATE `".PREFIX."static_pages` SET `title`='".$_dt['title']."', `desc`='".$_dt['desc']."', `tags`='".$_dt['tags']."', `body`='".$_dt['body']."', `image`='".$_dt['image']."', `views`=0, `time`=".time()." WHERE `language`='".$lng."' AND `slug`='".$page_slug."'");
                if(!$q){ // Запрос не удался
                    return ['complete'=>false, "code"=>"db_request_error"];
                }
            }
        }else{ // Для новой страницы
            foreach($data as $lng=>$val){ // Перебор языков
                $_dt = $val; // Данные
                $q = $request->send("INSERT INTO `".PREFIX."static_pages` SET `language`='".$lng."', `slug`='".$page_slug."', `title`='".$_dt['title']."', `desc`='".$_dt['desc']."', `tags`='".$_dt['tags']."', `body`='".$_dt['body']."', `image`='".$_dt['image']."', `views`=0, `time`=".time());
                if(!$q){ // Запрос не удался
                    return ['complete'=>false, "code"=>"db_request_error"];
                }
            }
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Удалить страницу
    public function removePage($page_slug){
        $request = $this->CBP->db->query; // Линк на БД
        $page_slug = $request->escape($page_slug); // Page Slug
        
        // Проверка контента
        $q = $request->send("SELECT * FROM `".PREFIX."static_pages` WHERE `slug`='".$page_slug."'");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Запрос удался
            if($request->num_rows($q)<1){ // Ничего не найдено, сохранение уже существующей
                return ['complete'=>false, 'code'=>'failed_to_found_page'];
            }
        }
        
        // Удаляем страницу
        $q = $request->send("DELETE FROM `".PREFIX."static_pages` WHERE `slug`='".$page_slug."'");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Все ок
        return ['complete'=>true];
    }
}