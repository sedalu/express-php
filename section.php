<?php
################################################################################
# SECTION.PHP                                                                  #
################################################################################
require_once('library/librarian.php');

if(!librarian_express_installed()) {
    header('Location: admin.php');
}

if(($section = db_fetch($TABLE[SECTIONS], '', $_GET['id'])) && ($categories = db_fetch($TABLE[CATEGORIES], 'priority DESC, title ASC'))) {
    $page = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[PAGE], $section['id']);
    $section_templet = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SECTION], $section['id']);

    for($i = 1; $i <= mysql_num_rows($categories); $i++) {
        $category = mysql_fetch_array($categories);
        if($entries = db_fetch($FETCH[ENTRY], 'date DESC, title ASC', $section['id'], $category['id'])) {
            $entry = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SECTION_ENTRY], $section['id'], $category['id']);

            for($j = 1; $j <= mysql_num_rows($entries); $j++) {
                $category['content'] .= templet_replace_tokens($entry, mysql_fetch_array($entries));
            }

        $section['content'] .= templet_replace_tokens($section_templet, $category);
        }
    }

    echo templet_replace_tokens($page, $section);
}
?>