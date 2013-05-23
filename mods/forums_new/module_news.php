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
/*
 * Get the latest updates of this module
 * @return list of news, [timestamp]=>
 */
function forums_new_news() {

    // Apparently module.php is in effect, so we know where we are, sort of
    require_once(AT_INCLUDE_PATH.'../'.AT_FORUMS_NEW__DIR.'/lib/forums.inc.php');
    $forums_d = AT_FORUMS_NEW__DIR;

    global $db, $enrolled_courses, $system_courses;
    $news = array();

    if ($enrolled_courses == ''){
        return $news;
    } 

    // NOTE: $enrolled_courses is a string representation of the list of
    // enrolled course course_id's, in python notation (parentheized and
    // comma delimited)

    $sql = 'SELECT E.approved, E.last_cid, C.* FROM '.TABLE_PREFIX.'course_enrollment E, '.TABLE_PREFIX.'courses C WHERE C.course_id in '. $enrolled_courses . ' AND E.member_id=1 AND E.course_id=C.course_id ORDER BY C.title';
    $result = mysql_query($sql, $db);
    if ($result) {
        while($row = mysql_fetch_assoc($result)){
            $all_forums = get_forums($row['course_id']);
            if (is_array($all_forums)){
                foreach($all_forums as $forums){
                    if (is_array($forums)){

                        foreach ($forums as $forum_obj){
                            $forum_obj['course_id'] = $row['course_id'];
                            $link_title = $forum_obj['title'];
                            $news[] = array(
                                'time'   => $forum_obj['last_post'], 
                                'object' => $forum_obj, 
                                'alt'    => _AT('forum'),
                                'thumb'  => 'images/pin.png',
                                'course' => $system_courses[$row['course_id']]['title'],
                                'link'   => '<a href="bounce.php?course='.$row['course_id'].SEP.'p='.urlencode($forums_d.'/forum/index.php?fid='.$forum_obj['forum_id']).'"'.
                                (strlen($link_title) > SUBLINK_TEXT_LEN ? ' title="'.AT_print($link_title, 'forums.title').'"' : '') .'>'. 
                                AT_print(validate_length($link_title, SUBLINK_TEXT_LEN, VALIDATE_LENGTH_FOR_DISPLAY), 'forums.title') .'</a>');
                        }
                    }
                }
            }
        }
    }
    return $news;
}

?>
