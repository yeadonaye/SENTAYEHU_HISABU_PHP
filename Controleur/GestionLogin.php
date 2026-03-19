<?php
// auth.php lives in Modele/DAO
require_once __DIR__ . '/../Modele/DAO/authapi.php';
require_once __DIR__ . '/../Modele/DAO/jwt_utils.php';


if (is_jwt_valid("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJsb2dpbiI6ImJvYiIsInJvbGUiOiJqb3VldXIiLCJleHAiOjE3NzM5MjM0MDl9.MQdT3u88m9O1BkIMua5zVyyzQ2MS6hBEjj2U258-k_A", "secret_key")) {
    header('Location: /index.php');
    exit;
}


$error = seConnecter();


?>