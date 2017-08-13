<?php

class Route {

    public function __construct(){

        $this->routes = [];
        $this->params = [];
        $this->routesPatterns = [];
        $this->wordsPattern = "[\w\d-_p{\ض\ص\ث\ق\ف\غ\ع\ه\خ\ح\ج\د\ش\س\ي\ب\ل\ا\ت\ن\م\ك\ط\ئ\ء\ؤ\ر\لا\ى\ة\و\ز\ظ\ذ\إ\أ\آ}]+";
    }

    public function addRoute($route,$cont){
        $this->route = $route;
        $this->routes[$route] = $cont;
    }

    public function getRoute($route){
        $ppp = $this->setupRoutes();
        $user_ok_route = false;
        foreach($ppp as $k => $v){
            $cm = explode('@', $v['cm']);
            $controller = $cm[0];
            if(!isset($cm[1]) || strlen(trim($cm[1])) === 0)
                die("You must specify the method for route:" . $this->route);

            $method = $cm[1];
            $params = $v['params'];
            $pattern = $v['pattern'];

            if(count($params) < 1){
                $pattern = mb_substr($pattern, 3);
                $pattern = mb_substr($pattern, 0, -2);
                $pattern = "/" . $pattern;
                $pattern =  str_ireplace('\\', '/', $pattern);
                $correct_route = intval($pattern == $route);
            }else{
                // echo $pattern . "<br/>";
                $correct_route = intval(preg_match($pattern, $route));
            }
            // echo var_dump($pattern) . "<br/>";
            // echo var_dump($correct_route);
            if($correct_route > 0){
                $user_ok_route = true;
                $uri = mb_substr($pattern, 1);
                $uri = str_ireplace($this->wordsPattern, '[azAZ09]', $uri);
                $uri = str_ireplace('\\', '', $uri);
                $uri = str_ireplace('+', '', $uri);
                $uri = str_ireplace('$', '', $uri);
                $uri = str_ireplace('-', '', $uri);
                $uri = str_ireplace('_', '', $uri);
                $exploded_uri = explode('/', $uri);
                $exploded_route = explode('/', $route);
                $load_info = [
                    'controller' => $controller,
                    'method' => $method,
                    'params' => []
                ];
                
                if(count($params) > 0){
                    $params_passed = [];
                    foreach($exploded_uri as $k => $v){
                        if($v === '[azAZ09]'){
                            $params_passed[] = $exploded_route[$k];
                        }
                    }
                    $final_params = [];
                    foreach($params as $k => $v){
                        $final_params[$v] = $params_passed[$k];
                    }
                    $load_info['params'] = $final_params;
                    $this->loadController($load_info);
                }else{
                    
                    // execute method without parameters                    
                    $this->loadController($load_info);
                }
            }else{
                if($k == count($ppp) - 1 && $user_ok_route == false){
                    // error 404
                    $v = new View();
                    $v->view("404");
                }
            }
        }
    }


    public function setupRoutes(){
        foreach($this->routes as $av_route => $controllerMethod){
            $params = [];
            preg_match_all("/\(([^\)]*)\)/", $av_route, $ar);
            foreach($ar[1] as $k => $v){
                $params[] = $v;
            }
            if(count($params) > 0){
                foreach($params as $k => $v){
                    if($k === 0){
                        $final_route_regex = str_ireplace("/" , "\\/", $av_route);
                        if($k == count($params) - 1)
                            $final_route_regex = str_ireplace("(" . $v . ")" , $this->wordsPattern . "$", $final_route_regex);
                        else
                            $final_route_regex = str_ireplace("(" . $v . ")" , $this->wordsPattern, $final_route_regex);
                    }
                    else if($k != count($params) - 1)
                        $final_route_regex = str_ireplace("(" . $v . ")" , $this->wordsPattern, $final_route_regex);
                    else
                        $final_route_regex = str_ireplace("(" . $v . ")" , $this->wordsPattern . "$", $final_route_regex);
                }

                $final_route_regex = "/^" . $final_route_regex . "/u";
            }else{
                $final_route_regex = "/^" . str_ireplace("/", "\\", $av_route) . "/u";
            }

            $this->routesPatterns[] = [
                "pattern" => $final_route_regex,
                "params" => $params,
                "cm" => $controllerMethod
            ];
        }

        return $this->routesPatterns;
    }


    public function loadController($data){

        $controller_name = $data['controller'];
        $method = $data['method'];
        $params = $data['params'];
        
        require_once("./controllers/" . $controller_name . ".php");
        $controller = new $controller_name();
        $controller->loadModel($controller_name);
        
        if(method_exists($controller_name,$method)){
            if(count($params) > 0)
                $controller->$method($params);
            else
                $controller->$method();
            /*
              if (version_compare(PHP_VERSION, '7.0.0') <= 0) {
              if(count($params) > 0)
              $controller->$controller_name($params);
              else
              $controller->$controller_name();
              }
              else {
              $method = $method . "";
              if(count($params) > 0)
              $controller->$method($params);
              else
              $controller->$method();
              }
            */
        }
        else
            die("Method : <b>'" . $method . "()'</b> dose not exists in class : <b>'" . ucfirst($controller_name) ."'</b>!");
    }

}

$r = new Route();

?>