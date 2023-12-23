<?php
namespace namespace1 ;
require_once "sort_service.php";
require_once "business_service.php";

class ENDPOINT extends \sortService\Sort_Service {

    public function  trig(){
        \namespace1\BUSINESSLAYER::routing();
    }
}
