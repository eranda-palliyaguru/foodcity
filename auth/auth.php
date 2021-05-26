<?php

\error_reporting(\E_ALL);
\ini_set('display_errors', 'stdout');

// enable assertions
\ini_set('assert.active', 1);
@\ini_set('zend.assertions', 1);
\ini_set('assert.exception', 1);

\header('Content-type: text/html; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

 include("connect.php");
// or
// $db = new \PDO('pgsql:dbname=php_auth;host=127.0.0.1;port=5432', 'postgres', 'monkey');
// or
// $db = new \PDO('sqlite:../Databases/php_auth.sqlite');

$auth = new \Delight\Auth\Auth($db);



//\showGeneralForm();
//\showDebugData($auth, $result);
$id = $auth->getUserId();

if ($auth->isLoggedIn()) {

}
else {
header("location:../index.php");
}

 ?>
