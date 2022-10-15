<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormRuleQldebugchecktableexists extends JFormRule
{
    /**
     * The regular expression to use in testing a form field value.
     *
     * @var    string
     * @since  11.1
     */
    //protected $regex = '^[\w.-]+(\+[\w.-]+)*@\w+[\w.-]*?\.\w{2,4}$';

    /**
     * Method to test the email address and optionally check for uniqueness.
     *
     * @param SimpleXMLElement $element The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param mixed $value The form field value to validate.
     * @param string $group The field name group control value. This acts as as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     * @param JRegistry $input An optional JRegistry object with the entire data set to validate against the entire form.
     * @param JForm $form The form object for which the field is being tested.
     *
     * @return  boolean  True if the value is valid, false otherwise.
     *
     * @since   11.1
     */
    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
        if ('' == $value || 0 == $value) return true;
        require_once JPATH_SITE . '/plugins/system/qldebug/php/classes/plgSystemQldebugHelper.php';
        $this->obj_helper = new plgSystemQldebugHelper(new stdClass());
        $tableName = $this->obj_helper->tableStorage;
        try {
            $msgError = array();
            if (1 != $this->tableExists($this->getDatabaseName(), $this->getTableName($tableName))) throw new Exception(sprintf(JText::_('PLG_SYSTEM_QLDEBUG_MSG_TABLENONEXISTENT'), $tableName, $tableName));
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
        return true;
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
    function getDatabase()
    {
        return JFactory::getDBO();
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
    private function tableExists($database, $table)
    {
        $db = $this->getDatabase();
        $db->setQuery('SHOW TABLES FROM `' . $database . '`');
        $db->query();
        foreach ($db->loadObjectList() as $k => $v) foreach ($v as $v2) $arr[$k] = $v2;
        if (is_array($arr) && in_array($table, $arr)) return true;
        else return false;
    }

    /**
     * Method for getting Joomla! table name
     *
     * @return  string table name
     *
     */
    private function getTableName($table)
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
     * Method to turn object to array with database fields
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  string insert query
     *
     */
    private function databaseFieldsObjectToArray($arrFields)
    {
        foreach ($arrFields as $k => $v) $arr[] = $v->Field;
        return array_flip($arr);
    }

    /**
     * Method to turn object to array
     *
     * @param string $database database name
     * @param string $table Name of table to save data in
     *
     * @return  string insert query
     *
     */
    private function objectToArrayOrTheOtherWay($input)
    {
        if (is_object($input)) {
            $output = array();
            foreach ($input as $k => $v) $output[$k] = $v;
        }
        if (is_array($input)) {
            $output = new stdClass();
            foreach ($input as $k => $v) $output->$k = $v;
        }
        return $output;
    }
}
