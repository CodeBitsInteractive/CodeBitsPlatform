<?php
//===============================================
//  Файл:           class.analytics.php
//  Назначение:     Модель для работы со аналитикой
//  Разработчик:    InterWave
//  Версия:         1.0
//===============================================
// Проверка доступа к файлу
defined('ROOT') OR exit('Прямой доступ к скрипту запрещен настройками безопасности');

//===============================================
//  Определяем класс
//===============================================
class CBP_Model_analytics extends BaseModel{
    var $params = [
        'num'=>7
    ];
    
    // Конструктор модели. Вызывается при ее инициализации
    public function __construct($controller, $args = []) {
        parent::__construct($controller, $args); // Запуск конструктора родителя
        $this->params = array_merge($args,  $this->params); // Передать параметры
    }
    
    // Пример работы с моделью
    public function getAnalyticsList($curr_page = 0){
        // Получаем параметры
        $num = $this->params['num']; // Вывод на страницу
        $page = $curr_page; // Навигатор
        
        // Получаем количество страниц
        $q = $this->CBP->db->query->send("SELECT COUNT(*) FROM `".PREFIX."analytics_data` ORDER BY `uid` DESC"); // Запрос к БД
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
        $q = $this->CBP->db->query->send("SELECT * FROM `".PREFIX."analytics_data` ORDER BY `uid` DESC LIMIT $start,$num");
        if(!$q){ // Ошибка запроса
            return ['complete'=>false, "code"=>"db_request_error"];
        }else{ // Все окей
            $list = []; // Массив страниц
            $cnt = 0;
            while($rw = $this->CBP->db->query->fetch($q)){
                $list[$cnt] = $rw; // Добавить в список
                $list[$cnt]['data'] = unserialize($list[$cnt]['data']);
                $cnt++;
            }
        }
        
        // Все окей
        return ['complete'=>true, 'list'=>$list, 'page'=>$curr_page, 'total'=>$total, 'posts'=>$posts, 'num'=>$num];
    }
    
    // Получить аналитику на сегодня
    public function getAnalyticsToday(){
        // Получаем данные аналитики на 2 дня
        $prev_date = date('d.m.Y', strtotime(date("d.m.Y").' -1 day')); // Прошлый день
        $q = $this->CBP->db->query->select_where("analytics_data", "`day`='".date("d.m.Y")."' OR `day`='".$prev_date."'", 2);
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }else{ // Запрос удался
            $_adata = [];
            if($this->CBP->db->query->num_rows($q)<1){
                $_adata[0] = [
                    'day'=>date("d.m.Y"),
                    'data'=>[
                        'hosts'=>0,
                        'views'=>0,
                        'effectivity'=>0,
                        'registred_users'=>0
                    ]
                ];
            }else{ // Данные есть
                $_cnt = 0;
                while($rw = $this->CBP->db->query->fetch($q)){
                    $_adata[$_cnt] = $rw;
                    $_adata[$_cnt]['data']=unserialize($_adata[$_cnt]['data']);
                    $_cnt++;
                }
            }
        }
        
        // Получаем количество пользователей в сети
        $_getOnline = $this->CBP->db->query->send("SELECT COUNT(*) FROM `".PREFIX."ip_actions` WHERE `action`='visit' AND `time`>".(time()-600)); // Запрос
        if(!$_getOnline){ // Ошибка
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }
        
        // Получаем счетчик
        $_online = $this->CBP->db->query->result($_getOnline);
        
        // Получаем размер всех медиа-данных
        $_media_size = 0; // Начальный размер
        $_media_size = $this->getDirectorySize($_SERVER['DOCUMENT_ROOT'].'/media/'); // Получить размер директории
        $_media_size = $_media_size/1024/1024; // Конверсия в МБ
        $_media_size = round($_media_size); // Округляем
        $_media_size = number_format($_media_size, 0); // Преобразовать
        
