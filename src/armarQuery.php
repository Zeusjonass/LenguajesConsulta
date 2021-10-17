<?php
    function sinCampos($input) {
        $categorias = ["product_name", "quantity_per_unit", "category"];
        $tableToSearch = "products";
        for ($i=0; $i < count($categorias); $i++) {
            $query = "SELECT " . "products.product_name, products.quantity_per_unit, products.category" . " FROM " . $tableToSearch . " WHERE ";
            for ($j=0; $j < count($input); $j++) { 
                switch ($input[$j]) {
                    case "AND":
                        $query .= " AND ";
                        break;
                    case "OR":
                        $query .= " OR ";
                        break;
                    case "NOT":
                        $query .= "NOT ";
                        break; 
                    default:
                        switch ( strstr($input[$j], '(', true) ) {
                            case 'PATRON':
                                $palabra = substr(strstr($input[$j], '('), 1, -1);
                                $query .= $categorias[$i] . " LIKE '%" . $palabra . "%'";  
                                break;
                            case 'CADENA':
                                if(strpos($input[$j], ")")) {
                                    $palabra = substr(strstr($input[$j], '('), 1, -1);
                                    $query .= $categorias[$i] . " = '" . $palabra ."'";
                                } else {
                                    $palabra = substr(strstr($input[$j], '('), 1);
                                    while(!strpos($input[$j], ")")) {
                                        $j++;
                                        $palabra .= " " . $input[$j];
                                    }
                                    $palabra = substr($palabra, 0 , -1);
                                    $query .= $categorias[$i] . " = '" . $palabra . "'";
                                }
                                break;
                            default:
                                $query .= $categorias[$i] . " LIKE '%" . $input[$j] . "%'";
                                break;
                        }
                        break;
                }
            }
            $resultados = consultar($query);
            foreach($resultados as $fila) {
                foreach ($fila as $descrip) {
                    echo $descrip . '<br/>';
                }
            }
        }
    }

    function campos($input, $inputCampos) {
        $array = explode(",", $inputCampos);
        $camposDeBusqueda = [];
        for ($i=0; $i < count($array); $i++) { 
            $temp = explode(".", $array[$i]);
            array_push($camposDeBusqueda, $temp[1]);
        }
        $tabla = $temp[0];

        for ($i=0; $i < count($camposDeBusqueda); $i++) { 
            $query = "SELECT " . $inputCampos . " FROM " . $tabla . " WHERE ";
            for ($j=0; $j < count($input); $j++) { 
                switch ($input[$j]) {
                    case "OR":
                        $query .= " OR ";
                        break;
                    case "NOT":
                        $query .= "NOT ";
                        break; 
                    case "AND":
                        $query .= " AND ";
                        break;
                    default:
                        switch ( strstr($input[$j], '(', true) ) {
                            case 'CADENA':
                                if(strpos($input[$j], ")")) {
                                    $palabra = substr(strstr($input[$j], '('), 1, -1);
                                    $query .= $camposDeBusqueda[$i] . " = '" . $palabra . "'";
                                } else {
                                    $palabra = substr(strstr($input[$j], '('), 1);
                                    while(!strpos($input[$j], ")")) {
                                        $j++;
                                        $palabra .= " " . $input[$j];
                                    }
                                    $palabra = substr($palabra, 0 , -1);
                                    $query .= $camposDeBusqueda[$i] . " = '" . $palabra . "'";
                                }
                                break;
                            case 'PATRON':
                                $palabra = substr(strstr($input[$j], '('), 1, -1);
                                $query .= $camposDeBusqueda[$i] . " LIKE '%" . $palabra . "%'";
                                break;
                            default:
                                $query .= $camposDeBusqueda[$i] . " LIKE '%" . $input[$j] . "%'";
                                break;
                        }
                        break;
                }
            }
            $resultados = consultar($query);
            foreach($resultados as $fila) {
                foreach ($fila as $descrip) {
                    echo $descrip . '<br/>';
                }
            }
        }
    }

?>