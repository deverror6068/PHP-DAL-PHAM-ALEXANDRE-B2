<?php
namespace namespace1 ;
require_once "sort_service.php";
require_once "business_service.php";

class ENDPOINT Extends \sortService\sort_Service {

    public function  trig(){
        \namespace1\BUSINESSLAYER::routing();
    }
}