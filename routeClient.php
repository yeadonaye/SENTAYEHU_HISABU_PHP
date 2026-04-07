<?php

class routeClient {

    private const AUTH_URL         = 'https://apiliverpool.alwaysdata.net/authapi.php';
    private const BACKEND_BASE_URL = 'https://yeadonaye.alwaysdata.net/Routes/';

    // -----------------------------------------------------------------------
    // Méthode centrale cURL
    // -----------------------------------------------------------------------
    public static function request(string $method, string $url, ?array $body = null, ?string $token = null): array {
        $headers = ['Content-Type: application/json'];

        if ($token !== null) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 10,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response   = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_errno($ch) ? curl_error($ch) : null;
        unset($ch);

        if ($curlError) {
            return ['status_code' => 500, 'status_message' => $curlError, 'data' => null];
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            return ['status_code' => 500, 'status_message' => 'Réponse invalide de l\'API', 'data' => null];
        }

        return $decoded;
    }

    // -----------------------------------------------------------------------
    // AUTH
    // -----------------------------------------------------------------------

    /** Connexion : retourne le token JWT */
    public static function login(string $login, string $password): array {
        return self::request('POST', self::AUTH_URL, [
            'login'    => $login,
            'password' => $password,
        ]);
    }

    /**
     * Vérifie la validité du token auprès de l'API d'authentification.
     * C'est l'auth API qui détient la passphrase — le frontend ne valide jamais lui-même.
     * Retourne ['status_code' => 200, 'data' => ['login' => ..., 'role' => ...]] si valide,
     * ou ['status_code' => 401, ...] si invalide/expiré.
     */
    public static function verifyToken(string $token): array {
        return self::request('GET', self::AUTH_URL, null, $token);
    }

    // -----------------------------------------------------------------------
    // MATCHS
    // -----------------------------------------------------------------------

    /** Liste tous les matchs (accessible sans token) */
    public static function getMatchs(?string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'matchapi.php', null, $token);
    }

    /** Récupère un match par son ID (pour pré-remplir le formulaire de modification) */
    public static function getMatchById(int $id, string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, null, $token);
    }

    /** Ajoute un match */
    public static function addMatch(array $data, string $token): array {
        return self::request('POST', self::BACKEND_BASE_URL . 'matchapi.php', $data, $token);
    }

    /** Met à jour un match */
    public static function updateMatch(int $id, array $data, string $token): array {
        return self::request('PUT', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, $data, $token);
    }

    /** Supprime un match */
    public static function deleteMatch(int $id, string $token): array {
        return self::request('DELETE', self::BACKEND_BASE_URL . 'matchapi.php?id=' . $id, null, $token);
    }

    // -----------------------------------------------------------------------
    // JOUEURS
    // -----------------------------------------------------------------------

    /** Liste tous les joueurs */
    public static function getJoueurs(string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'joueurapi.php', null, $token);
    }

    /** Récupère un joueur par son ID */
    public static function getJoueurById(int $id, string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'joueurapi.php?id=' . $id, null, $token);
    }

    /** Ajoute un joueur */
    public static function addJoueur(array $data, string $token): array {
        return self::request('POST', self::BACKEND_BASE_URL . 'joueurapi.php', $data, $token);
    }

    /** Met à jour un joueur */
    public static function updateJoueur(int $id, array $data, string $token): array {
        return self::request('PUT', self::BACKEND_BASE_URL . 'joueurapi.php?id=' . $id, $data, $token);
    }

    /** Supprime un joueur */
    public static function deleteJoueur(int $id, string $token): array {
        return self::request('DELETE', self::BACKEND_BASE_URL . 'joueurapi.php?id=' . $id, null, $token);
    }

    // -----------------------------------------------------------------------
    // FEUILLE DE MATCH
    // -----------------------------------------------------------------------

    /** Liste les participations d'un match */
    public static function getFeuilleDeMatch(int $idMatch, string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'feuilleDeMatchApi.php?matchId=' . $idMatch, null, $token);
    }

    /** Ajoute une participation */
    public static function addFeuilleDeMatch(array $data, string $token): array {
        return self::request('POST', self::BACKEND_BASE_URL . 'feuilleDeMatchApi.php', $data, $token);
    }

    /** Met à jour une participation */
    public static function updateFeuilleDeMatch(int $id, array $data, string $token): array {
        return self::request('PUT', self::BACKEND_BASE_URL . 'feuilleDeMatchApi.php?matchId=' . $id, $data, $token);
    }

    /** Supprime une participation */
    public static function deleteFeuilleDeMatch(int $id, string $token): array {
        return self::request('DELETE', self::BACKEND_BASE_URL . 'feuilleDeMatchApi.php?matchId=' . $id, null, $token);
    }

    // -----------------------------------------------------------------------
    // STATISTIQUES
    // -----------------------------------------------------------------------

    /** Récupère toutes les statistiques (matchs + joueurs) */
    public static function getStatistiques(string $token): array {
        return self::request('GET', self::BACKEND_BASE_URL . 'statistiques_api.php', null, $token);
    }

}