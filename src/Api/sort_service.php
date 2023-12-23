<?php

namespace sortService;
require_once  "endpoint.php" ;


abstract class sort_Service{
    public function __construct()
    {

        var_dump(get_debug_type($_GET["t"]));

        if (filter_has_var((int)$_GET,"t")){



        var_dump($_GET["t"]);
    
            $lentab = strlen($_GET["t"]);
            var_dump(count(explode(",",$_GET["t"])));
            var_dump(explode(",",$_GET["t"]));
            echo  $lentab ;

            $sanitizedinput ="[";
            $comma_in_a_row =0 ;
            $zero_in_a_row = 0 ;
            for ($i = 0 ; $i < $lentab ; $i++){

                var_dump($_GET["t"][$i]);

                if(is_numeric($_GET["t"][$i]) ||$_GET["t"][$i]=="," ){

                    if ($_GET["t"][$i]=="," ){

                        $comma_in_a_row = $comma_in_a_row + 1 ;
                        $zero_in_a_row  = 0;

                        if ($comma_in_a_row <2){

                            $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;

                        }


                    }else{
                    $comma_in_a_row = 0;
                    echo $comma_in_a_row;
                    if($_GET["t"][$i]=="0"){
                        $zero_in_a_row = $zero_in_a_row + 1 ;
                        echo $zero_in_a_row ;

                        echo '\n';
                    var_dump($_GET["t"][$i],"MTN");
                    var_dump($_GET["t"][$i-$zero_in_a_row],"avant");
                    if ($_GET["t"][$i-$zero_in_a_row] != ","){
                        var_dump("VALIDATED");
                        $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;
                    }
                        $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;


                    } else{
                        $zero_in_a_row = 0 ;
                        echo $zero_in_a_row ;
                        $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;

                    }


                    }

                }else{

                }


            }
            $sanitizedinput = rtrim($sanitizedinput ,",");
            $sanitizedinput = $sanitizedinput."]";
            $_GET["t"] =  $sanitizedinput;
            var_dump($_GET["t"]);
        

       // var_dump($_GET["t"]);

      //  die(0);
        
    }
        \namespace1\ENDPOINT::trig();



    }



}


