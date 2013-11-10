<?php
################################################################################
# LIBRARY/SESSION.PHP                                                          #
################################################################################

# SESSION_LOGIN ################################################################
# bool session_login(array $login)
function session_login($login) {
    global $DEMO, $SETTING, $TABLE;

    $user = (!$DEMO ? db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_USER]) : '');
    $pass = (!$DEMO ? db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_PASS]) : '');

    if($login['user'] == $user && $login['pass'] == $pass) {
        $_SESSION['logged_in'] = true;

        return true;
    } else {
        return false;
    }
}

# SESSION_VALIDATE #############################################################
# bool session_validate()
function session_validate() {
    if($_SESSION['logged_in']) {
        return true;
    } else {
        return false;
    }
}
?>