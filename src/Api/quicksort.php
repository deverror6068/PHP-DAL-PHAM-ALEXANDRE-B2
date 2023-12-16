<?php

namespace Namespace1;
    class QUICKSORT // le programme de tri rapide à été recupéré  sur Teams
    {
       public static function quickSort($tab) {
            $size = count($tab);
            if ($size <= 1) {
                return $tab;
            }

            $tmp = $tab[0];
            $left = array();
            $right = array();
            for ($i = 1; $i < $size; $i++) {

                if ($tab[$i] < $tmp) {
                    $left[] = $tab[$i];

                } else { //
                    $right[] = $tab[$i];

                }
            }

          $result = array_merge(self::quickSort($left), array($tmp), self::quickSort($right)); // on retourne le tableau left, la valeur de tmp et le tableau right

           return $result;

    }
    }

