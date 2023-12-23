<?php
namespace namespace1 ;
require_once "business/bubblesort.php"; // utilisation programme de tri , gestion d'erreur , réponses...
require_once "business/quicksort.php";
require_once "utils/errors.php";
require_once "view/sendresponse.php";
require_once "model/dbhandler.php";

class BUSINESSLAYER{
    public static function  routing() // routing du serveur par switchcase
{



    try
    {

        $args = $_GET;
        // verifie l'existence du paramètre t
        if(isset($_GET["t"])){
            echo"";
        }else{ //sinon on renvoie une erreur bad param
            throw new \Namespace1\CustomError400(); // gestion d'erreur personnalisée
        }

        $tabparam = json_decode(htmlspecialchars(($_GET["t"]))); //arguments du paramètre t


        $url = $_SERVER['REQUEST_URI'];



        if(isset($args["type"])){ // verifie l'existence du paramètre type
            echo "";
        }else{ //meme cas
            throw  new \Namespace1\CustomError400();
        }


        if (!isset(explode("/",$url)[3] )){ // verifie si l'url est malformée à a cause d'une route après la requete GET
            echo  "";
        }
        else //bad param
        {
            throw new  \Namespace1\CustomError400() ;
        }

        switch ($args["type"]) // verifie le contenu du paramètre  type
        {
            case "BubbleSort" : // tri à bulle
                if (!isset($tabparam)){ //le tableau n'existe pas dans les arguments rentrés
                    throw new  \Namespace1\CustomTabSizeError();
                }

                if (count($tabparam) == 0) // le tableau est vide
                {
                    throw new \Namespace1\CustomTabSizeError();
                }
                //l'url est bonne

                $sizesorttab=strlen(\namespace1\SORT::sortTable($tabparam)); //longueur tableau
                $result =\Namespace1\SORT::sortTable($tabparam);
                // formatage tableau au format [val1,val2,val3]
                $sorttab ="[";

                for ($i = 0; $i < $sizesorttab; $i++) {

                    if ($i!=$sizesorttab-2&&$result[$i]!="["&&$result[$i]!="]"){
                        $sorttab =  $sorttab.$result[$i]; // on récupère le tableau trié du programme de tri
                    }

                }

                $sorttab = $sorttab."]";
                $treatedquery = [];
                $treatedquery[] =  $_GET["t"];
                $treatedquery[] = $sorttab;
                $data_source_name ['name']= "tableau";
                $data_source_name ['host'] ="localhost:3306";

                $dbassets =\DBHANDLER\DBHANDLER::dbConnect($treatedquery,$data_source_name);//$test


/*

                $collumnstomodify['key0'] = 'tableau_trié';
                $collumnstomodify['val0'] = '[12,50,74,180]';
                $collumnstomodify['key1'] = 'tableau_original';
                $collumnstomodify['val1'] = '[50,180,74,12]';


                $filters['filterkey0'] = 'id_requete';
                $filters['operator0'] = ' = ';
                $filters['filtervalue0'] = "257";

                $dbassets["collumnstomodify"] = $collumnstomodify;
                $dbassets["filters"] = $filters;


                $table ="tableau";
                // \DBHANDLER\DBHANDLER::updateResults($dbassets,$table);
      */

                $bindingtab[]='Tableau_original';
                $bindingtab[]='Tableau_trié';
                $bindingtab[]='id_utilisateur';
                $bindingtab[]='Type_Tri';
                $dbassets['treatedquery'][] = "BubbleSort";

                $dbassets['binding'] = $bindingtab ;

                \DBHANDLER\DBHANDLER::insertResults($dbassets);


                $dbname = "tableau";
                $seltab [] ="*" ;
                // $wheretab[] = "Tableau_original";
                $andparams[] = "id_utilisateur";
                $andparams[] = "Type_tri";
                $whereval = $dbassets['treatedquery'][0];
                $andvalue[] =$dbassets['iduser'];
                $andvalue[] = "BubbleSort";

                $sendable_stream =\DBHANDLER\DBHANDLER::getResults($dbassets,$dbname,$whereval,$andvalue,$seltab,"Tableau_original",$andparams,$andparams);

/*
                $conditionbind = 258 ;
                $dbassets['conditionbind'] = $conditionbind ;
                $dbassets["delcondition"] = "id_requete";
               //   \DBHANDLER\DBHANDLER::deleteResults($dbassets,"tableau");

            */
                \Namespace1\SendResponse::sendResult( $sendable_stream[0],$sendable_stream[1]);

                $dbassets = null ;


                break;
            case "QuickSort" : // tri rapide
                if (!isset($tabparam)){ //le tableau n'existe pas dans les arguments rentrés
                    throw new  \Namespace1\CustomTabSizeError();
                }

                if (count($tabparam) == 0)// le tableau est vide
                {
                    throw new \Namespace1\CustomTabSizeError();
                }
                $sizesorttab=(count(\Namespace1\QUICKSORT::quickSort($tabparam))); //longueur tableau

                // formatage tableau au format [val1,val2,val3]
                $sorttab ="[";
                for ($i = 0; $i < $sizesorttab; $i++) {
                    // echo \Namespace1\QUICKSORT::quickSort($tabparam)[$i] , ",";
                    $sorttab = $sorttab .\Namespace1\QUICKSORT::quickSort($tabparam)[$i];

                    if($i!=$sizesorttab-1){
                        $sorttab = strval($sorttab) . ",";
                    }


                }

                $sorttab = $sorttab."]";

                $treatedquery = [];
                $treatedquery[] =  $_GET["t"];
                $treatedquery[] = $sorttab;
                $data_source_name ['name']= "tableau";
                $data_source_name ['host'] ="localhost:3306";


                $dbassets =\DBHANDLER\DBHANDLER::dbConnect($treatedquery,$data_source_name);


                $bindingtab[]='Tableau_original';
                $bindingtab[]='Tableau_trié';
                $bindingtab[]='id_utilisateur';
                $bindingtab[]='Type_Tri';
                $dbassets['binding'] = $bindingtab ;
                $dbassets['treatedquery'][] = "QuickSort";



                \DBHANDLER\DBHANDLER::insertResults($dbassets);

                $dbname = "tableau";
                $seltab [] ="*" ;
                // $wheretab[] = "Tableau_original";
                $andparams[] = "id_utilisateur";
                $andparams[] = "Type_tri";
                $whereval = $dbassets['treatedquery'][0];
                $andvalue[] =$dbassets['iduser'];
                $andvalue[] = "QuickSort";


                $sendable_stream =\DBHANDLER\DBHANDLER::getResults($dbassets,$dbname,$whereval,$andvalue,$seltab,"Tableau_original",$andparams,$andparams);

                \Namespace1\SendResponse::sendResult( $sendable_stream[0],$sendable_stream[1]); // on appelle la fonction d'encodage et de réponse

                 $dbassets = null ;

                break;
            default : // arguments du paramètre type invalides
                throw new  \Namespace1\CustomError400();
        }


    }
    catch (\TypeError $err)
    {

        echo "echo erreur: le tableau contient des valeurs invalides ";
    }
    catch (\Namespace1\CustomTabSizeError $err){

        \Namespace1\RaiseErrors::raiseTabSizeError();

    }
    catch (\Namespace1\CustomError404 $err){   //levage d'erreur parsonalisé

        \Namespace1\RaiseErrors::raiseError404();
    }
    catch (\Namespace1\CustomError400 $err){

        \Namespace1\RaiseErrors::raiseError400();
    }
    catch (\Namespace1\CustomDBConnectionError $err){

        \Namespace1\RaiseErrors::raiseDBConnectionError( $GLOBALS['code']);
    }
}
}