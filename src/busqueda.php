<?php

    include("conexion.php");
    include("armarQuery.php");

    function buscar($busqueda) {
        $camposInput = validaCampos($busqueda);
        $hasCamposInput = $camposInput ? true : false;
        
        if($hasCamposInput) {
            $busqueda = borrarCampos($busqueda);
            campos($busqueda, $camposInput);
        } else {
            sinCampos($busqueda);
        }
    }

    function borrarCampos($busqueda) {
        for ($i=0; $i < count($busqueda); $i++) { 
            if(strstr($busqueda[$i], '(', true) == "CAMPOS") {
                while(!strpos($busqueda[$i], ")")) {
                    $i++;
                    unset($busqueda[$i-1]);
                }
                if(strpos($busqueda[$i], ')')) {
                    unset($busqueda[$i]);   
                }
            }
        }
        return $busqueda;
    }

    function validaCampos($busqueda) {
        for ($i=0; $i < count($busqueda); $i++) { 
            if(strstr($busqueda[$i], '(', true) == "CAMPOS") {
                $campos = substr(strstr($busqueda[$i], '('), 1);
                while(!strpos($busqueda[$i], ")")) {
                    $i++;
                    $campos .= " " . $busqueda[$i];
                }
                $campos = substr($campos, 0 , -1);
                return $campos;
            }
        }
        return "";
    }
?>