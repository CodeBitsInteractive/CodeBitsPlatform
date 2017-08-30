//=======================================================
//  Файл:           api.js
//  Разработчик:    CodeBits Interactive
//  Версия:         1.0
//  Назначение:     CBP API Wrapper
//=======================================================
//  CodeBits Platform API Wrapper нужен для выполнения
//  запросов к API на базе платформы и обеспечения
//  возможности работы аналитики. Если вы хотите
//  включить поддержку запросов API на вашем сайте и
//  сбор аналитики - просто подключите данный файл
//  себе в Header. Также не забудьте настроить домен
//  для API в настройках
//=======================================================
//=======================================================
//  CodeBits API Class
//=======================================================
//  Wrapper API функций написан по паттерну синглтона
//  для более удобной работы с ним
//=======================================================
var CBAPI = (function(){
    // Здесь мы храним параметры нашего объекта API
    var instance; // Экземпляр объекта враппера API
    
    // Функция инициализации. Здесь мы возвращаем методы,
    // которые будут использоваться в объектах при инициализации
    // нашего API-синглтона
    function init(){ // Инициализатор
        return{ // Возврат публичных данных
            // Метод вызова методов API :D
            call: function(method, data, callback, errors){
                // Проверка существования метода
                var api_method = method || false; // Метод API
                if(!api_method){ // Метод API не задан
                    console.log("CB API Initialization Error: API Method not exists"); // Вывод ошибки в консоль
                    return false; // Не инициализирован
                }else{ // Метод задан
                    api_method = api_method.split('.'); // Разрубить запрос
                }
                
                // Задаем данные
                var api_data = data || {}; // Данные для отправки
                
                // Callback Функции
                var done = callback || function(){}; // Callback по завершению
                var fail = errors || function(){}; // Callback ошибки
                
                // Отправляем запрос на сервер (POST)
                $.post('/api/'+api_method.join('/'), data, function(dt){ // Запрос прошел гладко
                    console.log('API Responce: '+dt);
                    try{ // Пытаемся обработать то, что выдал нам сервер
                        var _resp = JSON.parse(dt); // Попытка парсинга JSON
                        if(!_resp.complete){ // Сервер выдал ошибку
                            fail({ // Выдаем ошибку запроса
                                message: _resp.message, // Сообщение
                                code: 1
                            });
                        }else{ // Все прошло хорошо
                            done(_resp); // Передать данные сервера
                        }
                    }catch(ex){ // Ошибка обработки данных
                        console.log("CB API Request Error: Failed to convert server response"); // Вывод ошибки в консоль
                        console.log(dt); // Вывод контента
                        fail({ // Выдаем ошибку запроса
                            message: "Failed to convert server response. Please, try again later", // Сообщение
                            code: 98
                        });
                    }
                }).error(function(err){ // Ошибка
                    console.log("CB API Request Error: "+err); // Вывод ошибки в консоль
                    fail({ // Выдаем ошибку запроса
                        message: err, // Сообщение
                        code: 99
                    });
                });
            },
            
            // Загрузка медиа-файла
            upload_media: function(file, callback, errors){
                // Callback Функции
                var done = callback || function(){}; // Callback по завершению
                var fail = errors || function(){}; // Callback ошибки
                
                // Формируем данные формы
                var form_data = new FormData(); // Данные
                form_data.append('file', file); // Применить даные
                
                // AJAX-Запрос
                $.ajax({
                    // Параметры запроса
                    url: '/api/media/upload/', // URL
                    type: 'POST', // Метод
                    data: form_data, // Данные формы
                    dataType: 'text', // Тип данных
                    cache: false, // Отключить кеширование
                    contentType: false, // Отключить тип контента
                    processData: false, // Отключить процессинг данных
                    success: function(dt){ // Загрузка завершена
                        try{ // Попытка обработки ответа
                            var _resp = JSON.parse(dt); // Попытка парсинга JSON
                            if(!_resp.complete){ // Сервер выдал ошибку
                                fail({ // Выдаем ошибку запроса
                                    message: _resp.message, // Сообщение
                                    code: 1
                                });
                            }else{ // Все прошло хорошо
                                done(_resp); // Передать данные сервера
                            }
                        }catch(e){ // Ошибка
                            console.log("CB API Upload Error: Failed to convert server response"); // Вывод ошибки в консоль
                            console.log(dt); // Вывод контента
                            fail({ // Выдаем ошибку запроса
                                message: "Failed to convert server response. Please, try again later", // Сообщение
                                code: 98
                            });
                        }
                    },
                    error: function(err){
                        console.log("CB API Upload Error: "+err); // Вывод ошибки в консоль
                        fail({ // Выдаем ошибку запроса
                            message: err, // Сообщение
                            code: 99
                        });
                    }
                });
            }
        }
    }
    
    // Возврат данных синглтона. Здесь мы оставляем метод
    // Get Instance для инициализации объекта
    return{ // Возвращаем данные
        // Метод получения экземпляра API
        getInstance: function () {
            if ( !instance ) {  // Если экземпляра нет
                instance = init(); // Инициализируем его
            }
            
            // Вовзращаем инстанс
            return instance;
        }
    }
})();

