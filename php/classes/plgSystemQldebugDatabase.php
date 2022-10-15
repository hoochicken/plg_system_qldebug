<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2017 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class plgSystemQldebugDatabase
{

    /**
     * Method for getting database fields
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  bool true on success, false on failure
     *
     */
    function getDatabase()
    {
        return JFactory::getDBO();
    }

    /**
     * Method for getting database fields
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  bool true on success, false on failure
     *
     */
    function getDatabaseFields($database, $table)
    {
        $db = $this->getDatabase();
        $db->setQuery('SHOW COLUMNS FROM `' . $table . '` FROM `' . $database . '` ');
        $db->query();
        return $db->loadObjectList();
    }

    /**
     * Method for checking if table exists
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  string insert query
     *
     */
    function tableExists($database, $table)
    {
        $db = $this->getDatabase();
        $query = 'SHOW TABLES FROM `' . $database . '`';
        $db->setQuery($query);
        $db->query();
        foreach ($db->loadObjectList() as $k => $v) foreach ($v as $v2) $arr[$k] = $v2;
        if (is_array($arr) and in_array($this->getTableName($table), $arr)) return true;
        else return false;
    }

    /**
     * Method for getting Joomla! table name
     *
     * @return  string table name
     *
     */
    function getTableName($table)
    {
        if (preg_match('/#__/', $table)) $table = $this->getPrefix() . substr($table, 3);
        return $table;
    }

    /**
     * Method for getting Joomla! database name
     *
     * @return  string database name
     *
     */
    function getDatabaseName()
    {
        $config = JFactory::getConfig();
        return $config->get('db');
    }

    /**
     * Method for getting Joomla! prefix name
     *
     * @return  string database name
     *
     */
    function getPrefix()
    {
        $config = JFactory::getConfig();
        return $config->get('dbprefix');
    }

    /**
     * Method for getting database fields
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  bool true on success, false on failure
     *
     */
    function getTableData($table, $selector = '*', $query = '')
    {
        $db = $this->getDatabase();
        if ('' == $query) {
            $query = $db->getQuery(true);
            $query->select($selector);
            $query->from($table);
        }
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Method for getting database fields
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  bool true on success, false on failure
     *
     */
    function insertData($table, $data)
    {
        $db = $this->getDatabase();
        if (0 == $this->tableExists($this->getDatabaseName(), $table)) return false;
        $obj_data = new stdClass();
        foreach ($data as $k => $v) {
            if (is_array($v) or is_object($v)) $obj_data->$k = @json_encode($v);
            else $obj_data->$k = $v;
        }
        $db->insertObject($table, $obj_data);
        return true;
    }

    /**
     * Method to generate table in current database
     * @param $table
     * @return bool
     */
    public function generateTable($table)
    {
        if (true == $this->tableExists($this->getDatabaseName(), $table)) return true;
        $query =
            'CREATE TABLE IF NOT EXISTS `#__qldebug_storage` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `created` datetime NOT NULL,
          `user_id` int(4) NOT NULL,
          `area` varchar(5) NOT NULL,
          `session_id` varchar(100) NOT NULL,
          `post` text NOT NULL,
          `get` varchar(200) NOT NULL,
          `server` varchar(1000) NOT NULL,
          `globals` text NOT NULL,
          `files` varchar(500) NOT NULL,
          `request` varchar(200) NOT NULL,
          `session` varchar(500) NOT NULL,
          `cookie` varchar(200) NOT NULL,
          `jinput` text NOT NULL,
          `juser` text NOT NULL,
          `japplication` text NOT NULL,
          `table` text NOT NULL,
          `included_files` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
        $this->getDatabase()->setQuery($query);
        $this->getDatabase()->query();
        return;
    }

    /**
     * method to drop table in current database
     * @param $table
     * @return bool
     */
    public function dropTable($table)
    {
        if (false == $this->tableExists($this->getDatabaseName(), $table)) return true;
        $this->getDatabase()->setQuery('DROP TABLE IF EXISTS`' . $table . '`');
        $this->getDatabase()->query();
        return true;
    }

    /**
     * Not working method to remove recursion from objects and arrays ... probably never will work
     * @static
     * @param $object
     * @param array $stack
     * @return string
     */
    public static function remove_recursion(&$object, &$stack = array())
    {
        if ((is_object($object) || is_array($object)) && $object) {
            if (!in_array($object, $stack, true)) {
                $stack[] = $object;
                foreach ($object as &$subobject) {
                    self::remove_recursion($subobject, $stack);
                }
            } else {
                $object = "***RECURSION***";
            }
        }
        return $object;
    }
}