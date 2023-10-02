<?php
    $primos = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);

    $condicao = false;

    $numero = 2520;
    while(!$condicao){
        $numero += 10;
        $condicao = true;
        foreach($primos as $num){
            if($numero % $num != 0){
                $condicao = false;
                break;
            }
        }
    }
    echo $numero;
?>