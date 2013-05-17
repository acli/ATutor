<?php // -*- mode: php; c-basic-offset: 4: -*- vi: set sw=4 et ai sm:
/****************************************************************************/
/* ATutor                                                                   */
/****************************************************************************/
/* Copyright (c) 2002-2010, 2013                                            */
/* Inclusive Design Institute                                               */
/* http://atutor.ca                                                         */
/*                                                                          */
/* This program is free software. You can redistribute it and/or            */
/* modify it under the terms of the GNU General Public License              */
/* as published by the Free Software Foundation.                            */
/****************************************************************************/

define('AT_MODULE_ROOT', '../');
require(AT_MODULE_ROOT.'lib/module.inc.php');
define('AT_INCLUDE_PATH', at_include_path_from(AT_MODULE_ROOT));
require(AT_INCLUDE_PATH.'vitals.inc.php');
$forums_d = MODULE_DIR;
$fid = intval($_GET['fid']);

if (!isset($_GET['fid']) || !$fid) {
    header('Location: list.php');
    exit;
}
require(AT_INCLUDE_PATH."../$forums_d/lib/forums.inc.php");

if (!valid_forum_user($fid)) {
    require(AT_INCLUDE_PATH.'header.inc.php');
    $msg->addError('FORUM_DENIED');
    $msg->printErrors();
    require(AT_INCLUDE_PATH.'footer.inc.php');
}

$_pages["$forums_d/forum/index.php"]['title']    = get_forum_name($fid);
$_pages["$forums_d/forum/index.php"]['parent']   = "$forums_d/forum/list.php";
$_pages["$forums_d/forum/index.php"]['children'] = array(
    "$forums_d/forum/new_thread.php?fid=$fid",
    'search.php?search_within[]=forums',
);

$_pages["$forums_d/forum/new_thread.php?fid=$fid"]['title_var'] = 'new_thread';
$_pages["$forums_d/forum/new_thread.php?fid=$fid"]['parent']    = "$forums_d/forum/index.php";

$_pages['search.php?search_within[]=forums']['title_var'] = 'search';
$_pages['search.php?search_within[]=forums']['parent']    = "$forums_d/forum/index.php?fid=$fid";

/* the last accessed field */
$last_accessed = array();
if ($_SESSION['valid_user'] === true && $_SESSION['enroll']) {
    $sql    = "SELECT post_id, last_accessed + 0 AS last_accessed, subscribe FROM ".TABLE_PREFIX."forums_accessed WHERE member_id=$_SESSION[member_id]";
    $result = mysql_query($sql, $db);
    while ($row = mysql_fetch_assoc($result)) {
        $post_id = $row['post_id'];
        unset($row['post_id']);
        $last_accessed[$post_id] = $row;

    }
}

require(AT_INCLUDE_PATH . 'header.inc.php');

require(AT_INCLUDE_PATH . '../mods/_standard/forums/html/forum.inc.php');

require(AT_INCLUDE_PATH . 'footer.inc.php');
?>
