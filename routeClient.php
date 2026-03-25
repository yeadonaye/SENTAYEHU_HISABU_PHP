<?php

class ApiClient {

    public static function login($login, $password) {
        $url = "https://yeadonaye.alwaysdata.net/authapi.php";

        $data = json_encode([
            "login" => $login,
            "password" => $password
        ]);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['status' => 500, 'data' => null];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}