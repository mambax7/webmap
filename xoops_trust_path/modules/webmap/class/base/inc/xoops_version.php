<?php
// $Id: xoops_version.php,v 1.1.1.1 2009/02/23 03:26:45 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_inc_xoops_version
//=========================================================
class webmap_base_inc_xoops_version
{
    public $_module_mid      = 0;
    public $_is_module_admin = false;

    public $_DIRNAME;
    public $_MODULE_URL;
    public $_MODULE_DIR;

    public $_HAS_ONINSATLL    = true;
    public $_HAS_MAIN         = false;
    public $_HAS_ADMIN        = false;
    public $_HAS_SEARCH       = false;
    public $_HAS_COMMENTS     = false;
    public $_HAS_SUB          = false;
    public $_HAS_BLOCKS       = false;
    public $_HAS_CONFIG       = false;
    public $_HAS_NOTIFICATION = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_DIRNAME    = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        $this->_module_mid      = $this->get_module_mid_by_dirname($dirname);
        $this->_is_module_admin = $this->get_user_is_module_admin($this->_module_mid);
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function build_modversion()
    {
        $this->modify_config_title_length();

        $arr = $this->build_basic();

        if ($this->_HAS_MAIN) {
            $arr['hasMain'] = 1;
        } else {
            $arr['hasMain'] = 0;
        }

        if ($this->_HAS_ADMIN) {
            $arr['hasAdmin']   = 1;
            $arr['adminindex'] = 'admin/index.php';
            $arr['adminmenu']  = 'admin/menu.php';
        } else {
            $arr['hasAdmin'] = 0;
        }

        if ($this->_HAS_SEARCH) {
            $arr['hasSearch'] = 1;
            $arr['search']    = $this->build_search();
        } else {
            $arr['hasSearch'] = 0;
        }

        if ($this->_HAS_COMMENTS) {
            $arr['hasComments'] = 1;
            $arr['comments']    = $this->build_comments();
        } else {
            $arr['hasComments'] = 0;
        }

        if ($this->_HAS_NOTIFICATION) {
            $arr['hasNotification'] = 1;
            $arr['notification']    = $this->build_notification();
        } else {
            $arr['hasNotification'] = 0;
        }

        if ($this->_HAS_SUB) {
            $arr['sub'] = $this->build_sub();
        }

        if ($this->_HAS_BLOCKS) {
            $arr['blocks'] = $this->build_blocks();
        }

        if ($this->_HAS_CONFIG) {
            $arr['config'] = $this->build_config();
        }

        if ($this->_HAS_ONINSATLL) {
            $arr['onInstall']   = 'include/oninstall.inc.php';
            $arr['onUpdate']    = 'include/oninstall.inc.php';
            $arr['onUninstall'] = 'include/oninstall.inc.php';
        }

        return $arr;
    }

    public function modify_config_title_length()
    {
        if (!$this->_is_module_admin) {
            return;
        }
        if (!$this->check_post_fct_modulesadmin()) {
            return;
        }
        if (!$this->check_post_dirname()) {
            return;
        }

        $config = new webmap_base_xoops_config_modify($this->_DIRNAME);
        $config->modify_title_length();
    }

    public function check_post_fct_modulesadmin()
    {
        if (isset($_POST['fct']) && ($_POST['fct'] == 'modulesadmin')) {
            return true;
        }
        return false;
    }

    public function check_post_dirname()
    {
        if (isset($_POST['dirname']) && ($_POST['dirname'] == $this->_DIRNAME)) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // Basic Defintion
    //---------------------------------------------------------
    public function build_basic()
    {
        $module_icon = 'module_icon.php';
        if (file_exists($this->_MODULE_DIR . '/images/module_icon.png')) {
            $module_icon = 'images/module_icon.png';
        }

        $arr = array();

        $arr['name']        = $this->lang('NAME') . '(' . $this->_DIRNAME . ')';
        $arr['description'] = $this->lang('DESC');
        $arr['author']      = 'Kenichi Ohwada';
        $arr['credits']     = 'Kenichi Ohwada';
        $arr['help']        = '';
        $arr['license']     = 'GPL see LICENSE';
        $arr['official']    = 0;
        $arr['image']       = $module_icon;
        $arr['dirname']     = $this->_DIRNAME;
        $arr['version']     = $this->get_version();

        // Any tables can't be touched by modulesadmin.
        $arr['sqlfile'] = false;
        $arr['tables']  = array();

        return $arr;
    }

    public function get_version()
    {
        return null;
    }

    //---------------------------------------------------------
    // Search
    //---------------------------------------------------------
    public function build_search()
    {
        $arr         = array();
        $arr['file'] = 'include/search.inc.php';
        $arr['func'] = $this->_DIRNAME . '_search';
        return $arr;
    }

    //---------------------------------------------------------
    // Comments
    //---------------------------------------------------------
    public function build_comments()
    {
        $arr = array();

        // Comments
        $arr['pageName'] = 'index.php';
        $arr['itemName'] = 'item_id';

        // Comment callback functions
        $arr['callbackFile']        = 'include/comment.inc.php';
        $arr['callback']['approve'] = $this->_DIRNAME . '_comments_approve';
        $arr['callback']['update']  = $this->_DIRNAME . '_comments_update';

        return $arr;
    }

    //---------------------------------------------------------
    // Notification
    //---------------------------------------------------------
    public function build_notification()
    {
        // dummy
    }

    //---------------------------------------------------------
    // Sub Menu
    //---------------------------------------------------------
    public function build_sub()
    {
        // dummy
    }

    //---------------------------------------------------------
    // Blocks
    //---------------------------------------------------------
    public function build_blocks()
    {
        // dummy
    }

    public function build_keep_blocks($blocks)
    {
        if (!$this->_is_module_admin) {
            return;
        }
        if (!$this->check_post_fct_modulesadmin()) {
            return;
        }
        if (!$this->check_post_dirname()) {
            return;
        }
        if (defined('XOOPS_CUBE_LEGACY')) {
            return;
        }
        if (substr(XOOPS_VERSION, 6, 3) >= 2.1) {
            return;
        }
        if ($_POST['op'] != 'update_ok') {
            return;
        }

        $block = new webmap_base_xoops_block($this->_DIRNAME);
        $block->keep_option($blocks);
    }

    //---------------------------------------------------------
    // Config
    //---------------------------------------------------------
    public function build_config()
    {
        // dummy
    }

    //---------------------------------------------------------
    // langauge
    //---------------------------------------------------------
    public function lang($name)
    {
        return constant($this->lang_name($name));
    }

    public function lang_name($name)
    {
        return strtoupper('_MI_' . $this->_DIRNAME . '_' . $name);
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function get_user_is_module_admin($mid)
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($mid)) {
                return true;
            }
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
