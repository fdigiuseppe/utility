<!DOCTYPE html>
<html>
<head>
    <title>EndOfLife Api</title>
</head>
<body>
    <h1>EndOfLife Api</h1>

    <form method="post" action="" id="form1">
        <label for="lib">Inserisci il nome della libreria da controllare: </label>
        <input type="text" name="lib" id="lib" required>
        <input type="submit" name="submit" id="button" value="Interroga">
    </form>

    <?php
    $curl = curl_init();
    $name = $_POST["lib"];

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://endoflife.date/api/".$name.".json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $data = json_decode($response, true);
        if ($data) {
            echo '<pre>';
            echo json_encode($data, JSON_PRETTY_PRINT);
            echo '</pre>';
        } else {
            echo 'Errore nella decodifica JSON.';
        }
    }
    ?>

</body>
</html>
