<?php
// $Id: form_lang.php,v 1.1.1.1 2009/02/23 03:26:46 ohwada Exp $

//=========================================================
// webbase module
// 2009-02-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webmap_base_lib_form_lang
//=========================================================
class webmap_base_lib_form_lang extends webmap_base_lib_form
{
    public $_post_class;
    public $_utility_class;
    public $_language_class;
    public $_xoops_class;

    // xoops param
    public $_is_login_user   = false;
    public $_is_module_admin = false;
    public $_xoops_language;
    public $_xoops_sitename;
    public $_xoops_uid       = 0;
    public $_xoops_uname     = null;
    public $_xoops_groups    = null;

    public $_DIRNAME       = null;
    public $_TRUST_DIRNAME = null;
    public $_MODULE_DIR;
    public $_MODULE_URL;
    public $_TRUST_DIR;

    public $_MODULE_NAME = null;
    public $_MODULE_ID   = 0;
    public $_TIME_START  = 0;

    public $_THIS_FCT_URL;

    public $_LANG_MUST_LOGIN = 'You must login';
    public $_LANG_TIME_SET   = 'Set Time';

    public $_FLAG_ADMIN_SUB_MENU = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME       = $dirname;
        $this->_MODULE_DIR    = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL    = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_NAME   = $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR     = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        parent::__construct();
        $this->set_form_name($dirname . '_form');
        $this->set_title_header($dirname);

        $this->_xoops_class    = new webmap_base_xoops_param();
        $this->_post_class     = new webmap_base_lib_post();
        $this->_utility_class  = new webmap_base_lib_utility();
        $this->_language_class = new webmap_base_d3_language($dirname, $trust_dirname);

        $this->_xoops_language  = $this->_xoops_class->get_config_by_name('language');
        $this->_xoops_sitename  = $this->_xoops_class->get_config_by_name('sitename');
        $this->_module_mid      = $this->_xoops_class->get_module_mid();
        $this->_module_name     = $this->_xoops_class->get_module_name('n');
        $this->_xoops_uid       = $this->_xoops_class->get_user_uid();
        $this->_xoops_uname     = $this->_xoops_class->get_user_uname('n');
        $this->_xoops_groups    = $this->_xoops_class->get_user_groups();
        $this->_is_login_user   = $this->_xoops_class->is_login_user();
        $this->_is_module_admin = $this->_xoops_class->is_module_admin();

        $this->_init_this_fct();
    }

    public function _init_this_fct()
    {
        $this->_THIS_FCT_URL = $this->_THIS_URL;
        $get_fct             = $this->_post_class->get_post_get_text('fct');
        if ($get_fct) {
            $this->_THIS_FCT_URL .= '?fct=' . $get_fct;
        }
    }

    //---------------------------------------------------------
    // build comp
    //---------------------------------------------------------
    public function build_comp_label($name)
    {
        return $this->build_row_label($this->get_lang($name), $name);
    }

    public function build_comp_label_time($name)
    {
        return $this->build_row_label_time($this->get_lang($name), $name);
    }

    public function build_comp_text($name, $size = 50)
    {
        return $this->build_row_text($this->get_lang($name), $name, $size);
    }

    public function build_comp_url($name, $size = 50, $flag_link = false)
    {
        return $this->build_row_url($this->get_lang($name), $name, $size, $flag_link);
    }

    public function build_comp_textarea($name, $rows = 5, $cols = 50)
    {
        return $this->build_row_textarea($this->get_lang($name), $name, $rows, $cols);
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function get_post_js_checkbox_array()
    {
        $name = $this->_FORM_NAME . '_id';
        return $this->_post_class->get_post($name);
    }

    //---------------------------------------------------------
    // utility class
    //---------------------------------------------------------
    public function str_to_array($str, $pattern)
    {
        return $this->_utility_class->str_to_array($str, $pattern);
    }

    public function array_to_str($arr, $glue)
    {
        return $this->_utility_class->array_to_str($arr, $glue);
    }

    public function format_filesize($size)
    {
        return $this->_utility_class->format_filesize($size);
    }

    public function parse_ext($file)
    {
        return $this->_utility_class->parse_ext($file);
    }

    public function mysql_datetime_to_str($datetime)
    {
        return $this->_utility_class->mysql_datetime_to_str($datetime);
    }

    public function get_mysql_date_today()
    {
        return $this->_utility_class->get_mysql_date_today();
    }

    public function build_error_msg($msg, $title = '', $flag_sanitize = true)
    {
        return $this->_utility_class->build_error_msg($msg, $title, $flag_sanitize);
    }

    //---------------------------------------------------------
    // xoops timestamp
    //---------------------------------------------------------
    public function format_timestamp($time, $format = 'l', $timeoffset = '')
    {
        return formatTimestamp($time, $format, $timeoffset);
    }

    //---------------------------------------------------------
    // xoops
    //---------------------------------------------------------
    public function get_xoops_group_objs()
    {
        return $this->_xoops_class->get_group_obj();
    }

    public function get_cached_xoops_db_groups()
    {
        return $this->_xoops_class->get_cached_groups();
    }

    public function get_xoops_user_name($uid, $usereal = 0)
    {
        return $this->_xoops_class->get_user_uname_from_id($uid, $usereal);
    }

    public function build_xoops_userinfo($uid, $usereal = 0)
    {
        return $this->_xoops_class->build_userinfo($uid, $usereal);
    }

    public function get_xoops_user_list($limit = 0, $start = 0)
    {
        return $this->_xoops_class->get_member_user_list($limit, $start);
    }

    //---------------------------------------------------------
    // d3 language
    //---------------------------------------------------------
    public function get_lang_array()
    {
        return $this->_language_class->get_lang_array();
    }

    public function get_lang($name)
    {
        return $this->_language_class->get_constant($name);
    }

    // --- class end ---
}
