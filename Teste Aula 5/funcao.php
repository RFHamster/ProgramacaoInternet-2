<?php
    function makecoffee($a){
        if($a == null){
            return "Fazendo café!";
        }
        return "Fazendo $a";
    }

    echo makecoffee();
?>