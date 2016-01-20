<?php

class AndroidNotification {

	public static function send($registation_ids, $message) {
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $registation_ids,
			'data' => array('json' => $message),
		);
		$headers = array(
			'Authorization:key=' . Config::get('GOOGLE_API_KEY'),
			'Content-Type:application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
		$result = curl_exec($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($result === FALSE) {
			return array(
				'response_code' => 'error',
				'result' => $curl_error
			);
		}

		return array(
			'response_code' => 'ok',
			'result' => json_decode($result, true)
		);
	}

}
