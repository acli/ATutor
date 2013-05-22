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
/* This file specifies the privilege levels for the new forums module.      */
/* Standard modules have automatic privilege levels so they just work,      */
/* but "extra" modules must have their privilege levels explicitly          */
/* declared or else things that are supposed to work in module.php will     */
/* not work.                                                                */
/* This file also creates a dummy content directory in case uninstall       */
/* actually works. Since we share the same SQL tables as the original       */
/* forums module we are not touching the SQL tables here at all.            */
/****************************************************************************/

if (!defined('AT_INCLUDE_PATH')) { exit; }

/*******
 * Note: the many options for these variables are used to decrease confusion.
 *       TRUE | FALSE | 1 will be the convention.
 *
 * $_course_privilege
 *     specifies the type of instructor privilege this module uses.
 *     set to empty | FALSE | 0   to disable any privileges.
 *     set to 1 | AT_PRIV_ADMIN   to use the instructor only privilege.
 *     set to TRUE | 'new'        to create a privilege specifically for this module:
 *                                will make this module available as a student privilege.
 *
 * $_admin_privilege
 *    specifies the type of ATutor administrator privilege this module uses.
 *    set to FALSE | AT_ADMIN_PRIV_ADMIN   to use the super administrator only privilege.
 *    set to TRUE | 'new'                  to create a privilege specifically for this module:
 *                                         will make this module available as an administrator privilege.
 *
 *
 * $_cron_interval
 *    if non-zero specifies in minutes how often the module's cron job should be run.
 *    set to 0 or not set to disable.
 */
 
/****
 * Modules can be limited to installation on main ATutor sites only in a multisite installation by 
 * adding the following line to the module_install.php file 
 ***/
//if (defined('IS_SUBSITE') && IS_SUBSITE) {
//      $msg->addError(array('MODULE_INSTALL', 'This module cannot be installed on subsites.'));
//}



$_course_privilege = TRUE; // possible values: FALSE | AT_PRIV_ADMIN | TRUE
$_admin_privilege  = TRUE; // possible values: FALSE | TRUE
//$_cron_interval    = 35; // run every 30 minutes


/********
 * Create a dummy module-specific content directory so that uninstall might work
 */
$directory = AT_CONTENT_DIR .'forums';

// check if the directory is writeable
if (!is_dir($directory) && !@mkdir($directory)) {
        $msg->addError(array('MODULE_INSTALL', '<li>'.$directory.' does not exist. Please create it.</li>'));
} else if (!is_writable($directory) && @chmod($directory, 0666)) {
        $msg->addError(array('MODULE_INSTALL', '<li>'.$directory.' is not writeable. On Unix issue the command <kbd>chmod a+rw</kbd>.</li>'));
}



?>
