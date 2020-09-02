<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{
  /**
   * Show the signup page
   *
   * @return void
   */
  public function loginAction() {
      View::renderTemplate('Login/login.html',[
        'page' => 'login'
    ]);
  }

  public function newAction() {
      $user = User::authenticate($_POST['email'], $_POST['password']);
      $remember_me = '';
      if ($user) {
          session_regenerate_id(true);
          $_SESSION['user_id'] = $user->id;
          $_SESSION['username'] = $user->username;

          $remember_me = isset($_POST['remember_me']);
          User::rememberLogin($user->id, $user->username);

          Flash::addMessage('Successfully Login!', 'success');
          $this->redirect('/');
      } else {
        Flash::addMessage('Email or password is wrong!', 'error');
        View::renderTemplate('Login/login.html',[
            'page' => 'login',
            'email' => $_POST['email'],
            'remember_me' => $remember_me
        ]);
      }
  }

  public function logoutAction() {
    $_SESSION = [];

    if (ini_get('session.use.cookies')) {
      $params = session_get_cookie_params();

      setcookie(
        session_name(),
        '',
        time() - 4200,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
      );
    }

    session_destroy();

    User::forgetLogin();

    $this->redirect('/');
  }
}
