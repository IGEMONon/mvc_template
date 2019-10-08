<?php

class Router
{

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath); //присваивает переменной routes значение массива из routes.php
    }
    //returns request string
    //@return string
    private function getURI() //возвращает запрос введённый в адресной строке
    {
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/'); //trim удаляет символы из начала и конца строки
        }
    }
    public function run()
    {
        //Получить строку запроса
        $uri = $this->getURI();
        //Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) { //preg_match для поиска паттерна в строке,~для обрамления
                // регулярного выражения потому что в строке запроса могут содержаться / и в них нельзя обрамлять
                //Если есть совпадения, определить какой контроллер и action обрабатывают запрос
                //Получаем внутренний путь из внешнего
                $internalRoute = preg_replace("~$uriPattern~",$path,$uri);
                $segments = explode('/',$internalRoute); //делит строку на массив из 2х элементов, до / и после /
                $controllerName = array_shift($segments).'Controller'; //берём первую часть стр segments, удаляет потом
                $controllerName = ucfirst($controllerName); //UpperCase для первой буквы имя Controller

                $actionName = 'action'.ucfirst(array_shift($segments)); //имя action
                $parameters = $segments;
                //Подключить файл класса контроллера
                $controllerFile = ROOT . '/controllers/'.$controllerName . '.php'; //полное расположение файла контроллера
                if(file_exists($controllerFile)){//если файл есть
                    include_once ($controllerFile);//подключить
                }
                //Создать объект вызвать метод (т.е. action)
                $controllerObject = new $controllerName;//объект класса (имя совпадает с именем файла контроллера)
                $result = call_user_func_array(array($controllerObject, $actionName),$parameters);//выполняется метод с
                //именем экшна для этого контроллера с параметрами
                if ($result != null){
                    break; //если нашли в массиве метод, то прерываем перебор
                }
            }
        }


        //echo 'Class Router, method run!';
    }
}