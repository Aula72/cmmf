<?php 
namespace Cmmf;
class Route{
    public $routes = [];
    // public function __construct(){
    //     // $this->routes = $route;
    // }
    public  function set_route($route=[]){
        $this->routes[] = $route;
    }
    public function get_routes(){
        return $this->routes;
    }
}