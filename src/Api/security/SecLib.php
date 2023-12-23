<?php

namespace SECURITY;
use namespace1\CustomGenericServerError;

require_once "./utils/errors.php";
class SecLib
{

    public  static  function  getCrendentials($file){
        try{


        $path =  $_SERVER["DOCUMENT_ROOT"]."../../Credentials/";


        $res = json_decode(file_get_contents($path.$file),true);

            $array = ['user'=>$res['user'],'password'=>$res['password'],'id'=>$res['id']];

            if(get_debug_type($res['user'])=="null"){
                throw new CustomGenericServerError();
            }


    }catch (\namespace1\CustomGenericServerError $err){

            \namespace1\RaiseErrors::raiseGenericServerError();

        }
        return  $array  ; //$_SERVER["DOCUMENT_ROOT"]
}
}
