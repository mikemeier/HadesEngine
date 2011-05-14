<?php
class home {

    public static function action_main() {
        // set title of the page in front of the site name
        tpl::title(lang::get('homeWelcome').' - ');
        // load template
        $tpl = new tpl('home');
        echo $tpl->parse();
        
        /*$form = new htmlform('test');
        $form->addDateSelect('test', array(
            'minYear' => 1900,
            'maxYear' => 2011
        ));
        $form->addTextArea('desc', 'blah blah blah');
        echo $form->build();*/
        module::callHook('welcome');
    }

}
