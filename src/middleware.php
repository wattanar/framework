<?php

use App\Auth\AuthAPI;
use Core\Flash;

$authUser = new AuthAPI;

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

// Check if user auth or not
$auth = function ($request, $response, $next) use ($authUser) {

	$token = $authUser->verifyToken();

	if ( $token['result'] === false ) {
		Flash::addMessage('error', 'Please login!');
		return $response->withRedirect('/user/login', 301);
	}

	// check use can access this page.
	// $userCanAccess = $authUser->accessPage($token['payload']['user_data']->role);

	return $next($request, $response);
};

$accessPage = function($request, $response, $next) use ($authUser) {

	$token = $authUser->verifyToken();

	if ( $token['result'] === false ) {
		Flash::addMessage('error', 'Please login!');
		return $response->withRedirect('/user/login', 301);
	}

	$userCanAccess = $authUser->accessPage($token['payload']['user_data']->role);

	if ( $userCanAccess['result'] === false ) {
		return $response->withRedirect('/user/unauthorize', 301);
	}

	return $next($request, $response);
};