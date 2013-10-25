<?php

    class CObject {

       /**
        * Members
        */
       public $config;
       public $request;
       public $data;
       public $db;
       public $views;
       public $session;
       

       /**
        * Constructor
        */
       protected function __construct() {
        $de = CDerpy::Instance();
        $this->config   = &$de->config;
        $this->request  = &$de->request;
        $this->data     = &$de->data;
        $this->db       = &$de->db;
        $this->views 	= &$de->views;
        $this->session  = &$de->session;
      }
      
        /**
         * Redirect to another url and store the session
         */
        protected function RedirectTo($url) {
    $de = CDerpy::Instance();
    if(isset($de->config['debug']['db-num-queries']) && $de->config['debug']['db-num-queries'] && isset($de->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }
    if(isset($de->config['debug']['db-queries']) && $de->config['debug']['db-queries'] && isset($de->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }
    if(isset($de->config['debug']['timer']) && $de->config['debug']['timer']) {
         $this->session->SetFlash('timer', $de->timer);
    }
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUrl($url));
  }

    }