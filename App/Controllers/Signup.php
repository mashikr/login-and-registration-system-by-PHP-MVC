<?php

namespace App\Controllers;

use \Core\View;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{
  /**
   * Show the signup page
   *
   * @return void
   */
  public function newAction()
  {
      View::renderTemplate('Signup/new.html');
  }
}