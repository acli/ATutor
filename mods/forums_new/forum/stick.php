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

$pid = intval($_GET['pid']);

/*
 * NOTE: "1-sticky" emulates a NOT operation (if 1 then 0, if 0 then 1).
 * The original code used ABS(sticky-1), but within the valid range of 0 and 1
 * the simpler and more idiomatic formula "1-sticky" works just as well.
 * When out of range, both formulas give the wrong results and at the invalid
 * value of -9999 both cause overflow errors, so none is better than the other.
 */
$sql    = "UPDATE ".TABLE_PREFIX."forums_threads SET sticky=1-sticky, last_comment=last_comment, date=date WHERE post_id=$pid";
$result = mysql_query($sql, $db);

$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');

header('Location: '.AT_BASE_HREF.$forums_d.'/forum/index.php?fid='.intval($_GET['fid']));
exit;

?>
