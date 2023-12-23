<?php
namespace DBHANDLER ;

use PDO;
require_once "utils/errors.php";

Class DBHANDLER {

 

   public static function dbConnect ($results,$datasourcename,$charset="utf8",$classname = null){

    try{
        require_once "security\SecLib.php";
        require_once "security\LinkLib.php";


        $creds=  \SECURITY\SecLib::getCrendentials("db.json");


        $newcreds = new \LINKLIB\GetJsonCredential() ;
        $newcreds ->setUser($creds['user']);
        $newcreds ->setPassword($creds['password']);
        $newcreds->setId($creds['id']);


        $host =  $datasourcename['host'];
        $dbname = $datasourcename['name'];

        $dsn = 'mysql:host='.$host ;
        $dsn = $dsn.';dbname='.$dbname ;
        $dsn = $dsn.';charset='.$charset ;




        $db = new PDO($dsn, $newcreds->getUser(),  $newcreds->getPassword());
       

        $results[]= $newcreds-> getId();

        $iduser= $newcreds->getId();
        $dbname = "tableau";



        $dbassets =['user_id'=>$newcreds->getId(),'db'=>$db,'iduser'=>$iduser,'treatedquery'=>$results,'dbtab'=>$dbname];

        return $dbassets ;




    }catch (\PDOException $err){

        $code = $err ->getCode();

        throw  new \Namespace1\CustomDBConnectionError($code);


    }

}




    public static  function getResults($dbassets,$dbname,$wherevalues,$andvalues,$selectparams = null,$whereparams = null,$andparams = null,$orparams = null){

        try{
            require_once "security\LinkLib.php";



            $db=$dbassets['db'];


            $lenparams = count($selectparams);

            $tab["where"] = $wherevalues ;



            $namedplaceholder = ":value" ;

            $params ="";
            for ($i =0 ; $i <$lenparams ; $i++){
                $params = $params.$namedplaceholder.$i ;

                if($i!=$lenparams-1){
                    $params = $params.',';
                }
            }


           $lenselparams = count ($selectparams);
            if ($lenselparams>1){
                $forming_query = "SELECT ";
                for($i =0 ; $i<$lenselparams;$i++){
                    $forming_query= $forming_query.$selectparams[$i];
                    if($i!= $lenselparams-1){
                       $forming_query= $forming_query.",";
                    }
                }

                $forming_query= $forming_query." FROM ".$dbname." WHERE  ".$whereparams." = ".":where";

            }else{
                $forming_query = "SELECT ".$selectparams[0]." FROM ".$dbname." WHERE  ".$whereparams." = ".":where" ;
            }

            $co = -1 ;
            $lenorparam = -1 ;

            if(get_debug_type($andparams)!="null"){
                $co = count($andparams);
            }

            if (get_debug_type($orparams)!="null"){
                $lenorparam = count($orparams);
            }

            if ($co >0){

                $ca = count($andvalues);
                for ($i = 0 ;$i < $ca;$i++ ){
                    $tab["valueand".$i] = $andvalues[$i];

                }



                for ( $i = 0 ; $i <$co ; $i++){
                    $forming_query = $forming_query." AND ".$andparams[$i]." = ".":valueand".$i;

                }
            }


            if ($lenorparam > 0){

                for ($i = 0 ;$i < $lenorparam;$i++ ){
                    $tab["valueor".$i] = $orparams[$i];

                }



                for ( $i = 0 ; $i <$lenorparam ; $i++){
                    $forming_query = $forming_query." OR ".$orparams[$i]." = ".":valueor".$i;

                }

            }




            /** @var  $query  requetefinale   contenu de la requete préparée  */

            $query =  "$forming_query";
        $recipesStatement = $db->prepare($query);
        $recipesStatement->execute($tab);
        $recipes = $recipesStatement->fetchAll();
        $fetchresults = [];




// meme si il existe plusieures requete qui ont le meme tableau initial  le resultat est le meme pour toutes donc on prends seulement le résultat de la 1ère requète faite par l'utilisateur


        foreach ($recipes as $recipe) {

                $fetchresults[] = $recipe['Tableau_original'];
                $fetchresults[] = $recipe['Tableau_trié'];

            break;

        }


    }catch(\PDOException $err){

    $code = $err ->getCode();


    throw  new \Namespace1\CustomDBConnectionError($code);
        }
        return $fetchresults;
}





    public static  function insertResults($dbassets){


        try{

            if (get_debug_type($dbassets)=="null"){
                throw  new \PDOException();

            }


            if (get_debug_type($dbassets["user_id"]) =="null"|| get_debug_type($dbassets["db"])=="null"||get_debug_type($dbassets["treatedquery"])=="null"||get_debug_type($dbassets["binding"])=="null"||get_debug_type($dbassets["dbtab"])=="null" ){
                throw  new \PDOException();
            }
        $user_id= $dbassets['user_id'];
        $db=$dbassets['db'];

        $treatedresult = $dbassets['treatedquery'];
        $binding = $dbassets['binding'];
        $tablename =$dbassets['dbtab'];

        $lencolumn = count($binding);
        $columnsbinding ="";

        for ($i = 0 ; $i < $lencolumn ; $i ++){

            $columnsbinding = $columnsbinding.'`'.$binding[$i].'`';

            if ($i !=$lencolumn-1){

                $columnsbinding = $columnsbinding.',';
            }

        }
        $tab = [];
        $lenparams = count($treatedresult);

        for ($i = 0 ;$i < $lenparams;$i++ ){

        $tab["value".$i] = $treatedresult[$i];


        }


        $namedplaceholder =":value";
        $params ="";

        for ($i =0 ; $i <$lenparams ; $i++){

           $params = $params.$namedplaceholder.$i ;

            if($i!=$lenparams-1){

                $params = $params.',';

            }
        }



            //$tabpar
        $req = "INSERT INTO `$tablename` ($columnsbinding) VALUES($params)";
        $recipesStatement = $db->prepare($req);
        $recipesStatement->execute($tab);




}catch(\PDOException $err){
            $code = $err ->getCode();

            throw  new \Namespace1\CustomDBConnectionError($code);
        }
    }



    public static  function deleteResults($dbassets,$dbname){
       // $dbname = "tableau";
        $condition = $dbassets["delcondition"];

        if (get_debug_type($dbassets)=="null"){

        }





        $exectab['condition1'] = $dbassets['conditionbind'] ;
        $db=$dbassets['db'];
        $treatedresult = $dbassets['treatedquery'];
        $valtodelete = null;

        $req = "DELETE $valtodelete FROM $dbname  WHERE $condition = :condition1";

        $recipesStatement = $db->prepare($req);

        $recipesStatement->execute($exectab);
        $recipes = $recipesStatement->fetchAll();




    }

    public static  function  updateResults($dbassets,$dbname){
        require_once "utils/errors.php";
        try{



         if (get_debug_type($dbassets)=="null")  {
             //manque d'un arguments obligatoire
            // throw  new \Namespace1\CustomError400();
         }

         if( get_debug_type($dbname)!="string"){
             //erreur de type pour le nom de la table
         }

         if(get_debug_type($dbassets['user_id']) == "null"|| get_debug_type($dbassets['db'])=="null" ||get_debug_type($dbassets['treatedquery'])=="null" ){
                // il manque un tableau associatif
         }




        $db=$dbassets['db'];
        $treatedresult = $dbassets['treatedquery'];

            $collumnstomodify = $dbassets['collumnstomodify'];

            $lencollumns = count($collumnstomodify);

            $filters = $dbassets['filters'];




            $columns = "";
            for($i =0 ; $i < $lencollumns/2 ; $i ++){

                $arraykey = 'key'.$i ;
                $arrvalue = 'val'.$i ;

                if( str_contains( $collumnstomodify[$arrvalue],"[") || str_contains( $collumnstomodify[$arrvalue],"]")){
                    $collumnstomodify[$arrvalue] = '"'.$collumnstomodify[$arrvalue].'"';
                }
                $columns = $columns.$collumnstomodify[$arraykey]." = ".$collumnstomodify[$arrvalue];

                if ($i != $lencollumns/2-1){

                    $columns = $columns.",";

                }


            }

            $lenfilter = count($filters)/3;
            $executetab =[];

            for ($i = 0 ;$i < $lenfilter;$i++ ){

                $arrayval ='filtervalue'.$i;
                $executetab["value".$i] = $filters[$arrayval];


            }

            $filterbinding =" ";
            $namedplaceholder = ":value";

            for ($i = 0 ;$i < $lenfilter;$i++ ){

                $arraykey = 'filterkey'.$i ;
                $operator = 'operator'.$i ;
                $filterbinding = $filterbinding.$filters[$arraykey].$filters[$operator].$namedplaceholder.$i;

                if ($i != $lenfilter-1){

                    $filterbinding = $filterbinding.",";

                }
            }



            $req = "UPDATE $dbname SET $columns  WHERE $filterbinding";

        $recipesStatement = $db->prepare($req);

        $recipesStatement->execute($executetab);
        $recipes = $recipesStatement->fetchAll();


    }catch(\PDOException $err){
$code = $err ->getCode();

throw  new \Namespace1\CustomDBConnectionError($code);
}

    }


}