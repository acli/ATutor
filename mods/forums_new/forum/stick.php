<?php // -*- mode: php; c-basic-offset: 4: -*- vi: set sw=4 et ai sm:
/****************************************************************/
/* ATutor                                                       */
/****************************************************************/
/* Copyright (c) 2002-2010, 2013                                */
/* Inclusive Design Institute                                   */
/* http://atutor.ca                                             */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.                */
/****************************************************************/

define('AT_MODULE_ROOT', '../');
require(AT_MODULE_ROOT.'lib/module.inc.php');
define('AT_INCLUDE_PATH', at_include_path_from(AT_MODULE_ROOT));
require(AT_INCLUDE_PATH.'vitals.inc.php');
$forums_d = MODULE_DIR;

authenticate(AT_PRIV_FORUMS);

$table_prefix = TABLE_PREFIX;
$at_base_href = AT_BASE_HREF;
$pid = intval($_GET['pid']);
$fid = intval($_GET['fid']);

$sql = <<<EOT
UPDATE {$table_prefix}forums_threads
   SET sticky=CASE sticky WHEN 1 THEN 0 ELSE 1 END,
       last_comment=last_comment,
       date=date
 WHERE post_id=$pid
EOT;
$result = mysql_query($sql, $db);

$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');

header("Location: $at_base_href$forums_d/forum/index.php?fid=$fid");
exit;

?>
