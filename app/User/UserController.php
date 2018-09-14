<?php

namespace App\User;

use Core\CSRF;
use Core\Render;
use Core\JWT;
use Core\Cookie;
use Core\Flash;
use Core\Response;
use Core\Validate;
use App\User\UserAPI;
use App\Auth\AuthAPI;

class UserController
{
  private $user = null;

  public function __construct() {
    $this->user = new UserAPI;
    $this->auth = new AuthAPI;
  }

  public function userProfile($request, $response, $args) {
    
    $csrf = CSRF::generate();

    $user_login = $this->auth->verifyToken();

    return Render::View('/pages/users/profile', [
      'name' => $csrf['name'],
			'value' => $csrf['value'],
      'key' => $csrf['key'],
      'user_data' => $this->user->getUserInfo($user_login['payload']['user_data']->username)
    ]);
  }

  public function userChangePassword($request, $response, $args) {
    
    $csrf = CSRF::generate();

    return Render::View('/pages/users/change_password', [
      'name' => $csrf['name'],
			'value' => $csrf['value'],
			'key' => $csrf['key']
    ]);
  }

  public function userLogin($request, $response, $args) {
    
    $csrf = CSRF::generate();

  	return Render::View('pages/users/login', [
      'name' => $csrf['name'],
			'value' => $csrf['value'],
			'key' => $csrf['key']
    ]);
  }

  public function userLogout($request, $response, $args) {

    if ( isset($_COOKIE[TOKEN_NAME]) ) {
      
      unset($_COOKIE[TOKEN_NAME]);
      Cookie::setCookie(TOKEN_NAME, null, -1);

      return $response->withRedirect('/', 302);
    } else {

      return $response->withRedirect('/', 302);
    }
  }

  public function userAuth($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    if (!isset($parsedBody['user_login']) || !isset($parsedBody['user_pass'])) {
      Flash::addMessage('error', 'Username or password incorrect!');
      return $response->withRedirect('/user/login', 302);
    }

    $user_auth = $this->user->userAuth($parsedBody['user_login'], $parsedBody['user_pass']);

    if ($user_auth['result'] === false) {
      Flash::addMessage('error', $user_auth['message']);
      return $response->withRedirect('/user/login', 302);
    }

    $userInfo = $this->user->getUserInfo($parsedBody['user_login']);

    $token = JWT::createToken([
      'username' => $userInfo[0]['user_login'],
      'role' => $userInfo[0]['user_role']
    ]);

    Cookie::setCookie(TOKEN_NAME, $token);

    return $response->withRedirect('/', 302);
  }

  public function verifyToken() {
    return $response->withJson($this->auth->verifyToken());
  }

  public function userUpdatePassword($request, $response, $args) {
    
    $parsedBody = $request->getParsedBody();

    $user_data = $this->auth->verifyToken();

    $checkOldPass = $this->user->userAuth(
      $user_data['payload']['user_data']->username, 
      $parsedBody['old_pass']
    );

    if ( $checkOldPass['result'] === false ) {
      Flash::addMessage('error', 'Old password incorrect!');
      return $response->withRedirect('/user/change_password', 302);
    }

    if ($parsedBody['new_pass'] !== $parsedBody['confirm_new_pass']) {
      Flash::addMessage('error', ' Password not match!');
      return $response->withRedirect('/user/change_password', 302);
    }

    $updatePassword = $this->user->updatePassword(
      $user_data['payload']['user_data']->username,
      $parsedBody['new_pass']
    );

    if ($updatePassword === true) {
      Flash::addMessage('success', 'Change password successful!');
      return $response->withRedirect('/user/change_password', 302);
    } else {
      Flash::addMessage('error', 'Change password failed!');
      return $response->withRedirect('/user/change_password', 302);
    }
  }

  public function userUpdateProfile($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    $updateData = [
      'email' => htmlspecialchars($parsedBody['user_email']),
      'firstname' => htmlspecialchars($parsedBody['user_firstname']),
      'lastname' => htmlspecialchars($parsedBody['user_lastname'])
    ];

    $updateProfile = $this->user->updateProfile(
      $parsedBody['user_login'],
      $updateData
    );

    if ($updateProfile === true) {
      Flash::addMessage('success', 'Update profile successful!');
      return $response->withRedirect('/user/profile', 302);
    } else {
      Flash::addMessage('error', 'Update profile failed!');
      return $response->withRedirect('/user/profile', 302);
    }
  }

  public function roles($request, $response, $args) {
    return Render::View('pages/users/roles');
  }

  public function getRoles($request, $response, $args) {
    return $response->withJson($this->user->getRoles());
  }

  public function getRolesActive($request, $response, $args) {
    return $response->withJson($this->user->getRolesActive());
  }

  public function getCapabilities($request, $response, $args) {
    return $response->withJson($this->user->getCapabilities());
  }

  public function getCapabilitiesActive($request, $response, $args) {
    return $response->withJson($this->user->getCapabilitiesActive());
  }

