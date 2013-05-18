<?php // -*- mode: php; c-basic-offset: 4: -*- vi: set sw=4 et ai sm:
/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2002-2010, 2013                                        */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
/* Module-dependent stuff outside of module.xml, module.php,            */
/* module_delete.php, module_groups.php, and module_news.php            */
/* that used to be hard-coded in the original forums module             */
/************************************************************************/

define('MODULE_ID', 'forums_new');
define('MODULE_DIR', 'mods/forums_new');

/*
 * A bit of hackery to figure out if we are a standard or an extra module
 *
 * @param   string  $module_root  relative path to module's top-level directory
 * @return  string  $at_include_path
 */
function at_include_path_from( $module_root ) {
    $det = $module_root . (substr($module_root, -1) == '/'? '': '/') . '..';
    if (is_dir("$det/_core") && is_dir("$det/_standard")) { // We're an extra
        $at_include_path = "$det/../include/";
    } elseif (is_dir("$det/../_standard")) {
        $at_include_path = "$det/../../include/";
    } else {
        throw new Exception('Invalid module root ' . $module_root);
    }
    return $at_include_path;
}

/*
 * Concatenate the given subpaths and eliminate . and .. segments.
 *
 * @return  string  $path
 */
function compose_path() {
    $subpaths = func_get_args();
    $path = '';
    foreach ($subpaths as $subpath) {
        $c = substr($path, -1);
        if ($path && $c != DIRECTORY_SEPARATOR && $c != '/') {
            $path .= '/';
        }
        $path .= $subpath;
    }
    print "\n\npath=($path)\n";
    $sep_re = (DIRECTORY_SEPARATOR == '/')? '\/':
            ('(?:(?:\/|' . addslashes(DIRECTORY_SEPARATOR) . '))');
    $not_sep_re = (DIRECTORY_SEPARATOR == '/')? '[^\/]':
            ('(?!(?:\/|' . addslashes(DIRECTORY_SEPARATOR) . '))');
    $path = preg_replace("/$sep_re\\.$sep_re/", '/', $path);
    $path = preg_replace("/$not_sep_re+$sep_re\\.\\.$sep_re($not_sep_re+)/", '$1',
            $path);
    $path = preg_replace("/^\.$sep_re+/", '', $path);
    print "\n\npath=($path)\n";
    return ($path);
}

?>
