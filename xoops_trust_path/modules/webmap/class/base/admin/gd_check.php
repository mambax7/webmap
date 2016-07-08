<?php
// $Id: gd_check.php,v 1.1.1.1 2009/02/23 03:26:45 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_admin_gd_check
//=========================================================
class webmap_base_admin_gd_check
{
    public $_lang_success = 'Success';
    public $_lang_failed  = 'Failed';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function main()
    {
        xoops_cp_header();

        restore_error_handler();
        error_reporting(E_ALL);

        if (imagecreatetruecolor(200, 200)) {
            echo $this->_lang_success;
        } else {
            echo $this->_lang_failed;
        }

        echo "<br /><br />\n";
        echo '<input class="formButton" value="' . _CLOSE . '" type="button" onclick="javascript:window.close();" />';

        xoops_cp_footer();
    }

    public function set_lang_success($val)
    {
        $this->_lang_success = $val;
    }

    public function set_lang_failed($val)
    {
        $this->_lang_failed = $val;
    }

    // --- class end ---
}
