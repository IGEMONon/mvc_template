<?php

//FRONT CONTROLLER

//1. Общие настройки
ini_set('display_errors',1); //отображение всех ошибок на время написания кода
error_reporting(E_ALL);


//2. Подключение файлов системы
define('ROOT', dirname(__FILE__)); //Объявляется константа ROOT, dirname - функция получения полного пути к
                                         //директории файла
require_once(ROOT.'/components/Router.php');
//3 Установка соединения с БД
require_once(ROOT.'/components/Db.php');

//4. Вызов Router
$router = new Router();
$router->run();