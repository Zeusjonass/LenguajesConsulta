<?php

include("./conexion.php");

function subeArchivos(){
    $total = count($_FILES['upload']['name']);
    
    // Loop through each file
    echo "<ul>";
    for( $i=0 ; $i < $total ; $i++ ) {
    //Get the temp file path
        $destination_path = getcwd().DIRECTORY_SEPARATOR;
        $file_name = basename( $_FILES["upload"]["name"][$i]);
        $target_path = $destination_path . 'uploadFiles/' . $file_name;
        echo $target_path;
       
        if (file_exists($target_path)) {
            echo "<li> Sorry, file " . $file_name . " already exists. </li>";
        }else{
            @move_uploaded_file($_FILES['upload']['tmp_name'][$i], $target_path);
            $words = explode(" ",file_get_contents($target_path));
            $fragmentText = substr(file_get_contents($target_path),0,50);
            $idDocument = guardarRegistroDocumento($file_name, count($words));
            $groupedTerms = agruparTerminos($words);
            guardarTerminos($groupedTerms, $idDocument, $fragmentText);
            echo "<li>" . $target_path ." created succesfully. </li>";
            
        }
    }
    echo "</ul>";
}

function agruparTerminos($words){
    $groupedTerms = [];
    foreach($words as $word){
        if($groupedTerms[$word]){
            $groupedTerms[$word] +=  1;
        }else{
            $groupedTerms[$word] =  1;
        }
    }

    return $groupedTerms;
}

function guardarRegistroDocumento($file_name, $count_word){
    $query = "INSERT INTO documents (document_name, count_words) VALUES ('".$file_name."', ".$count_word.");";
    $result = insertar($query);
    return $result;
}

function guardarTerminos($terms, $idDocument, $fragmentText){
    foreach($terms as $term => $frequency){
         $result = consultar("SELECT * FROM terms WHERE term = '". $term ."';" );
        if (count($result)>0){
            $query = "UPDATE terms SET num_docs=" . $result[0]["num_docs"]+1 . ", all_frequencies=" . $result[0]["all_frequencies"] + $frequency . " WHERE term='" . $term . "';";
        }else{
            $query = "INSERT INTO terms (term, num_docs, all_frequencies) VALUES ('" . $term. "', 1, ". $frequency . ");";
        }
        $newId = insertar($query); 
        $idTerm= $newId? $newId : $result[0]["id_term"];
        actualizarPosting($frequency, $idTerm, $idDocument, $fragmentText);
    }
}

function actualizarPosting($frequency, $idTerm, $idDocument, $fragmentText){
    $query= "INSERT INTO posting
    (id_term, id_document, frecuency, fragment_text)
    VALUES(".$idTerm.", ".$idDocument.", ".$frequency.", '".$fragmentText."');
    ";
    insertar($query);
}

?>