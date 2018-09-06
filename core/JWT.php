<?php

namespace Core;

use \Firebase\JWT\JWT as FirebaseJWT;

class JWT 
{
  public static function createToken(array $payload = []) {

		$data = [
			'typ'=> 'JWT',
			'nbf' => time(),
			'exp' => time() + (24*60*60),
			'user_data' => $payload
		];

		return FirebaseJWT::encode($data, APP_KEY);
	}

	public static function validateToken($token) {

		try {
			$payload = (array)FirebaseJWT::decode($token, APP_KEY, array('HS256'));
			return [
				'result' => true,
				'message' => 'token valid',
				'payload' => $payload,
			];
		} catch (\Exception $e) {
			return [
				'result' => false,
				'message' => 'token invalid',
				'payload' => []
			];
		}
	}
}