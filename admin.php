<?php
################################################################################
# ADMIN.PHP                                                                    #
################################################################################
require_once('library/librarian.php');
session_start();

if(session_validate()) {
    $content = '<div id="content"><h1>Express';

    if($_GET['modify'] == 'settings') {
        $content .= ': Settings';
    }

    $content .= '</h1>' . "\n";

    if(isset($_POST['create'])) {
        db_create($TABLE[strtoupper($_POST['create'])], $_POST['item']);
        unset($_POST['item']);
    } elseif(isset($_POST['modify'])) {
        if($_POST['modify'] == 'settings') {
            foreach($_POST['setting'] as $item['id'] => $item['value']) {
                db_modify($TABLE[strtoupper($_POST['modify'])], $item);
            }
        } else {
            db_modify($TABLE[strtoupper($_POST['modify'])], $_POST['item']);
        }

        unset($_POST['item']);
    } elseif(isset($_POST['remove'])) {
        db_remove($TABLE[strtoupper($_POST['remove'])], $_POST['id']);
        unset($_POST['id']);
    }

    if(isset($_GET['create'])) {
        $content .= html_form($TABLE{strtoupper($_GET['create'])}, 'new');
    } elseif(isset($_GET['modify'])) {
        if($_GET['modify'] == 'settings') {
            $content .= html_admin_settings();
        } elseif(isset($_GET['id'])) {
            $content .= html_form($TABLE{strtoupper($_GET['modify'])}, $_GET['id']);
        } else {
            $content .= get_selection_list($TABLE[strtoupper($_GET['modify'])]);
        }
    } elseif(isset($_GET['remove'])) {
        $content .= get_selection_list($TABLE[strtoupper($_GET['remove'])], true);
    } elseif($_GET['mode'] == 'panel') {
        $content .= html_admin_panel();
    } elseif($_GET['mode'] == 'preview') {
        header('Location: index.php');
    } elseif($_GET['mode'] == 'logout') {
        unset($_SESSION['logged_in']);
        session_destroy();
        header('Location: index.php');
    } elseif($_GET['mode'] == 'settings') {
        $content .= html_admin_settings();
    } else {
        $frame = html_admin_frame();
    }

    $content .= html_footer();
    html_display_page('Express: ' . db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]), $content, (($_GET['mode'] == 'panel') ? 'panel' : ''), $frame);
} elseif(session_login($_POST['login'])) {
    unset($_POST['login']);
    header('Location: admin.php');
} else {
    $content = '<div id="content"><form name="login" action="admin.php" method="post">' . "\n"
        . html_textbox('login[user]', 'User')
        . html_textbox('login[pass]', 'Pass', '', true)
        . '<p class="form"><input type="reset" value="Clear" /> <input type="submit" value="Login" /></p>' . "\n"
        . '</form></div>';
    html_display_page('Express: ' . db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]), $content);
}

# GET_SELECTION_LIST ###########################################################
# string get_selection_list(string $table[, bool $is_remove])
function get_selection_list($table, $is_remove = false) {
    global $DB, $TABLE;

    return '<h2>' . ($is_remove ? 'Remove' : 'Modify') . ' ' . ucfirst($table) .'</h2>' . "\n"
        . '<form action="?mode=preview" method="' . ($is_remove ? 'post' : 'get') . '">' . "\n"
        . '<p>Choose a ' . $table .' to ' . ($is_remove ? 'remove' : 'modify') . '.</p>' . "\n"
        . '<input type="hidden" name="' . ($is_remove ? 'remove' : 'modify') . '" value="' . $table .'" />' . "\n"
        . html_get_selection_list($table, 'id', ucfirst($table) ,'')
        . '<p class="form"><input type="submit" value="' . ($is_remove ? 'Remove' : 'Modify') . '" /></p>' . "\n"
        . '</form>' . "\n";
}
?>