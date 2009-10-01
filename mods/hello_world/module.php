<?php
/*******
 * doesn't allow this file to be loaded with a browser.
 */
if (!defined('AT_INCLUDE_PATH')) { exit; }

/******
 * this file must only be included within a Module obj
 */
if (!isset($this) || (isset($this) && (strtolower(get_class($this)) != 'module'))) { exit(__FILE__ . ' is not a Module'); }

/*******
 * assign the instructor and admin privileges to the constants.
 */
define('AT_PRIV_HELLO_WORLD',       $this->getPrivilege());
define('AT_ADMIN_PRIV_HELLO_WORLD', $this->getAdminPrivilege());

/*******
 * create a side menu box/stack.
 */
$this->_stacks['hello_world'] = array('title_var'=>'hello_world', 'file'=>'mods/hello_world/side_menu.inc.php');
// ** possible alternative: **
// $this->addStack('hello_world', array('title_var' => 'hello_world', 'file' => './side_menu.inc.php');

/*******
 * create optional sublinks for module "detail view" on course home page
 * when this line is uncommented, "mods/hello_world/sublinks.php" need to be created to return an array of content to be displayed
 */
//$this->_list['hello_world'] = array('title_var'=>'hello_world','file'=>'mods/hello_world/sublinks.php');

// Uncomment for tiny list bullet icon for module sublinks "detail view" on course home page
//$this->_pages['mods/hello_world/index.php']['icon']      = 'mods/hello_world/hello_world_sm.jpg';

// ** possible alternative: **
// the text to display on module "detail view" when sublinks are not available
$this->_pages['mods/hello_world/index.php']['text']      = _AT('hello_world_text');

/*******
 * if this module is to be made available to students on the Home or Main Navigation.
 */
$_group_tool = $_student_tool = 'mods/hello_world/index.php';

/*******
 * add the admin pages when needed.
 */
if (admin_authenticate(AT_ADMIN_PRIV_HELLO_WORLD, TRUE) || admin_authenticate(AT_ADMIN_PRIV_ADMIN, TRUE)) {
	$this->_pages[AT_NAV_ADMIN] = array('mods/hello_world/index_admin.php');
	$this->_pages['mods/hello_world/index_admin.php']['title_var'] = 'hello_world';
	$this->_pages['mods/hello_world/index_admin.php']['parent']    = AT_NAV_ADMIN;
}

/*******
 * instructor Manage section:
 */
$this->_pages['mods/hello_world/index_instructor.php']['title_var'] = 'hello_world';
$this->_pages['mods/hello_world/index_instructor.php']['parent']   = 'tools/index.php';
// ** possible alternative: **
// $this->pages['./index_instructor.php']['title_var'] = 'hello_world';
// $this->pages['./index_instructor.php']['parent']    = 'tools/index.php';

/*******
 * student page.
 */
$this->_pages['mods/hello_world/index.php']['title_var'] = 'hello_world';
$this->_pages['mods/hello_world/index.php']['img']       = 'mods/hello_world/hello_world.jpg';

/* public pages */
$this->_pages[AT_NAV_PUBLIC] = array('mods/hello_world/index_public.php');
$this->_pages['mods/hello_world/index_public.php']['title_var'] = 'hello_world';
$this->_pages['mods/hello_world/index_public.php']['parent'] = AT_NAV_PUBLIC;

/* my start page pages */
$this->_pages[AT_NAV_START]  = array('mods/hello_world/index_mystart.php');
$this->_pages['mods/hello_world/index_mystart.php']['title_var'] = 'hello_world';
$this->_pages['mods/hello_world/index_mystart.php']['parent'] = AT_NAV_START;

function hello_world_get_group_url($group_id) {
	return 'mods/hello_world/index.php';
}
?>