<?php
// $Id: manage.php,v 1.1.1.1 2009/02/23 03:26:45 ohwada Exp $

//=========================================================
// webmap module
// 2009-02-11 K.OHWADA
//=========================================================

//=========================================================
// class webmap_base_admin_manage
//=========================================================
class webmap_base_admin_manage extends webmap_base_lib_form_lang
{
    public $_base_class;
    public $_pagenavi_class;
    public $_footer_class;
    public $_manage_handler;

    public $_manage_id_name;
    public $_manage_title      = null;
    public $_manage_desc       = null;
    public $_manage_total      = 0;
    public $_manage_start_time = 0;
    public $_manage_menu       = null;

    public $_manage_sub_title_array         = null;
    public $_MANAGE_SUB_TITLE_ARRAY_DEFAULT = array('ID ascent', 'ID descent');

    public $_manage_list_column_array = null;

    public $_MANAGE_TITLE_ID_DEFAULT = 'ID';
    public $_MANAGE_TIME_SUCCESS     = 1;
    public $_MANAGE_TIME_FAIL        = 5;

    public $_SIZE_PERPAGE    = 10;
    public $_PAEPAGE_DEFAULT = 50;
    public $_MAX_SORTID      = 2;

    public $_LANG_SHOW_LIST  = 'show list';
    public $_LANG_ADD_RECORD = 'add record';
    public $_LANG_NO_RECORD  = 'there are no record';
    public $_LANG_THERE_ARE  = 'there are %s records';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_base_class   = new webmap_base_admin_base($dirname, $trust_dirname);
        $this->_footer_class = new webmap_base_lib_footer();

        $this->_pagenavi_class = new webmap_base_lib_pagenavi();
        $this->_pagenavi_class->set_perpage_default($this->_PAEPAGE_DEFAULT);
        $this->_pagenavi_class->set_max_sortid($this->_MAX_SORTID);

        $this->set_manage_sub_title_array($this->_MANAGE_SUB_TITLE_ARRAY_DEFAULT);

        $const_name = strtoupper('_AM_' . $trust_dirname . '_MANAGE_DESC');
        if (defined($const_name)) {
            $this->set_manage_desc(constant($const_name));
        }

