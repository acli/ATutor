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
include(AT_MODULE_ROOT.'lib/forums.inc.php');

$fid = intval($_REQUEST['fid']);

// check if they have access
if (!valid_forum_user($fid) || !$_SESSION['enroll']) {
    $msg->addError('FORUM_NOT_FOUND');
    header('Location: list.php');
    exit;
}

$sql = "SELECT title FROM ".TABLE_PREFIX."forums WHERE forum_id=$fid";
$result = mysql_query($sql, $db);
if ($row = mysql_fetch_assoc($result)) {
    $forum_title = $row['title'];
} else {
    $msg->addError('FORUM_NOT_FOUND');
    header('Location: list.php');
    exit;
}

if (isset($_GET['us'])) {
    $sql = "DELETE from ".TABLE_PREFIX."forums_subscriptions WHERE forum_id = $fid AND member_id = $_SESSION[member_id]";
    $result = mysql_query($sql, $db);
    $msg->addFeedback(array(FORUM_UNSUBSCRIBED, $forum_title));

} else {
    $sql = "INSERT into ".TABLE_PREFIX."forums_subscriptions VALUES($fid, '$_SESSION[member_id]')";
    mysql_query($sql, $db);

    $msg->addFeedback(array(FORUM_SUBSCRIBED,$forum_title));
}

header('Location: list.php');
exit;
?>
