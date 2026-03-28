<?php

class routeClient {

    private const AUTH_URL = 'https://apiliverpool.alwaysdata.net/authapi.php';
    private const BACKEND_BASE_URL = 'https://yeadonaye.alwaysdata.net/Routes/';

    public static function request(string $method, string $url, ?array $body = null, ?string $token = null) {
        $headers = ['Content-Type: application/json'];

        if ($token !== null) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 10
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch) ? curl_error($ch) : null;
        curl_close($ch);

        if ($curlError) {
            return ['status_code' => 500, 'status_message' => $curlError, 'data' => null];
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            return ['status_code' => 500, 'status_message' => 'Réponse invalide de l\'API', 'data' => null];
        }

        return $decoded;
    }

    public static function login(string $login, string $password): array {
        return self::request('POST', self::AUTH_URL, [
            'login'    => $login,
            'password' => $password
        ]);
    }

    // Match
    public static function getMatch(string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'matchapi.php', null, $token);
    }

    public static function getMatchById(int $id, string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, null, $token);
    }

    public static function addMatch(array $data, string $token): array {
        return self::request('POST', self::BACKEND_BASE_URL . 'matchapi.php', $data, $token);
    }

    public static function updateMatch(int $id, array $data, string $token): array {
        return self::request('PUT', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, $data, $token);
    }

    public static function deleteMatch(int $id, string $token): array {
        return self::request('DELETE', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, null, $token);
    }

}