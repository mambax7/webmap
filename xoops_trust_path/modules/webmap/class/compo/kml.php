<?php
// $Id: kml.php,v 1.1.1.1 2009/02/23 03:26:47 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

//=========================================================
// class webmap_compo_build_kml
//=========================================================
class webmap_compo_kml extends webmap_compo_lib_kml_build
{
    public $_DIRNAME;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;
        parent::__construct();
    }

    public static function getSingleton($dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new webmap_compo_kml($dirname);
        }
        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // pulic
    //---------------------------------------------------------
    public function api_build_kml($placemarks)
    {
        $this->_set_kml($placemarks);
        $this->build_kml();
    }

    public function api_view_kml($placemarks)
    {
        $this->_set_kml($placemarks);
        $this->view_kml();
    }

    public function _set_kml($placemarks)
    {
        $template = 'db:' . $this->_DIRNAME . '_main_kml.html';

        $this->init_obj();
        $this->set_dirname($this->_DIRNAME);
        $this->set_template($template);
        $this->set_document_tag_use(true);
        $this->set_document_open_use(true);
        $this->set_document_name($this->build_document_name());
        $this->set_placemarks($placemarks);
    }

    // --- class end ---
}
