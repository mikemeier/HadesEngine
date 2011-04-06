<?php

class home {
    public static function main() {
        // set title of the page in front of the site name
        tpl::title('Willkommen - ');
        // load template
        $tpl = new tpl('home');
        echo $tpl->parse();
    }
}