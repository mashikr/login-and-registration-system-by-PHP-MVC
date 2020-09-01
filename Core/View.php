<?php

namespace Core;

class view {

    public static function render ($view, $args = []) {

        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            // echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $args = []) {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('username', \Core\Controller::username());
            $twig->addGlobal('flash_msg', \App\Flash::getMessage());
        }

        echo $twig->render($template, $args);
    }
}

?>