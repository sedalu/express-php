<?php
################################################################################
# ENTRY.PHP                                                                    #
################################################################################
require_once('library/librarian.php');

if(!librarian_express_installed()) {
    header('Location: admin.php');
}

if($entry =  db_fetch($TABLE[ENTRIES], '', $_GET['id'])) {
    $_POST['comment']['entry'] = $entry['id'];

    if($_POST['comment']['name'] && $_POST['comment']['comment']) {
        db_create($TABLE[COMMENTS], $_POST['comment']);
        unset($_POST['comment']);
    }

    $page = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[PAGE], $entry['section'], $entry['category']);
    $entry_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[ENTRY], $entry['section'], $entry['category']);

    if($segments = db_fetch($FETCH[ENTRY_SEGMENTS], '', $entry['id'])) {
        $segment_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SEGMENT], $entry['section'], $entry['category']);

        for($i = 1; $i <= mysql_num_rows($segments); $i++) {
            $entry['segments'] .= templet_replace_tokens($segment_templet, mysql_fetch_array($segments));
        }
    }

    if($comments = db_fetch($FETCH[ENTRY_COMMENTS], '', $entry['id'])) {
       $comment_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[COMMENT], $entry['section'], $entry['category']);

        for($i = 1; $i <= mysql_num_rows($comments); $i++) {
            $entry['comments'] .= templet_replace_tokens($comment_templet, mysql_fetch_array($comments));
        }
    }

    $entry['content'] .= templet_replace_tokens($entry_templet, $entry);
    echo templet_replace_tokens($page, $entry);
}
?>