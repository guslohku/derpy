<?php
/**
 * Helpers for theming, available for all themes in their template files and functions.php.
 * This file is included right before the themes own functions.php
 */
 

/**
* Print debuginformation from the framework.
*/
function get_debug() {
      $de = CDerpy::Instance(); 
      $html = null;
      if(isset($de->config['debug']['db-num-queries']) && $de->config['debug']['db-num-queries'] && isset($de->db)) {
        $html .= "<p>Database made " . $de->db->GetNumQueries() . " queries.</p>";
      }   
      if(isset($de->config['debug']['db-queries']) && $de->config['debug']['db-queries'] && isset($de->db)) {
        $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $de->db->GetQueries()) . "</pre>";
      }   
      if(isset($de->config['debug']['dedia']) && $de->config['debug']['dedia']) {
        $html .= "<hr><h3>Debuginformation</h3><p>The content of CDerpy:</p><pre>" . htmlent(print_r($de, true)) . "</pre>";
      }   
      return $html;
}


/**
 * Prepend the base_url.
 */
function base_url($url) {
  return $de->request->base_url . trim($url, '/');
}


/**
 * Return the current url.
 */
function current_url() {
  return $de->request->current_url;
}

    /**
    * Render all views.
    */
    function render_views() {
      return CDerpy::Instance()->views->Render();
    }
    
/**
* Get messages stored in flash-session.
*/
function get_messages_from_session() {
  $messages = CDerpy::Instance()->session->GetMessages();
  $html = null;
  if(!empty($messages)) {
    foreach($messages as $val) {
      $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
      $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
      $html .= "<div class='$class'>{$val['message']}</div>\n";
    }
  }
  return $html;
}


