<?php
// ---------------------------------------------------------------------------------------
//
// PHASE: BOOTSTRAP
//
define('DERPY_INSTALL_PATH', dirname(__FILE__));
define('DERPY_SITE_PATH', DERPY_INSTALL_PATH . '/application');

require(DERPY_INSTALL_PATH.'/src/bootstrap.php');

$de = CDerpy::Instance();


// ---------------------------------------------------------------------------------------
//
// PHASE: FRONTCONTROLLER ROUTE
//
$de->FrontControllerRoute();


// ---------------------------------------------------------------------------------------
//
// PHASE: THEME ENGINE RENDER
//
$de->ThemeEngineRender();
