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
    // FIXME: BIG PROBLEM: we are at the ATutor root and don't know where
    // FIXME: the module directory is. We might be an extra module or we
    // FIXME: might have moved up to mods/_standard space. There's no way
    // FIXME: we can know which file to include!!!
    require_once(AT_INCLUDE_PATH.'../mods/forums_new/lib/module.inc.php');
    require_once(AT_INCLUDE_PATH.'../mods/forums_new/lib/forums.inc.php');
    global $db, $enrolled_courses, $system_courses;
    $news = array();

    if ($enrolled_courses == ''){
        return $news;
    } 

    // Make sure we are accessing the new forum posts only through courses the
    // student is actually enrolled in. (The global $enrolled_courses looks
    // like (course_id1, course_id2,..., course_idN).

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
                            $news[] = array('time'=>$forum_obj['last_post'], 
                                'object'=>$forum_obj, 
                                'alt'=>_AT('forum'),
                                'thumb'=>'images/pin.png',
                                'course'=>$system_courses[$row['course_id']]['title'],
                                'link'=>'<a href="bounce.php?course='.$row['course_id'].SEP.'p='.urlencode('mods/_standard/forums/forum/index.php?fid='.$forum_obj['forum_id']).'"'.
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