        // Все ок
        return ['complete'=>true, 'a_data'=>$_adata, 'online'=>$_online, 'media_size'=>$_media_size];
    }
    
    // Получить данные о посетителях за сегодня
    public function getVisitorsToday(){
        // Получить данные последних 20-ти визитов
        $q = $this->CBP->db->query->select_where("ip_actions", "`day`='".date("d.m.Y")."' AND `action`='visit' ORDER BY `uid` DESC", 20);
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }else{ // Запрос удался
            $_vdata = [];
            $_cnt = 0;
            while($rw = $this->CBP->db->query->fetch($q)){
                $_vdata[$_cnt] = $rw;
                $_vdata[$_cnt]['data']=unserialize($_vdata[$_cnt]['data']);
                $_cnt++;
            }
        }
        
        // Все ок
        return ['complete'=>true, 'v_data'=>$_vdata];
    }

    // Сохранить аналитику на сегодня
    public function updateAnalytics($_purl){
        // Получаем IP пользователя
        $_uip = $this->CBP->user->getIP(); // User IP
        if(!$this->CBP->config->line['system']['analytics']){
            return ['complete'=>true];
        }
        
        // Получаем данные аналитики на сегодня
        $q = $this->CBP->db->query->select_where("analytics_data", "`day`='".date("d.m.Y")."'", 1);
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }else{ // Запрос удался
            if($this->CBP->db->query->num_rows($q)<1){
                $_analyticHasData = false; // Данных нет
                $_adata = [
                    'day'=>date("d.m.Y"),
                    'data'=>[
                        'hosts'=>0,
                        'views'=>0,
                        'effectivity'=>0,
                        'registred_users'=>0
                    ]
                ];
            }else{ // Данные есть
                $_analyticHasData = true; // Данные есть
                $_adata = $this->CBP->db->query->fetch($q); // Получить данные
                $_adata['data']=unserialize($_adata['data']);
            }
        }
        
        // Получаем количество зарегистрированных пользователей
        $_ucount = $this->CBP->user->getUsersCount(); // Получить счетчик
        if(!$_ucount['complete']){ // Запрос не удался
            return ['complete'=>false, "code"=>$_ucount['code']];
        }else{ // Все окей
            $_adata['data']['registred_users'] = $_ucount['count'];
        }
        
        // Получаем действия визитора на сегодня
        $q = $this->CBP->db->query->select_where("ip_actions", "`day`='".date("d.m.Y")."' AND `action`='visit' AND `ip`='".$_uip."'", 1);
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }else{ // Запрос удался
            if($this->CBP->db->query->num_rows($q)<1){
                $_hasData = false; // Данных нет
                $_adata['data']['hosts'] = $_adata['data']['hosts']+1;
                $_adata['data']['views'] = $_adata['data']['views']+1;
                $_ipaction = [
                    'login'=>($this->CBP->user->auth['is_auth'])?$this->CBP->user->auth['login']:'',
                    'ip'=>$_uip,
                    'action'=>'visit',
                    'time'=>time(),
                    'day'=>date("d.m.Y"),
                    'data'=>[
                        0=>[
                            'url'=>$_purl,
                            'time'=>time()
                        ]
                    ]
                ];
            }else{
                $_hasData = true; // Данные есть
                $_ipaction = $this->CBP->db->query->fetch($q); // Получить данные
                $_ipaction['data']=unserialize($_ipaction['data']);
                $_ipaction['login']=($this->CBP->user->auth['is_auth'])?$this->CBP->user->auth['login']:'';
                $_ipaction['time']=time();
                $_ipaction['data'][count($_ipaction['data'])]=[
                    'url'=>$_purl,
                    'time'=>time()
                ];
                $_adata['data']['views'] = $_adata['data']['views']+1;
            }
        }
        
        // Обновляем эффективность
        $_adata['data']['effectivity']=round($_adata['data']['views']/$_adata['data']['hosts']);
        
        // Обновляем статы
        if(!$_analyticHasData){ // Вставка
            $q = $this->CBP->db->query->insert_array("analytics_data", $_adata); // Вставка
        }else{ // Обновление
            $q = $this->CBP->db->query->update_array("analytics_data", $_adata, "`day`='".date("d.m.Y")."'");
        }
        
        // Если запрос не удался
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }
        
        // Обновляем данные визитора
        if(!$_hasData){
            $q = $this->CBP->db->query->insert_array("ip_actions", $_ipaction); // Вставка
        }else{
            $q = $this->CBP->db->query->update_array("ip_actions", $_ipaction, "`day`='".date("d.m.Y")."' AND `action`='visit'");
        }
        
        // Если запрос не удался
        if(!$q){ // Запрос не удался
            return ['complete'=>false, "code"=>"db_request_error", "db_error"=>$this->CBP->db->query->error()];
        }
        
        // Все окей
        return ['complete'=>true];
    }
    
    // Получить размер директории
    private function getDirectorySize($path){
        $bytestotal = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }
}