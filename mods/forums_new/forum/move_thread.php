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
$forums_d = AT_FORUMS_NEW__DIR;

authenticate(AT_PRIV_FORUMS);

$_REQUEST['pid']  = intval($_REQUEST['pid']);
$_REQUEST['ppid'] = intval($_REQUEST['ppid']);
$_REQUEST['fid']  = intval($_REQUEST['fid']);

if (!valid_forum_user($_REQUEST['fid'])) {
    $msg->addError('FORUM_NOT_FOUND');
    header('Location: list.php');
    exit;
}

if (isset($_POST['cancel'])) {
    $msg->addFeedback('CANCELLED'); 
    header('Location: index.php?fid='.$_REQUEST['fid']);
    exit;

} else if (isset($_POST['submit'])) {
    // check if they have access
    if (!valid_forum_user($_REQUEST['fid']) || !valid_forum_user($_REQUEST['new_fid'])) {
        $msg->addError('FORUM_NOT_FOUND');
        header('Location: list.php');
        exit;
    }

    if ($_REQUEST['fid'] == $_REQUEST['new_fid']) {
        $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
        header('Location: index.php?fid='.$_REQUEST['fid']);
        exit;
    }

    $sql    = "SELECT * FROM ".TABLE_PREFIX."forums_threads WHERE post_id=$_REQUEST[pid] AND forum_id=$_REQUEST[fid]";
    $result = mysql_query($sql, $db);
    if (!($row = mysql_fetch_assoc($result))) {
        $msg->addError('FORUM_NOT_FOUND');
        header('Location: list.php');
        exit;
    } // else:

    /* Decrement count for number of posts and topics*/
    $sql    = "UPDATE ".TABLE_PREFIX."forums SET num_posts=num_posts-1-".$row['num_comments'].", num_topics=num_topics-1, last_post=last_post WHERE forum_id=$_REQUEST[fid]";
    $result = mysql_query($sql, $db);

    $sql    = "UPDATE ".TABLE_PREFIX."forums SET num_posts=num_posts+1+".$row['num_comments'].", num_topics=num_topics+1, last_post=last_post WHERE forum_id=$_REQUEST[new_fid]";
    $result = mysql_query($sql, $db);

    $sql    = "UPDATE ".TABLE_PREFIX."forums_threads SET forum_id=$_REQUEST[new_fid], last_comment=last_comment, date=date WHERE (parent_id=$_REQUEST[pid] OR post_id=$_REQUEST[pid]) AND forum_id=$_REQUEST[fid]";
    $result = mysql_query($sql, $db);

    $msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
    header('Location: index.php?fid='.$_REQUEST['fid']);
    exit;
}

$_pages["$forums_d/forum/index.php?fid=$_REQUEST[fid]"]['title']    = get_forum_name($_REQUEST['fid']);
$_pages["$forums_d/forum/index.php?fid=$_REQUEST[fid]"]['parent']   = "$forums_d/forum/list.php";
$_pages["$forums_d/forum/index.php?fid=$_REQUEST[fid]"]['children'] = array(
    "$forums_d/forum/move_thread.php"
);

$_pages["$forums_d/forum/move_thread.php"]['title_var'] = 'move_thread';
$_pages["$forums_d/forum/move_thread.php"]['parent']    = "$forums_d/forum/index.php?fid=$_REQUEST[fid]";
$_pages["$forums_d/forum/move_thread.php"]['children']  = array();

require(AT_INCLUDE_PATH.'header.inc.php');
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="fid" value="<?php echo $_REQUEST['fid']; ?>" />
<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid']; ?>" />
<input type="hidden" name="ppid" value="<?php echo $_REQUEST['ppid']; ?>" />

<div class="input-form">
    <div class="row">
        <?php echo _AT('move_thread_to');
        $all_forums = get_forums($_SESSION['course_id']);
        ?>
        <ul style="list-style: none">
        <?php foreach($all_forums['nonshared'] as $row): ?>
            <li>
                <input type="radio" name="new_fid" value="<?php echo $row['forum_id']; ?>" id="f<?php echo $row['forum_id']; ?>" <?php if ($row['forum_id'] == $_REQUEST['fid']) { echo 'checked="checked"'; } ?> /><label for="f<?php echo $row['forum_id']; ?>"><?php echo AT_print($row['title'], 'forums.title'); ?></label>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <div class="row buttons">
        <input type="submit" name="submit" value="<?php echo _AT('move'); ?>" />
        <input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" />
    </div>
</div>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>
