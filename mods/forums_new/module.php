<?php // -*- mode: php; c-basic-offset: 4: -*- vi: set sw=4 et ai sm:

/******************************************************************************
 * File 1 of 3 of PHP portion of the new forum module's module definition (the
 * other being module_install.php and the third being module_uninstall.php).
 * The XML part, which is only used during installation, is module.xml.
 *
 * WARNING: Due to insufficiencies in the design of ATutor's architecture, it
 * is impossible to make the rest of this module depend only on this or any
 * other file.  Most dependencies have been isolated into lib/module.inc.php,
 * but if you need to change the module's name (such as when the time comes to
 * move it to mods/_standard space), make sure you check module_delete.php,
 * module_groups.php, and module_news.php for hard-coded id's and pathnames.
 ******************************************************************************/

if (!defined('AT_INCLUDE_PATH')) { exit; }
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

$real_id = 'forums';      // what we REALLY want to be known as, eventually

$id = 'forums_new';       // what we are currently known as
$dir = 'mods/forums_new'; // where we currently reside at

define("AT_PRIV_FORUMS",       $this->getPrivilege() );
define("AT_ADMIN_PRIV_FORUMS", $this->getAdminPrivilege() );

// Side menu - Note that the standard forums module uses 'posts' instead of $id
$this->_stacks['posts'] = array(
    'title_var' => 'posts',
    'file'      => AT_INCLUDE_PATH."../$dir/dropdown/posts.inc.php",
);

// Tool manager
$this->_tool[$id] = array(
    'title_var' => $id,
    'file'      => 'mods/_core/tool_manager/forums_tool.php',
);

/******************************************************************************
 * Instructor's pages:
 *
 * The entry point for the instructor's management tool chain must have its
 * parent set to tools/index.php in order for it to appear under the Manage
 * option within each course.
 *
 * Note: $_course_privilege and $_admin_privilege must be also specified in
 * module_install.php at install time or else things will not work. The day
 * we move into mods/_standard space this will not be required, but until
 * then we need module_install.php.
 */

// Management tool chain at index.php (as opposed to forum/list.php)
$this->_pages["$dir/index.php"]['title_var'] = $real_id;
$this->_pages["$dir/index.php"]['parent']    = 'tools/index.php';
$this->_pages["$dir/index.php"]['guide']     = 'instructor/?p=forums.php';
$this->_pages["$dir/index.php"]['children']  = array(
    "$dir/add_forum.php"
);

// Various other instructor-only functions
$this->_pages["$dir/add_forum.php"]['title_var']  = 'create_forum';
$this->_pages["$dir/add_forum.php"]['parent'] = "$dir/index.php";

$this->_pages["$dir/delete_forum.php"]['title_var']  = 'delete_forum';
$this->_pages["$dir/delete_forum.php"]['parent'] = "$dir/index.php";

$this->_pages["$dir/edit_forum.php"]['title_var'] = 'edit_forum';
$this->_pages["$dir/edit_forum.php"]['parent'] = "$dir/index.php";

/******************************************************************************
 * Student's pages
 *
 * The entry point(s) for the student's tool chain is specified in $_group_tool
 * and $_student_tool. These variables are dynamically inherited from
 * mods/_core/modules/classes/Module.class.php so they don't need to be
 * declared global. Note that if $_student_tool and $_group_tool are different
 * we must define their behaviours separately; in this case they are the same.
 *
 * Note that the course instructor must still manually enable the module in
 * the Course Tools and Student Tools management panels.
 *
 * Our "sublinks.php" goes here. What it is is the little blurb that goes
 * under the module's box in the course home page, and it is keyed to the
 * title_var of the $_group_tool or $_student_tool entry point (but keyed
 * under $this->_list instead of $this->_pages). sublinks.php should act like
 * a function that returns something and if it does not return anything the
 * value of the 'text' key is used instead.
 *
 * There is actually no requirement that there be a sublinks.php file; there is
 * not even a requirement that it be called sublinks.php. All that is required
 * is that it's keyed to the correct title_var in the $this->_list array.
 */

$_group_tool = $_student_tool = "$dir/forum/list.php";

$this->_pages[$_student_tool]['title_var'] = $real_id;
$this->_pages[$_student_tool]['img']       = 'images/home-forums.png';
$this->_pages[$_student_tool]['icon']      = 'images/pin.png'; // favicon
$this->_pages[$_student_tool]['children']  = array(
    'search.php?search_within[]=forums'
);
// NOTE: $this->_pages[$_student_tool]['title_var'] is of course just $real_id,
//       but writing it this way makes it absolutely clear why we want $real_id
$this->_list[$this->_pages[$_student_tool]['title_var']] = array(
    'title_var' => $id,
    'file'      => "$dir/sublinks.php",
);
//$this->_pages[$_student_tool]['text']  = '';  // sublinks fallback

// list.php's children
$this->_pages['search.php?search_within[]=forums']['title_var'] = 'search';
$this->_pages['search.php?search_within[]=forums']['parent']    = "$dir/index.php";

/******************************************************************************
 * ATutor administrator use only
 */

if (admin_authenticate(AT_ADMIN_PRIV_FORUMS, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
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

/******************************************************************************
 * FIXME: Need to figure out what this does.
 * @param   TODO $group_id      id of the course
 */

function forums_get_group_url($group_id) {
    global $db;
    $sql = "SELECT forum_id FROM ".TABLE_PREFIX."forums_groups WHERE group_id=$group_id";
    $result = mysql_query($sql, $db);
    $row = mysql_fetch_assoc($result);

    return "$dir/forum/index.php?fid=".$row['forum_id'];
}
?>
