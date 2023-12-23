<?php

namespace sortService;
use namespace1\CustomError400;
use namespace1\RaiseErrors;

require_once  "endpoint.php" ;
require_once  "utils/errors.php";


abstract class Sort_Service{
    public function __construct()
    {


        $server = new Server();
        $filter = new FilterMiddleware ;
        $server->setMiddleware($filter);
        $filter->cleanInput();
        \namespace1\ENDPOINT::trig();



    }




}
abstract class Middleware
{

    private $next;


    public function linkWith(Middleware $next): Middleware
    {
        $this->next = $next;

        return $next;
    }


    public function check(string $email, string $password): bool
    {
        if (!$this->next) {
            return true;
        }

        return $this->next->check($email, $password);
    }
}
 class FilterMiddleware Extends Middleware
{

    private $next;


    public function cleanInput()
    {
        try{


        if (filter_has_var((int)$_GET,"t")){



            $lentab = strlen($_GET["t"]);

            echo  $lentab ;

            $sanitizedinput ="[";

            $comma_in_a_row =0 ;

            $zero_in_a_row = 0 ;
            for ($i = 0 ; $i < $lentab ; $i++){



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


                            if ($sanitizedinput[strlen($sanitizedinput)-1] != ","){

                                $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;

                            }




                        } else{

                            $zero_in_a_row = 0 ;
                            echo $zero_in_a_row ;
                            $sanitizedinput = $sanitizedinput.$_GET["t"][$i] ;


                        }


                    }

                }else{
                    throw new CustomError400();

                }


            }
                $sanitizedinput = rtrim($sanitizedinput ,",");
                $sanitizedinput = $sanitizedinput."]";
                $_GET["t"] =  $sanitizedinput;






        }else{
          //  throw  new CustomError400();
        }

        if(isset($_GET["t"])){
            return $_GET["t"];
            }

        }catch (\namespace1\CustomError400){
 RaiseErrors::raiseError400();
 }
    }
}





class Server
{
    private $users = [];


    private $middleware;



    public function setMiddleware(Middleware $middleware): void
    {
        $this->middleware = $middleware;
    }



}














