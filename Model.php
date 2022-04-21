<?php
namespace App\Models;
require_once __DIR__ . '/../../vendor/autoload.php';
use Mapon\MaponApi;
use DateTime;

date_default_timezone_set('UTC');

class Model{

    //Api connection
    public function api(){
        $apiKey = "5333a9720180356462a0d9615a38f6dfff4581aa";
        $apiUrl = "https://mapon.com/api/v1/";
        $api = new MaponApi($apiKey, $apiUrl);
        return $api;
    }
    //Gets data from spec Unit
    public function getUnit($unit, $unitId){
        $resultUnit = $this->api()->get("unit/list", array(
            "units" => $unit,
            "unit_id" => $unitId
        ));
        return $resultUnit;
    }

    //Gets data from spec Route
    public function getRoute($timeFrom, $timeTill, $unit, $unitId, $include){
        $resultRoute = $this->api()->get("route/list", array(
            "from" => $timeFrom,
            "till" => $timeTill,
            "units" => $unit,
            "unit_id" => $unitId,
            "include" => array ($include)
        ));
        return $resultRoute;
    }

    //Gets all data about units
    public function getAllData(){
        $json = "https://mapon.com/api/v1/unit/list.json?key=5333a9720180356462a0d9615a38f6dfff4581aa";
        $allData = json_decode(file_get_contents($json, true));
        return $allData;
    }

    //Gets all car names
    public function getCarNames(){
        $carArray = [];
        $z = 0;
        foreach($this->getAllData()->data->units as $name => $value){
            foreach(array_combine((array)$value->label, (array)$value->unit_id) as $carLabel => $carId){
                $carArray["label"][$z] = $carLabel;
                $carArray["carId"][$z] = $carId;
                $z++;
            }
        }
        return $carArray;
    }

    //Converts into datetime format
    public function convertTime($date, $time){
        $converted = $date."T".$time.":00Z";
        return $converted;
    }

    //Returns all polyline points
    //Must insert route data from getRoute()
    public function returnPolylinePoints($routeData, $routeId){
        foreach($routeData->data->units as $unitValue => $unitData){
            foreach($unitData->routes as $route){
                if($route->type == "route"){
                    if($routeId == $route->route_id){
                        $points = $this->api()->decodePolyline($route->polyline);
                    }
                }
            }
        }
        return $points;
    }

    //Auto foreach function so i dont need multiple foreaches 
    public function returnForeach($arg){
        foreach($arg->data->units as $unitData){
            
        }
        return $unitData;
    }
    public function returnRouteWithId($routeData, $routeId){
        $data = [];
        foreach($routeData->data->units as $unitValue => $unitData){
            foreach($unitData->routes as $route){
                if($route->type == "route"){
                    if($routeId == $route->route_id){
                        array_push($data, $route);
                    }
                }
            }
        }
        return $data;
    }
    
    public function timeToNormal($startTime, $endTime){
        $replace = array("T", "Z");
        $endTimeForm= str_replace($replace, "", $endTime);
        $startTimeForm = str_replace($replace, "", $startTime);
        $time = (strtotime($endTimeForm) - strtotime($startTimeForm));
        $res = date("H:i:s", $time);
        return $res;
    }
    
}

?>
