<?php

require('../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

$app = new Silex\Application();

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

$app->before(function (Request $request) use($bot) {
    // TODO validation
});

$app->get('/callback', function (Request $request) use ($app) {
    $response = "";
    if ($request->query->get('hub_verify_token') === getenv('FACEBOOK_PAGE_VERIFY_TOKEN')) {
        $response = $request->query->get('hub_challenge');
    }

    return $response;
});

$app->post('/callback', function (Request $request) use ($app) {
    // Let's hack from here!
    // メッセージ受信
$json_string = file_get_contents('php://input');
error_log($json_string);
$json_object = json_decode($json_string);
$from_user_id =$json_object->entry{0}->messaging{0}->sender->id;

$post = <<< EOM
{
    "recipient":{
        "id":"{$from_user_id}"
    },
    "message":{
        "attachment":{
            "type":"template",
            "payload":{
                "template_type":"button",
                "text":"What do you want to do next?",
                "buttons":[
                    {
                        "type":"web_url",
                        "url":"https://messengerplatform.fb.com",
                        "title":"Show Website"
                    },
                    {
                        "type":"postback",
                        "title":"Start Chatting",
                        "payload":"USER_DEFINED_PAYLOAD"
                    }
                ]
            }
        }
    }
}
EOM;

    
    
    
    /*
    $body = json_decode($request->getContent(), true);
    $client = new Client(['base_uri' => 'https://graph.facebook.com/v2.6/']);

    foreach ($body['entry'] as $obj) {
        $app['monolog']->addInfo(sprintf('obj: %s', json_encode($obj)));

        foreach ($obj['messaging'] as $m) {
            $app['monolog']->addInfo(sprintf('messaging: %s', json_encode($m)));
            $from = $m['sender']['id'];
            $text = $m['message']['text'];

            if ($text) {
                $path = sprintf('me/messages?access_token=%s', getenv('FACEBOOK_PAGE_ACCESS_TOKEN'));
                $json = [
                    'recipient' => [
                        'id' => $from,
                    ],
                    'message' => [
                        'text' => sprintf('%sじゃないかも', $text),
 
                    ],
                ];
                $client->request('POST', $path, ['json' => $json]);*/
            }
        }

    }

    return 0;
});

$app->run();
