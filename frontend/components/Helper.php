<?php

namespace frontend\components;

use Yii;
use frontend\models\FcmPushLog;
use frontend\models\UserFcmToken;

class Helper{

    public static function pushNotification($tokens = [], $data = '', $clear_tokens = true){

    	$api_url = 'https://fcm.googleapis.com/fcm/send';

        if ($data && Yii::$app->params['pushEnabled'] && Yii::$app->params['pushServerKey']){

        	if ($data){

        		$fields = array(
		            'data' =>  $data
		        );

		        //send to specific devices
		        if (count($tokens) > 0){
		        	$fields["registration_ids"] = $tokens;
		        }
		        //send to topic/all (devices should be subscribed to topic/all)
		        else{
		        	$fields["to"] = "/topics/all";
		        }

		        $headers = array(
		            'Authorization:key ='.Yii::$app->params['pushServerKey'],
		            'Content-Type: application/json'
		        );

		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $api_url);
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		        $result = curl_exec($ch);
		        $curl_info = curl_getinfo($ch);

		        //log push data
		        $log = [];
		        $log["user_id"] = Yii::$app->user->identity->id;
		        $log["request"] = json_encode($fields);
		        if ($curl_info["http_code"] == 200){
		        	$log["response"] = $result;
		        }
		        else{
		        	$log["response"] = $curl_info["url"]."\nUnexpected HTTP code: ".$curl_info["http_code"];
				}

				//Save log
				//  echo "===".Yii::getAlias('@runtime').'/logs/push.log';
				//  exit;
				file_put_contents(Yii::getAlias('@runtime').'/logs/push.log', print_r($log, true), FILE_APPEND);

		        if ($curl_info["http_code"] == 200){

		        	//check and clear invalid tokens
			        if ($clear_tokens){

			        	$result_data = json_decode($result);

			        	if (method_exists($result_data, "failure") && $result_data->failure > 0){

							foreach($result_data->results as $key => $value){

			        			if (isset($value->error) && ($value->error == "InvalidRegistration" || $value->error == "NotRegistered")){

			        				//delete token
			        				UserFcmToken::deleteAll(['registration_token' => $tokens[$key]]);
			        			}
			        		}
			        	}
			        }

		        	return $result;
		        }
		        else{
		        	return false;
		        }
        	}
        }
    }
}