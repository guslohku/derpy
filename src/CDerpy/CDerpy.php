<?php
class CDerpy implements ISingleton {

	private static $instance = null;
        public $config = null;
        public $request = null;
        public $data = null;
        public $db = null;
        public $views;
        public $session;

	/**
	 * Constructor
	 */
	 protected function __construct() {
		$de = &$this;				
		require(DERPY_SITE_PATH.'/config.php');
		session_name($this->config['session_name']);
                session_start();
                $this->session = new CSession($this->config['session_key']);
                $this->session->PopulateFromSession();
                
		if(isset($this->config['database'][0]['dsn'])) {
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
        	}
        	$this->views = new CViewContainer();
	} 
  
	/**
	 * Singleton pattern. Get the instance of the latest created object or create a new one. 
	 * 
	 */
	 public static function Instance() {
		if(self::$instance == null) {
			self::$instance = new CDerpy();
		}
		return self::$instance;
	}	

	/**
	 * Frontcontroller, check url and route to controllers.
	 */
	 public function FrontControllerRoute() {
	    
	    // Take current url and divide it in controller, method and parameters
	    $this->request = new CRequest($this->config['url_type']);
	    $this->request->Init($this->config['base_url']);
	    $controller = $this->request->controller;
	    $method     = $this->request->method;
	    $arguments  = $this->request->arguments;
	    
	    // Is the controller enabled in config.php?
	    $controllerExists 	= isset($this->config['controllers'][$controller]);
	    $controllerEnabled 	= false;
	    $className			    = false;
	    $classExists 		    = false;
	    $formattedMethod = str_replace(array('_', '-'), '', $method);
	
	    if($controllerExists) {
	      $controllerEnabled 	= ($this->config['controllers'][$controller]['enabled'] == true);
	      $className					= $this->config['controllers'][$controller]['class'];
	      $classExists 		    = class_exists($className);
	    }
	    
	    // Check if controller has a callable method in the controller class, if then call it
	    if($controllerExists && $controllerEnabled && $classExists) {
	      $rc = new ReflectionClass($className);
	      if($rc->implementsInterface('IController')) {
		if($rc->hasMethod($method)) {
		  $controllerObj = $rc->newInstance();
		  $methodObj = $rc->getMethod($method);
		  if($methodObj->isPublic()) {
		    $methodObj->invokeArgs($controllerObj, $arguments);
		  } else {
		    die("404. " . get_class() . ' error: Controller method not public.');          
		  }
		} else {
		  die("404. " . get_class() . ' error: Controller does not contain method.');
		}
	      } else {
		die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
	      }
	    } 
	    else { 
	      die('404. Page is not found.');
	    }
  }
  
  
	/**
	 * ThemeEngineRender, renders the repde of the request to HTML or whatever.
	 */
	  public function ThemeEngineRender() {
	    // Get the paths and settings for the theme
	    $themeName 	= $this->config['theme']['name'];
	    $themePath 	= DERPY_INSTALL_PATH . "/themes/{$themeName}";
	    $themeUrl		= $this->request->base_url . "themes/{$themeName}";
	    
	    // Add stylesheet path to the $de->data array
	    $this->data['stylesheet'] = "{$themeUrl}/style.css";
	
	    // Include the global functions.php and the functions.php that are part of the theme
	    $de = &$this;
	    include(DERPY_INSTALL_PATH . '/themes/functions.php');
	    $functionsPath = "{$themePath}/functions.php";
	    if(is_file($functionsPath)) {
	      include $functionsPath;
	    }
	
	    // Extract $ly->data and $ly->view->data to own variables and handover to the template file
	    extract($this->data);     
	    extract($this->views->GetData());     
	    include("{$themePath}/default.tpl.php");
	  }

}