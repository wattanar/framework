<?php

use App\User\UserAPI;
use Core\Flash;

$user = new UserAPI;

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$auth = function ($request, $response, $next) use ($user) {

	$token = $user->verifyToken();

	if ( $token['result'] === false ) {
		Flash::addMessage('error', 'Please login!');
		return $response->withRedirect('/user/login', 301);
	}

	return $next($request, $response);
};