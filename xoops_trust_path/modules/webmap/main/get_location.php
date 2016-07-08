<?php
// $Id: get_location.php,v 1.1.1.1 2009/02/23 03:26:42 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/class/template.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webmap_include_once('main/header.php');
webmap_include_once('class/main/get_location.php');

//=========================================================
// main
//=========================================================
$manage = webmap_main_get_location::getInstance(WEBMAP_DIRNAME);
$manage->main();
exit();
