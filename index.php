<?php
################################################################################
# INDEX.PHP                                                                    #
################################################################################
require_once('library/librarian.php');

if(!librarian_express_installed()) {
    header('Location: admin.php');
}

if($_GET['feed'] == 'rss') {
    header('Content-type: application/rss+xml');
    $class = $TEMPLET_CLASS[RSS_XML];
} else {
    $class = $TEMPLET_CLASS[HTML];
}

$page_value['content'] = '';
$page_value['title'] = db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_TITLE]);

if($entries = db_fetch($FETCH[INDEX_ENTRIES], 'date DESC, title ASC')) {
    for($i = 1; $i <= mysql_num_rows($entries); $i++) {
        $entry = mysql_fetch_array($entries);
        $entry_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[INDEX_ENTRY], $entry['section'], $entry['category'], $class);
        $page_value['content'] .= templet_replace_tokens($entry_templet, $entry);
    }
}

echo templet_replace_tokens(db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[PAGE], '', '', $class), $page_value);
?>