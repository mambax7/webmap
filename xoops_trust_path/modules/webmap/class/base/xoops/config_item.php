<?php
// $Id: config_item.php,v 1.1.1.1 2009/02/23 03:26:44 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_xoops_config_item
//=========================================================
class webmap_base_xoops_config_item
{
    public $_config_handler;
    public $_module_mid = 0;
    public $_conf_objs  = array();

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_config_handler = xoops_getHandler('ConfigItem');
        $this->_module_mid     = $this->get_module_mid_by_dirname($dirname);
    }

    //---------------------------------------------------------
    // save module config
    //---------------------------------------------------------
    public function get_objects()
    {
        $this->_conf_objs = array();

        $criteria = new CriteriaCompo(new Criteria('conf_modid', $this->_module_mid));
        $objs     = $this->_config_handler->getObjects($criteria);

        if (is_array($objs)) {
            foreach ($objs as $obj) {
                $this->_conf_objs[$obj->getVar('conf_name')] = $obj;
            }
        }
    }

    public function save($name, $val)
    {
        $obj = $this->get_obj($name);
        if (is_object($obj)) {
            $obj->setVar('conf_value', $val);
            $this->_config_handler->insert($obj);
        }
    }

    public function get_obj($name)
    {
        $ret = false;
        if (isset($this->_conf_objs[$name])) {
            return $this->_conf_objs[$name];
        }
        return false;
    }

    //---------------------------------------------------------
    // module handler
    //---------------------------------------------------------
    public function get_module_mid_by_dirname($dirname)
    {
        $module_handler = xoops_getHandler('module');
        $module         = $module_handler->getByDirname($dirname);
        if (is_object($module)) {
            return $module->getVar('mid');
        }
        return 0;
    }

    // --- class end ---
}