//=======================================================
//  Embed Media Manager
//=======================================================
//  Встраиваемый медиа-менеджер для сайта
//=======================================================
(function($){
    // Параметры по-умолчанию
    var url = ''; // URL файла
    var options; // Пользовательские опции
    var manager_html = '<div id="media_manager" class="media-modal"><div class="mm-content"><div class="mm-header"><span class="cls-btn">&times;</span><h4>Media Manager</h4></div><div class="mm-body">'+
            '<div class="mm-tabs"><a href="#!" class="active upload-tab" data-action="show_mmtab" data-uid="0">Upload</a><a href="#!" data-action="show_mmtab" data-uid="1" class="gallery-tab">From Gallery</a></div>'+
            '<div class="upload-tab mm-tabs-container" data-model="mmtab" data-uid="0">'+
            '<form id="upload_file" enctype="multipart/form-data" method="post"><input type="file" name="file" class="mm-fileloader" /><p id="preload_media" style="display: none; text-align: center;"><img src="/frontend/assets/img/preloader.gif" /></p></form>'+
            '</div><div class="upload-tab mm-tabs-container" data-model="mmtab" data-uid="1">'+
            '<div class="mm-inner"></div>'+
            '</div></div></div></div>'; // HTML медиа-менеджера
    var manager_css = '.media-modal{display: none;position: fixed;z-index: 9999;left: 0;top: 0;width: 100%;height: 100%;overflow: auto;background-color: rgb(0,0,0);background-color: rgba(0,0,0,0.4);}' +
                      '.media-modal .mm-content{position: relative;background-color: #fefefe;margin: auto;padding: 0;width: 100%; max-width: 700px;box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);-webkit-animation-name: animatetop;-webkit-animation-duration: 0.4s;animation-name: animatetop;animation-duration: 0.4s}'+
                      '.cls-btn{color: #fff;float: right;font-size: 28px;font-weight: bold;}'+
                      '.cls-btn:hover,.cls-btn:focus{color: black;text-decoration: none;cursor: pointer;}'+
                      '.media-modal .mm-header{padding: 2px 16px;background-color: #5cb85c;color: white;}'+
                      '.media-modal .mm-header h4{color: #fff; font-size: 20px; margin: 10px 0 10px 0;}'+
                      '.media-modal .mm-body {padding: 2px 16px;box-sizing:border-box;width:100%;position:relative;}'+
                      '.media-modal .mm-body .mm-img {cursor: pointer; display: inline-block; vertical-align: middle; width: 200px; height: 200px; margin: 10px; background-repeat: no-repeat; background-size: cover; transition: .10s linear all;-webkit-transition: .10s linear all;}'+
                      '.media-modal .mm-body .mm-img:hover {box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);}'+
                      '.media-modal .mm-fileloader{width:100%;}'+
                      '.media-modal .mm-inner{margin: 20px;}'+
                      '.media-modal .mm-selector{} .media-modal .mm-hover{}'+
                      '.media-modal .mm-tabs-container{position: relative; display: inline-block;width:100%;margin: 10px 5px 20px 5px;box-sizing:border-box;}'+
                      '.media-modal .mm-tabs {position: relative; display: inline-block; margin: 10px 5px 10px 5px; vertical-align: middle;}'+
                      '.media-modal .mm-tabs a{position: relative; display: inline-block;vertical-align:middle; padding: 10px 15px; background: #f0f0f0;color: #5cb85c;} .media-modal .mm-tabs a.active{background-color: #5cb85c; color: #fff;}'+
                      '.media-modal .mm-footer{padding: 2px 16px;background-color: #5cb85c;color: white;}'+
                      '@-webkit-keyframes animatetop{from{top: -300px; opacity: 0} to{top: 0; opacity: 1}}@keyframes animatetop{from {top: -300px; opacity: 0}to{top: 0; opacity: 1}}'; // CSS медиа-менеджера
    var defaults = {
        title: "Media Manager", // Заголовок окна
        upload_title: "Upload", // Заголовок вкладки "Закачать"
        gallery_title: "From Gallery", // Заголовок вкладки "Из галлереи"
        enable_gallery: false, // Можно ли выбрать из галлереи
        on_shown: function(){}, // Callback отображения окна
        on_hidden: function(){} // Callback скрытия окна
    };
     
    // Объект методов плагина
    var methods = {
        // инициализация плагина
        init:function(params) {
            // Общее
            var _self = $(this); // Объект медиа-менеджера
            options = $.extend({}, defaults, params); // Загрузить настройки
            
            // Внедрить код модального окна
            if($('#media_manager').length<1){ // Если окна нет
                $('body').prepend(manager_html); // Внедрить HTML
                $('body').append('<style>'+manager_css+'</style>'); // Внедрить стили
            }
            
            // Установить надписи
            $('#media_manager').find('h4').empty().append(options.title); // Заголовок окна
            $('#media_manager').find('a[data-action="show_mmtab"][data-uid="0"]').empty().append(options.upload_title); // Заголовок вкладки "Upload"
            $('#media_manager').find('a[data-action="show_mmtab"][data-uid="1"]').empty().append(options.gallery_title); // Заголовок вкладки "From Gallery"
            
            // Скрываем ненужное
            $('#preload_media').hide();
            $('#media_manager').find('.mm-fileloader').show();
            
            // Смотрим, нужна ли галерея
            if(options.enable_gallery){ $('#media_manager').find('.gallery-tab').show(); $('#media_manager').find('.gallery-tab').removeClass('active'); }else{ $('#media_manager').find('.gallery-tab').hide(); }
            _self.find('h4').empty().append(options.title);
            $('#media_manager').find('.upload-tab').removeClass('active').addClass('active');
            $('#media_manager').find('div[data-model="mmtab"]').hide(); // Скрыть
            $('#media_manager').find('div[data-model="mmtab"][data-uid="0"]').fadeIn(100); // Показать
            
            // Переключение вкладок
            $('#media_manager').find('a[data-action="show_mmtab"]').off('click').on('click', function(e){
                // Работа с переключателями
                $('#media_manager').find('a[data-action="show_mmtab"]').removeClass('active');
                $(this).addClass('active');
                
                // Работа с вкладками
                $('#media_manager').find('div[data-model="mmtab"]').hide(); // Скрыть
                $('#media_manager').find('div[data-model="mmtab"][data-uid="'+$(this).attr('data-uid')+'"]').fadeIn(100); // Показать
                
                // Переключение вкладок
                if($(this).attr('data-uid')==1){ // Галерея
                    var _tab = $('#media_manager').find('div[data-model="mmtab"][data-uid="'+$(this).attr('data-uid')+'"]');
                    var api = CBAPI.getInstance(); // Instance
                    api.call('media.getList',{}, function(dt){
                        // Работа с контейнером
                        var _cont = '';
                        for(i=0;i<dt.list.length;i++){
                            _cont += '<div data-action="get_media_gallery" data-file="'+dt.list[i]+'" class="mm-img" style="background-image: url(\'/media/'+dt.list[i]+'\');"></div>';
                        }
                        _tab.empty().append(_cont);
                        
                        // Применяем слушатели
                        $('div[data-action="get_media_gallery"]').off('click').on('click', function(){
                            url = '/media/'+$(this).attr('data-file');
                            _self.media_manager('hide', _self);
                        });
                    }, function(dts){
                        _self.media_manager('hide', _self);
                        alert(dts.message);
                        url = '';
                    });
                }
                
                // Отмена действий
                e.preventDefault();
                return false;
            });
            
            // Загрузка файла
            $('#media_manager').find('.mm-fileloader').off('change').on('change', function () {
                $('#preload_media').show();
                $('#media_manager').find('.mm-fileloader').hide();
                var file = $(this).prop('files')[0]; // Файл для загрузки
                var api = CBAPI.getInstance(); // Instance
                api.upload_media(file, function (dt) { // Закачать файл
                    $('#preload_media').hide();
                    $('#media_manager').find('.mm-fileloader').show();
                    url = dt.url; // URL
                    _self.media_manager('hide', _self);
                }, function (dt) { // Ошибка
                    $('#preload_media').hide();
                    $('#media_manager').find('.mm-fileloader').show();
                    _self.media_manager('hide', _self);
                    alert(dt.message);
                    url = '';
                });
            });
            
            // Нажатие на элемент
            _self.off('click').on('click',function(e){
                _self.media_manager('show');
                // Отмена действий
                e.preventDefault();
                return false;
            });
            
            // Нажатие на кнопку закрытия
            $('#media_manager').find('.cls-btn').off('click').on('click',function(e){
                _self.media_manager('hide', _self);
                // Отмена действий
                e.preventDefault();
                return false;
            });            

            // Инициализация прошла
            return 'Plugin Loaded: jQuery Media Manager';
        },
        
        // Показать медиа-менеджер
        show: function(){
            $('#media_manager').css('display','block'); // Показать медиа-менеджер
            options.on_shown(); // Окно показано
        },
        
        // Скрыть медиа-менеджер
        hide: function(self){
            $('#media_manager').css('display','none'); // Показать медиа-менеджер
            options.on_hidden(self); // Окно показано
        },
        
        // Получить ссылку
        getURL: function(){
            return url;
        }
    };
    
    // Собственно реализация плагина
    $.fn.media_manager = function(method){
        // Смотрим, существует ли метод
        if (methods[method]){ // Метод существует
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 )); // Запуск метода
        } else if ( typeof method === 'object' || ! method ) { // В место метода - параметры
            return methods.init.apply( this, arguments ); // Запускаем конструктор
        } else { // Ну и если ничего нет
            $.error('jQuery Media Manager: Запрашиваемый метод: "' +  method + '" не существует в данном плагине');
        }
    };
})(jQuery);