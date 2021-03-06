 <?php
    class Sis_Plugin_PrepareNavigation extends Zend_Controller_Plugin_Abstract
    {
    	
    	
        public function routeShutdown(Zend_Controller_Request_Abstract $request)
        {

            $viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
            $viewRenderer->initView();
             $view = $viewRenderer->view;
          
            $container = new Zend_Navigation(Zend_Registry::get('configuration')->navigation);
            $container = new Zend_Navigation();
            $this->view->navigation($container); 
            $page = $this->navigation()->findOneBy('controller', 'home');
            echo $this->navigation()->htmlify($page);
            die();
 
            foreach ($container->getPages() as $page) {
                $uri = $page->getHref();
                
                if ($uri === $request->getRequestUri()) {
                	
                    $page->setClass('active');
                }
            }
            $view->navigation($container);
        }
    }

