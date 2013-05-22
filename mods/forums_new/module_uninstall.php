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
/* This file removes the dummy content directory created at install time.   */
/* The installer may or may not allow this. If it does not allow you to     */
/* uninstall, just manually rmdir the dummy directory and manually delete   */
/* the table row in AT_modules corresponding to this module; i.e.,          */
/* delete from at_modules where dir_name='forums_new';                      */
/****************************************************************************/

if (!defined('AT_INCLUDE_PATH')) { exit; }

/********
 * Remove the dummy module-specific content directory
 */
$directory = AT_CONTENT_DIR .'forums';

// check if the directory exists
if (is_dir($directory)) {
        require(AT_INCLUDE_PATH.'lib/filemanager.inc.php');

        if (!clr_dir($directory))
                $msg->addError(array('MODULE_UNINSTALL', '
'.$directory.' can not be removed. Please manually remove it.
'));
}

?>
