<?php
    // Установка параметров
    $LANG->load("installer"); // Получить язык инсталлятора
    $step = (isset($_GET['step']) && intval($_GET['step'])>0)?intval($_GET['step']):1; // Шаг
    $error = (isset($_GET['error']) && strlen($_GET['error'])>0)?$LANG->line($_GET['error']):false; // Флаг ошибки
    $langs = [['name'=>'Русский','code'=>'ru'],['name'=>'English','code'=>'en']];
    $drivers = ['mysqli','mysql'];
    
    // Если есть данные формы
    if(isset($_POST['form']) && strlen($_POST['form'])>0){
        $form = $_POST['form']; // Форма
        switch ($form){ // Исходя из типа формы
            case "db": // DB
                $dbs = $_POST['db']; // Параметры DB
                $dbs['encoding']='utf8'; // Кодировка
                
                // Проверка префикса
                if(preg_match("/[^a-z\_]+$/u", strtolower($dbs['prefix']))){
                    header("Location: /?step=2&error=error_installer_db_prefix");
                    exit();
                }
                
                // Попытка подключения
                if($dbs['driver']=='mysql'){ // MySQL
                    $connect = mysql_connect($dbs['host'], $dbs['login'], $dbs['password']); // Подключение
                    if(!$connect){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_connection");
                        exit();
                    }
                    $setchar = mysql_set_charset($dbs['encoding']); // Задать кодировку
                    if(!$setchar){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_encoding");
                        exit();
                    }
                    $select = mysql_select_db($dbs['name']); // Выбрать БД
                    if(!$select){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_select");
                        exit();
                    }
                }else{ // MySQLi
                    $mysqli = null;
                    $mysqli = new mysqli($dbs['host'], $dbs['login'], $dbs['password']); // Подключение
                    if(!$mysqli){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_connection");
                        exit();
                    }
                    $setchar = $mysqli->set_charset($dbs['encoding']); // Задать кодировку
                    if(!$setchar){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_encoding");
                        exit();
                    }
                    $select = $mysqli->select_db($dbs['name']); // Выбрать БД
                    if(!$select){ // Ошибка
                        header("Location: /?step=2&error=error_installer_db_select");
                        exit();
                    }
                }
                
                // Задаем конфигурации
                $CFG->line['db'] = array_replace_recursive($CFG->line['db'], $dbs);
                $CFG->line['system']['secret'] = md5(rand(10000,9999999)."|secretkey");
                $_json = json_encode($CFG->line);
                $_save = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/api/core/configs/conf.main.json', $_json);
                
                // Запросы в БД (Создание таблиц)
                $_dbq[0]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."activation` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `email` varchar(250) NOT NULL, `code` varchar(250) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
                $_dbq[1]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."analytics_data` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `day` varchar(20) NOT NULL, `data` blob NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15;";
                $_dbq[2]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."auth` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `login` varchar(250) NOT NULL, `token` varchar(250) NOT NULL, `from` varchar(100) NOT NULL, `is_admin` int(1) NOT NULL, `profile_uid` bigint(20) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
                $_dbq[3]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."ip_actions` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `ip` varchar(50) NOT NULL, `login` varchar(255) NOT NULL, `action` varchar(200) NOT NULL, `data` blob NOT NULL, `time` bigint(20) NOT NULL, `day` varchar(30) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14;";
                $_dbq[4]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."notifications` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `icon` varchar(50) NOT NULL, `text` blob NOT NULL, `for` bigint(20) NOT NULL, `readed` int(1) NOT NULL, `time` bigint(20) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33;";
                $_dbq[5]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."profiles` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `avatar` varchar(250) NOT NULL, `nickname` varchar(30) NOT NULL, `email` varchar(250) NOT NULL, `profile_data` blob NOT NULL, `ban_data` blob NOT NULL, `last_login_day` bigint(20) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
                $_dbq[6]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."reset` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `email` varchar(250) NOT NULL, `code` varchar(250) NOT NULL, `time` bigint(20) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
                $_dbq[7]="CREATE TABLE IF NOT EXISTS `".$dbs['prefix']."static_pages` (`uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `language` varchar(2) NOT NULL, `slug` varchar(50) NOT NULL, `title` varchar(50) NOT NULL, `desc` varchar(200) NOT NULL, `tags` varchar(250) NOT NULL, `body` longblob NOT NULL, `image` varchar(250) NOT NULL, `views` bigint(20) NOT NULL, `time` bigint(20) NOT NULL, PRIMARY KEY (`uid`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16;";
                
                // Запрос в БД (Наполнение данными)
                $_dbq[8]="INSERT INTO `".$dbs['prefix']."analytics_data` (`uid`, `day`, `data`) VALUES (6, '19.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a31323b733a31313a226566666563746976697479223b643a31323b733a31353a227265676973747265645f7573657273223b733a313a2231223b7d),
                            (7, '20.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a3132333b733a31313a226566666563746976697479223b643a3132333b733a31353a227265676973747265645f7573657273223b733a313a2231223b7d),
                            (8, '21.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a313b733a31313a226566666563746976697479223b643a313b733a31353a227265676973747265645f7573657273223b733a313a2231223b7d),
                            (9, '22.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a37373b733a31313a226566666563746976697479223b643a37373b733a31353a227265676973747265645f7573657273223b733a313a2232223b7d),
                            (10, '24.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a38363b733a31313a226566666563746976697479223b643a38363b733a31353a227265676973747265645f7573657273223b733a313a2233223b7d),
                            (11, '25.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a343b733a31313a226566666563746976697479223b643a343b733a31353a227265676973747265645f7573657273223b733a313a2233223b7d),
                            (12, '26.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a31313b733a31313a226566666563746976697479223b643a31313b733a31353a227265676973747265645f7573657273223b733a313a2233223b7d),
                            (13, '27.08.2017', 0x613a343a7b733a353a22686f737473223b693a313b733a353a227669657773223b693a3132313b733a31313a226566666563746976697479223b643a3132313b733a31353a227265676973747265645f7573657273223b733a313a2232223b7d),
                            (14, '28.08.2017', 0x613a343a7b733a353a22686f737473223b693a323b733a353a227669657773223b693a39323b733a31313a226566666563746976697479223b643a34363b733a31353a227265676973747265645f7573657273223b733a313a2233223b7d);";
                $_dbq[9]="INSERT INTO `".$dbs['prefix']."ip_actions` (`uid`, `ip`, `login`, `action`, `data`, `time`, `day`) VALUES (13, '127.0.0.1', 'davemirruel', 'visit', 0x613a313a7b693a303b613a323a7b733a333a2275726c223b733a32383a22687474703a2f2f7465737470726f6a6563742e72752f61646d696e2f223b733a343a2274696d65223b693a313530333933363131323b7d7d, 1503936112, '28.08.2017');";
                $_dbq[10]="INSERT INTO `".$dbs['prefix']."static_pages` (`uid`, `language`, `slug`, `title`, `desc`, `tags`, `body`, `image`, `views`, `time`) VALUES (12, 'RU', 'about', 'О нас', 'Это простой пример страницы о вашей компании. Вы можете изменить ее в панели управления CodeBits Platform.', 'просто,страница,о нас', 0x3c703e3c703ed0a2d0bed0b2d0b0d180d0b8d189d0b82120d181d0bbd0bed0b6d0b8d0b2d188d0b0d18fd181d18f20d181d182d180d183d0bad182d183d180d0b020d0bed180d0b3d0b0d0bdd0b8d0b7d0b0d186d0b8d0b820d0b8d0b3d180d0b0d0b5d18220d0b2d0b0d0b6d0bdd183d18e20d180d0bed0bbd18c20d0b220d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d0b820d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d18ed189d0b8d0b920d183d181d0bbd0bed0b2d0b8d0b920d0b0d0bad182d0b8d0b2d0b8d0b7d0b0d186d0b8d0b82e20d0a0d0b0d0b7d0bdd0bed0bed0b1d180d0b0d0b7d0bdd18bd0b920d0b820d0b1d0bed0b3d0b0d182d18bd0b920d0bed0bfd18bd18220d0bad0bed0bdd181d183d0bbd18cd182d0b0d186d0b8d18f20d18120d188d0b8d180d0bed0bad0b8d0bc20d0b0d0bad182d0b8d0b2d0bed0bc20d181d0bfd0bed181d0bed0b1d181d182d0b2d183d0b5d18220d0bfd0bed0b4d0b3d0bed182d0bed0b2d0bad0b820d0b820d180d0b5d0b0d0bbd0b8d0b7d0b0d186d0b8d0b820d184d0bed180d0bc20d180d0b0d0b7d0b2d0b8d182d0b8d18f2e3c2f703e3c62723e0d0a3c703ed09dd0b520d181d0bbd0b5d0b4d183d0b5d1822c20d0bed0b4d0bdd0b0d0bad0be20d0b7d0b0d0b1d18bd0b2d0b0d182d18c2c20d187d182d0be20d0bdd0b0d187d0b0d0bbd0be20d0bfd0bed0b2d181d0b5d0b4d0bdd0b5d0b2d0bdd0bed0b920d180d0b0d0b1d0bed182d18b20d0bfd0be20d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d18e20d0bfd0bed0b7d0b8d186d0b8d0b820d182d180d0b5d0b1d183d18ed18220d0bed18220d0bdd0b0d18120d0b0d0bdd0b0d0bbd0b8d0b7d0b020d181d0b8d181d182d0b5d0bcd18b20d0bed0b1d183d187d0b5d0bdd0b8d18f20d0bad0b0d0b4d180d0bed0b22c20d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d0b5d18220d0bdd0b0d181d183d189d0bdd18bd0bc20d0bfd0bed182d180d0b5d0b1d0bdd0bed181d182d18fd0bc2e20d0a120d0b4d180d183d0b3d0bed0b920d181d182d0bed180d0bed0bdd18b20d0bdd0b0d187d0b0d0bbd0be20d0bfd0bed0b2d181d0b5d0b4d0bdd0b5d0b2d0bdd0bed0b920d180d0b0d0b1d0bed182d18b20d0bfd0be20d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d18e20d0bfd0bed0b7d0b8d186d0b8d0b820d0bfd0bed0b7d0b2d0bed0bbd18fd0b5d18220d0b2d18bd0bfd0bed0bbd0bdd18fd182d18c20d0b2d0b0d0b6d0bdd18bd0b520d0b7d0b0d0b4d0b0d0bdd0b8d18f20d0bfd0be20d180d0b0d0b7d180d0b0d0b1d0bed182d0bad0b520d0bdd0b0d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b920d0bfd180d0bed0b3d180d0b5d181d181d0b8d0b2d0bdd0bed0b3d0be20d180d0b0d0b7d0b2d0b8d182d0b8d18f2e20d09dd0b520d181d0bbd0b5d0b4d183d0b5d1822c20d0bed0b4d0bdd0b0d0bad0be20d0b7d0b0d0b1d18bd0b2d0b0d182d18c2c20d187d182d0be20d0bfd0bed181d182d0bed18fd0bdd0bdd18bd0b920d0bad0bed0bbd0b8d187d0b5d181d182d0b2d0b5d0bdd0bdd18bd0b920d180d0bed181d18220d0b820d181d184d0b5d180d0b020d0bdd0b0d188d0b5d0b920d0b0d0bad182d0b8d0b2d0bdd0bed181d182d0b820d182d180d0b5d0b1d183d18ed18220d0bed0bfd180d0b5d0b4d0b5d0bbd0b5d0bdd0b8d18f20d0b820d183d182d0bed187d0bdd0b5d0bdd0b8d18f20d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d18ed189d0b8d0b920d183d181d0bbd0bed0b2d0b8d0b920d0b0d0bad182d0b8d0b2d0b8d0b7d0b0d186d0b8d0b82e20d097d0bdd0b0d187d0b8d0bcd0bed181d182d18c20d18dd182d0b8d18520d0bfd180d0bed0b1d0bbd0b5d0bc20d0bdd0b0d181d182d0bed0bbd18cd0bad0be20d0bed187d0b5d0b2d0b8d0b4d0bdd0b02c20d187d182d0be20d181d0bbd0bed0b6d0b8d0b2d188d0b0d18fd181d18f20d181d182d180d183d0bad182d183d180d0b020d0bed180d0b3d0b0d0bdd0b8d0b7d0b0d186d0b8d0b820d0b2d0bbd0b5d187d0b5d18220d0b7d0b020d181d0bed0b1d0bed0b920d0bfd180d0bed186d0b5d181d18120d0b2d0bdd0b5d0b4d180d0b5d0bdd0b8d18f20d0b820d0bcd0bed0b4d0b5d180d0bdd0b8d0b7d0b0d186d0b8d0b820d0bcd0bed0b4d0b5d0bbd0b820d180d0b0d0b7d0b2d0b8d182d0b8d18f2e20d0a2d0b0d0bad0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc20d0bfd0bed181d182d0bed18fd0bdd0bdd18bd0b920d0bad0bed0bbd0b8d187d0b5d181d182d0b2d0b5d0bdd0bdd18bd0b920d180d0bed181d18220d0b820d181d184d0b5d180d0b020d0bdd0b0d188d0b5d0b920d0b0d0bad182d0b8d0b2d0bdd0bed181d182d0b820d0bed0b1d0b5d181d0bfd0b5d187d0b8d0b2d0b0d0b5d18220d188d0b8d180d0bed0bad0bed0bcd18320d0bad180d183d0b3d1832028d181d0bfd0b5d186d0b8d0b0d0bbd0b8d181d182d0bed0b22920d183d187d0b0d181d182d0b8d0b520d0b220d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d0b820d0bdd0b0d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b920d0bfd180d0bed0b3d180d0b5d181d181d0b8d0b2d0bdd0bed0b3d0be20d180d0b0d0b7d0b2d0b8d182d0b8d18f2e3c2f703e3c62723e0d0a3c703ed098d0b4d0b5d0b9d0bdd18bd0b520d181d0bed0bed0b1d180d0b0d0b6d0b5d0bdd0b8d18f20d0b2d18bd181d188d0b5d0b3d0be20d0bfd0bed180d18fd0b4d0bad0b02c20d0b020d182d0b0d0bad0b6d0b520d0bdd0b0d187d0b0d0bbd0be20d0bfd0bed0b2d181d0b5d0b4d0bdd0b5d0b2d0bdd0bed0b920d180d0b0d0b1d0bed182d18b20d0bfd0be20d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d18e20d0bfd0bed0b7d0b8d186d0b8d0b820d0b8d0b3d180d0b0d0b5d18220d0b2d0b0d0b6d0bdd183d18e20d180d0bed0bbd18c20d0b220d184d0bed180d0bcd0b8d180d0bed0b2d0b0d0bdd0b8d0b820d181d0bed0bed182d0b2d0b5d182d181d182d0b2d183d18ed189d0b8d0b920d183d181d0bbd0bed0b2d0b8d0b920d0b0d0bad182d0b8d0b2d0b8d0b7d0b0d186d0b8d0b82e20d0a2d0bed0b2d0b0d180d0b8d189d0b82120d180d0b0d0bcd0bad0b820d0b820d0bcd0b5d181d182d0be20d0bed0b1d183d187d0b5d0bdd0b8d18f20d0bad0b0d0b4d180d0bed0b220d0bfd0bed0b7d0b2d0bed0bbd18fd0b5d18220d0b2d18bd0bfd0bed0bbd0bdd18fd182d18c20d0b2d0b0d0b6d0bdd18bd0b520d0b7d0b0d0b4d0b0d0bdd0b8d18f20d0bfd0be20d180d0b0d0b7d180d0b0d0b1d0bed182d0bad0b520d0bcd0bed0b4d0b5d0bbd0b820d180d0b0d0b7d0b2d0b8d182d0b8d18f2e20d0a2d0b0d0bad0b8d0bc20d0bed0b1d180d0b0d0b7d0bed0bc20d0bfd0bed181d182d0bed18fd0bdd0bdd18bd0b920d0bad0bed0bbd0b8d187d0b5d181d182d0b2d0b5d0bdd0bdd18bd0b920d180d0bed181d18220d0b820d181d184d0b5d180d0b020d0bdd0b0d188d0b5d0b920d0b0d0bad182d0b8d0b2d0bdd0bed181d182d0b820d181d0bfd0bed181d0bed0b1d181d182d0b2d183d0b5d18220d0bfd0bed0b4d0b3d0bed182d0bed0b2d0bad0b820d0b820d180d0b5d0b0d0bbd0b8d0b7d0b0d186d0b8d0b820d0b4d0b0d0bbd18cd0bdd0b5d0b9d188d0b8d18520d0bdd0b0d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b920d180d0b0d0b7d0b2d0b8d182d0b8d18f2e20d097d0b0d0b4d0b0d187d0b020d0bed180d0b3d0b0d0bdd0b8d0b7d0b0d186d0b8d0b82c20d0b220d0bed181d0bed0b1d0b5d0bdd0bdd0bed181d182d0b820d0b6d0b520d180d0b5d0b0d0bbd0b8d0b7d0b0d186d0b8d18f20d0bdd0b0d0bcd0b5d187d0b5d0bdd0bdd18bd18520d0bfd0bbd0b0d0bdd0bed0b2d18bd18520d0b7d0b0d0b4d0b0d0bdd0b8d0b920d0bfd0bed0b7d0b2d0bed0bbd18fd0b5d18220d0b2d18bd0bfd0bed0bbd0bdd18fd182d18c20d0b2d0b0d0b6d0bdd18bd0b520d0b7d0b0d0b4d0b0d0bdd0b8d18f20d0bfd0be20d180d0b0d0b7d180d0b0d0b1d0bed182d0bad0b520d0b4d0b0d0bbd18cd0bdd0b5d0b9d188d0b8d18520d0bdd0b0d0bfd180d0b0d0b2d0bbd0b5d0bdd0b8d0b920d180d0b0d0b7d0b2d0b8d182d0b8d18f2e0d0a3c2f703e3c2f703e, '/media/c1a10a11c5313d91491ddeb44a82db54_164154052.jpg', 0, 1503935602),
                            (13, 'EN', 'about', 'About us', 'Just an example page about your company. You can change this page in the CodeBits Platform Control Panel.', 'just,about,page', 0x3c703e3c703e436f6d72616465732120746865206578697374696e6720737472756374757265206f6620746865206f7267616e697a6174696f6e20706c61797320616e20696d706f7274616e7420726f6c6520696e2074686520666f726d6174696f6e206f662074686520617070726f70726961746520636f6e646974696f6e7320666f72207265766974616c697a6174696f6e2e204469766572736520616e64207269636820657870657269656e63652c20636f6e73756c746174696f6e20776974682061207769646520617373657420636f6e747269627574657320746f20746865207072657061726174696f6e20616e6420696d706c656d656e746174696f6e206f6620646576656c6f706d656e7420666f726d732e3c2f703e3c62723e0d0a3c703e57652073686f756c64206e6f742c20686f77657665722c20666f7267657420746861742074686520626567696e6e696e67206f6620746865206461792d746f2d64617920776f726b206f6e20706f736974696f6e696e6720726571756972657320757320746f20616e616c797a652074686520747261696e696e672073797374656d206f6620706572736f6e6e656c2c206d6565747320746865207072657373696e67206e656564732e204f6e20746865206f746865722068616e642c2074686520626567696e6e696e67206f66206461792d746f2d64617920776f726b206f6e2074686520666f726d6174696f6e206f66206120706f736974696f6e20616c6c6f777320706572666f726d696e6720696d706f7274616e74207461736b7320696e2074686520646576656c6f706d656e74206f6620646972656374696f6e7320666f722070726f677265737369766520646576656c6f706d656e742e2057652073686f756c64206e6f742c20686f77657665722c20666f72676574207468617420636f6e7374616e74207175616e74697461746976652067726f77746820616e64207468652073636f7065206f66206f757220616374697669747920726571756972652074686520646566696e6974696f6e20616e642073706563696669636174696f6e206f6620617070726f70726961746520636f6e646974696f6e7320666f722061637469766174696f6e2e20546865207369676e69666963616e6365206f662074686573652070726f626c656d7320697320736f206f6276696f7573207468617420746865206578697374696e6720737472756374757265206f6620746865206f7267616e697a6174696f6e20656e7461696c73207468652070726f63657373206f6620696e74726f647563696e6720616e64206d6f6465726e697a696e672074686520646576656c6f706d656e74206d6f64656c2e20546875732c2074686520636f6e7374616e74207175616e74697461746976652067726f77746820616e642073636f7065206f66206f75722061637469766974792070726f7669646573206120776964652072616e676520286f66207370656369616c697374732920776974682070617274696369706174696f6e20696e2073686170696e672074686520646972656374696f6e73206f662070726f677265737369766520646576656c6f706d656e742e3c2f703e3c62723e0d0a3c703e4964656f6c6f676963616c20636f6e73696465726174696f6e73206f66206120686967686572206f726465722c2061732077656c6c2061732074686520626567696e6e696e67206f66206461792d746f2d64617920776f726b206f6e20706f736974696f6e20666f726d6174696f6e2c20706c617920616e20696d706f7274616e7420726f6c6520696e2073686170696e672074686520636f72726573706f6e64696e672061637469766174696f6e20636f6e646974696f6e732e20436f6d72616465732120546865206672616d65776f726b20616e642074686520706c616365206f6620747261696e696e67206f6620706572736f6e6e656c20616c6c6f777320706572666f726d696e6720696d706f7274616e74207461736b7320696e2074686520646576656c6f706d656e74206f662074686520646576656c6f706d656e74206d6f64656c2e20546875732c2074686520636f6e7374616e74207175616e74697461746976652067726f77746820616e642073636f7065206f66206f757220616374697669747920636f6e747269627574657320746f20746865207072657061726174696f6e20616e6420696d706c656d656e746174696f6e206f66206675727468657220646576656c6f706d656e7420646972656374696f6e732e20546865207461736b206f6620746865206f7267616e697a6174696f6e2c20657370656369616c6c792074686520696d706c656d656e746174696f6e206f662074686520706c616e6e656420746172676574732c20616c6c6f777320746f206361727279206f757420696d706f7274616e74207461736b7320696e2074686520646576656c6f706d656e74206f662066757274686572206172656173206f6620646576656c6f706d656e742e0d0a3c2f703e3c2f703e, '/media/c1a10a11c5313d91491ddeb44a82db54_164154052.jpg', 0, 1503935602),
                            (14, 'RU', 'contacts', 'Контакты', 'Хотите начать работу с нами? Просто напишите нам!', 'контакты,пример', 0x3c703e3c68333ed0a1d0b2d18fd0b7d18c20d18120d0bdd0b0d0bcd0b83c2f68333e3c703ed0a2d0b5d0bbd0b5d184d0bed0bd3a202b3728393939293939392d39392d39393c2f703e3c703e456d61696c3a203c6120687265663d226d61696c746f3a737570706f72744069776170732e727522207469746c653d22436f6e74616374205573223e737570706f72744069776170732e72753c2f613e3c2f703e3c703ed0a1d0b0d0b9d1823a203c6120687265663d227777772e636f6465626974732e78797a22207469746c653d224f7572205765627369746522207461726765743d225f626c616e6b223e7777772e636f6465626974732e78797a3c2f613e3c2f703e3c62723e3c703e3c6469763e3c2f6469763e3c2f703e3c2f703e, '/media/f49cd90cfa029d62e348ba3e0c2c58ac_364471435.jpg', 0, 1503935891),
                            (15, 'EN', 'contacts', 'Contacts', 'Would you like to start work with us? Just write us today!', 'contacts,example', 0x3c703e3c68333e436f6e746163742055733c2f68333e3c703e50686f6e65204e756d6265723a202b3728393939293939392d39392d39393c2f703e3c703e456d61696c3a203c6120687265663d226d61696c746f3a737570706f72744069776170732e727522207469746c653d22436f6e74616374205573223e737570706f72744069776170732e72753c2f613e3c2f703e3c703e576562736974653a203c6120687265663d227777772e636f6465626974732e78797a22207469746c653d224f7572205765627369746522207461726765743d225f626c616e6b223e7777772e636f6465626974732e78797a3c2f613e3c2f703e3c703e3c6469763e3c2f6469763e3c2f703e3c2f703e, '/media/f49cd90cfa029d62e348ba3e0c2c58ac_364471435.jpg', 0, 1503935891);";
                
                // Вгружаем данные в таблицы
                for($i=0;$i<count($_dbq);$i++){
                    // Формируем запрос
                    if($dbs['driver']=='mysql'){ // MySQL
                        $q = mysql_query($_dbq[$i]);
                    }else{ // MySQLi
                        $q = $mysqli->query($_dbq[$i]);
                    }
                    
                    // Обработка результата
                    if(!$q){
                        header("Location: /?step=2&error=error_installer_db_fill");
                        exit();
                    }
                }
                
                // Подключение успешно
                header("Location: /?step=3");
                exit();
                break;
            case "admin":
                // Попытка подключения
                if($CFG->line['db']['driver']=='mysql'){ // MySQL
                    $connect = mysql_connect($CFG->line['db']['host'], $CFG->line['db']['login'], $CFG->line['db']['password']); // Подключение
                    if(!$connect){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_connection");
                        exit();
                    }
                    $setchar = mysql_set_charset($CFG->line['db']['encoding']); // Задать кодировку
                    if(!$setchar){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_encoding");
                        exit();
                    }
                    $select = mysql_select_db($CFG->line['db']['name']); // Выбрать БД
                    if(!$select){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_select");
                        exit();
                    }
                }else{ // MySQLi
                    $mysqli = null;
                    $mysqli = new mysqli($CFG->line['db']['host'], $CFG->line['db']['login'], $CFG->line['db']['password']); // Подключение
                    if(!$mysqli){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_connection");
                        exit();
                    }
                    $setchar = $mysqli->set_charset($CFG->line['db']['encoding']); // Задать кодировку
                    if(!$setchar){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_encoding");
                        exit();
                    }
                    $select = $mysqli->select_db($CFG->line['db']['name']); // Выбрать БД
                    if(!$select){ // Ошибка
                        header("Location: /?step=3&error=error_installer_db_select");
                        exit();
                    }
                }
                
                // Проверка данных (имя)
                if(!isset($_POST['fullname']) || mb_strlen($_POST['fullname'])<1){
                    header("Location: /?step=3&error=fullname_required");
                    exit();
                }else{
                    $fullname = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($_POST['fullname']):$mysqli->real_escape_string($_POST['fullname']);
                }
                
                // Проверка символов и длинны (имя)
                if(preg_match("/[^a-zA-Zа-яА-Я ]+$/u", $fullname) || mb_strlen($fullname)<2 || mb_strlen($fullname)>40){
                    header("Location: /?step=3&error=wrong_fullname");
                    exit();
                }
                
                // Проверка данных (логин)
                if(!isset($_POST['login']) || mb_strlen($_POST['login'])<1){
                    header("Location: /?step=3&error=login_required");
                    exit();
                }else{
                    $login = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($_POST['login']):$mysqli->real_escape_string($_POST['login']);
                }
                
                // Проверка длинны (Логин)
                if(mb_strlen($login)<5 || mb_strlen($login)>30){
                    header("Location: /?step=3&error=login_length");
                    exit();
                }
                
                // Проверка символов (Логин)
                if(preg_match("/[^0-9a-zA-Z]+$/u", $login)){
                    header("Location: /?step=3&error=login_symbols");
                    exit();
                }
                
                // Проверка данных (пароль)
                if(!isset($_POST['password']) || mb_strlen($_POST['password'])<1){
                    header("Location: /?step=3&error=password_required");
                    exit();
                }else{
                    $password = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($_POST['password']):$mysqli->real_escape_string($_POST['password']);
                }
                
                // Проверка длинны (Пароль)
                if(mb_strlen($password)<6 || mb_strlen($password)>32){
                    header("Location: /?step=3&error=password_length");
                    exit();
                }
                
                // Проверка данных (повтор пароля)
                if(!isset($_POST['repass']) || mb_strlen($_POST['repass'])<1){
                    header("Location: /?step=3&error=repass_required");
                    exit();
                }else{
                    $repass = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($_POST['repass']):$mysqli->real_escape_string($_POST['repass']);
                }
                
                // Проверка совпадений паролей
                if($password!=$repass){
                    header("Location: /?step=3&error=pass_no_equal");
                    exit();
                }
                
                // Проверка данных (email)
                if(!isset($_POST['email']) || mb_strlen($_POST['email'])<1){
                    header("Location: /?step=3&error=email_required");
                    exit();
                }else{
                    $email = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($_POST['email']):$mysqli->real_escape_string($_POST['email']);
                }
                
                // Проверка Email через фильтра
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    header("Location: /?step=3&error=wrong_email");
                    exit();
                }
                
                // Генерация токена
                $token = sha1(md5($login.'|'.$password).'|'.$CFG->line['system']['secret']); // Токен
                $token = ($CFG->line['db']['driver']=='mysql')?mysql_real_escape_string($token):$mysqli->real_escape_string($token);
                
                // Запрос в БД (Наполнение данными)
                $_dbq = "INSERT INTO `".$CFG->line['db']['prefix']."profiles` SET `avatar`='', `nickname`='".$fullname."', `email`='".$email."', `profile_data`='', `ban_data`='', `last_login_day`=".time();
                if($CFG->line['db']['driver']=='mysql'){ // MySQL
                    $q = mysql_query($_dbq);
                }else{ // MySQLi
                    $q = $mysqli->query($_dbq);
                }
                    
                // Ошибка во время запроса
                if(!$q){ // Ошибка
                    header("Location: /?step=3&error=error_installer_db_fill");
                    exit();
                }else{
                    $_puid = ($CFG->line['db']['driver']=='mysql')?mysql_insert_id():$mysqli->insert_id;
                }
                
                $_dbq = "INSERT INTO `".$CFG->line['db']['prefix']."auth` SET `login`='".$login."', `token`='".$token."', `from`='default', `is_admin`=1, `profile_uid`=".$_puid;
                if($CFG->line['db']['driver']=='mysql'){ // MySQL
                    $q = mysql_query($_dbq);
                }else{ // MySQLi
                    $q = $mysqli->query($_dbq);
                }
               
                // Ошибка во время запроса
                if(!$q){ // Ошибка
                    header("Location: /?step=3&error=error_installer_db_fill");
                    exit();
                }
                
                // Подключение успешно
                header("Location: /?step=4");
                exit();
                break;
            case "finish":
                // Обновление конфигов
                $CFG->line['installed'] = true;
                $_json = json_encode($CFG->line);
                $_save = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/api/core/configs/conf.main.json', $_json);
                
                // Подключение успешно
                header("Location: /");
                exit();
                break;
        }
    }
?>
<!DOCTYPE html>
<html lang="<?=$LANG->curr_lang?>">
    <head>
        <!-- BASE META -->
        <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$LANG->line('application')?> » <?=$LANG->line('installation')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="CodeBits" />
        <meta name="generator" content="CodeBits Platform" />
        <meta name="robots" content="NOINDEX,NOFOLLOW" />
        <!-- BASE META -->
        
        <!-- ICONS -->
        <link rel="shortcut icon" href="/frontend/assets/img/favicon.ico" />
        <!-- ICONS -->
        
        <!-- THEME -->
	<?php include(FRONTEND.'/view/admin/parts/app_colors.php'); ?>
        <?php include(FRONTEND.'/view/admin/parts/base_styles.php'); ?>
        <link href="/frontend/view/admin/assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- THEME -->
        
        <!-- HTML5 SUPPORT -->
        <?php include(FRONTEND.'/view/admin/parts/html5.php'); ?>
        <!-- HTML5 SUPPORT -->
    </head>
    <body class="signin-page">
        <!-- PRELOADER -->
        <?php include(FRONTEND.'/view/admin/parts/preloader.php'); ?>
        <!-- PRELOADER -->
        
        <!-- CONTAINER -->
        <div class="mn-content valign-wrapper">
            <main class="mn-inner container">
                <div class="valign">
                    <div class="row">
                        <div class="col s12 m12 l10 offset-l1">
                            <?php if($error!==false): ?>
                                <div class="card-panel red darken-1">
                                    <span class="white-text"><?=$error?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($step==1): ?>
                            <div class="card purple darken-1">
                                <div class="card-content">
                                    <div style="text-align: center;">
                                        <h1><i class="material-icons white-text" style="font-size: 80px;">settings</i></h1>
                                        <h5 class="white-text"><?=$LANG->line("installer_welcome")?></h5>
                                        <p class="white-text"><?=$LANG->line("installer_welcome_desc")?></p>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($step==2): ?>
                            <div class="card green darken-1">
                                <div class="card-content">
                                    <div style="text-align: center;">
                                        <h1><i class="material-icons white-text" style="font-size: 80px;">storage</i></h1>
                                        <h5 class="white-text"><?=$LANG->line("installer_db")?></h5>
                                        <p class="white-text"><?=$LANG->line("installer_db_desc")?></p>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($step==3): ?>
                            <div class="card blue darken-1">
                                <div class="card-content">
                                    <div style="text-align: center;">
                                        <h1><i class="material-icons white-text" style="font-size: 80px;">person</i></h1>
                                        <h5 class="white-text"><?=$LANG->line("installer_admin")?></h5>
                                        <p class="white-text"><?=$LANG->line("installer_admin_desc")?></p>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($step==4): ?>
                            <div class="card orange darken-1">
                                <div class="card-content">
                                    <div style="text-align: center;">
                                        <h1><i class="material-icons white-text" style="font-size: 80px;">check_circle</i></h1>
                                        <h5 class="white-text"><?=$LANG->line("installer_complete")?></h5>
                                        <p class="white-text"><?=$LANG->line("installer_complete_desc")?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="card white darken-1">
                                <div class="card-content">
                                    <?php if($step==1): ?>
                                    <div class="row">
                                        <div class="col s12">
                                            <label><?=$LANG->line("language_select")?></label>
                                            <select class="browser-default" id="lang_switch">
                                                <option value="" disabled selected><?=$LANG->line("language_select")?></option>
                                                <?php foreach($langs as $key=>$lang): ?>
                                                <option value="<?=$lang['code']?>"><?=$lang['name']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <a href="/?step=2" class="waves-effect waves-light btn purple"><?=$LANG->line('installer_next')?><i class="material-icons right">navigate_next</i></a>
                                        </div>
                                    </div>
                                    <?php elseif($step==2): ?>
                                    <div class="row">
                                        <form role="form" action="/" method="POST">
                                            <input type="hidden" name="form" value="db" />
                                            <div class="col s12">
                                                <label><?=$LANG->line("db_driver")?></label>
                                                <select class="browser-default" name="db[driver]" required="">
                                                    <option value="" disabled selected><?=$LANG->line("db_driver_desc")?></option>
                                                    <?php foreach($drivers as $key=>$driver): ?>
                                                    <option value="<?=$driver?>"><?=$LANG->line("db_drv_".$driver)?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('db_host_desc')?>" id="db_host" name="db[host]" type="text" class="validate" required="" />
                                                <label for="db_host"><?=$LANG->line('db_host')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('db_login_desc')?>" id="db_login" name="db[login]" type="text" class="validate" required="" />
                                                <label for="db_login"><?=$LANG->line('db_login')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('db_password_desc')?>" id="db_pass" name="db[password]" type="password" class="validate" required="" />
                                                <label for="db_pass"><?=$LANG->line('db_password')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('db_name_desc')?>" id="db_name" name="db[name]" type="text" class="validate" required="" />
                                                <label for="db_name"><?=$LANG->line('db_name')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('db_prefix_desc')?>" id="db_login" name="db[prefix]" type="text" class="validate" required="" value="cbp_" />
                                                <label for="db_prefix"><?=$LANG->line('db_prefix')?></label>
                                            </div>
                                            <div style="clear: both; height: 40px;"></div>
                                            <div class="col s12">
                                                <button type="submit" class="waves-effect waves-light btn green"><?=$LANG->line('installer_next')?><i class="material-icons right">navigate_next</i></button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php elseif($step==3): ?>
                                    <div class="row">
                                        <form role="form" action="/" method="POST">
                                            <input type="hidden" name="form" value="admin" />
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('admin_fullname_desc')?>" id="adm_name" name="fullname" type="text" class="validate" required="" maxlength="40" />
                                                <label for="adm_name"><?=$LANG->line('admin_fullname')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('admin_login_desc')?>" id="adm_login" name="login" type="text" class="validate" required="" maxlength="30" />
                                                <label for="adm_login"><?=$LANG->line('admin_login')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('admin_password_desc')?>" id="adm_pass" name="password" type="password" class="validate" required="" maxlength="32" />
                                                <label for="adm_pass"><?=$LANG->line('admin_password')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('admin_repass_desc')?>" id="adm_repass" name="repass" type="password" class="validate" required="" maxlength="32" />
                                                <label for="adm_repass"><?=$LANG->line('admin_repass')?></label>
                                            </div>
                                            <div style="clear: both; height: 20px;"></div>
                                            <div class="input-field col s12">
                                                <input placeholder="<?=$LANG->line('admin_email_desc')?>" id="adm_email" name="email" type="email" class="validate" required="" />
                                                <label for="adm_email"><?=$LANG->line('admin_email')?></label>
                                            </div>
                                            <div style="clear: both; height: 40px;"></div>
                                            <div class="col s12">
                                                <button type="submit" class="waves-effect waves-light btn blue"><?=$LANG->line('installer_next')?><i class="material-icons right">navigate_next</i></button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php elseif($step==4): ?>
                                    <div class="row">
                                        <form role="form" action="/" method="POST">
                                            <input type="hidden" name="form" value="finish" />
                                            <div class="col s12">
                                                <h5 class="red-text"><?=$LANG->line('installer_warn')?></h5>
                                                <p class="red-text"><?=$LANG->line('installer_warn_desc')?></p>
                                            </div>
                                            <div style="clear: both; height: 40px;"></div>
                                            <div class="col s12">
                                                <button type="submit" class="waves-effect waves-light btn orange"><?=$LANG->line('installer_finish')?><i class="material-icons right">navigate_next</i></button>
                                            </div>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- CONTAINER -->
        
        <!-- SCRIPTS -->
        <?php include(FRONTEND.'/view/admin/parts/base_scripts.php'); ?>
        <script type="text/javascript">
            $('#lang_switch').change(function(){
                var _elem = $(this);
                document.location.href = '/?lang='+_elem.val();
            });
        </script>
        <!-- SCRIPTS -->
    </body>
</html>