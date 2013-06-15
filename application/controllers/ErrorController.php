<?php
/**
 * Error Controller
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class ErrorController extends Zend_Controller_Action {

    public function errorAction ()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                $this->view->type = 404;
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                $this->view->type = 500;
                break;
        }
        
        if (Utils::getSettings('firebug', false)) {
            $fbLog = new Zend_Log(new Zend_Log_Writer_Firebug());
            $fbLog->crit($errors->exception);
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request = $errors->request;
    }

    private function log($item) {
        error_log(var_export($item, true));
    }
    
    public function getLog ()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (! $bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
