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
$forums_d = AT_FORUMS_NEW__DIR;

$fid = intval($_GET['fid']);
$_GET['reply'] = isset($_GET['reply']) ? $_GET['reply'] : '';


if (!isset($_GET['fid']) || !$fid) {
    header('Location: list.php');
    exit;
}

require(AT_MODULE_ROOT."/lib/layout.inc.php"); // number_of_pages
require(AT_MODULE_ROOT."/lib/forums.inc.php"); // for print_entry et al

if (!valid_forum_user($fid)) {
    require(AT_INCLUDE_PATH.'header.inc.php');
    $msg->printErrors('FORUM_DENIED');
    require(AT_INCLUDE_PATH.'footer.inc.php');
    exit;
}

// set default thread display order to ascending
if (!isset($_SESSION['thread_order']))
{
    $_SESSION['thread_order'] = 'a';
}
else if (isset($_GET['order']))
{
    $_SESSION['thread_order'] = $_GET['order'];
}

$forum_info = get_forum($fid);

$_pages[url_rewrite("$forums_d/forum/index.php?fid=$fid")]['title']    = get_forum_name($fid);
$_pages[url_rewrite("$forums_d/forum/index.php?fid=$fid")]['parent']   = "$forums_d/forum/list.php";
$_pages[url_rewrite("$forums_d/forum/index.php?fid=$fid")]['children'] = array(
    url_rewrite("$forums_d/forum/new_thread.php?fid=$fid"),
    'search.php?search_within[]=forums',
);

$_pages[url_rewrite("$forums_d/forum/new_thread.php?fid=$fid")]['title_var'] = 'new_thread';
$_pages[url_rewrite("$forums_d/forum/new_thread.php?fid=$fid")]['parent']    = url_rewrite("$forums_d/forum/index.php?fid=$fid");

$_pages["$forums_d/forum/view.php"]['parent'] = url_rewrite("$forums_d/forum/index.php?fid=$fid");
$_pages['search.php?search_within[]=forums']['title_var'] = 'search';
$_pages['search.php?search_within[]=forums']['parent']    = url_rewrite("$forums_d/forum/index.php");

if ($_REQUEST['reply']) {
    $onload = 'document.form.subject.focus();';
}

$pid = intval($_GET['pid']);

$num_per_page = NUM_PER_PAGE;

/* get the first thread first */
$sql    = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d %H:%i:%s') AS date, UNIX_TIMESTAMP(date) AS udate FROM ".TABLE_PREFIX."forums_threads WHERE post_id=$pid AND forum_id=$fid";
$result = mysql_query($sql, $db);

if (!($post_row = mysql_fetch_array($result))) {
    require(AT_INCLUDE_PATH.'header.inc.php');
    $_pages["$forums_d/forum/view.php"]['title']  = _AT('no_post');

    echo _AT('no_post');
    require(AT_INCLUDE_PATH.'footer.inc.php');
    exit;
}

$_pages["$forums_d/forum/view.php"]['title']  = $post_row['subject'];

require(AT_INCLUDE_PATH.'header.inc.php');

?>
    <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>#post" style="border: 0px;"><img src="<?php echo $_base_path; ?>images/clr.gif" height="1" width="1" border="0" alt="<?php echo _AT('reply'); ?>" /></a>
<?php
    /**
    * Jacek M.
    * Protect data consistency
    * Make sure the pid we are inserting is actually a thread post_id, otherwise we get dangling pointers
    * in the case of injection
    */

    if ($_SESSION['valid_user'] === true) {
        $sql2 = "INSERT INTO ".TABLE_PREFIX."forums_accessed VALUES ($pid, $_SESSION[member_id], NOW(), 0)";
        $result2 = mysql_query($sql2, $db);
        if (!$result2) {
            $sql2 = "UPDATE ".TABLE_PREFIX."forums_accessed SET last_accessed=NOW() WHERE post_id=$pid AND member_id=$_SESSION[member_id]";
            $result2 = mysql_query($sql2, $db);
        }
    }
    
    /*
     * Determine the number of pages, and then use that to constrain the page
     * number (one-based) that we got from the CGI query. Then we use the page
     * number to arrive at a comment number (zero-based) for use in our SQL
     * queries. The original post in the thread is not counted as one of the
     * $num_per_page items on the page.
     */
    $num_pages = number_of_pages($post_row['num_comments'], $num_per_page);
    if (!$_GET['page']) {
        $page = 1;
    } else {
        $page = intval($_GET['page']);
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $num_pages) {
            $page = $num_pages;
        }
    }
    $start = ($page-1)*$num_per_page;

    $locked = $post_row['locked'];
    if ($locked == 1) {
        echo '<p><strong>'._AT('lock_no_read1').'</strong></p>';
        require(AT_INCLUDE_PATH.'footer.inc.php');
        exit;
    }

    $parent_name = $post_row['subject'];

    echo '<ul class="forum-thread">';
    print_entry($post_row);
    $subject = $post_row['subject'];
    if ($_GET['reply'] == $post_row['post_id']) {
        $saved_post = $post_row;
    }
    echo '</ul>
      <div class="forum-paginator">&nbsp;
      </div><br />';

    $AT_ = TABLE_PREFIX;
    $ASC_or_DESC = ($_SESSION['thread_order'] == 'a')? 'ASC': 'DESC';
    $sql = <<<EOT
