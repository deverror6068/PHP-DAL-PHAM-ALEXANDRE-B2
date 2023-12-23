<?php
    namespace namespace1;

    class  SendResponse{

      public static function   sendResult($inittab,$sorttab){ // formatage et envoi du résultat de la requete
          $resultmessage = array(
              'Status' => 'Success',
              'TableauInitial' => strval($inittab),
              'TableauTrie'=> strval($sorttab)
          );

          $encodedmessage = json_encode($resultmessage);
          header("Content-Type:Json");
          echo $encodedmessage; //envoi du resultat

    }

    }