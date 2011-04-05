<?php
class tpl {
    private $tplName;
    private $moduleName;
    private $vars = array();

    public function  __construct($tplName, $moduleName=false) {
        $this->tplName = $tplName;
        if(!$moduleName)
            $this->moduleName = utils::current('module');
        else
            $this->moduleName = $moduleName;
    }

    public function set($name, $value=false) {
        // check if array
        if(!is_array($name) & !$value) {
            // assign var to array
            $this->vars[$name] = $value;
        } else {
            // assign all array vars
            foreach($name as $name => $value) {
                $this->vars[$name] = $value;
            }
        }
    }

    public function parse() {
        // start output
        ob_start();

        // go through all vars
        foreach($this->vars as $tplVarName => $tplVarValue) {
            $$tplVarName = $tplVarValue;
        }

        // load template
        include 'modules/'.$this->moduleName.'/tpl/'.$this->tplName.'.tpl.php';

        // give all output
        return ob_get_clean();
    }
}