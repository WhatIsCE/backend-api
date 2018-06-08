<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

/*$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/

define('DEBUG',true);

$app->get('/notes/all/limit/{num}', function() {

    require_once('db.php');
    $get_id = intval($request->getAttribute('num'));
    $querystring = "select * from notes order by `date` LIMIT $get_id";

    try {
        $results = query($querystring,[],true);

        if (count($results)>0) {
         echo json_encode($results);
        } else {
            echo '{"status":"not found"}';
        }
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }

    }

});

$app->post('/notes/new', function($request){

    require_once('db.php');
    $querystring = "INSERT INTO notes (`author`,`content`,`date`) VALUES (:author,:content,NOW())";
    $note_author = $request->getParsedBody()['note_author'];
    $note_content = $request->getParsedBody()['note_content'];

    try {
        query($querystring, [
            'author' => $note_author,
            'content' => $note_content
        ]);
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }
    }


});

$app->get('/notes/{note_id}', function($request){
    require_once('db.php');

    $get_id = $request->getAttribute('note_id');
    $querystring = "SELECT * from notes WHERE `id`= :id";

    try {
        $results = query($querystring, [
            "id" => $get_id
        ],true);

        if (count($results)>0) {
         echo json_encode($results);
        } else {
            echo '{"status":"Not Found!"}';
        }
    }
    catch(Exception $e) {
        if (DEBUG) {
            echo $e->getMessage();
        } else {
            echo '{"status":"error"}';
        }
    }


});