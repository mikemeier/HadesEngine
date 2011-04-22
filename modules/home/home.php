<?php
class home {

    public static function action_main() {
        // set title of the page in front of the site name
        /*tpl::title(lang::get('homeWelcome').' - ');*/
        // load template
        /*$tpl = new tpl('home');
        echo $tpl->parse();*/
        $form = new htmlform('test');
        $form->addDateSelect(array('name' => 'test', 'day' => array(), 'month' => array(), 'year' => array('min' => 1900, 'max' => 2011)));
        $form->addTextArea(array('name' => 'desc'));
        echo $form->build();
    }

}