        $const_name = strtoupper($trust_dirname . '_TIME_START');
        if (defined($const_name)) {
            $this->set_manage_start_time(constant($const_name));
        }
    }

    public function set_manage_handler(&$handler)
    {
        $this->_manage_handler = $handler;
        $this->_manage_id_name = $handler->get_id_name();
    }

    public function set_manage_title($val)
    {
        $this->_manage_title = $val;
    }

    public function set_manage_desc($val)
    {
        $this->_manage_desc = $val;
    }

    public function set_manage_start_time($val)
    {
        $this->_manage_start_time = (float)$val;
    }

    public function set_manage_sub_title_array($arr)
    {
        if (is_array($arr)) {
            $this->_manage_sub_title_array = $arr;
            $this->_pagenavi_class->set_max_sortid(count($this->_manage_sub_title_array));
        }
    }

    public function set_manage_list_column_array($arr)
    {
        if (is_array($arr)) {
            $this->_manage_list_column_array = $arr;
        }
    }

    public function set_manage_title_by_name($name)
    {
        $this->set_manage_title($this->get_admin_title($name));
    }

    public function set_manage_menu($val)
    {
        $this->_manage_menu = $val;
    }

    public function set_lang_show_list($val)
    {
        $this->_LANG_SHOW_LIST = $val;
    }

    public function set_lang_add_record($val)
    {
        $this->_LANG_ADD_RECORD = $val;
    }

    public function set_lang_no_record($val)
    {
        $this->_LANG_NO_RECORD = $val;
    }

    public function set_lang_there_are($val)
    {
        $this->_LANG_THERE_ARE = $val;
    }

    //---------------------------------------------------------
    // id
    //---------------------------------------------------------
    public function get_post_id()
    {
        $id = $this->get_post_get_int($this->_manage_id_name);
        if ($id > 0) {
            return $id;
        }

        return $this->get_post_get_int('id');
    }

    public function get_manage_id($row = null)
    {
        if (empty($row)) {
            $row = $this->_row;
        }
        if (isset($row[$this->_manage_id_name])) {
            return (int)$row[$this->_manage_id_name];
        }
        return false;
    }

    //---------------------------------------------------------
    // list
    //---------------------------------------------------------
    public function manage_list()
    {
        $this->_pagenavi_class->set_page_by_get();
        $this->_pagenavi_class->set_perpage_by_get();
        $this->_pagenavi_class->set_sortid_by_get();

        echo $this->build_manage_bread_crumb();
        echo $this->build_manage_list_menu();
        echo $this->build_show_title();
        echo $this->build_show_desc();

        $total_all = $this->get_total_all();
        if ($total_all == 0) {
            echo $this->build_show_no_record(true);
            echo "<br /><br />\n";
            echo $this->build_show_add_record();
            return false;
        }

        $total = $this->get_list_total();

        echo $this->build_sub_title_list();
        echo $this->build_show_add_record();
        echo $this->build_sub_title();
        echo $this->build_show_there_are($total);

        if ($total == 0) {
            return true;
        }

        $this->_pagenavi_class->set_total($total);
        $limit = $this->_pagenavi_class->get_perpage();
        $start = $this->_pagenavi_class->calc_start();
        $rows  = $this->get_list_rows($limit, $start);

        $this->print_list($rows);

        return true;
    }

    public function build_sub_title()
    {
        $title = $this->get_sub_title_by_num($this->pagenavi_get_sortid());
        if ($title) {
            $text = '<h4>' . $title . "</h4>\n";
        } else {
            $text = '<h4 style="color:#ff0000">' . 'unknown' . "</h4>\n";
        }
        return $text;
    }

    public function build_sub_title_list()
    {
        $text = '<ul>' . "\n";

        $count = $this->get_sub_title_count();
        for ($i = 0; $i < $count; ++$i) {
            $text .= '<li><a href="' . $this->_THIS_FCT_URL . '&amp;sortid=' . $i . '">';
            $text .= $this->get_sub_title_by_num($i);
            $text .= '</a> (';
            $text .= $this->_get_count_by_sortid($i);
            $text .= ') </li>' . "\n";
        }

        $text .= '</ul>' . "\n";
        $text .= "<br />\n";
        return $text;
    }

    public function get_sub_title_count()
    {
        if (is_array($this->_manage_sub_title_array)) {
            return count($this->_manage_sub_title_array);
        }
        return 0;
    }

    public function get_sub_title_by_num($num)
    {
        if (isset($this->_manage_sub_title_array[$num])) {
            return $this->_manage_sub_title_array[$num];
        }
        return false;
    }

    public function get_total_all()
    {
        return $this->_manage_handler->get_count_all();
    }

    public function get_list_total()
    {
        return $this->_get_list_total();
    }

    public function get_list_rows($limit, $start)
    {
        return $this->_get_list_rows($limit, $start);
    }

    public function print_list($rows)
    {
        $get_fct = $this->get_post_get_text('fct');

        echo $this->build_form_begin();
        echo $this->build_input_hidden('op', 'edit_all');

        if ($get_fct) {
            echo $this->build_input_hidden('fct', $get_fct);
        }

        echo $this->build_table_begin();

        echo '<tr align="center">';
        echo $this->build_manage_list_headers();
        echo '</tr>' . "\n";

        foreach ($rows as $row) {
            $class = $this->get_alternate_class();
            echo '<tr>';
            echo $this->build_manage_list_columns($row);
            echo "</tr>\n";
        }

        echo '<tr>';
        echo '<td class="head">';
        echo '<input type="submit" name="delete_all" value="' . _DELETE . '" />';
        echo '</td>';
        echo '<td class="head" colspan="' . $this->get_manage_list_submit_colspan() . '"></td>';
        echo "</tr>\n";
        echo "</table></form>\n";
        echo "<br />\n";

        echo $this->build_form_pagenavi_perpage();
        echo $this->build_manage_pagenavi();
    }

    public function build_manage_list_menu()
    {
        return $this->_manage_menu;
    }

    public function build_manage_list_headers()
    {
        $arr = $this->get_manage_list_column_array();

        $text = '<th>' . $this->build_js_checkall() . '</th>';
        $text .= $this->build_comp_td($this->_manage_id_name);

        foreach ($arr as $name) {
            $text .= $this->build_comp_td($name);
        }
        return $text;
    }

    public function build_manage_list_columns($row)
    {
        $arr = $this->get_manage_list_column_array();
        $id  = (int)$row[$this->_manage_id_name];

        $text = $this->build_manage_line_js_checkbox($id);
        $text .= $this->build_manage_line_id($id);

        foreach ($arr as $name) {
            $text .= $this->build_manage_line_value($row[$name]);
        }
        return $text;
    }

    public function get_manage_list_submit_colspan()
    {
        $ret = $this->get_manage_list_column_count() + 1;
        return $ret;
    }

    public function get_manage_list_column_count()
    {
        if (is_array($this->_manage_list_column_array)) {
            return count($this->_manage_list_column_array);
        }
        return 0;
    }

    public function get_manage_list_column_array()
    {
        return $this->_manage_list_column_array;
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function manage_form()
    {
        $this->manage_print_form();
    }

    public function manage_form_with_error($msg = null)
    {
        // show error if noo record
        $row = $this->get_manage_row_by_id();
        if (!is_array($row)) {
            return false;
        }

        echo $this->build_manage_bread_crumb();

        if ($msg) {
            echo $this->build_error_msg($msg);
        }

        $this->_manage_print_title_and_form($row);
    }

    public function manage_print_form()
    {
        // show error if no record
        $row = $this->get_manage_row_by_id();
        if (!is_array($row)) {
            return false;
        }

        echo $this->build_manage_bread_crumb();

        $this->_manage_print_title_and_form($row);
    }

    public function _manage_print_title_and_form($row)
    {
        echo $this->build_show_title();
        echo $this->build_show_list();
        echo $this->build_show_add_record();

        $this->_print_form($row);
    }

    //---------------------------------------------------------
    // add
    //---------------------------------------------------------
    public function manage_add()
    {
        $row_new    = $this->_manage_handler->create(true);
        $row_add    = $this->_build_row_add();
        $row_insert = array_merge($row_new, $row_add);

        $newid = $this->_manage_handler->insert($row_insert);
        if (!$newid) {
            $msg = 'DB error <br />';
            $msg .= $this->_manage_handler->get_format_error();
            redirect_header($this->build_manage_form_url(), $this->_MANAGE_TIME_FAIL, $msg);
            exit();
        }

        redirect_header($this->build_manage_form_url($newid), $this->_MANAGE_TIME_SUCCESS, 'Added');
        exit();
    }

    public function build_manage_form_url($id = 0)
    {
        if (empty($id)) {
            $id = $this->get_post_id();
        }
        $url = $this->_THIS_FCT_URL . '&amp;op=form&amp;id=' . (int)$id;
        return $url;
    }

    //---------------------------------------------------------
    // edit
    //---------------------------------------------------------
    public function manage_edit()
    {
        $row_edit = $this->_build_row_edit();
        $id       = $this->get_manage_id($row_edit);

        // exit if no record
        $row_current = $this->_manage_edit_get_row($id);

        // exit if failed
        $this->_manage_edit_exec(array_merge($row_current, $row_edit));

        redirect_header($this->build_manage_form_url($id), $this->_MANAGE_TIME_SUCCESS, 'Updated');
        exit();
    }

    public function _manage_edit_get_row($id)
    {
        return $this->_manage_get_row_or_exit($id);
    }

    public function _manage_get_row_or_exit($id)
    {
        $row = $this->_manage_handler->get_row_by_id($id);
        if (!is_array($row)) {
            $msg = 'no match record';
            redirect_header($this->build_manage_form_url(), $this->_MANAGE_TIME_FAIL, $msg);
            exit();
        }

        return $row;
    }

    public function _manage_edit_exec($row)
    {
        $ret = $this->_manage_handler->update($row);
        if (!$ret) {
            $msg = 'DB error <br />';
            $msg .= $this->_manage_handler->get_format_error();
            redirect_header($this->build_manage_form_url($id), $this->_MANAGE_TIME_FAIL, $msg);
            exit();
        }

        return true;
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------
    public function manage_delete()
    {
        // exit if no record
        $row = $this->_manage_delete_get_row();

        $this->_manage_delete_option($row);
        $this->_manage_delete_exec($row);

        redirect_header($this->_THIS_FCT_URL, $this->_MANAGE_TIME_SUCCESS, 'Deleted');
        exit();
    }

    public function _manage_delete_get_row()
    {
        return $this->_manage_get_row_or_exit($this->get_post_id());
    }

    public function _manage_delete_exec($row)
    {
        $id  = $this->get_manage_id($row);
        $ret = $this->_manage_handler->delete($row);
        if (!$ret) {
            $msg = 'DB error <br />';
            $msg .= $this->_manage_handler->get_format_error();
            redirect_header($this->build_manage_form_url($id), $this->_MANAGE_TIME_FAIL, $msg);
            exit();
        }

        return true;
    }

    public function manage_delete_all()
    {
        $id_arr = $this->get_post_js_checkbox_array();

        $flag_error = false;
        $url        = $this->_THIS_FCT_URL;

        foreach ($id_arr as $id) {
            $ret = $this->_manage_delete_all_each($id);
            if (!$ret) {
                $flag_error = true;
            }
        }

        if ($flag_error) {
            $msg = 'DB error <br />';
            $msg .= $this->get_format_error();
            redirect_header($url, $this->_MANAGE_TIME_FAIL, $msg);
            exit();
        }

        redirect_header($url, $this->_MANAGE_TIME_SUCCESS, 'Deleted');
        exit();
    }

    public function _manage_delete_all_each($id)
    {
        $row = $this->_manage_handler->get_row_by_id($id);
        if (!is_array($row)) {
            return true;
        }

        $this->_manage_delete_all_each_option($row);

        $ret = $this->_manage_handler->delete($row);
        if (!$ret) {
            $this->_set_error($this->_manage_handler->get_errors());
            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // manage title
    //---------------------------------------------------------
    public function build_manage_title()
    {
        $text = $this->build_manage_bread_crumb();
        $text .= $this->build_show_title();
        $text .= $this->build_show_list();
        $text .= $this->build_show_add_record();
        return $text;
    }

    public function build_show_title()
    {
        $text = '<h3>' . $this->_manage_title . "</h3>\n";
        return $text;
    }

    public function build_show_desc()
    {
        if ($this->_manage_desc) {
            $text = $this->_manage_desc . "<br /><br />\n";
            return $text;
        }
        return null;
    }

    public function build_manage_bread_crumb()
    {
        $text = '<a href="index.php">';
        $text .= $this->sanitize($this->_MODULE_NAME);
        $text .= '</a>';
        $text .= ' &gt;&gt; ';
        $text .= '<a href="' . $this->_THIS_FCT_URL . '">';
        $text .= $this->sanitize($this->_manage_title);
        $text .= '</a>';
        $text .= "<br /><br />\n";
        return $text;
    }

    //---------------------------------------------------------
    // manage list
    //---------------------------------------------------------
    public function get_manage_total_print_error()
    {
        $total = $this->get_manage_total();
        if ($total == 0) {
            echo $this->build_manage_bread_crumb();
            echo $this->build_show_no_record(true);
            return 0;
        }

        return $total;
    }

    public function build_show_no_record($flag_highlight = false)
    {
        $text = $this->_LANG_NO_RECORD;
        if ($flag_highlight) {
            $text = $this->highlight($text);
        }
        return $text;
    }

    public function build_show_list()
    {
        $text = '<a href="' . $this->_THIS_FCT_URL . '">';
        $text .= $this->_LANG_SHOW_LIST;
        $text .= '</a>';
        $text .= "<br /><br >\n";
        return $text;
    }

    public function build_show_add_record()
    {
        $text = '<a href="' . $this->_THIS_FCT_URL . '&amp;op=form">';
        $text .= $this->_LANG_ADD_RECORD;
        $text .= '</a>';
        $text .= "<br /><br />\n";
        return $text;
    }

    public function build_show_there_are($total)
    {
        $text = sprintf($this->_LANG_THERE_ARE, $total) . "<br /><br />\n";
        return $text;
    }

    public function get_manage_total()
    {
        $total               = $this->_manage_handler->get_count_all();
        $this->_manage_total = $total;
        return $total;
    }

    public function build_manage_line_js_checkbox($id)
    {
        $text = '<td class="' . $this->_alternate_class . '">';
        $text .= $this->build_js_checkbox($id);
        $text .= '</td>';
        return $text;
    }

    public function build_manage_line_id($id)
    {
        $id  = (int)$id;
        $url = $this->_THIS_FCT_URL . '&amp;op=form&amp;id=' . $id;

        $text = '<td class="' . $this->_alternate_class . '">';
        $text .= '<a href="' . $url . '">';
        $text .= sprintf('%04d', $id) . '</a>';
        $text .= '</td>';
        return $text;
    }

    public function build_manage_line_value($value, $flag = true)
    {
        if ($flag) {
            $value = $this->sanitize($value);
        }
        $text = '<td class="' . $this->_alternate_class . '">';
        $text .= $value;
        $text .= '</td>';
        return $text;
    }

    //---------------------------------------------------------
    // manage navi
    //---------------------------------------------------------
    public function build_manage_pagenavi()
    {
        $script = $this->_THIS_FCT_URL;
        $script = $this->_pagenavi_class->add_script_sortid($script);
        $script = $this->_pagenavi_class->add_script_perpage($script);

        $navi = $this->_pagenavi_class->build($script);

        $text = '';
        if ($navi) {
            $text .= '<div align="center">';
            $text .= $navi;
            $text .= "</div><br />\n";
        }

        return $text;
    }

    //---------------------------------------------------------
    // manage form
    //---------------------------------------------------------
    public function get_manage_row_by_id($id = null)
    {
        $false = false;

        if (empty($id)) {
            $id = $this->get_post_id();
        }
        $id = (int)$id;

        if ($id > 0) {
            $row = $this->_manage_handler->get_row_by_id($id);
            if (!is_array($row)) {
                echo $this->build_manage_bread_crumb();
                echo $this->build_show_no_record(true);
                return $false;
            }
            $op = 'edit';
        } else {
            $op  = 'add';
            $row = $this->_manage_handler->create(true);
        }

        $row['op'] = $op;

        return $row;
    }

    public function build_manage_form_begin($row)
    {
        $this->set_row($row);

        $op      = isset($row['op']) ? $row['op'] : null;
        $id      = $this->get_manage_id($row);
        $get_fct = $this->get_post_get_text('fct');

        $text = $this->build_form_begin();
        if ($get_fct) {
            $text .= $this->build_input_hidden('fct', $get_fct);
        }
        if ($op) {
            $text .= $this->build_input_hidden('op', $op);
        }
        if ($id > 0) {
            $text .= $this->build_input_hidden($this->_manage_id_name, $id);
        }
        return $text;
    }

    public function build_manage_header()
    {
        return $this->build_line_title($this->_manage_title);
    }

    public function build_manage_id($row = null)
    {
        $title = $this->get_lang($this->_manage_id_name);
        if (empty($title)) {
            $title = $this->_MANAGE_TITLE_ID_DEFAULT;
        }
        $id = $this->substitute_empty($this->get_manage_id($row));
        return $this->build_line_ele($title, $id);
    }

    public function build_manage_submit($row = null)
    {
        $id = $this->get_manage_id($row);
        if ($id) {
            return $this->build_line_edit();
        }
        return $this->build_line_add();
    }

    //---------------------------------------------------------
    // complement caption by name
    //---------------------------------------------------------
    public function build_comp_td($name)
    {
        $str = '<th>' . $this->get_lang($name) . '</th>';
        return $str;
    }

    //---------------------------------------------------------
    // footer
    //---------------------------------------------------------
    public function build_admin_footer()
    {
        $text = "<br /><hr />\n";
        $text .= $this->build_execution_time($this->_manage_start_time);
        $text .= $this->build_memory_usage();
        return $text;
    }

    //---------------------------------------------------------
    // footer
    //---------------------------------------------------------
    public function build_execution_time($time_start = 0)
    {
        return $this->_footer_class->build_execution_time($time_start);
    }

    public function build_memory_usage()
    {
        return $this->_footer_class->build_memory_usage();
    }

    //---------------------------------------------------------
    // base
    //---------------------------------------------------------
    public function build_admin_title($name, $format = true)
    {
        return $this->_base_class->build_admin_title($name, $format);
    }

    public function get_admin_title($name)
    {
        return $this->_base_class->get_admin_title($name);
    }

    //---------------------------------------------------------
    // paginavi
    //---------------------------------------------------------
    public function pagenavi_get_sortid()
    {
        return $this->_pagenavi_class->get_sortid();
    }

    public function pagenavi_get_perpage()
    {
        return $this->_pagenavi_class->get_perpage();
    }

    public function build_form_pagenavi_perpage()
    {
        $form_name = $this->_FORM_NAME . '_perpage';

        $text = '<div align="center">';
        $text .= $this->build_form_tag($form_name, $this->_THIS_URL, 'get');
        $text .= $this->build_input_hidden('sortid', $this->pagenavi_get_sortid());
        $text .= $this->build_input_hidden('fct', $this->get_post_get_text('fct'));
        $text .= 'per page' . ' ';
        $text .= $this->build_input_text('perpage', $this->pagenavi_get_perpage(), $this->_SIZE_PERPAGE);
        $text .= ' ';
        $text .= $this->build_input_submit('submit', 'SET');
        $text .= $this->build_form_end();
        $text .= "</div><br />\n";
        return $text;
    }

    //---------------------------------------------------------
    // post
    //---------------------------------------------------------
    public function get_post_get_int($key)
    {
        return $this->_post_class->get_post_get_int($key);
    }

    public function get_post_get_text($key)
    {
        return $this->_post_class->get_post_get_text($key);
    }

    public function get_post_text($key)
    {
        return $this->_post_class->get_post_text($key);
    }

    //---------------------------------------------------------
    // sample
    //---------------------------------------------------------
    public function _main()
    {
        switch ($this->_get_op()) {
            case 'add':
            case 'edit':
            case 'delete':
            case 'edit_all':
            case 'delete_all':
                if (!$this->check_token()) {
                    xoops_cp_header();
                    $this->manage_form_with_error('Token Error');
                    xoops_cp_footer();
                    exit();
                }
                $this->_execute();
                break;

            case 'form':
                xoops_cp_header();
                $this->manage_form();
                break;

            case 'list':
            default:
                xoops_cp_header();
                $this->manage_list();
                break;
        }

        echo $this->build_admin_footer();
        xoops_cp_footer();
        exit();
    }

    public function _execute()
    {
        switch ($this->_get_op()) {
            case 'add':
                $this->manage_add();
                break;

            case 'edit':
                $this->manage_edit();
                break;

            case 'delete':
                $this->manage_delete();
                break;

            case 'delete_all':
                $this->manage_delete_all();
                break;
        }
    }

    public function _get_op()
    {
        if ($this->get_post_text('add')) {
            return 'add';
        } elseif ($this->get_post_text('edit')) {
            return 'edit';
        } elseif ($this->get_post_text('delete')) {
            return 'delete';
        } elseif ($this->get_post_text('delete_all')) {
            return 'delete_all';
        }

        return $this->get_post_get_text('op');
    }

    //=========================================================
    // override for caller
    //=========================================================
    public function _get_list_total()
    {
        $total               = $this->_get_count_by_sortid($this->pagenavi_get_sortid());
        $this->_manage_total = $total;
        return $total;
    }

    public function _get_count_by_sortid($sortid)
    {
        switch ($sortid) {
            case 0:
            case 1:
            default:
                $count = $this->_manage_handler->get_count_all();
                break;
        }

        return $count;
    }

    public function _get_list_rows($limit, $start)
    {
        switch ($this->pagenavi_get_sortid()) {
            case 1:
                $rows = $this->_manage_handler->get_rows_all_desc($limit, $start);
                break;

            case 0:
            default:
                $rows = $this->_manage_handler->get_rows_all_asc($limit, $start);
                break;
        }

        return $rows;
    }

    public function _build_row_add()
    {
        return $this->_build_row_by_post();
    }

    public function _build_row_edit()
    {
        return $this->_build_row_by_post();
    }

    public function _build_row_form()
    {
        return $this->_build_row_by_post();
    }

    public function _build_row_by_post()
    {
        // dummy
    }

    public function _print_list($total, $rows)
    {
        // dummy
    }

    public function _print_form()
    {
        // dummy;
    }

    public function _manage_delete_option($row)
    {
        // dummy
    }

    public function _manage_delete_all_each_option($row)
    {
        // dummy
    }

    // --- class end ---
}
