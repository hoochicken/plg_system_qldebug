<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2017 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */


//no direct access
defined('_JEXEC') or die ('Restricted Access');
jimport('joomla.plugin.plugin');

class plgSystemQldebug extends JPlugin
{
    public $params;
    public $arrQldebug = ['browsersize', 'device', 'post', 'get', 'server', 'session', 'files', 'cookie', 'globals', 'request', 'juser', 'jinput', 'japplication', 'table', 'includedfiles'];

    /**
     * constructor
     *setting language
     */
    public function __construct(&$subject, $config)
    {
        //echo '<pre>';print_R($subject);print_R($config);echo '</pre>';die;
        //echo '<pre>';print_R($config);echo '</pre>';die;
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    /**
     * method to set module params as valid params for plugin
     * @param $params
     */
    public function forceParams($params)
    {
        //echo '<pre>';print_r($this->params);
        $params->set('getHtml', 'module');
        $this->params = $params;
    }

    /**
     * onContentPrepare :: some kind of controller of plugin
     */
    public function onAfterDispatch()
    {
        /*HERE create new registry object*/
        //echo '<pre>';print_r($this->params);die;
        $app = JFactory::getApplication();
        if ($app->isAdmin()) $area = 'admin';
        if ($app->isSite()) $area = 'site';
        $display = false;
        //echo '<pre>';print_R('ASDASD');die;

        if (1 == $this->params->get('stateSite', 0) and 'site' == $area) $display = true;
        if (1 == $this->params->get('stateAdmin', 0) and 'admin' == $area) $display = true;
        if (1 == isset($_GET['qldebug'])) $display = true;

        /*when to be NOT displayed, then return without any further action*/
        if (0 == $this->params->get('stateAccess', 0) or false == $display) return;

        /*initiate helper*/
        require_once JPATH_ROOT . '/plugins/system/qldebug/php/classes/plgSystemQldebugHelper.php';
        $obj_helper = new plgSystemQldebugHelper($this->params);

        $user = JFactory::getUser();
        $arrayIntersect = @array_intersect($user->get('_authGroups'), $user->get('groups'));

        $user_id = plgSystemQldebugHelper::checkIfUserLoggedIn();
        switch ($this->params->get('stateAccess')) {
            /*never, already returned*/
            case 0 :
                $stateDisplay = 0;
                break;
            /*only logged-in Super Users*/
            case 1 :
                if (false == $user_id) {
                    $stateDisplay = 0;
                    break;
                }
                if (0 < $user_id and true == plgSystemQldebugHelper::checkIfUserIsSuperuser($user_id, $this->params->get('usergroup', 8))) $stateDisplay = 1;
                break;
            /*only logged-in users*/
            case 2 :
                if (0 < $user_id) $stateDisplay = 1;
                break;
            /*only when user is not logged in*/
            case 3 :
                if (false == $user_id) $stateDisplay = 0;
                break;
            /*always*/
            case 4 :
                $stateDisplay = 1;
                break;
            case 5 :
                if ($_SERVER['REMOTE_ADDR'] == $this->params->get('ip', '') and '' != $this->params->get('ip', '')) $stateDisplay = 1;
                //echo '<pre>';print_r($_SERVER);die;
                break;
            default :
                $stateDisplay = 0;
        }

        if (isset($_GET['qldebug'])) $stateDisplay = 1;
        if (isset($stateDisplay) and 1 == $stateDisplay) {
            $arr = [];
            $styles = preg_replace('/"/', '', strip_tags($this->params->get('css')));

            $data = new stdClass();
            foreach ($this->arrQldebug as $k => $v) {
                if
                (
                    (1 != $this->params->get($v) and 'server' != $v)
                    or
                    ('server' == $v and 0 >= count($this->params->get($v)))
                ) {
                    unset($this->arrQldebug[$k]);
                    continue;
                }
                $var_name = $v;
                $fn = 'get_' . $v;
                $arr[] = $fn;
                ${$var_name} = $obj_helper->$fn();
                $data->$var_name = $this->{$var_name} = $obj_helper->$fn();
            }
            if (1 == $this->params->get('databasestorage', 0)) $obj_helper->storeInDatabase($data);
            if (1 == $this->params->get('output', 1) or 2 == $this->params->get('output', 1)) {
                $document = JFactory::getDocument();
                $document->addStyleSheet(JURI::base() . 'plugins/system/' . $this->get('_name') . '/css/qldebug.css');
                $document->addStyleDeclaration('.qldebugPlugin{' . $this->params->get('css') . '}');
                if ('module' == $this->params->get('getHtml', 'plugin')) return $this;
                echo $this->getHtml();
            }
        }
    }

    /*
    * method to get html source code of gallery
    */
    function getHtml()
    {
        $params = $this->params;
        $obj_plugin = $this;
        $type = 'plugin';
        //$this->Plugin=$this->get('_name');
        ob_start();
        include JPATH_ROOT . '/plugins/system/' . $this->get('_name') . '/tmpl/' . $this->params->get('layout', 'default') . '.php';
        //include JPATH_ROOT.'/plugins/system/'.$this->get('_name').'/html/'.$this->params->get('layout','default').'.php';
        $strHtml = ob_get_contents();
        ob_end_clean();
        return $strHtml;
    }

    function getLayoutPath($subtemplate = '')
    {
        if ('module' == $this->params->get('getHtml', 'plugin')) return JPATH_ROOT . '/modules/mod_qldebug/tmpl/' . $this->params->get('layout', 'default') . $subtemplate . '.php';
        return JPATH_ROOT . '/plugins/system/' . $this->get('_name') . '/tmpl/' . $this->params->get('layout', 'default') . $subtemplate . '.php';
    }
}