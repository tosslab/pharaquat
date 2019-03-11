<?php
namespace Acme;

// ready
require_once(__DIR__.'/../dist/pharaquat');

// set variables
$env = 'development';
switch ($env) {
    case 'development':
        $username = 'onion.jeong@tosslab.com';
        $password = 'password';
        $teamId = 279;
        $roomId = 20033742;
        break;
    case 'production':
        $username = 'onion.jeong@tosslab.com';
        $password = 'password';
        $teamId = 279;
        $roomId = 17754871;
        break;
    default:
        $username = null;
        $password = null;
        $teamId = null;
        $roomId = null;
}
if (!$username || !$password || !$teamId || !$roomId) {
    echo 'variables failed', PHP_EOL;
    exit();
}

try {
    // create client
    $client = new \Jandi\Client();
    // set env - 'development' or 'production'
    $client->setEnv($env);

    // get auth
    $auth = $client->getAuthAsMember($username, $password);
    if ($auth->isValid()) {
        echo 'auth succeeded', PHP_EOL;
    } else {
        echo 'auth failed', PHP_EOL;
        exit();
    }

    // send attachment request
    $attachment = new \Jandi\Request\Attachment([
        'auth' => $auth,
        'team' => $teamId,
        'room' => $roomId,
        'file' => [
            'file' => __DIR__.'/files/sample.png',
        ],
    ]);
    $response = $client->send($attachment);
    if ($response->getStatusCode() === 200) {
        echo 'attachment succeeded', PHP_EOL;
    } else {
        echo $response->getStatusCode(), PHP_EOL;
        echo 'attachment failed', PHP_EOL;
        exit();
    }
    // get attachment id
    $attachmentId = $response->getData()->id;

    // send post request #1
    // with attachment
    $post = new \Jandi\Request\Post([
        'auth' => $auth,
        'team' => $teamId,
        'room' => $roomId,
        'body' => [
            'title' => 'NEW TITLE #1',
            'content' => 'NEW CONTENT #1'.PHP_EOL.'WITH ATTACHMENT',
            'fileIds' => [$attachmentId],
        ],
    ]);
    $response = $client->send($post);
    if ($response->getStatusCode() === 200) {
        echo 'post #1 succeeded', PHP_EOL;
    } else {
        echo $response->getStatusCode(), PHP_EOL;
        echo 'post #1 failed', PHP_EOL;
        exit();
    }

    // send post request #2
    // reuse $post object, only set body
    $post->setBody([
        'title' => 'NEW TITLE #2',
        'content' => 'NEW CONTENT #2',
    ]);
    $response = $client->send($post);
    if ($response->getStatusCode() === 200) {
        echo 'post #2 succeeded', PHP_EOL;
    } else {
        echo $response->getStatusCode(), PHP_EOL;
        echo 'post #2 failed', PHP_EOL;
        exit();
    }
} catch (\Jandi\Exception $e) {
    echo $e, PHP_EOL;
} catch (\Exception $e) {
    echo $e, PHP_EOL;
} catch (\Error $e) {
    echo $e, PHP_EOL;
}
