<?php
require('tokenValidation.php');
$headers = apache_request_headers();
if(isset($headers['Authorization'])){
    $authorizationHeader = $headers['Authorization'];
}else{
    $authorizationHeader = '';
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        insertArticle($authorizationHeader);
        break;
    case 'GET':
        getArticles();
        break;
    case 'PUT':

        break;
    case 'DELETE':

        break;
}

function insertArticle($authorizationHeader) {
    $response = array();
    if(($userOwnId = validateToken($authorizationHeader)) > 0) {
        $jsonReceived = trim(file_get_contents("php://input"));
        $content = json_decode($jsonReceived, true);
        if(!is_array($content)){
            $response["statusCode"] = 405;
            $response["message"] = "Request must be in valid json";
        }else{
            if (isNotNullOrEmpty($articleType = $content["articletype"] && $articleTitle = $content["title"] && isNotNullOrEmpty($articleContent = $content['content']))){
                require('../config.php');
                
                $conn = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
                $stmt = $conn->prepare("insert into article (publisherId, content, title, publishDate, articletype) values (?,?,?,?,?)");
                $now = (string)date_format(new DateTime(),"Y-m-d H:i:s");
                echo $now;
                $stmt->bind_param("sssss", $userOwnId, $articleContent, $articleTitle, $now, $articleType);
                
    
                $result = $stmt->execute();
                if (!$result) {
                    http_response_code(400);
                    $response["statusCode"] = 400;
                    $response["message"] = "Insert article is not successful."; 
                } else {
                    $articleId = mysqli_insert_id($conn);
                }
            }else{
                http_response_code(400);
                $response["statusCode"] = 400;
                $response["message"] = "Please enter title at least."; 
            }
        }
    }else{
        http_response_code(401);
        $response["statusCode"] = 401;
        $response["message"] = "Unauthorized";
    }
    echo json_encode($response);
}

function getArticles() {
    $reponse = array();
    
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') == 0){
        $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathSegments = explode('/', $_SERVER['REQUEST_URI_PATH']);
        $articleId= $pathSegments[count($pathSegments)-1];

        require('../config.php');

        $conn = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);

        if(strcasecmp($articleId, 'article.php') != 0){
            $sql_check_credential = "SELECT * FROM article WHERE id='$articleId'";
            $result = mysqli_query($conn,$sql_check_credential);
            if (mysqli_num_rows($result)==0) {
                http_response_code(404);
                $response["statusCode"] = 404;
                $response["message"] = "articles not found"; 
            } else {
                $row= mysqli_fetch_assoc($result);
                $responseArticle = array();
                $responseArticle["Id"]  = $row["ID"];
                $responseArticle["content"] = $row["content"];
                $responseArticle["title"] = $row["title"];;

                http_response_code(200);
                $response["statusCode"] = 200;
                $response["article"] = $responseArticle;
            }
        }else{
            $sql_check_credential = "SELECT * FROM article";
            $result = mysqli_query($conn,$sql_check_credential);
            $reponseCollectionOfArticles = array();

            while ($row= mysqli_fetch_assoc($result)) {
                $responseArticle = array();
                $responseArticle["Id"]  = $row["ID"];
                $responseArticle["content"] = $row["content"];
                $responseArticle["title"] = $row["title"];;

                array_push($reponseCollectionOfArticles, $responseArticle);
            }
            http_response_code(200);
            $response["statusCode"] = 200;
            $response["user"] = $reponseCollectionOfArticles;
        }
    }else{
        http_response_code(405);
        $response["statusCode"] = 405;
        $response["message"] = "Request not supported";
    }
    echo json_encode($response);
}

function isNotNullOrEmpty($eval) {
    return (!is_null($eval) && isset($eval) && !empty($eval)) ? true : false;
}

?>