<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Gorilla Soft</title>
        <style>
            body {
                font-family: Times New Roman, serif;
                font-size: 10pt;
                /*text-align: center;*
            }
        </style>
    </head>
    <body>
        <h1>Demo :title</h1>
        <?php
        echo "Hello, " . $name . "! <br />";
        echo '<ul>';
        foreach ($superheroes as $superheroe) {
            echo '<li>' . $superheroe->name . '</li>';
        }
        echo '</ul>';
        ?>
    </body>
</html>
