<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2004 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/

$page = 'language';
$_user_location = 'admin';

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }
if (!AT_DEVEL_TRANSLATE) { exit; }

require_once(AT_INCLUDE_PATH . 'classes/Language/LanguageEditor.class.php'); 

$lang =& $languageManager->getLanguage($_GET['lang_code']);
if ($lang === FALSE) {
	require(AT_INCLUDE_PATH.'header.inc.php'); 
	echo '<h3>'._AT('edit_language').'</h3>';
	$errors[] = AT_ERROR_NO_LANGUAGE;

	require(AT_INCLUDE_PATH . 'html/feedback.inc.php');
	require(AT_INCLUDE_PATH.'footer.inc.php'); 
	exit;
}

if (isset($_POST['cancel'])) {
	header('Location: language.php?f='.urlencode_feedback(AT_FEEDBACK_CANCELLED));
	exit;
} else if (isset($_POST['submit'])) {
	$languageEditor =& new LanguageEditor($_GET['lang_code']);
	$errors = $languageEditor->updateLanguage($_POST, $languageManager->exists($_POST['code'], $_POST['locale']));

	if ($errors === TRUE) {
		header('Location: language.php?f=' . urlencode_feedback(AT_FEEDBACK_LANG_UPDATED));
		exit;
	}
}

require(AT_INCLUDE_PATH.'header.inc.php'); 

echo '<h3>'._AT('edit_language').'</h3>';
require(AT_INCLUDE_PATH . 'html/feedback.inc.php');

if (!isset($_POST['submit'])) {
	$_POST['code']         = $lang->getParentCode();
	$_POST['locale']       = $lang->getLocale();
	$_POST['charset']      = $lang->getCharacterSet();
	$_POST['direction']    = $lang->getDirection();
	$_POST['reg_exp']      = $lang->getRegularExpression();
	$_POST['native_name']  = $lang->getNativeName();
	$_POST['english_name'] = $lang->getEnglishName();
}

?>
<br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?lang_code=' . $_GET['lang_code']; ?>">

<input type="hidden" name="old_code" value="<?php echo $lang->getCode(); ?>" />

	<table cellspacing="1" cellpadding="0" class="bodyline" border="0" summary="" align="center">
	<tr>
		<td class="row1" align="right"><label for="code"><?php echo _AT('code'); ?>:</label></td>
		<td class="row1" align="left"><input id="code" name="code" type="text" size="2" maxlength="2" class="formfield" value="<?php echo $_POST['code']; ?>" /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="locale"><?php echo _AT('locale'); ?>:</label></td>
		<td class="row1" align="left"><input id="locale" name="locale" type="text" size="2" maxlength="2" class="formfield" value="<?php echo $_POST['locale']; ?>" /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="charset"><?php echo _AT('charset'); ?>:</label></td>
		<td class="row1" align="left"><input id="charset" name="charset" type="text" size="31" maxlength="20" class="formfield" value="<?php echo $_POST['charset']; ?>" /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="ltr"><?php echo _AT('direction'); ?>:</label></td>
		<?php 
			if ($_POST['direction'] == 'rtl') { 
				$rtl = 'checked="checked"';  
				$ltr='';  
			} else { 
				$rtl = '';  
				$ltr='checked="checked"'; 
			}
		?>
		<td class="row1" align="left"><input id="ltr" name="direction" type="radio" value="ltr" <?php echo $ltr; ?> /><label for="ltr"><?php echo _AT('ltr'); ?></label>, <input id="rtl" name="direction" type="radio" value="rtl" <?php echo $rtl; ?> /><label for="rtl"><?php echo _AT('rtl'); ?></label></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="reg_exp"><?php echo _AT('reg_exp'); ?>:</label></td>
		<td class="row1" align="left"><input id="reg_exp" name="reg_exp" type="text" size="31" class="formfield" value="<?php echo $_POST['reg_exp']; ?>" /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="nname"><?php echo _AT('name_in_language'); ?>:</label></td>
		<td class="row1" align="left"><input id="nname" name="native_name" type="text" size="31" maxlength="20" class="formfield" value="<?php echo $_POST['native_name']; ?>" /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="right"><label for="ename"><?php echo _AT('name_in_english'); ?>:</label></td>
		<td class="row1" align="left"><input id="ename" name="english_name" type="text" size="31" maxlength="20" class="formfield" value="<?php echo $_POST['english_name'];?>" /><br /><br /></td>
	</tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr><td height="1" class="row2" colspan="2"></td></tr>
	<tr>
		<td class="row1" align="center" colspan="2"><br /><input type="submit" name="submit" value="<?php echo _AT('submit'); ?>" class="button" /> | <input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?>" class="button" />
		</td>
	</tr>
	</table>
</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php');  ?>