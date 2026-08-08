<?php
/**
 * Al NAHDA Agency application bootstrap — required once by public/index.php
 * (or public_html/index.php on hosting) before the router runs.
 */

$basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);

require $basePath . '/vendor/autoload.php';
require __DIR__ . '/Helpers/functions.php';

App\Core\Env::load();
App\Core\Url::init();

error_reporting(E_ALL);
ini_set('display_errors', App\Core\Env::get('APP_DEBUG', '1') === '1' ? '1' : '0');
