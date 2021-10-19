<?php
include("./src/busqueda.php");
include("./src/tfIdf.php");
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
            <h1>Buscador Northwind</h1>
            <form action="./index.php" method="GET">
                <label for="filtro">
                    Buscador:
                    <input id="busqueda" name="busqueda" type="text">
                </label<br><br><br>
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
