<?php
namespace  Namespace1;
class SORT{
//tri à bulle
    static public function bubbleSort($tab2)
    {

        for($i=0; $i<count($tab2)-1; $i++)
        {
            for($j=0; $j<(count($tab2)-1-$i); $j++)
            {
                if ($tab2[$j] > $tab2[$j+1] )
                {
                    $temp = $tab2[$j+1];
                    $tab2[$j+1] = $tab2[$j];
                    $tab2[$j] = $temp;
                }
            }
        }
        return $tab2;
    }


    static function displayTab ($tab2)
    {

       // formatage tableau au format [val1,val2,val3]
        $str = "[";
        $lig2 = "";

        foreach ($tab2 as $val)
        {
            $lig2 .= "$val";
            $lig2.=",";
        }
        $str .= $lig2;
        $str.="]";
        return $str;
    }
    static  function sortTable($test)//initialisation du tri
    {
        $tab2 = $test;

        return self::displayTab(self::bubbleSort($tab2));

    }



}