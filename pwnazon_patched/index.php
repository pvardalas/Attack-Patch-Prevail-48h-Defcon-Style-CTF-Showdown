<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path === '/key') {
    header('Content-Type: text/plain');
    header('Transfer-Encoding: chunked');
    header('Connection: keep-alive');

    function send_chunk($chunk) {
        $len = dechex(strlen($chunk));
        echo $len . "\r\n";
        echo $chunk . "\r\n";
        flush();
    }

    set_time_limit(60);

    for ($i = 0; $i < 300; $i++) { // 300 * 0.1s = ~30s delay
        send_chunk(str_repeat("FLAG - YOU_4LM0ST_G0T_US_L1TTLE_BR0 ", 36864)); // 36 KB string
	    usleep(100000);
    }

    // Send zero-length chunk to indicate end of response
    echo "0\r\n\r\n";
    flush();
    usleep(100000);
    http_response_code(403);
    exit('Access Denied');
}

$fullPath = __DIR__ . $path;
if (php_sapi_name() === 'cli-server' && is_file($fullPath) && $path !== '/index.php') {
    return false;
}
include "utils.php";

make_header("Pwnazon");
?>

<h2>Pwnazon.com</h2>

<h3>-By far the least pwnable web store on the internet!</h3>
<p>We sell almost everything you could ever want. Shop with confidence knowing
your address, name, shopping history, and credit card infor are all safe with
us!</p>
