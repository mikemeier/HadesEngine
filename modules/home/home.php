<?php

class home {
    public static function main() {
        // set title of the page in front of the site name
        tpl::title(lang::get('homeWelcome').' - ');
        // load template
        $tpl = new tpl('home');
        echo $tpl->parse();
    }
}