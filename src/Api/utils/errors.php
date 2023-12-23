<?php
namespace namespace1;


    class HttpResponseRequest{

        public static  function get_MIME_Type($data_type){
            switch ($data_type){
                case "json":
                    return "Content-Type:Json";
                default:
                    return "Content-Type:text/plain";
            }
        }
    }

    class RaiseErrors {



        public static function raiseError429(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '429',
                'Message' => " Erreur 429 Trop de Requetes Envoyées",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
            header("HTTP/1.1 429 Too Many Request");
            echo $encodedmessage;
        }


      public  static function raiseError404(){ //peut etre utilisée dans des cas futur
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '404',
                'Message' => " Erreur 404 Page Non Trouvee",

            );


            $encodedmessage = json_encode($errormessage);
            header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
            header("HTTP/1.1 404 Not Found");

            echo $encodedmessage;
      }
      public static  function raiseError400(){// bad param  formatage et envoi avec le code html correspondant au "client"
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '400',
                'Message' => " Mauvaise Requete",

            );
            $encodedmessage = json_encode($errormessage);

           header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
            header("HTTP/1.1 400 Bad Param");
            echo $encodedmessage;


        }
        public static function raiseTabSizeError(){
            $errormessage = array(
            'Status' => 'Fail',
                'Errorcode' => '400',
                'Message' => "le Tableau est vide  ou n'est pas specifie",

            );
            $encodedmessage = json_encode($errormessage);

           header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
            header("HTTP/1.1 400 Bad Param");

           // http_response_code(400);
            echo $encodedmessage;

        }

        public static  function raiseGenericServerError(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '500',
                'Message' => "le serveur ne peux repondre a la requete suite a un evenement inattendu",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
            header("HTTP/1.1 500 Internal Error");


            echo $encodedmessage;

    }

        public static function raiseDBConnectionError($code){

            switch ( $code ){ //on recupère le code d'état sql (sqlstate)
                case  1045 :
                    echo "bad credits ";
                    self::raiseBadCreditsError();
                    break;
                case  1049:
                    echo "bd not found";
                    self::raiseDBnotfoundError();
                    break;
                case 2002:
                    self::raiseConnectionRefusedToDBError();
                    break;
                case 42000:
                    self::raiseMaxRessourceExceedError();
                    break;
                default :
                    $errormessage = array(
                        'Status' => 'Fail',
                        'Errorcode' => '500',
                        'Message' => "Erreur Interne : le serveur ne peux repondre a la requete suite a un evenement inattendu ",

                    );
                    $encodedmessage = json_encode($errormessage);

                    header(HttpResponseRequest::get_MIME_Type("json")); // type MIME
                    header("HTTP/1.1 500  Internal Error: Unable to complete request");
                    echo $encodedmessage;
                    break;
            }


        }

        public static function raiseBadCreditsError(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '401',
                'Message' => "Les credits de connection ne sont pas valides",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json"));
            header("HTTP/1.1 401 Unauthorized  : Invalids Credits");

            // http_response_code(400);
            echo $encodedmessage;
        }

        public static  function raiseDBnotfoundError(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '500',
                'Message' => "erreur lors de la connection à la base de donnée : la base de donnée n'existe pas ",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json"));
            header("HTTP/1.1 500  Internal Error: Invalid Database");
            echo $encodedmessage;

        }

        public static function raiseConnectionRefusedToDBError(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '500',
                'Message' => "Erreur Interne : L'acces a la base de données est refuse , le port specifie est invalide ",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json"));
            header("HTTP/1.1 500  Internal Error: Refused Access");
            echo $encodedmessage;
        }

        public  static function raiseMaxRessourceExceedError(){
            $errormessage = array(
                'Status' => 'Fail',
                'Errorcode' => '500',
                'Message' => "Erreur Interne : le serveur ne peux repondre a la requete : l'utilisateur a probablement depasse son nombre de requetes par heure autorise ",

            );
            $encodedmessage = json_encode($errormessage);

            header(HttpResponseRequest::get_MIME_Type("json"));
            header("HTTP/1.1 500  Internal Error: Syntax error or access violation To Database");
            echo $encodedmessage;


        }

    }



    class CustomTabSizeError extends \Exception{ // erreurs personnalisées héritant de la classe erreur

    }

    class CustomError400 extends \Exception {

    }
    class CustomError404 extends \Exception {

    }

    class  CustomError429 extends \Exception{

    }

    class CustomDBConnectionError extends \Exception{
        public function __construct($code) {

            $GLOBALS['code'] = $code;



        }
    }

    class CustomUnreachableDBError extends \Exception{

    }

    class CustomGenericServerError extends  \Exception {

    }


