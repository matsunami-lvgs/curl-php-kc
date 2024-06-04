<?php
require('urls.php');

function main ()
{
    date_default_timezone_set('Asia/Tokyo');
    foreach(PAGES as $key => $page) {
        echo 'start: ' . $page['url'] . "\n";
        $fp = fopen(getFileName($page['url'], $key), "w");
        echo ' time: ' . date('Y-m-d H:i:s') . "\n";
        $ch = curl_init($page['url']);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_USERAGENT, selectUserAgent($page['device']));
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        if(curl_error($ch)) {
            fwrite($fp, curl_error($ch));
        }
        curl_close($ch);
        fclose($fp);
        sleep(7);
    }
}

function selectUserAgent($device):string
{
    $arr = [
        'PC' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'SP' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36',
        '共通' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36',
        'レガシー' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; Tablet PC 2.0)',
    ];
    return $arr[$device] ?? 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.83 Mobile Safari/537.36';
}

function getFileName($url, $key):string
{
    //$urlからhttps://kc.local.com/ を削除
    $url = str_replace('https://kc.local.com/', '', $url);
    // /を_に変換
    $url = str_replace('/', '_', $url);
    // パラメータの値を削除
    $url = preg_replace('/\?.*/', '', $url);
    $filename = './result/' . $key .  $url . '.html';
    echo " make:" . $filename . "\n";
    return $filename;
}

main();
