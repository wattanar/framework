<?php

$app->group('/user', function () use ($auth, $container, $app) {

  $app->get('/login', 'App\User\UserController:userLogin');
  $app->get('/logout', 'App\User\UserController:userLogout')->add($auth);
  $app->post('/auth', 'App\User\UserController:userAuth')->add($container->get('csrf'));
  $app->get('/profile', 'App\User\UserController:userProfile')->add($auth);
  
  $app->post('/profile', 'App\User\UserController:userUpdateProfile')
    ->add($auth)
    ->add($container->get('csrf'));

  $app->get('/change_password', 'App\User\UserController:userChangePassword')->add($auth);
  
  $app
    ->post('/change_password', 'App\User\UserController:userUpdatePassword')
    ->add($auth)
    ->add($container->get('csrf'));
  
  $app->get('/roles', 'App\User\UserController:roles')->add($auth);
  $app->get('/capabilities', 'App\User\UserController:capabilities')->add($auth);

  $app->get('/all', 'App\User\UserController:allUsers')->add($auth);
  $app->get('/unauthorize', 'App\User\UserController:unauthorizePage');

});


$app->group('/api/v1', function() use ($auth, $app) {

  $app->get('/roles', 'App\User\UserController:getRoles')->add($auth);
  $app->get('/roles_active', 'App\User\UserController:getRolesActive')->add($auth);
  $app->post('/roles/create', 'App\User\UserController:createRoles')->add($auth);
  $app->post('/roles/edit', 'App\User\UserController:editRoles')->add($auth);
  $app->post('/roles/delete', 'App\User\UserController:deleteRoles')->add($auth);
  $app->post('/roles/update_capabilities', 'App\User\UserController:updateCapabilities')->add($auth);
  $app->get('/roles/capabilities_by_role/{role_id}', 'App\User\UserController:getCapabilitiesByRoles')->add($auth);

  $app->get('/capabilities', 'App\User\UserController:getCapabilities')->add($auth);
  $app->get('/capabilities_active', 'App\User\UserController:getCapabilitiesActive')->add($auth);
  $app->post('/capabilities/create', 'App\User\UserController:createCapabilities')->add($auth);
  $app->post('/capabilities/edit', 'App\User\UserController:editCapabilities')->add($auth);
  $app->post('/capabilities/delete', 'App\User\UserController:deleteCapabilities')->add($auth);

  $app->get('/users', 'App\User\UserController:getAllUsers')->add($auth);
  $app->post('/users/create', 'App\User\UserController:createUser')->add($auth);
  $app->post('/users/edit', 'App\User\UserController:editUsers')->add($auth);
  $app->post('/users/update_roles', 'App\User\UserController:updateRoles')->add($auth);

  $app->post('/users/reset_password', 'App\User\UserController:resetPassworrd')->add($auth);

});