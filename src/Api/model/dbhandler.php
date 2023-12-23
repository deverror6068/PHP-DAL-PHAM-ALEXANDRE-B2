<?php
namespace DBHANDLER ;

use PDO;
require_once "utils/errors.php";

Class DBHANDLER {

   public static function dbConnect ($results,$datasourcename,$charset="utf8"){

    try{
        require_once "security\SecLib.php";
        require_once "security\LinkLib.php";
        $creds=  \SECURITY\SecLib::getCrendentials("db.json");
        $newcreds =new \LINKLIB\GetJsonCredential() ;
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
        $dbassets =['newcreds'=>$newcreds,'db'=>$db,'iduser'=>$iduser,'treatedquery'=>$results,'dbtab'=>$dbname];

        return $dbassets ;




    }catch (\PDOException $err){

        $code = $err ->getCode();
        var_dump($err->getLine());
        var_dump($err ->getMessage());
        throw  new \Namespace1\CustomDBConnectionError($code);


    }

}




    public static  function getResults($dbassets,$dbname,$wherevalues,$andvalues,$selectparams = null,$whereparams = null,$andparams = null,$orparams = null){

        try{
            require_once "security\LinkLib.php";

        $newcreds = $dbassets['newcreds'];
        $db=$dbassets['db'];
        $iduser = $dbassets['iduser'];




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
            //echo $andparams,$whereparams;

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
                    var_dump($tab,);
                }



                for ( $i = 0 ; $i <$co ; $i++){
                    $forming_query = $forming_query." AND ".$andparams[$i]." = ".":valueand".$i;
                   // var_dump($andparams[$i]);
                }
            }


            if ($lenorparam > 0){

                for ($i = 0 ;$i < $lenorparam;$i++ ){
                    $tab["valueor".$i] = $orparams[$i];
                    var_dump($tab,);
                }



                for ( $i = 0 ; $i <$lenorparam ; $i++){
                    $forming_query = $forming_query." OR ".$orparams[$i]." = ".":valueor".$i;
                    // var_dump($andparams[$i]);
                }

            }

        var_dump($forming_query);

            //SELECT *   FROM `$dbname` WHERE Tableau_original = :initialtab AND  id_utilisateur = :id_user
            //['initialtab' => "$treatedresult[0]",'id_user' => "$iduser"]

          $query =  "$forming_query";
        $recipesStatement = $db->prepare($query);
        $recipesStatement->execute($tab);
        $recipes = $recipesStatement->fetchAll();
        $fetchresults = [];

     //   var_dump($recipes)['Tableau_trié']; ;



        foreach ($recipes as $recipe) { // meme si il existe plusieures requete qui ont le meme tableau initial  le resultat est le meme pour toutes donc on prends seulement le résultat de la 1ère requète faite par l'utilisateur


                $fetchresults[] = $recipe['Tableau_original'];
                $fetchresults[] = $recipe['Tableau_trié'];




            break;

        }
            var_dump($fetchresults);
       // var_dump($recipe,$recipes);

    }catch(\PDOException $err){
    $code = $err ->getCode();
            var_dump($err->getLine());
    throw  new \Namespace1\CustomDBConnectionError($code);
        }
        return $fetchresults;
}





    public static  function insertResults($dbassets){


        try{
            if (get_debug_type($dbassets)=="null"){

            }
            if (get_debug_type($dbassets["newcreds"]) =="null"|| get_debug_type($dbassets["db"])=="null"||get_debug_type($dbassets["treatedquery"])=="null"||get_debug_type($dbassets["binding"])=="null"||get_debug_type($dbassets["dbtab"]=="null") ){

            }
        $newcreds = $dbassets['newcreds'];
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
            var_dump($columnsbinding);
        }
        $tab = [];
        $lenparams = count($treatedresult);
        for ($i = 0 ;$i < $lenparams;$i++ ){
        $tab["value".$i] = $treatedresult[$i];
        var_dump($tab);
        }


        $namedplaceholder =":value";
        $params ="";
        for ($i =0 ; $i <$lenparams ; $i++){
           $params = $params.$namedplaceholder.$i ;

            if($i!=$lenparams-1){
                $params = $params.',';
            }
        }

        var_dump($params);

        var_dump($tab);

            var_dump(['initialtab' => "$treatedresult[0]",'sorttab'=>$treatedresult[1],'iduser'=>$newcreds->GetId()]);


            //$tabpar
        $req = "INSERT INTO `$tablename` ($columnsbinding) VALUES($params)";
        $recipesStatement = $db->prepare($req);
        $recipesStatement->execute($tab);

        var_dump(PDO::ATTR_ERRMODE,PDO::ATTR_CONNECTION_STATUS );


}catch(\PDOException $err){
            $code = $err ->getCode();
            var_dump($err->getMessage());
            var_dump($err->getLine());
            throw  new \Namespace1\CustomDBConnectionError($code);
        }
    }



    public static  function deleteResults($dbassets,$dbname){
       // $dbname = "tableau";
        $condition = $dbassets["delcondition"];

        if (get_debug_type($dbassets)=="null"){

        }



        $newcreds = $dbassets['newcreds'];

        $exectab['condition1'] = $dbassets['conditionbind'] ;
        $db=$dbassets['db'];
        $treatedresult = $dbassets['treatedquery'];
        $valtodelete = null;
        $req = "DELETE $valtodelete FROM $dbname  WHERE $condition = :condition1";
        //'DELETE FROM tableau  WHERE id_utilisateur = :iduser'
        $recipesStatement = $db->prepare($req);
        //['iduser'=>$newcreds::GetId()]
        $recipesStatement->execute($exectab);
        $recipes = $recipesStatement->fetchAll();




    }

    public static  function  updateResults($dbassets,$dbname){
        require_once "utils/errors.php";
        try{
            //var_dump(get_debug_type($dbname));

            var_dump($dbassets);
         if (get_debug_type($dbassets)=="null")  {
             //manque d'un arguments obligatoire
            // throw  new \Namespace1\CustomError400();
         }

         if( get_debug_type($dbname)!="string"){
             //erreur de type pour le nom de la table
         }

         if(get_debug_type($dbassets['newcreds']) == "null"|| get_debug_type($dbassets['db'])=="null" ||get_debug_type($dbassets['treatedquery'])=="null" ){
                // il manque un tableau associatif
         }



        $newcreds = $dbassets['newcreds'];
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
                var_dump($columns);

            }

            $lenfilter = count($filters)/3;
            $executetab =[];
            for ($i = 0 ;$i < $lenfilter;$i++ ){
                $arrayval ='filtervalue'.$i;
                $executetab["value".$i] = $filters[$arrayval];
                var_dump($executetab);
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


            var_dump($columns);
            $req = "UPDATE $dbname SET $columns  WHERE $filterbinding";
            var_dump($req);
        $recipesStatement = $db->prepare($req);

        //'UPDATE tableau SET tableau_trié  = "[12,50,74,180]",tableau_original="[50,180,74,12]"  WHERE id_requete = :idrequete'
        $recipesStatement->execute($executetab);
        $recipes = $recipesStatement->fetchAll();


    }catch(\PDOException $err){
$code = $err ->getCode();
var_dump($err->getMessage());
var_dump($err->getLine());
throw  new \Namespace1\CustomDBConnectionError($code);
}
//catch (\Namespace1\CustomError400 $err){
           // var_dump($err->getLine());
            //var_dump($err ->getMessage());
          //  \Namespace1\RaiseErrors::RaiseError400();
        //}

    }


}