  public function createRoles($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    if (trim($parsedBody['role_name']) === '') {
          return $response->withJson([
            'result' => false,
            'message' => 'Unable to create role!'
          ]);
    }

    $create = $this->user->createRoles(
      $parsedBody['role_name']
    );

    if ($create['result'] === true) {
      return $response->withJson([
        'result' => true,
        'message' => 'Create role successful!'
      ]);
    } else {
      return $response->withJson([
        'result' => false,
        'message' => $create['message']
      ]);
    }
  }

  public function createCapabilities($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    if (trim($parsedBody['cap_slug']) === '' || trim($parsedBody['cap_name']) === '') {
          return $response->withJson([
            'result' => false,
            'message' => 'Unable to create capability!'
          ]);
    }

    $create = $this->user->createCapabilities(
      $parsedBody['cap_slug'], 
      $parsedBody['cap_name']
    );

    if ($create['result'] === true) {
      return $response->withJson([
        'result' => true,
        'message' => 'Create Capabilities successful!'
      ]);
    } else {
      return $response->withJson([
        'result' => false,
        'message' => 'Create Capabilities failed!'
      ]);
    }
  }

  public function editRoles($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if (trim($parsedBody['name']) === '') {
          return $response->withJson([
            'result' => false,
            'message' => 'Data incorrect!'
          ]);
    }

    $update = $this->user->editRoles(
      $parsedBody['id'],
      $parsedBody['name'],
      $parsedBody['status']
    );

    if ($update['result'] === true) {
      return $response->withJson([
        'result' => true,
        'message' => $update['message']
      ]);
    } else {
      return $response->withJson([
        'result' => false,
        'message' => $update['message']
      ]);
    }
  }

  public function editCapabilities($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if (trim($parsedBody['slug']) === '' ||
        trim($parsedBody['name']) === '') {
          return $response->withJson([
            'result' => false,
            'message' => 'Data incorrect!'
          ]);
    }

    $update = $this->user->editCapabilities(
      $parsedBody['id'],
      strtolower($parsedBody['slug']), 
      $parsedBody['name'],
      $parsedBody['status']
    );

    if ($update['result'] === true) {
      return $response->withJson([
        'result' => true,
        'message' => 'edit role successful!'
      ]);
    } else {
      return $response->withJson([
        'result' => false,
        'message' => $update['message']
      ]);
    }
  }

  public function capabilities($request, $response, $args) {
    return Render::View('pages/users/capabilities');
  }

  public function allUsers($request, $response, $args) {
    return Render::View('pages/users/users');
  }

  public function getAllUsers($request, $response, $args) {
    return $response->withJson($this->user->getAllUsers());
  }

  public function editUsers($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $update = $this->user->editUsers(
      \htmlspecialchars($parsedBody['id']),
      \htmlspecialchars($parsedBody['user_email']),
      \htmlspecialchars($parsedBody['user_status']),
      \htmlspecialchars($parsedBody['user_firstname']),
      \htmlspecialchars($parsedBody['user_lastname'])
    );

    return $response->withJson($update);
  }

  public function updateRoles($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $update = $this->user->updateRoles($parsedBody['user_id'], $parsedBody['role_id']);
    return $response->withJson($update);
  }

  public function resetPassworrd($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    $user_data = $this->auth->verifyToken();

    if ( $user_data['result'] === false ) {
      return Response::result(false, "You're not authorize!");
    }

    $updatePassword = $this->user->updatePassword(
      $user_data['payload']['user_data']->username,
      $parsedBody['new_password']
    );

    if ( $updatePassword === true) {
      return $response->withJson([
        'result' => true,
        'message' => 'Update successful!'
      ]);
    } else {
      return $response->withJson([
        'result' => false,
        'message' => 'Update failed!'
      ]);
    }
  }

  public function createUser($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    $create = $this->user->createUser(
      Validate::clean($parsedBody['user_login']),
      Validate::clean($parsedBody['user_password'])
    );

    if ($create['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $create['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => 'Create successful!'
      ]);
    }
  }

  public function updateCapabilities($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    if ( Validate::clean($parsedBody['role_id']) === null || $parsedBody['cap_id'] === '') {
      return $response->withJson([
        'result' => false,
        'message' => 'Unable to update!'
      ]);
    }

    $update = $this->user->updateCapabilities(
      $parsedBody['role_id'],
      $parsedBody['cap_id']
    );

    if ($update['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $update['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $update['message']
      ]);
    }
  }

  public function getCapabilitiesByRoles($request, $response, $args) {
    return $response->withJson(
      $this->user->getCapabilitiesByRoles(
        $args['role_id']
      )
    );
  }

  public function unauthorizePage($request, $response, $args) {
    return Render::View('pages/users/401');
  }

  public function deleteCapabilities($request, $response, $args) {

    $parsedBody = $request->getParsedBody();
    
    $delete = $this->user->deleteCapabilities(
      $parsedBody['cap_id']
    );

    if ($delete['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $delete['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $delete['message']
      ]);
    }
  }

  public function deleteRoles($request, $response, $args) {

    $parsedBody = $request->getParsedBody();

    $delete = $this->user->deleteRoles(
      $parsedBody['role_id']
    );

    if ($delete['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $delete['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $delete['message']
      ]);
    }
  }
}