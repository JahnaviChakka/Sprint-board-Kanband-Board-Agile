<?php
// include google API client
include "vendor/autoload.php";
// set google client ID
$google_oauth_client_id = "960963656376-180pmm572qc6lkfrpaaojdllrcmnne7c.apps.googleusercontent.com";
$google_oauth_client_secret = "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";

// create google client object with client ID
$client = new Google_Client([
    'client_id' => $google_oauth_client_id
]);

// verify the token sent from AJAX
$id_token = $_POST["id_token"];
 
$payload = $client->verifyIdToken($id_token);
if ($payload && $payload['aud'] == $google_oauth_client_id)
{
    // get user information from Google
    $user_google_id = $payload['sub'];
 
    $name = $payload["name"];
    $email = $payload["email"];
    $picture = $payload["picture"];
    // send the response back to client side
    echo json_encode([
        "status" => "success"
    ]);
}
else
{
    // token is not verified or expired
    echo json_encode([
        "status" => "error"
    ]);
}