SELECT *,
            DATE_FORMAT(date, '%Y-%m-%d %H-%i:%s') AS date,
            UNIX_TIMESTAMP(date) AS udate
       FROM {$AT_}forums_threads
 WHERE parent_id=$pid AND forum_id=$fid
 ORDER BY date $ASC_or_DESC
 LIMIT $start, $num_per_page
EOT;
    
    $result = mysql_query($sql, $db);

    if (mysql_num_rows($result) > 0)
    {
        echo '<div class="forum-paginator">';
        echo '<div style="float:right;">';
        if ($_SESSION['thread_order'] == 'a')
            echo '<a href="'.url_rewrite($_SERVER['PHP_SELF'].'?fid='.$fid.SEP.'pid='.$pid.SEP.'page='.$page.SEP.'order=d').'">
                  <img src="'.AT_BASE_HREF.'images/up.png" border="0" alt="">&nbsp;'._AT('recent_first').'
                </a>';
        else
            echo '<a href="'.url_rewrite($_SERVER['PHP_SELF'].'?fid='.$fid.SEP.'pid='.$pid.SEP.'page='.$page.SEP.'order=a').'">
                  <img src="'.AT_BASE_HREF.'images/down.png" border="0" alt="">&nbsp;'._AT('recent_last').'
                </a>';
        
        echo '</div>';
        
        echo _AT('page').': ';
        for ($i=1; $i<=$num_pages; $i++) {
            if ($i == $page) {
                echo '<span class="forum-paginator-active">'.$i.'</span>';
            } else {
                echo '<a href="'.url_rewrite($_SERVER['PHP_SELF'].'?fid='.$fid.SEP.'pid='.$pid.SEP.'page='.$i.SEP.'order='.$_SESSION['thread_order']).'">'.$i.'</a>';
            }
    
            if ($i<$num_pages){
                echo ' <span class="spacer">|</span> ';
            }
        }
        echo '</div>';
        echo '<ul class="forum-thread">';
    
        while ($row = mysql_fetch_assoc($result)) {
            print_entry($row);
            $subject = $row['subject'];
            if ($_GET['reply'] == $row['post_id']) {
                $saved_post = $row;
            }
        }
        echo '</ul>';
        
        echo '<div  class="forum-paginator">';
        echo _AT('page').': ';
        for ($i=1; $i<=$num_pages; $i++) {
            if ($i == $page) {
                echo '<span class="forum-paginator-active">'.$i.'</span>';
            } else {
                echo '<a href="'.url_rewrite($_SERVER['PHP_SELF'].'?fid='.$fid.SEP.'pid='.$pid.SEP.'page='.$i.SEP.'order='.$_SESSION['thread_order']).'">'.$i.'</a>';
            }
    
            if ($i<$num_pages){
                echo ' <span class="spacer">|</span> ';
            }
        }
        echo '</div>';
    }

    $parent_id = $pid;
    $body      = '';
    if (substr($subject,0,3) != 'Re:') {
        $subject = 'Re: '.$subject;
    }
    
    if ($_SESSION['valid_user'] === true && $_SESSION['enroll'] && !$locked) {
        $sql    = "SELECT subscribe FROM ".TABLE_PREFIX."forums_accessed WHERE post_id=$_GET[pid] AND member_id=$_SESSION[member_id]";
        $result = mysql_query($sql, $db);
        $row = mysql_fetch_assoc($result);
        if ($row['subscribe']) {
            echo '<p><a href="'.$forums_d.'/forum/subscribe.php?fid='.$fid.SEP.'pid='.$_GET['pid'].SEP.'us=1">'._AT('unsubscribe').'</a></p>';
            $subscribed = true;
        } else {
            echo '<p><a href="'.$forums_d.'/forum/subscribe.php?fid='.$fid.SEP.'pid='.$_GET['pid'].'">'._AT('subscribe').'</a></p>';
            $subscribed = false;
        }
    }
    if ($_SESSION['valid_user'] === true && !$_SESSION['enroll']) {
        echo '<p><strong>'._AT('enroll_to_post').'</strong></p>';
    } else if ($locked == 0) {
        require(AT_INCLUDE_PATH."../$forums_d/html/new_thread.inc.php");
    } else {
        echo '<p><strong>'._AT('lock_no_post1').'</strong></p>';
    }

require(AT_INCLUDE_PATH.'footer.inc.php');
?>
