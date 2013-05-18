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
/* Test cases for lib/module.inc.php                                    */
/************************************************************************/

require_once('simpletest/autorun.php');
require_once('lib/module.inc.php');

/*
 * From the given module root we can infer what the ATutor root should be,
 * but both this and AT_INCLUDE_PATH is relative to the actual system current
 * directory, so a straight observation does not really work. The correlation
 * between the two must inform our results, and when no such correlation exists
 * we must acknowledge our lack of knowledge and not pretend that we know the
 * answer; in other words, we must throw an exception.
 */

class LibModule_at_include_path_from_standard_TestCase extends UnitTestCase {

    /*
     * Set up a fake environment $this->{'dir'} which acts as the expected
     * ATutor root, so that the expected AT_INCLUDE_PATH would be the include
     * directory in this fake environment.
     *
     * Our original real current directory is saved to $this->{'cwd'};
     */
    function setUp() {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'temp.' . rand();
        $this->{'cwd'} = getcwd();
        $this->{'dir'} = $dir;
        $this->assertTrue(mkdir($dir));
        $this->assertTrue(mkdir("$dir/_mods"));
        $this->assertTrue(mkdir("$dir/_mods/_core"));
        $this->assertTrue(mkdir("$dir/_mods/_standard"));
        $this->assertTrue(mkdir("$dir/_mods/_standard/forums"));
        $this->assertTrue(mkdir("$dir/_mods/_standard/forums/html"));
        $this->assertTrue(mkdir("$dir/_mods/forums_new"));
        $this->assertTrue(mkdir("$dir/_mods/forums_new/html"));
        $this->assertTrue(mkdir("$dir/include"));
    }

    function tearDown() {
        $this->assertTrue(rmdir($this->{'dir'}.'/include'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/forums_new/html'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/forums_new'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/_standard/forums/html'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/_standard/forums'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/_standard'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods/_core'));
        $this->assertTrue(rmdir($this->{'dir'}.'/_mods'));
        $this->assertTrue(rmdir($this->{'dir'}));
        $this->assertTrue(chdir($this->{'cwd'}));
    }

    function test__at_include_path_from__standard_module_root() {
        $dir = $this->{'dir'};
        $det = realpath("$dir/include");
        $this->assertTrue(chdir("$dir/_mods/_standard/forums"));
        $this->assertEqual(realpath(at_include_path_from('.')), $det);
    }

    function test__at_include_path_from__extra_module_root() {
        $dir = $this->{'dir'};
        $det = realpath("$dir/include");
        $this->assertTrue(chdir("$dir/_mods/forums_new"));
        $this->assertEqual(realpath(at_include_path_from('.')), $det);
    }

    function test__at_include_path_from__1_level_below_standard_module_root() {
        $dir = $this->{'dir'};
        $det = realpath("$dir/include");
        $this->assertTrue(chdir("$dir/_mods/_standard/forums/html"));
        $this->assertEqual(realpath(at_include_path_from('..')), $det);
    }

    function test__at_include_path_from__1_level_below_extra_module_root() {
        $dir = $this->{'dir'};
        $det = realpath("$dir/include");
        $this->assertTrue(chdir("$dir/_mods/forums_new/html"));
        $this->assertEqual(realpath(at_include_path_from('..')), $det);
    }

    function test__at_include_path_from__random_location_1() {
        $dir = $this->{'dir'};
        $this->assertTrue(chdir("$dir/_mods"));
        $this->expectException();
        at_include_path_from('.');
    }

    function test__at_include_path_from__random_location_2() {
        $dir = $this->{'dir'};
        $this->assertTrue(chdir("$dir/include"));
        $this->expectException();
        at_include_path_from('.');
    }

    function test__at_include_path_from__random_location_3() {
        $dir = $this->{'dir'};
        $this->assertTrue(chdir($dir));
        $this->expectException();
        at_include_path_from('.');
    }

}

class LibModule_compose_path_TestCase extends UnitTestCase {

    function test__compose_path__1() {
        $this->assertEqual(compose_path('foo', 'bar'), 'foo/bar');
    }

    function test__compose_path__2() {
        $this->assertEqual(compose_path('foo/bar', 'baz'), 'foo/bar/baz');
    }

    function test__compose_path__3() {
        $this->assertEqual(compose_path('foo/bar/', 'baz'), 'foo/bar/baz');
    }

    function test__compose_path__4() {
        $this->assertEqual(compose_path('foo/./', 'bar'), 'foo/bar');
    }

    function test__compose_path__5() {
        $this->assertEqual(compose_path('foo/bar/../', 'baz'), 'foo/baz');
    }

}

?>
