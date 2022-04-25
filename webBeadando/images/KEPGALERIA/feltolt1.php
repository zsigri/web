<?php
    // Alkalmazás logika:
    include('config.inc.php');
    $uzenet = array();   

    // Űrlap ellenőrzés:
    if (isset($_POST['kuld'])) {
        /*
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
        */
        $fajlok = $_FILES["fajlok"];
        for($i = 0; $i < count($fajlok["name"]); $i++) {
            if ($fajlok['error'][$i] == 4)    // Nem töltött fel fájlt
                $uzenet[] = "Nem töltött fel fájlt";
            elseif ($fajlok['error'][$i] == 1   // A fájl túllépi a php.ini -ben megadott maximális méretet
                        or $fajlok['error'][$i] == 2   // A fájl túllépi a HTML űrlapban megadott maximális méretet
                        or $fajlok['size'][$i] > $MAXMERET) 
                $uzenet[] = " Túl nagy állomány: " . $fajlok['name'][$i];
            elseif (!in_array($fajlok['type'][$i], $MEDIATIPUSOK))
                $uzenet[] = " Nem megfelelő típus: " . $fajlok['name'][$i];
            else {
                $vegsohely = $MAPPA.strtolower($fajlok['name'][$i]);
                if (file_exists($vegsohely))
                    $uzenet[] = " Már létezik: " . $fajlok['name'][$i];
                else {
                    move_uploaded_file($fajlok['tmp_name'][$i], $vegsohely);
                    $uzenet[] = ' Ok: ' . $fajlok['name'][$i];
                }
            }
        }        
    }
    // Megjelenítés logika:
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Galéria</title>
    <style type="text/css">
        label { display: block; }
    </style>
</head>
<body>
    <h1>Feltöltés a galériába:</h1>
<?php
    if (!empty($uzenet))
    {
        echo '<ul>';
        foreach($uzenet as $u)
            echo "<li>$u</li>";
        echo '</ul>';
    }
?>
    <form action="feltolt1.php" method="post"
                enctype="multipart/form-data">
        <input type="hidden" name="max_file_size" value="110000">
        <label>Fájlok:
            <input type="file" name="fajlok[]" accept="image/png, image/jpeg" multiple required>
        </label>
        <input type="submit" name="kuld">
      </form>    
</body>
</html>
