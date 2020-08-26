<?php

define('NEO_PHP_SCRIPT', 'bootstrip');
define('NEO_LOAD_WEBPAGE', false);
define('NEO_IN_PHPUNIT', true);

$_POST['jwt'] = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1OTE4Nzg5NTIsImp0aSI6InBwMnYzYWFldVBhbERzT09LQ3dyZXFHY1o1TmtKMllzMGNMSTY3b2k3bWs9IiwiaXNzIjoibmVvIiwibmJmIjoxNTkxODc4OTUyLCJleHAiOjE1OTIzOTczNTIsInVpZCI6MSwidW5tIjoiemdpYSJ9.sRyUmTCsAGKZ2g37zXkucLAHQMbG48NjP4Z1tDdTEsWUbIuTFiuYmbEhaONWA8FYZ3B4tvcAFKW9Lhdv-7BO4g';

// 包含全局文件
require_once '../global.php';

require_once ABSPATH . DIRECTORY_SEPARATOR . 'tests/BaseTester.php';
