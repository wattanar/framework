<?php

$app->get('/', 'App\Landing\LandingController:demo')
  ->add($auth)
  ->add($access('cap_1'));