<?php
  include("./tfIdf.php");

  function sinCampos($input) {
    $tableToSearch = "terms";
    $query = "SELECT * FROM {$tableToSearch} WHERE ";
    $putOr = true;
    for ($i = 0; $i < count($input); $i++) {
      $string = $input[$i];
      switch($string) {
        case "AND":
        case "OR":
        case "NOT":
          $query .= " {$string} ";
          $putOr = false;
          break; 
        default:
          if (strpos($string, "PATRON") === 0) {
            $pattern = substr($string,7, -1);
            $query .= " term LIKE '%{$pattern}%' ";
            $putOr = false;
          }else {
            if($i > 0) {
              $query .= $putOr ? " OR term = '{$string}'" : " term = '{$string}'";
              $putOr = true;
            } else {
              $query .= " term = '{$string}'";
            }
          }
          break;
      }
    }
  
    $resultados = consultar($query);
    $totalArray = Array();
    foreach ($resultados as $term) {
      $docs = obtenerDocumentos($term['id_term']);
      $docTFIDF = calcTFIDF($docs, $term);
      $totalArray = array_merge($totalArray, $docTFIDF);
    }

    foreach($totalArray as $doc) {
      $name = key($doc);
      $TFIDF = $doc[$name];
      echo "<div>Nombre del archivo: </div>";
      echo "<a href='./uploadFiles/{$name}' download >{$name} </a> <br/>";
      
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