<?php

namespace LINKLIB{



    class GetJsonCredential{

        public  string $user ;
        public  string $password;

        public  string $id ;



        public function  setUser( string $cred_user) :void{
           $this-> user = $cred_user;

        }

        public function  setPassword(string $cred_password):void{
            $this-> password = $cred_password;
        }

        public  function  setId(string $cred_id):void{
            $this->id = $cred_id;
        }

        public function getUser(){
        return $this->user;

        }
        public  function getPassWord() {


            return $this->password;


        }

        public  function getId(){
            require_once"errors.php";
            return $this->id;



        }
    }

}