<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "utils/Utils.php";
use Dotenv\Dotenv;

use Models\Accounts;
use Models\Auth;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$UTILS = new Utils();
$URI = $_ENV['URI'];
$USER_NAME = $_ENV['USER_NAME'];
$ACCESS_KEY = $_ENV['ACCESSKEY'];

$auth = new Auth($URI, $USER_NAME, $ACCESS_KEY);
// $SESSION_NAME= $auth->doLogin();
$SESSION_NAME= "29b1c38e6790cccbabdee";

$accounts = new Accounts();
// $accounts = $accounts->all();
// $teste = $accounts->describe();
// $accounts->count('1', 'create')->execute();
$teste = $accounts->create();

var_dump($teste);
// var_dump($accounts);