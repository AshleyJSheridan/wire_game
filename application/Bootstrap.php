<?php

/**
 * Bootstrap
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    
    /**
     * Initialize routing
     *
     * @return Zend_Controller_Router_Rewrite
     */
    
    public function _initRouter() {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routes');
        $router->addConfig($config); 

        return $router;
    }
    
    public function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }    
    
    protected function _initLogger() {
        if (Utils::getSettings('firebug', false)) {
            $logger = new Zend_Log(new Zend_Log_Writer_Firebug());
            Zend_Registry::set('lgr', $logger);
        }
    }
    
    protected function _initDocType()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->doctype('HTML5');
    }

}
