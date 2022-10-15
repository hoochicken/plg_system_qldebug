<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class plgSystemQldebugHelper
{

    public $params;
    public $plgname = 'qldebug';
    public $tableStorage = '#__qldebug_storage';

    function __construct($params)
    {
        require_once dirname(__FILE__) . '/plgSystemQldebugDatabase.php';
        $this->params = $params;
        $this->obj_db = new plgSystemQldebugDatabase();
    }

    static function checkIfUserLoggedIn()
    {
        $user = JFactory::getUser();
        $user_id = $user->get('id');
        if ($user_id > 0) return $user_id;
        else return false;
    }

    static function checkIfUserIsSuperuser($user_id, $user_group_id = '8')
    {
        if (false == $user_id) return false;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('group_id');
        $query->from('`#__user_usergroup_map`');
        $query->where('`user_id`=\'' . $user_id . '\'');
        $db->setQuery($query);
        $user_data = $db->loadObject();
        if (isset($user_data) && is_object($user_data) && isset($user_data->group_id) && $user_group_id == $user_data->group_id) return true;
        else return false;
    }

    public function get_japplication()
    {
        return JFactory::getApplication();
    }

    public function get_jinput()
    {
        return JFactory::getApplication()->input;
    }

    public function get_juser()
    {
        return JFactory::getUser();
    }

    public function get_post()
    {
        return $_POST;
    }

    public function get_get()
    {
        return $_GET;
    }

    /**
     * @return array
     */
    public function get_server()
    {
        $server = [];
        $servParam = $this->params->get('server');
        if (is_array($servParam) && 0 < count($servParam)) foreach ($servParam as $k => $v) $server[$v] = $_SERVER[$v];
        return $server;
        //return $_SERVER;
    }

    public function get_session()
    {
        if (isset($_SESSION)) return $_SESSION;
        else return false;
    }

    public function get_cookie()
    {
        return $_COOKIE;
    }

    public function get_request()
    {
        return $_REQUEST;
    }

    public function get_globals()
    {
        return $GLOBALS;
    }

    public function get_files()
    {
        return $_FILES;
    }

    public function get_includedfiles()
    {
        return get_included_files();
    }

    public function get_browsersize()
    {
        JFactory::getDocument()->addScript(JUri::base() . 'plugins/system/' . $this->plgname . '/javascript/browsersize.js');
        return '<div id="browsersizeContent"></div>';
    }

    public function get_device()
    {
        $browser = JBrowser::getInstance();
        $agent = $browser->getAgentString();
        $device = new stdClass;
        $device->device = 'desktop';
        $device->mobile = true;
        $device->browser = false;
        preg_match('~(firefox|safari|chrome)~i', $agent, $matches);
        if (isset($matches[0])) $device->browser = strtolower($matches[0]);
        preg_match('~(ipad|ipod|iphone|android)~i', $agent, $matches);
        if (!isset($matches[0])) $device->mobile = false;
        return $device;
        /*
        JFactory::getDocument()->addScript(JUri::base().'plugins/system/'.$this->plgname.'/javascript/device.js');
        return '<div id="deviceContent"></div>';
        */
    }

    /**
     * Method to get coumn information of current table
     * @return array|bool
     */
    public function get_table()
    {
        $table = $this->params->get('tablename');
        if (true != $this->checkIfTableExists($table)) return false;
        /*generate array with table info (columns and data)*/
        $arr_table_columns = [];
        if (1 == $this->params->get('tablecolumns')) $arr_table_columns['columns'] = $this->obj_db->getDatabaseFields($this->database, $table);
        if (1 == $this->params->get('tabledata')) $arr_table_columns['data'] = $this->obj_db->getTableData($table, '*', trim($this->params->get('tablequery')));
        return $arr_table_columns;
    }

    /**
     * Method to check if a table exists in current database
     * @param $table
     * @return bool
     */
    public function checkIfTableExists($table)
    {
        $this->database = $this->obj_db->getDatabaseName();
        if (true != $this->obj_db->tableExists($this->database, $table)) return false;
        return true;
    }

    /**
     * Method to create new table in current database
     * @param string $table
     */
    public function generateTable($table = '')
    {
        if ('' == $table) $table = $this->tableStorage;
        $this->obj_db->generateTable($table);
    }

    /**
     * Method to drop new table in current database
     * @param string $table
     */
    public function dropTable($table = '')
    {
        if ('' == $table) $table = $this->tableStorage;
        return $this->obj_db->dropTable($table);
    }

    /**
     * Method to store data (object) in current table
     * @param $data
     */
    public function storeInDatabase($data)
    {
        $data = $this->removeRecursion($data);
        $data = $this->prepareData($data);
        //echo '<pre>';print_r(json_encode((array)$data->jinput));
        $data = $this->jsonify($data);
        //echo '<pre>';print_r($data);die;
        $this->obj_db->insertData($this->tableStorage, $data);
    }

    /**
     * Method to jsonify arrays or object within given data
     * @param $data
     * @return mixed bool|object
     */
    private function jsonify($data)
    {
        if (!is_object($data)) return false;
        foreach ($data as $k => $v) {
            if (!is_string($v)) $data->$k = json_encode($v);
            else $data->$k = $v;
        }
        return $data;
    }

    /**
     * Method to prepare data, e. g. add additional info
     * @param $data
     * @return object
     */
    public function prepareData($data)
    {
        $app =& JFactory::getApplication();
        if ($app->isAdmin()) $data->area = 'admin';
        elseif ($app->isSite()) $data->area = 'site';
        $data->created = date('Y-m-d H:i:s');
        $data->user_id = JFactory::getUser()->id;
        $data->session_id = session_id();
        $data->jinput = (array)$data->jinput;
        //echo '<pre>';print_r($data);die;
        return $data;
    }

    /**
     * Method to remove recursive objects and arrays that make the database explode
     * @param $data
     * @return object
     */
    public function removeRecursion($data)
    {
        /*forbidden because of recursion, to be solved some day ... my prince will come*/
        $arrForbidden = array('japplication', 'Xjuser', 'session', 'globals');
        foreach ($data as $k => $v) {
            if ((is_array($v) || is_object($v)) && in_array($k, $arrForbidden)) unset($data->$k);
        }
        return $data;
    }
}
