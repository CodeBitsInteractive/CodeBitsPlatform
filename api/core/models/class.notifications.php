<?php
//===============================================
//  Файл:           class.notifications.php
//  Назначение:     Модель для работы со уведомлениями
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_Model_notifications extends BaseModel{
    var $params = [
        'enabled'=>false
    ];
    
    // Конструктор модели. Вызывается при ее инициализации
    public function __construct($controller, $args = []) {
        parent::__construct($controller, $args); // Запуск конструктора родителя
        $this->params = array_merge($args,  $this->params); // Передать параметры
        
        // Получаем параметры
        $this->params['enabled'] = $this->CBP->config->line['users']['notifications'];
    }
    
    // Получить список уведомлений
    public function getNotificationsList($curr_page, $num = 20){
        $request = $this->CBP->db->query; // Линк на БД
        
        // Задаем параметры
        $num = intval($num); // Вывод на страницу
        $page = intval($curr_page); // Навигатор
        
        // Получаем количество страниц
        $q = $this->CBP->db->query->send("SELECT COUNT(*) FROM `".PREFIX."notifications` WHERE `for`=-1 OR `for`=".$this->CBP->user->auth['profile_uid']); // Запрос к БД
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
        $q = $this->CBP->db->query->send("SELECT * FROM `".PREFIX."notifications` WHERE `for`=-1 OR `for`=".$this->CBP->user->auth['profile_uid']." ORDER BY `time` DESC LIMIT $start,$num");
        if(!$q){ // Ошибка запроса
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Все окей
            $list = []; // Массив страниц
            $cnt = 0;
            while($rw = $this->CBP->db->query->fetch($q)){
                $list[$cnt] = $rw; // Добавить в список
                $cnt++;
            }
        }
        
        // Считаем количество не прочитанных
        $q = $this->CBP->db->query->send("SELECT COUNT(*) FROM `".PREFIX."notifications` WHERE (`for`=-1 OR `for`=".$this->CBP->user->auth['profile_uid'].") AND `readed`=0"); // Запрос к БД
        if(!$q){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{
            $unreaded = $this->CBP->db->query->result($q); // Получить результат
        }
        
        // Все окей
        return ['complete'=>true, 'list'=>$list, 'page'=>$curr_page, 'total'=>$total, 'posts'=>$posts, 'num'=>$num, 'new'=>$unreaded];
    }
    
    // Создать уведомление
    public function createNotification($for, $text, $icon = ""){
        $request = $this->CBP->db->query; // Линк на БД
        $for = intval($for); // Получатель
        $text = $request->escape($text); // Текст
        $icon = $request->escape($icon); // Иконка
        
        if(!$this->params['enabled']){
            return ['complete'=>true];
        }
        
        // Поиск получателя
        if($for!=-1){ // Не для админов
            // Делаем запрос списка страниц
            $q = $this->CBP->db->query->send("SELECT `uid` FROM `".PREFIX."profiles` WHERE `uid`=".$for." LIMIT 1");
            if(!$q){ // Ошибка запроса
                return ['complete'=>false, "code"=>"db_request_error"];
            }else{
                if($request->num_rows($q)<1){
                    return ['complete'=>false, "code"=>"notification_for_error"];
                }
            }
        }
        
        // Создаем уведомление
        $q = $request->send("INSERT INTO `".PREFIX."notifications` SET `for`=".$for.", `icon`='".$icon."', `text`='".$text."', `readed`=0, `time`=".time());
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Чтение уведомлений
    public function readAll(){
        $request = $this->CBP->db->query; // Линк на БД
        
        // Ставим флаг чтения
        $q = $request->send("UPDATE `".PREFIX."notifications` SET `readed`=1 WHERE `readed`=0 AND `for`=".$this->CBP->user->auth['profile_uid']." OR `for`=-1");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Все ок
        return ['complete'=>true];
    }
    
    // Удаление уведомления
    public function remove($uid){
        $request = $this->CBP->db->query; // Линк на БД
        $uid = intval($uid); // Page Slug
        
        // Удаляем оповещение
        $q = $request->send("DELETE FROM `".PREFIX."notifications` WHERE `uid`=".$uid." AND (`for`=".$this->CBP->user->auth['profile_uid']." OR `for`=-1)");
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error"];
        }
        
        // Все ок
        return ['complete'=>true];
    }
}
?>