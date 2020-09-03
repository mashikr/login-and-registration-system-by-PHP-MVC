<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller {

    protected function before() {
        //echo "(before)";
        //return false;
    }

    protected function after() {
        //echo "(after)";
    }

    public function indexAction() {
        //\App\Mail::send('test@test.io', 'Test', 'This is test msg', '<b>This is test msg</b>');
        View::renderTemplate('Home/index.html', [
            'page' => 'home'
        ]);
    }
}

?>