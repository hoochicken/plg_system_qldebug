<?php
/**
 * @package        plg_system_qldebug
 * @copyright    Copyright (C) 2017 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="<?php echo $v; ?>">
    <?php if (2 != $params->get('output', 1)) require $obj_plugin->getLayoutPath('_headline'); ?>
    <?php if (1 == $params->get('quicklinks', 0)) require $obj_plugin->getLayoutPath('_totop'); ?>
    <?php if (1 == $params->get('pre')) {
        echo '<pre';
        if (1 == $params->get('preBreakWord')) echo ' style="white-space:pre-wrap;"';
        echo '>';
    } ?>
    <?php print_r($obj_plugin->$v); ?>
    <?php if (1 == $params->get('pre')) echo '</pre>'; ?>
</div>