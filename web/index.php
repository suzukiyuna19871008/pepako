<?php
error_log('fbdev callback!');
$access_token = "EAAJsajacL7QBAJFCL9qKYUVNKPYNwjiIRL5uILcnpi2wGNcCFhlg80FTlQUllNJ0Bj7TpAuGbZBTZCSsXW4sCD6la2mZCosSRPUh6odZCXVUoq2l0tEhiQURpCyIic0XPh1cj3531MDd6nWZCVRySr3bfM1brtG4lXlxhVcKXeJHONvnVZCNk5";
// メッセージ受信
$json_string = file_get_contents('php://input');
error_log($json_string);
$json_object = json_decode($json_string);
$from_user_id =$json_object->entry{0}->messaging{0}->sender->id;
$post = <<< EOM
{
    "sender":{
        "id":"USER_ID"
    },

    "recipient":{
        "id":"{$from_user_id}"
    },
    //YS added for persistant function
    "postback":{
    "payload":"USER_DEFINED_PAYLOAD"
    }


//YS added persistant function
curl -X POST -H "Content-Type: application/json" -d '{
  "setting_type" : "call_to_actions",
  "thread_state" : "existing_thread",
  "call_to_actions":[
    {
      "type":"postback",
      "title":"Help",
      "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_HELP"
    },
    {
      "type":"postback",
      "title":"Start a New Order",
      "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_START_ORDER"
    },
    {
      "type":"web_url",
      "title":"Checkout",
      "url":"http://petersapparel.parseapp.com/checkout",
      "webview_height_ratio": "full",
      "messenger_extensions": true
    },
    {
      "type":"web_url",
      "title":"View Website",
      "url":"http://petersapparel.parseapp.com/"
    }
  ]
}' "https://graph.facebook.com/v2.6/me/thread_settings?access_token=PAGE_ACCESS_TOKEN"    

    


    /*"message":{
        "attachment":{
            "type":"template",
            "payload":{
                "template_type":"generic",
                "elements":[
                    {
                        "title":"あなたのリクエストを教えて！",
                        "image_url":"http://hiroki-suzuki.com/wp-content/uploads/2015/06/pepper.jpg",
                        "subtitle":"ペパ子はあなたのコンシェルジュです。",
                        "buttons":[
                            {
                                "type":"web_url",
                                "url":"https://messengerplatform.fb.com",
                                "title":"View Item"
                            },
                            {
                                "type":"web_url",
                                "url":"https://developers.facebook.com/docs/messenger-platform",
                                "title":"Buy Item"
                            },
                            {
                                "type":"postback",
                                "title":"Bookmark Item",
                                "payload":"USER_DEFINED_PAYLOAD_FOR_ITEM100"
                            }                            
                        ]
                    }
                ]
            }
        }
    },*/
}
EOM;
// メッセージ送信
api_send_request($access_token, $post);
function api_send_request($access_token, $post) {
    $url = "https://graph.facebook.com/v2.6/me/messages?access_token={$access_token}";
    $headers = array(
        "Content-Type: application/json"
    );
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($curl);
}
