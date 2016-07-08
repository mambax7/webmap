<?php
// $Id: config_dirname.php,v 1.1.1.1 2009/02/23 03:26:44 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_xoops_config_dirname
//=========================================================
class webmap_base_xoops_config_dirname extends webmap_base_xoops_config
{
    public $_cached = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        parent::__construct();

        $config = $this->get_config_by_dirname($dirname);
        if (is_array($config)) {
            $this->_cached = $config;
        }
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function get_value_by_name($name)
    {
        if (isset($this->_cached[$name])) {
            return $this->_cached[$name];
        }
        return false;
    }

    // --- class end ---
}
