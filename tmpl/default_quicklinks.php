<?php
/**
 * @package		plg_system_qldebug
 * @copyright	Copyright (C) 2017 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$uri='';
if (isset($_SERVER['REQUEST_URI']))$uri=$_SERVER['REQUEST_URI'];
?>
<div class="quicklinks">
    <h3 id="quicklinks"><?php echo JText::_('PLG_SYSTEM_QLDEBUG_QUICKLINKS');?></h3>
    <?php if (1==$params->get('quicklinks',0))require $obj_plugin->getLayoutPath('_totop');?>
    <ul style="margin-left:30px;list-style-type:circle;">
        <?php
        foreach ($obj_plugin->arrQldebug as $v) echo '<li><a href="'.$uri.'#'.$v.'">'.ucwords($v).'</a></li>';?>
    </ul>
</div>