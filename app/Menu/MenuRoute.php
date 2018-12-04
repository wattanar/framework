<?php

$app->get('/menu', 'App\Menu\MenuController:index')
  ->add($auth);

$app->group('/api/v1', function () use ($app, $auth, $accessPage) {
  $app->get('/menu', 'App\Menu\MenuController:getMenu')->add($auth);
  $app->post('/menu/create', 'App\Menu\MenuController:createMenu')->add($auth);
  $app->post('/menu/edit', 'App\Menu\MenuController:editMenu')->add($auth);
  $app->post('/menu/delete', 'App\Menu\MenuController:deleteMenu')->add($auth);
  $app->get('/memu/generate', 'App\Menu\MenuController:generateMenu')->add($auth);
  $app->post('/menu/update_capabilities', 'App\Menu\MenuController:updateCapabilities')->add($auth);
});