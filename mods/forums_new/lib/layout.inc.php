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
/* Functions originally in html/forums.inc.php singled out for          */
/* automated testing. Test cases in tests/1000_forum_funcs.t.php.       */
/* To run tests please run run_tests.sh                                 */
/************************************************************************/

define('NUM_PER_PAGE', 10);     // forum/view.php, html/forum.inc.php

/*
 * Calculates the number of pages required to display the given number of items
 * given the desired number of items per page.
 *
 * @param   integer  $number_of_items
 * @param   integer  $items_per_page
 * @param   integer  $number_of_static_items_per_page
 * @return  integer  $number_of_pages
 * @access  public
 */

function number_of_pages( $number_of_items, $items_per_page,
        $number_of_static_items_per_page = 0 ) {

    return (int)floor(($number_of_items - 1)
                    /
            ($items_per_page - $number_of_static_items_per_page)) + 1;
} /* number_of_pages */

/*
 * Calculates the "starting item" number given the page number and the number
 * of items on a page
 *
 * @param  integer  $page_number
 * @param  integer  $items_per_page
 */
function starting_item_number( $page_number, $items_per_page) {
    return ($page_number - 1)*$items_per_page + 1;
}

?>
