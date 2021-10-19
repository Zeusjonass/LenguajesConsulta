<?php
  //include("conexion.php");

  function calcIDF($term) {
    $numDocs = (int)$term["num_docs"];
    $allFrequencies = (int)$term["all_frequencies"];

    return log10($numDocs / $allFrequencies);
  }
  
  function obtenerDocumentos($id) {
    $query = "SELECT frecuency, document_name, count_words FROM posting JOIN documents ON posting.id_document=documents.id_document WHERE id_term = {$id}";
    $resultado = consultar($query);
    return $resultado;
  }

  function calcTF($document) {
    $frecuency = (int)$document["frecuency"];
    $countWords = (int)$document["count_words"];

    return $frecuency / $countWords;
  }

  function calcTFIDF($documents, $term) {
    $IDF = calcIDF($term);
    
    $documentsWithTFIDF = array();
    foreach ($documents as $document) {
      $documentName = $document["document_name"];
      $TF = calcTF($document);
      $TFIDF = $TF * $IDF;
      $documentsWithTFIDF[] = array($documentName => $TFIDF);
    }
    asort($documentsWithTFIDF);
    return $documentsWithTFIDF;
  }
?>