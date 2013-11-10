<?php
################################################################################
# COMMENT.PHP                                                                  #
################################################################################
require_once('library/librarian.php');

if(!librarian_express_installed()) {
    header('Location: admin.php');
}

if($_GET['id'] && ($entry = db_fetch($TABLE[ENTRIES], 'date DESC, title ASC', $_GET['id']))) {
    $page = db_fetch($FETCH[TEMPLET], '', 'page', $entry['section'], $entry['category']);
    $comment = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[COMMENT_ENTRY], $entry['section'], $entry['category']);
    $page_values['content'] = templet_replace_tokens($comment, $entry);
    $page_values['title'] = 'Comment';
    echo templet_replace_tokens($page, $page_values);
} else {
    header('Location: index.php');
}
?>