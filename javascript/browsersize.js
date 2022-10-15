/**
 * @package		plg_system_qldebug
 * @copyright	Copyright (C) 2015 ql.de All rights reserved.
 * @author 		Ingo Holewczuk info@ql.de; Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
jQuery( document ).ready(function()
{
    var html=getSize();
    assignHtml(html)
    jQuery(window).resize(function()
    {
        var html=getSize();
        assignHtml(html)
    });

    function getSize()
    {
        var html='';
        html+='Window-size: ';
        html+=jQuery(window).width();
        html+='/';
        html+=jQuery(window).height();
        html+='<br />';
        html+='Html-size: ';
        html+=jQuery(document).width();
        html+='/';
        html+=jQuery(document).height();
        return html;
    }

    function assignHtml(html)
    {
        jQuery('#browsersizeContent').html(html);
    }
});



