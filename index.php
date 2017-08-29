<?php
//==============================================================
//  CodeBits Platform
//  Open Source PHP Framework for Applications and Websites
//  by Ilya Rastorguev
//==============================================================
//  Файл:           index.php
//  Назначение:     Главный исполнительный файл Frontend
//  Разработчик:    InterWave
//  Версия:         1.0
//==============================================================
define("WRAPPER", true); // Задаем просмотр через враппер
define("FRONTEND", __DIR__.'/frontend'); // Frontend Folder
define("GLOBALROOT", __DIR__); // Самый корень
include(__DIR__.'/api/index.php'); // Подключить ядро API
?>