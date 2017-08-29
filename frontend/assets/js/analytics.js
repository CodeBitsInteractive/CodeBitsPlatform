//=======================================================
//  Файл:           analytics.js
//  Разработчик:    CodeBits Interactive
//  Версия:         1.0
//  Назначение:     CBP API Analytics Helper
//=======================================================
//  CodeBits Platform API Analytics Helper нужен для
//  записи данных в статистику посещений. Данный помошник
//  лишь отправляет запрос на обновление статистики к
//  контроллеру class.analytics.php
//=======================================================
//=======================================================
//  CodeBits API Analytics Helper
//=======================================================
//  Стоит использовать его при полной загрузке документа
//=======================================================
(function(){ // Based on jQuery
    // Загрузка документа
    $(document).ready(function(){
        console.log("CBAPI Analytics: Sending data...");
        var _url = document.location.href; // URL API
        var _api = CBAPI.getInstance(); // Получаем экземпляр API
        _api.call("analytics.update", {
            page: _url // URL страницы
        }, function(dt){
            console.log("CBAPI Analytics: Visitor data successfully updated");
        }, function(dt){
            console.log("CBAPI Analytics (ERROR): "+dt.message);
        });
    });
})(jQuery);