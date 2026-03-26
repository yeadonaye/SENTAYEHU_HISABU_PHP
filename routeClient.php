<?php

class routeClient {

public static function login($login, $password) {
    $url = "https://yeadonaye.alwaysdata.net/authapi.php";

    $data = json_encode([
        "login" => $login,
        "password" => $password
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return ['status_code' => 500, 'status_message' => curl_error($ch), 'data' => null];
    }

    curl_close($ch);

    $decoded = json_decode($response, true);

    // Sécurité si la réponse n'est pas du JSON valide
    if ($decoded === null) {
        return ['status_code' => 500, 'status_message' => 'Réponse invalide de l\'API', 'data' => null];
    }

    return $decoded;
}
}