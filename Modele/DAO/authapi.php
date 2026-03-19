<?php
    require_once 'jwt_utils.php';
    require_once 'connexionBD.php'; //J'utilise une BD séparer

    //Il faut accepter que les réquêtes de méthode POST
    /*
    function seConnecter(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Récupérer le contenu JSON envoyé
            $json = file_get_contents('php://input', TRUE);
            $data = json_decode($json);

            if (!empty($data->login) && !empty($data->password)){
                $user = isValidUser($data->login, $data->password, $linkpdo);

                if ($user) {
                    $login = $data->login;
                    $role = $user['role'];
                    
                    $headers = array('alg'=>'HS256', 'typ'=>'JWT');
                    $payload = array('login'=>$login, 'role'=>$role, 'exp'=>(time() + 3600));

                    $jwt = generate_jwt($headers, $payload, "secret_key");
                    deliver_response('200', 'Authentification réussie', $jwt);
                } else {
                    deliver_response('400', 'Login et/ou mot de passe incorrectes', null);
                }
            } else {
                deliver_response('400', 'Les champs login et password sont obligatoires', null);
            }
        }
    }
    */ 

    function seConnecter() {
        global $linkpdo;
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!empty($login) && !empty($password)) {
            $user = isValidUser($login, $password, $linkpdo);
            if ($user) {
                $headers = ['alg'=>'HS256','typ'=>'JWT'];
                $payload = [
                    'login' => $login,
                    'role'  => $user['role'],
                    'exp'   => time() + 3600
                ];

                return generate_jwt($headers, $payload, "secret_key"); // return JWT
            }
        }

        return false;
    }

    function isValidUser($login, $password, $linkpdo) {
        $query = "SELECT password, role FROM authentification WHERE login = :login"; 
        $stmt = $linkpdo->prepare($query);
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; 
        }
        return false;
    }
?>