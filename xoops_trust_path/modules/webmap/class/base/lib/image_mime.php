<?php
// $Id: image_mime.php,v 1.1.1.1 2009/02/23 03:26:47 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_lib_image_mime
//=========================================================
class webmap_base_lib_image_mime
{
    public $_EXTS  = array('gif', 'jpg', 'jpeg', 'png');
    public $_MIMES = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        //
    }

    //---------------------------------------------------------
    // get param
    //---------------------------------------------------------
    public function get_exts()
    {
        return $this->_EXTS;
    }

    public function get_mimes()
    {
        return $this->_MIMES;
    }

    // --- class end ---
}
