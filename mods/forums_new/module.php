<?php
/* vi: set sw=4 et ai sm: # vi mode line - DO NOT MOVE OR REMOVE */

if (!defined('AT_INCLUDE_PATH')) { exit; }
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

$id = 'forums_new';
$dir = 'mods/forums_new';

$id_uc = strtoupper($id);
define("AT_PRIV_$id_uc",       $this->getPrivilege() );
define("AT_ADMIN_PRIV_$id_uc", $this->getAdminPrivilege() );

// Specify the main page for this module as a student tool and as a group tool
$_group_tool = $_student_tool = "$dir/forum/list.php";

// Side menu - Note that the standard forums module uses 'posts' instead of $id
$this->_stacks['posts'] = array(
    'title_var' => 'posts',
    'file'      => AT_INCLUDE_PATH."../$dir/dropdown/posts.inc.php",
);

// Module-specific sub-content in course details page
$this->_list[$id] = array(
    'title_var' => $id,
    'file'      => "$dir/sublinks.php",
);

// Tool manager
$this->_tool[$id] = array('title_var'=>$id,'file'=>'mods/_core/tool_manager/forums_tool.php');

// Instructor pages
$this->_pages["$dir/index_instructor.php"]['title_var'] = $id;
$this->_pages["$dir/index_instructor.php"]['parent']    = 'tools/index.php';
$this->_pages["$dir/index.php"]['title_var'] = $id;
$this->_pages["$dir/index.php"]['parent']    = 'tools/index.php';
$this->_pages["$dir/index.php"]['guide']     = 'instructor/?p=forums.php';
$this->_pages["$dir/index.php"]['children']  = array("$dir/add_forum.php");

$this->_pages["$dir/add_forum.php"]['title_var']  = 'create_forum';
$this->_pages["$dir/add_forum.php"]['parent'] = "$dir/index.php";

$this->_pages["$dir/delete_forum.php"]['title_var']  = 'delete_forum';
$this->_pages["$dir/delete_forum.php"]['parent'] = "$dir/forums/index.php";

$this->_pages["$dir/edit_forum.php"]['title_var'] = 'edit_forum';
$this->_pages["$dir/edit_forum.php"]['parent'] = "$dir/index.php";

// Student pages
$this->_pages["$dir/forum/list.php"]['title_var'] = $id;
$this->_pages["$dir/forum/list.php"]['img']       = 'images/home-forums.png';
$this->_pages["$dir/forum/list.php"]['icon']      = 'images/pin.png';       //added favicon
$this->_pages["$dir/forum/list.php"]['text']      = 'Sezione Forum';                //added text
$this->_pages["$dir/forum/list.php"]['children']  = array('search.php?search_within[]=forums');
    //list.php"s children
    $this->_pages['search.php?search_within[]=forums']['title_var'] = 'search';
    $this->_pages['search.php?search_within[]=forums']['parent']    = "$dir/index.php";

// For admin
if (admin_authenticate(AT_ADMIN_PRIV_FORUMS_NEW, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN_NEW, TRUE)) {
    if (admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
        $this->_pages['mods/_core/courses/admin/courses.php']['children'] = array("$dir/admin/forums.php");
        $this->_pages["$dir/admin/forums.php"]['parent'] = 'mods/_core/courses/admin/courses.php';
    } else {
        $this->_pages[AT_NAV_ADMIN] = array("$dir/admin/forums.php");
        $this->_pages["$dir/admin/forums.php"]['parent'] = AT_NAV_ADMIN;
    }

    $this->_pages["$dir/admin/forums.php"]['title_var'] = $id;
    $this->_pages["$dir/admin/forums.php"]['guide']     = "$dir/admin/?p=forums.php";
    $this->_pages["$dir/admin/forums.php"]['children']  = array("$dir/admin/forum_add.php");

    $this->_pages["$dir/admin/forum_add.php"]['title_var'] = 'create_forum';
    $this->_pages["$dir/admin/forum_add.php"]['parent']    = "$dir/admin/forums.php";

    $this->_pages["$dir/admin/forum_edit.php"]['title_var'] = 'edit_forum';
    $this->_pages["$dir/admin/forum_edit.php"]['parent']    = "$dir/admin/forums.php";

    $this->_pages["$dir/admin/forum_delete.php"]['title_var'] = 'delete_forum';
    $this->_pages["$dir/admin/forum_delete.php"]['parent']    = "$dir/admin/forums.php";
}

function forums_get_group_url($group_id) {
    global $db;
    $sql = "SELECT forum_id FROM ".TABLE_PREFIX."forums_groups WHERE group_id=$group_id";
    $result = mysql_query($sql, $db);
    $row = mysql_fetch_assoc($result);

    return "$dir/forum/index.php?fid=".$row['forum_id'];
}
?>
