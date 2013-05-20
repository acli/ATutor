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
/* Test cases for lib/layout.inc.php                                    */
/* Please see https://github.com/acli/ATutor/wiki/ for the rationales   */
/* behind the existence of these specific tests.                        */
/************************************************************************/

require_once('simpletest/autorun.php');
require_once('lib/layout.inc.php');

class LibLayoutTestCase extends UnitTestCase {

    /*
     * The following set of tests aim to make sure there are no "off-by-one"
     * errors in the thread listing as well as on each individual page within
     * a thread.
     */

    function test__number_of_pages_given_1_item_should_be_1() {
        $this->assertEqual(number_of_pages(1, 10), 1);
    }

    function test__number_of_pages_given_10_items_10_per_page_should_be_1() {
        $this->assertEqual(number_of_pages(10, 10), 1);
    }

    function test__number_of_pages_given_11_items_10_per_page_should_be_2() {
        $this->assertEqual(number_of_pages(11, 10), 2);
    }

    /*
     * To simulate the behaviour of the original forums module, forum/view.php
     * should be able to tell us that there is a static element (the original
     * post) that should be included in the calculations. In the original
     * forums code the number of static elements was under-counted and the
     * items per page count was misused; we need to avoid both.
     */

    function test__number_of_pages_given_1_item_with_1_static_item_should_be_1() {
        $this->assertEqual(number_of_pages(1, 10, 1), 1);
    }

    function test__number_of_pages_given_9_items_with_1_static_item_should_be_1() {
        $this->assertEqual(number_of_pages(9, 10, 1), 1);
    }

    function test__number_of_pages_given_10_items_with_1_static_item_should_be_2() {
        $this->assertEqual(number_of_pages(10, 10, 1), 2);
    }

    function test__number_of_pages_given_10_items_with_1_static_item_2_items_per_page_should_be_2() {
        // |(k, 1), (k, 2), (k, 3), (k, 4),..., (k, 10)| = 10
        $this->assertEqual(number_of_pages(10, 2, 1), 10);
    }

    /*
     * These test cases, together with the corresponding function, are not
     * really useful and so they are probably going to be scrapped soon.
     */
    function test__starting_item_number_for_page_1_should_be_1() {
        $this->assertEqual(starting_item_number(1, 10), 1);
    }

    function test__starting_item_number_for_page_2_10_per_page_should_be_11() {
        $this->assertEqual(starting_item_number(2, 10), 11);
    }

}

?>
