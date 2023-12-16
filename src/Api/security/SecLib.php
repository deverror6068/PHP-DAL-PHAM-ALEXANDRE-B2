<?php

namespace SECURITY;

class SecLib
{
    public  static  function  getCrendentials($file){

        $root =   $_SERVER["DOCUMENT_ROOT"];
        $path =  $_SERVER["DOCUMENT_ROOT"]."../../Credentials/";
       // $pathnamepspace = $_SERVER["DOCUMENT_ROOT"]."/Api/LinkLib.php";
      //  require_once ("$pathnamepspace");
      //  echo $root,$path;

        $res = json_decode(file_get_contents($path.$file),true);
        //$array[] =$res['user'];
        //$array[] =$res['password'];
        //$array[] =$res['id'];
            $array = ['user'=>$res['user'],'password'=>$res['password'],'id'=>$res['id']];

        return  $array  ; //$_SERVER["DOCUMENT_ROOT"]

}
}
