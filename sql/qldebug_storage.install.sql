/**
 * @package		plg_system_qldebug
 * @copyright	Copyright (C) 2015 ql.de All rights reserved.
 * @author 		Ingo Holewczuk info@ql.de; Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

CREATE TABLE IF NOT EXISTS `#__qldebug_storage` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `user_id` int(4) NOT NULL,
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
  `includedfiles` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;