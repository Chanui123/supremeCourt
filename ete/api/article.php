<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        insertArticle();
        break;
    case 'GET':
        getArticles();
        break;
    case 'PUT':

        break;
    case 'DELETE':

        break;
}

function insertArticle() {

    /* @var $_POST type */
    $title = "";
    $content = "";
    $author = "";
    if (isNotNullOrEmpty($title = filter_input(INPUT_POST, 'title')) && isNotNullOrEmpty($content = filter_input(INPUT_POST, 'content'))) {
        http_response_code(200);
        if (isNotNullOrEmpty(filter_input(INPUT_POST, 'author'))) {
            $author = filter_input(INPUT_POST, 'author');
        }
        // ur insert SQL code here
        echo $title . ' ' . $author . ' ' . $content;
    } else {
        http_response_code(400);
        echo 'incomplete';
    }
}

function getArticles() {
    
}

function isNotNullOrEmpty($eval) {
    return (!is_null($eval) && isset($eval) && !empty($eval)) ? true : false;
}

?>