<?php
require_once'jwt_utils.php';
require_once 'connexion_bd.php';
require_once 'response.php';

// Configuration des headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Vérification de la méthode HTTP
$http_method = $_SERVER['REQUEST_METHOD'];

if($http_method !== 'POST') {
    http_response_code(405);
    deliver_response(405, "Méthode non autorisée", null);
    exit();
}
// Récupération des données POST
$postedData = file_get_contents('php://input');
$data = json_decode($postedData, true);

if(!isset($data['Nom']) || !isset($data['Prenom']) || !isset($data['Mot_de_passe'])) {
    http_response_code(400);
    deliver_response(400, "Bad Request", ["error" => "Les données sont incorrectes"]);
    exit();
}

try {
    // Connexion à la base de données
    $pdo = connectionBD();

    // Préparation de la requête
    $sql = "SELECT * FROM utilisateur WHERE Nom = :Nom AND Prenom = :Prenom";
    $stmt = $pdo->prepare($sql);

    // Exécution de la requête
    $stmt->execute([
        ':Nom' => $data['Nom'],
        ':Prenom' => $data['Prenom']
    ]);

    // Récupération du résultat
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && sha1($data['Mot_de_passe'])=== $user['Mot_de_passe']) {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload = array(
            "Nom" => $user['Nom'],
            "Prenom" => $user['Prenom'],
            "exp" => time() + (60 * 60) // Expiration dans 1 heure
        );
        $secret="secret";
        $jwt=generate_jwt($header, $payload, $secret);

        // Envoi de la réponse
        http_response_code(200);
        deliver_response(200, "OK", ["jwt" => $jwt]);
    } else {
        // Utilisateur non trouvé
        http_response_code(401);
        deliver_response(401, "Unauthorized", ["error" => "Utilisateur non trouvé ou mdp incorrect"]);
    }

} catch(Exception $e) {
    http_response_code(500);
    deliver_response(500, "Internal Server Error", ["error" => $e->getMessage()]);
}
?>
