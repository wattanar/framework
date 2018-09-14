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
	return $next($request, $response);
};

// Check capability for access this url
$access = function ($cap_slug) use ($authUser) {
	return function ($request, $response, $next) use ($cap_slug, $authUser) {

		$checkCap = $authUser->accessPage($cap_slug);

		if ($checkCap['result'] === false) {
			return $response->withRedirect('/user/unauthorize', 301);
		} else {
			return $next($request, $response);
		}
	};
};