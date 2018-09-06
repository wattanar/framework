<?php

$app->get('/', 'App\Landing\LandingController:demo')->add($auth);