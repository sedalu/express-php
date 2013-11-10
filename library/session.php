<?php
################################################################################
# LIBRARY/SESSION.PHP                                                          #
################################################################################

# SESSION_LOGIN ################################################################
# void session_login()
function session_login() {
    global $DEMO, $SETTING, $TABLE;

    if(librarian_express_installed()) {
        $user = ($DEMO ? '' : db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_USER]));
        $pass = ($DEMO ? '' : db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_PASS]));

        if($_POST['login']['user'] == $user && $_POST['login']['pass'] == $pass) {
            $_SESSION['logged_in'] = true;
            unset($_POST['login']);

            if(!$DEMO) {
                header('Location: admin.php');
            }
        }
    }
}

# SESSION_LOGOUT ###############################################################
# void session_logout()
function session_logout() {
    unset($_SESSION['logged_in']);
    session_destroy();
    header('Location: index.php');
}

# SESSION_VALIDATE #############################################################
# bool session_validate()
function session_validate() {
    global $SETTING, $TABLE;

    if(librarian_express_installed()) {
        if($_SESSION['logged_in']) {
            return true;
        } else {
            $content = '<div id="content"><h1>Administration Login</h1>' . "\n"
                . '<form action="admin.php" method="post">' . "\n"
                . html_textbox('login[user]', 'User')
                . html_textbox('login[pass]', 'Pass', '', true)
                . '<p class="form"><input type="reset" value="Clear" /> <input type="submit" value="Login" /></p>' . "\n"
                . '</form></div>';
            html_display_page('Express: ' . db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]), $content);
        }
    }
}
?>