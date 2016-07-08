<?php
// $Id: kml.php,v 1.1.1.1 2009/02/23 03:26:43 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

//=========================================================
// class webmap_show_kml
//=========================================================
class webmap_view_kml
{
    public $_xoops_param;
    public $_kml_class;

    public $_DIRNAME;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_DIRNAME     = $dirname;
        $this->_xoops_param = new webmap_base_xoops_param();
        $this->_kml_class   = webmap_compo_kml::getSingleton($dirname);
    }

    public static function getInstance($dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webmap_view_kml($dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // pulic
    //---------------------------------------------------------
    public function build_webmap_kml()
    {
        $this->_kml_class->api_build_kml($this->_build_placemarks());
    }

    public function view_webmap_kml()
    {
        $this->_kml_class->api_view_kml($this->_build_placemarks());
    }

    public function _build_placemarks()
    {
        $placemark = array(
            'name'        => $this->get_config('address'),
            'description' => $this->get_config('loc_marker_info'),
            'latitude'    => $this->get_config('latitude'),
            'longitude'   => $this->get_config('longitude'),
        );
        return array($placemark);
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------
    public function get_config($name)
    {
        return $this->_xoops_param->get_module_config_by_name($name);
    }

    // --- class end ---
}
