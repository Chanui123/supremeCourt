<?php
require('tokenValidation.php');
$response = array();
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    $response["statusCode"] = 405;
    $response["message"] = "Request not supported";
} 
else {
    $jsonReceived = trim(file_get_contents("php://input"));
    $content = json_decode($jsonReceived, true);
    if(!is_array($content)){
        $response["statusCode"] = 405;
        $response["message"] = "Request must be in valid json";
    }else{
        if (isset($content["username"]) && !empty($content["username"]) 
            && isset($content["password"]) && !empty($content["password"])) {
            
            require('../config.php');
            
            $conn = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
            $username = $content["username"];
            $password = $content["password"];
            if (!$conn) {
                http_response_code(500);
                $response["statusCode"] = 500;
                $response["message"] = "Database connection failure."; 
            } else {
                $sql_check_credential = "SELECT * FROM users WHERE username = '$username' AND  password = '$password'";
                $result = mysqli_query($conn,$sql_check_credential);
                if (mysqli_num_rows($result)==0) {
                    http_response_code(400);
                    $response["statusCode"] = 400;
                    $response["message"] = "Login is not successful. Try again."; 
                } else {
                    $row= mysqli_fetch_assoc($result);
                    $userResponse = array();
                    $userResponse["userId"] = $userId = $row["ID"];
                    $userResponse["username"] = $row["UserName"];
                    $userResponse["email"] = $row["Email"];
                    $resultToken = generateToken($userId);
                    if (!$resultToken) {
                        http_response_code(400);
                        $response["statusCode"] = 400;
                        $response["message"] = "Login is not successful. Try again."; 
                    }else{
                        http_response_code(200);
                        $response["statusCode"] = 200;
                        $response["token"] = $resultToken;
                        $response["user"] = $userResponse;
                    }
                }
                mysqli_close($conn);
            }
        } else {
            http_response_code(400);
            $response["statusCode"] = 400;
            $response["message"] = "Username or Password missing."; 
        }
    }
}
echo json_encode($response);
?>