<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "utils/Utils.php";

use Dotenv\Dotenv;

use Models\Auth;
use Models\HttpRequestFactory;
use Models\Accounts;
use Models\Contacts;
use Models\Potential;
// use Models\Quote;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$UTILS = new Utils();
$HTTP_REQUEST = new HttpRequestFactory();
$URI = $_ENV['URI'];
$USER_NAME = $_ENV['USER_NAME'];
$ACCESS_KEY = $_ENV['ACCESSKEY'];

$auth = new Auth($URI, $USER_NAME, $ACCESS_KEY);
$SESSION_NAME= $auth->doLogin();
// $SESSION_NAME = "67ff8aa7679242a08069";

$module = new Potential($UTILS, $HTTP_REQUEST);
$module->count(2400, 'create')->execute();
// $module->count(2400, 'create')->execute();
// $module->all();