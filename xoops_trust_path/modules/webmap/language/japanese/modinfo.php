<?php
// $Id: modinfo.php,v 1.1.1.1 2009/02/23 03:26:42 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

// test
if (defined('FOR_XOOPS_LANG_CHECKER')) {
    $MY_DIRNAME = 'webmap';

    // normal
} elseif (isset($GLOBALS['MY_DIRNAME'])) {
    $MY_DIRNAME = $GLOBALS['MY_DIRNAME'];

    // call by altsys/mytplsadmin.php
} elseif ($mydirname) {
    $MY_DIRNAME = $mydirname;

    // probably error
} else {
    echo 'not set dirname in ' . __FILE__ . " <br />\n";
    $MY_DIRNAME = 'webmap';
}

$constpref = strtoupper('_MI_' . $MY_DIRNAME . '_');

// === define begin ===
if (defined('FOR_XOOPS_LANG_CHECKER') || !defined($constpref . 'LANG_LOADED')) {
    define($constpref . 'LANG_LOADED', 1);

    // module name
    define($constpref . 'NAME', 'Google Maps');
    define($constpref . 'DESC', 'Google Maps API �����Ѥ����Ͽޤ�ɽ������');

    // module config
    define($constpref . 'CFG_APIKEY', 'Google API Key');
    define($constpref . 'CFG_APIKEY_DSC',
           'Google Maps �����Ѥ������ <br /> <a href="http://www.google.com/apis/maps/signup.html" target="_blank">Sign Up for the Google Maps API</a> <br /> �ˤ� <br /> API key ��������Ƥ�������<br /><br />�ѥ�᡼���ξܺ٤ϲ���������������<br /><a href="http://www.google.com/apis/maps/documentation/reference.html" target="_blank">Google Maps API Reference</a>');
    define($constpref . 'CFG_ADDRESS', '����');
    define($constpref . 'CFG_LATITUDE', '����');
    define($constpref . 'CFG_LONGITUDE', '����');
    define($constpref . 'CFG_ZOOM', '������');
    define($constpref . 'CFG_MAP_TYPE', '�Ͽޤη���');
    define($constpref . 'CFG_MAP_TYPE_DSC', 'GMapType');
    define($constpref . 'OPT_MAP_TYPE_NORMAL', '�Ͽ�: Normal');
    define($constpref . 'OPT_MAP_TYPE_SATELLITE', '�Ҷ��̿�: Satellite');
    define($constpref . 'OPT_MAP_TYPE_HYBRID', '�Ͽ�+�̿�: Hybrid');
    define($constpref . 'OPT_MAP_TYPE_PHYSICAL', '�Ϸ�: Physical');
    define($constpref . 'CFG_MAP_CONTROL', '�ޥåס�����ȥ���');
    define($constpref . 'CFG_MAP_CONTROL_DSC', 'GLargeMapControl, GSmallMapControl, GSmallZoomControl');
    define($constpref . 'OPT_MAP_CONTROL_NON', 'ɽ�����ʤ�');
    define($constpref . 'OPT_MAP_CONTROL_LARGE', '�礭������ȥ���: Large');
    define($constpref . 'OPT_MAP_CONTROL_SMALL', '����������ȥ���: Small');
    define($constpref . 'OPT_MAP_CONTROL_ZOOM', '������: Zoom');
    define($constpref . 'CFG_TYPE_CONTROL', '�Ͽ޷����ܥ������Ѥ���');
    define($constpref . 'CFG_TYPE_CONTROL_DSC', 'GMapTypeControl, addMapType');
    define($constpref . 'OPT_TYPE_CONTROL_DEFAULT', '�ǥե����: �Ͽ�, �Ҷ��̿�, �Ͽ�+�̿�');
    define($constpref . 'OPT_TYPE_CONTROL_PHYSICAL', '���Ϸ��פ��ɲä���');
    define($constpref . 'CFG_USE_SCALE_CONTROL', '��Υɽ������Ѥ���');
    define($constpref . 'CFG_USE_SCALE_CONTROL_DSC', 'GScaleControl');
    define($constpref . 'CFG_USE_OVERVIEW_MAP', '�����ξ������Ͽޤ���Ѥ���');
    define($constpref . 'CFG_USE_OVERVIEW_MAP_DSC', 'GOverviewMapControl');
    define($constpref . 'CFG_USE_DRAGGABLE_MARKER', '[����] �ɥ�å����ޡ���������Ѥ���');
    define($constpref . 'CFG_USE_DRAGGABLE_MARKER_DSC', 'GMarker - draggable');
    define($constpref . 'CFG_USE_SEARCH_MARKER', '[����] ������̤Υޡ���������Ѥ���');
    define($constpref . 'CFG_USE_SEARCH_MARKER_DSC', 'GMarker - icon');
    define($constpref . 'CFG_USE_LOC_MARKER', '[���] �ޡ���������Ѥ���');
    define($constpref . 'CFG_USE_LOC_MARKER_DSC', 'GMarker');
    define($constpref . 'CFG_USE_LOC_MARKER_CLICK', '[���] �ޡ������Υ���å�����Ѥ���');
    define($constpref . 'CFG_USE_LOC_MARKER_CLICK_DSC', 'GEvent - addListener');
    define($constpref . 'CFG_LOC_MARKER_INFO', '[���] �ޡ������򥯥�å������Ȥ�������');
    define($constpref . 'CFG_LOC_MARKER_INFO_DSC', 'GMarker - openInfoWindowHtml');
    define($constpref . 'CFG_GEO_URL', '[GeoRSS] RSS �� URL');
    define($constpref . 'CFG_GEO_URL_DSC', 'GGeoXml <br />GeoRSS ���б�����URL����ꤹ��');
    define($constpref . 'CFG_GEO_TITLE', '[GeoRSS] �����ȥ�');
    define($constpref . 'CFG_GICON_PATH', '���åץ��ɡ��ե�����Υǥ��쥯�ȥ�');
    define($constpref . 'CFG_GICON_PATH_DSC',
           "[Google��������] ���åץ��ɻ�����¸��ǥ��쥯�ȥ�<br />XOOPS���󥹥ȡ����褫������Хѥ�����ꤹ��<br />�ǽ�ȺǸ��'/'������<br />Unix�ǤϤ��Υǥ��쥯�ȥ�ؤν��°����ON�ˤ��Ʋ�����");
    define($constpref . 'CFG_GICON_FSIZE', '����ե���������');
    define($constpref . 'CFG_GICON_FSIZE_DSC', '[Google��������] ���åץ��ɻ��Υե�������������(byte)');
    define($constpref . 'CFG_GICON_WIDTH', '����β����ȹ⤵');
    define($constpref . 'CFG_GICON_WIDTH_DSC', '[Google��������] ���åץ��ɻ��β����ȹ⤵�κ���');
    define($constpref . 'CFG_GICON_QUALITY', 'JPEG �ʼ�');
    define($constpref . 'CFG_GICON_QUALITY_DSC', '[Google��������] ���åץ��ɻ��Υ������ѹ������Ȥ����ʼ�<br />1 - 100');

    define($constpref . 'ADMENU_INDEX', '�ܼ�');
    define($constpref . 'ADMENU_LOCATION', '���١����٤��������');
    define($constpref . 'ADMENU_KML', 'KML�ΥǥХå�ɽ��');
    define($constpref . 'ADMENU_GICON_MANAGER', 'Google�����������');
    define($constpref . 'ADMENU_GICON_TABLE_MANAGE', 'Google��������ơ��֥����');
}// === define begin ===
;
