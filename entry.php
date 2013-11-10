<?php
################################################################################
# ENTRY.PHP                                                                    #
################################################################################
require_once('library/librarian.php');

if($entry =  db_fetch($TABLE[ENTRIES], 'date DESC, title ASC', $_GET['id'])) {
    $page = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[PAGE], $entry['section'], $entry['category']);
    $entry_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[ENTRY], $entry['section'], $entry['category']);

    if($segments = db_fetch($FETCH[ENTRY_SEGMENTS], '', $entry['id'])) {
        $segment_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SEGMENT], $entry['section'], $entry['category']);

        for($i = 1; $i <= mysql_num_rows($segments); $i++) {
            $entry['segments'] .= templet_complete($segment_templet, mysql_fetch_array($segments));
        }
    }

    if($comments = db_fetch($FETCH[ENTRY_COMMENTS], '', $entry['id'])) {
       $comment_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[COMMENT], $entry['section'], $entry['category']);

        for($i = 1; $i <= mysql_num_rows($comments); $i++) {
            $entry['comments'] .= templet_complete($comment_templet, mysql_fetch_array($comments));
        }
    }

    $entry['content'] .= templet_complete($entry_templet, $entry);
    echo templet_complete($page, $entry);
}
?>