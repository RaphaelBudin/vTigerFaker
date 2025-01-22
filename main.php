<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "utils/Utils.php";
use Dotenv\Dotenv;

use Models\Accounts;
use Models\Auth;
use Models\Contacts;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$UTILS = new Utils();
$HTTP_REQUEST = new HttpRequestFactory();
$URI = $_ENV['URI'];
$USER_NAME = $_ENV['USER_NAME'];
$ACCESS_KEY = $_ENV['ACCESSKEY'];

$auth = new Auth($URI, $USER_NAME, $ACCESS_KEY);
// $SESSION_NAME= $auth->doLogin();
$SESSION_NAME= "29b1c38e6790cccbabdee";

// $accounts = new Accounts();
// $accounts = $accounts->all();
// $teste = $accounts->describe();
// $accounts->count('300', 'create')->execute();
// $teste = $accounts->create();

$contacts = new Contacts($HTTP_REQUEST);
$contacts->describe();

// var_dump($teste);
// var_dump($accounts);