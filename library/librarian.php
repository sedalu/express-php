<?php
################################################################################
# LIBRARY/LIBRARIAN.PHP                                                        #
################################################################################

$EXPRESS[CATEGORIES][TABLE] = 'categories';
$EXPRESS[CATEGORIES][SINGLE] = 'category';
$EXPRESS[COMMENTS][TABLE] = 'comments';
$EXPRESS[COMMENTS][SINGLE] = 'comment';
$EXPRESS[ENTRIES][TABLE] = 'entries';
$EXPRESS[ENTRIES][SINGLE] = 'entry';
$EXPRESS[SECTIONS][TABLE] = 'sections';
$EXPRESS[SECTIONS][SINGLE] = 'section';
$EXPRESS[SEGMENTS][TABLE] = 'segments';
$EXPRESS[SEGMENTS][SINGLE] = 'segment';
$EXPRESS[SETTINGS][TABLE] = 'settings';
$EXPRESS[SETTINGS][SINGLE] = 'setting';
$EXPRESS[TEMPLETS][TABLE] = 'templets';
$EXPRESS[TEMPLETS][SINGLE] = 'templet';

require_once('config.php');
require_once('database.php');
require_once('html.php');
require_once('session.php');
require_once('templet.php');

if(librarian_express_installed()) {
    $EXPRESS[VERSION] = db_fetch($TABLE[SETTINGS], '', 'express version');
}

# LIBRARIAN_EXPRESS_INSTALLED ##################################################
# bool librarian_express_installed()
function librarian_express_installed() {
    global $DB;

    if(!$DB[HOST] || !$DB[USER] || !$DB[PASS] || !$DB[DATABASE]) {
        return false;
    }

    return true;
}
?>