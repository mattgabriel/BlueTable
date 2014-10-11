<?php

abstract class controllerObject {
    protected $_componentName;
    protected $_view;


    protected abstract function _setComponentName();
    
    static function __callStatic($name, $arguments) {
        $return = false;
        $class = get_called_class();

        if ($name == '_'.$class)
        {
            $return = new $class($arguments);
        }
        return $return;
    }
    
    private function _loadConstants()
    {
        $obj = new ReflectionClass($this);
        $fn = $obj->getFileName();  
        if($fn)
        {
            define('PAGE_NAME', basename($fn, '.php'));
            define('PAGE_PATH', substr($fn,strrpos($fn,APP_PATH.'controllers') + strlen(APP_PATH.'controllers'), -strlen('.php')));
            define('VIEW_DIR', substr(str_replace('controllers','views',substr($fn,strrpos($fn,APP_PATH.'controllers'))), 0, -strlen('.php')));
            define('VIEW_NAME', get_called_class());
        }
        return $fn;
    }
    
    private function _loadView()
    {
        $viewCodeClass = VIEW_NAME . 'Code';
        
        if(file_exists(VIEW_DIR.'/'.$viewCodeClass.'.php'))
        {
            require_once VIEW_DIR.'/'.$viewCodeClass.'.php';
            $this->_view = new $viewCodeClass();
        }
        else
            $this->_view = new View(VIEW_DIR.'/'.VIEW_NAME.'.php');
    }
    
    public function __construct() {
        $this->_loadConstants();
        $this->_setComponentName();
        $this->_loadView();
    }
}
