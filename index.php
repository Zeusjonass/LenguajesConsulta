<?php
include("./src/busqueda.php");
include("./src/tfIdf.php");
include("./src/upload.php");
?>
<html>
    <head>
        <style>
            #container{
                text-align: center;
            }
            #resultados{
                margin: 20px
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>Subida de archivos</h1>
            <form action="./index.php" method="post" enctype="multipart/form-data">
                <label>Seleccione los archivos .txt que desee subir:</label>
                <br>
                <input name="upload[]" type="file" multiple="multiple"  accept=".txt">
                <br><br>
                <input type="submit" value="Subir">
            </form>
            <?php
             if(isset( $_FILES['upload']['name'] )) {
                subeArchivos();
            }
            ?>
            <hr>
            <h1>Buscador Northwind</h1>
            <form action="./index.php" method="GET">
                <label>
                    Buscador:
                    <input id="busqueda" name="busqueda" type="text">
                </label><br><br>
                <input type="submit" value="Buscar">
            </form>
        </div> 
        <div id="resultados">
            <p>Resultados de <?php echo $_GET["busqueda"];?>:</p>
            <?php
            if(isset( $_GET["busqueda"] )) {
                $input = $_GET["busqueda"];
                $busqueda = explode(" ", $input);             
                buscar($busqueda);
            }
            ?>
        </div>
    </body>
</html>
