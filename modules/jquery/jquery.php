<?php
class module_jquery {

    public static function hook_module_before() {
        tpl::addJS('jquery', 'jquery.min');
    }
    
}
