<?php
################################################################################
# ADMIN.PHP                                                                    #
################################################################################
require_once('library/librarian.php');
session_start();
session_login();

if(!librarian_express_installed()) {
    $content = '<div id="content"><h1>Install Express</h1>' . "\n";

    if(isset($_POST['install'])) {
        $DB[HOST] = $_POST['install']['database']['host'];
        $DB[USER] = $_POST['install']['database']['user'];
        $DB[PASS] = $_POST['install']['database']['pass'];
        $DB[DATABASE] = $_POST['install']['database']['database'];
        $DB[PREFIX] = $_POST['install']['database']['prefix'];
        $code = '<?php' . "\n"
            . '################################################################################' . "\n"
            . '# LIBRARY/CONFIG.PHP                                                           #' . "\n"
            . '################################################################################' . "\n"
            . '' . "\n"
            . '$DB[HOST] = \'' . $DB[HOST] . '\';' . "\n"
            . '$DB[USER] = \'' . $DB[USER] . '\';' . "\n"
            . '$DB[PASS] = \'' . $DB[PASS] . '\';' . "\n"
            . '' . "\n"
            . '$DB[DATABASE] = \'' . $DB[DATABASE] . '\';' . "\n"
            . '$DB[PREFIX] = \'' . $DB[PREFIX] . '\';' . "\n"
            . '?>';
    
        if(@$file = fopen('library/config.php', 'w')) {
            fwrite($file, $code);
            fclose($file);
            header('Location: admin.php');
        } else {
            $content .= '<h2>Unable to Save File</h2>' . "\n"
                . '<p>Please copy the lines of code to \'librarian/config.php\'.</p>' . "\n"
                . '<h3>libraian/config.php</h3>' . "\n"
                . '<pre>' . htmlentities($code) . '</pre>';
        }

        $DB[LINK] = mysql_connect($DB[HOST], $DB[USER], $DB[PASS]);
        mysql_select_db($DB[DATABASE], $DB[LINK]);

        foreach($TABLE as $table => $value) {
            db_create_table($TABLE[$table], true);
        }

        db_create($TABLE[CATEGORIES], array('title' => 'Category', 'priority' => 1));
        db_create($TABLE[SECTIONS], array('title' => 'Section', 'priority' => 1, 'index_display' => 1));
        db_create($TABLE[ENTRIES], array('section' => 1, 'category' => 1, 'title' => 'Welcome to Express', 'text' => 'Express is a content management system designed to be totally customizable.'));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[SITE_TITLE], 'value' => $_POST['install']['express']['title']));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[INDEX_LIMIT], 'value' => '5'));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[INDEX_TITLE], 'value' => 'Index Title'));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[FORMAT_DATE], 'value' => 'F jS, Y'));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[FORMAT_TIME], 'value' => 'g:i:s A T'));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[ADMIN_PASS], 'value' => $_POST['install']['express']['pass']));
        db_create($TABLE[SETTINGS], array('id' => $SETTING[ADMIN_USER], 'value' => $_POST['install']['express']['user']));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[PAGE], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<?xml version="1.0" encoding="utf-8"?>\r\n<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n<title><!-- site title -->: <!-- title --></title>\r\n<link rel="stylesheet" type="text/css" href="css/style_sample.css" />\r\n</head>\r\n<body>\r\n<div id="header"><!-- site title --></div>\r\n<div id="navigation"><a href="index.php" title="Home">Home</a> |<!-- section links -->| <a href="mailto:user@doman.tld" title="Email">Email</a></div>\r\n<div id="content"><h1><!-- title --></h1>\r\n<!-- content --></div>\r\n<div id="footer"><p><a href="http://express.sedalu.com/" title="Express">Powered by Express</a></p></div>\r\n</body>\r\n</html>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[PAGE], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[RSS_XML], 'text' => '<?xml version="1.0" encoding="UTF-8"?>\r\n<rss version="2.0">\r\n<channel>\r\n<title><!-- site title -->: <!-- title --></title>\r\n<link><!-- site url --></link>\r\n<description><!-- site title -->: <!-- title --></description>\r\n<copyright>Copyright (c) 2004 Your Name</copyright>\r\n<generator>Express (version <!-- express version -->)</generator>\r\n<language>en-us</language>\r\n<!-- content -->\r\n</channel>\r\n</rss>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[COMMENT], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h3><!-- name --></h3>\n<div><!-- date --> | <!-- time --></div>\n<p><!-- comment --></p>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a name="<!-- category anchor -->"></a><!-- title --></h2>\n<!-- content -->'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[COMMENT_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><!-- title --></h2>\n<p><!-- text --></p>\n<h2>Leave Comment</h2>\n<!-- comment form -->'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<div><!-- date --> | <a href="<!-- comment url -->">Comment</a></div>\n<p><!-- text --></p>\n<!-- segments -->\n<h2><a name="comments"></a>Comments</h2>\n<div><a href="<!-- comment url -->">Comment</a></div>\n<!-- comments -->'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[INDEX_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a href="<!-- entry url -->" name="<!-- title -->"><!-- title --></a></h2>\n<div><!-- date --> | <a href="<!-- section url -->#<!-- category anchor -->"><!-- section -->: <!-- category --></a> | <!-- comment link --></div>\n<p><!-- text --></p>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[INDEX_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[RSS_XML], 'text' => '<item>\r\n<title><!-- title --></title>\r\n<guid><!-- site url --><!-- entry url --></guid>\r\n<link><!-- site url --><!-- entry url --></link>\r\n<pubDate><!-- rss date --> <!-- rss time --></pubDate>\r\n<category><!-- section -->: <!-- category --></category>\r\n<description><!-- text --></description>\r\n<comments><!-- site url --><!-- entry url -->#comments</comments>\r\n</item>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h3><a href="<!-- entry url -->"><!-- title --></a></h3>\n<div><!-- date --> | <!-- comment link --></div>\n<p><!-- text --></p>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION_LINK], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => ' <a href="<!-- section url -->" title="<!-- title -->"><!-- title --></a> |'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SEGMENT], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a name="<!-- segment anchor -->"></a><!-- title --></h2>\n<p><!-- text --></p>'));
    } else {
        $content .= '<form action="admin.php" method="post">' . "\n"
            . '<h2>Database Settigns</h2>' . "\n"
            . '<h3>Server Login</h3>' . "\n"
            . html_textbox('install[database][host]', 'Host', 'localhost')
            . html_textbox('install[database][user]', 'Username')
            . html_textbox('install[database][pass]', 'Password', '', true)
            . '<h3>Database</h3>' . "\n"
            . html_textbox('install[database][database]', 'Database')
            . html_textbox('install[database][prefix]', 'Table Prefix', 'express_')
            . '<h2>Express Settings</h2>' . "\n"
            . '<h3>Admin Login</h3>' . "\n"
            . html_textbox('install[express][user]', 'Username')
            . html_textbox('install[express][pass]', 'Password', '', true)
            . '<h3>Site Info</h3>' . "\n"
            . html_textbox('install[express][title]', 'Site Title')
            . '<p class="form"><input type="reset" value="Reset" /><input type="submit" value="Install" /></p>' . "\n"
            . '</form>';
    }

    html_display_page('Express: Install', $content . '</div>' . "\n" . admin__html_footer());
} elseif(session_validate()) {
    $version = db_fetch($TABLE[SETTINGS], '', 'express version');
    if($version == '') {
        $query = 'ALTER TABLE `express_templets` CHANGE `class` `class` ENUM( \'HTML\', \'RSS/XML\' ) DEFAULT \'HTML\' NOT NULL';
        if(mysql_query($query, $DB[LINK])) {
            $setting['id'] = 'express version';
            $setting['value'] = '1.1.0';
            db_create($TABLE[SETTINGS], $setting);
        }
    }
    if($version == '1.1.0') {
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[PAGE], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[RSS_XML], 'text' => '<?xml version="1.0" encoding="UTF-8"?>\r\n<rss version="2.0">\r\n<channel>\r\n<title><!-- site title -->: <!-- title --></title>\r\n<link><!-- site url --></link>\r\n<description><!-- site title -->: <!-- title --></description>\r\n<copyright>Copyright (c) 2004 Your Name</copyright>\r\n<generator>Express (version <!-- express version -->)</generator>\r\n<language>en-us</language>\r\n<!-- content -->\r\n</channel>\r\n</rss>'));
        db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[INDEX_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[RSS_XML], 'text' => '<item>\r\n<title><!-- title --></title>\r\n<guid><!-- site url --><!-- entry url --></guid>\r\n<link><!-- site url --><!-- entry url --></link>\r\n<pubDate><!-- rss date --> <!-- rss time --></pubDate>\r\n<category><!-- section -->: <!-- category --></category>\r\n<description><!-- text --></description>\r\n<comments><!-- site url --><!-- entry url -->#comments</comments>\r\n</item>'));
        $setting['id'] = 'express version';
        $setting['value'] = '1.1.1';
        db_modify($TABLE[SETTINGS], $setting);
    }

    $content = '<div id="content"><h1>';

    if(isset($_GET['create'])) {
        $content .= 'Create ' . ucfirst($EXPRESS[strtoupper($_GET['create'])][SINGLE]);
    } elseif($_GET['modify'] == 'settings') {
        $content .= 'Settings';
    } elseif(isset($_GET['modify'])) {
        $content .= 'Modify ' . ucfirst($EXPRESS[strtoupper($_GET['modify'])][SINGLE]);
    } elseif(isset($_GET['remove'])) {
        $content .= 'Remove ' . ucfirst($EXPRESS[strtoupper($_GET['remove'])][SINGLE]);
    } else {
        $content .= 'Express';
    }

    $content .= '</h1>' . "\n";

    if(isset($_POST['create'])) {
        db_create($EXPRESS[strtoupper($_POST['create'])][TABLE], $_POST['item']);
        unset($_POST['item']);
    } elseif(isset($_POST['modify'])) {
        if($_POST['modify'] == 'settings') {
            foreach($_POST['setting'] as $item['id'] => $item['value']) {
                db_modify($EXPRESS[strtoupper($_POST['modify'])][TABLE], $item);
            }
        } else {
            db_modify($EXPRESS[strtoupper($_POST['modify'])][TABLE], $_POST['item']);
        }

        unset($_POST['item']);
    } elseif(isset($_POST['remove'])) {
        db_remove($EXPRESS[strtoupper($_POST['remove'])][TABLE], $_POST['id']);
        unset($_POST['id']);
    }

    if(isset($_GET['create'])) {
        $content .= admin__html_form($EXPRESS[strtoupper($_GET['create'])][TABLE], 'new');
    } elseif(isset($_GET['modify'])) {
        if($_GET['modify'] == 'settings') {
            $content .= '<form action="admin.php?mode=preview" method="post">' . "\n"
                . '<input type="hidden" name="modify" value="settings" />' . "\n"
                . '<h3>Login</h3>' . "\n"
                . html_textbox('setting[' . $SETTING[ADMIN_USER] . ']', 'Username', ($DEMO ? '' : db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_USER])))
                . html_textbox('setting[' . $SETTING[ADMIN_PASS] . ']', 'Password', ($DEMO ? '' : db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_PASS])), true)
                . '<h3>Format</h3>' . "\n"
                . html_textbox('setting[' . $SETTING[FORMAT_DATE] . ']', 'Date', db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_DATE]))
                . html_textbox('setting[' . $SETTING[FORMAT_TIME] . ']', 'Time', db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_TIME]))
                . '<h3>Miscellanea</h3>' . "\n"
                . html_textbox('setting[' . $SETTING[SITE_TITLE] . ']', 'Site Title', db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]))
                . html_textbox('setting[' . $SETTING[INDEX_TITLE] . ']', 'Index Title', db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_TITLE]))
                . html_textbox('setting[' . $SETTING[INDEX_LIMIT] . ']', 'Index Limit', db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_LIMIT]))
                . '<p class="form"><input type="reset" value="Reset" /><input type="submit" value="Save" /></p>' . "\n"
                . '</form>' . "\n";
        } elseif(isset($_GET['id'])) {
            $content .= admin__html_form($EXPRESS[strtoupper($_GET['modify'])][TABLE], $_GET['id']);
        } else {
            $content .= admin__selection_list($EXPRESS[strtoupper($_GET['modify'])][TABLE]);
        }
    } elseif(isset($_GET['remove'])) {
        $content .= admin__selection_list($EXPRESS[strtoupper($_GET['remove'])][TABLE], true);
    } elseif($_GET['mode'] == 'panel') {
        $content .= '<div><a href="index.php" target="content">Preview</a> || <a href="?mode=logout" target="_parent">Logout</a></div>' . "\n"
            . '<h2>Organization</h2>' . "\n"
            . '<h3>Sections</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?create=sections" target="content">Create</a></li>' . "\n"
            . '<li><a href="?modify=sections" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=sections" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h3>Categories</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?create=categories" target="content">Create</a></li>' . "\n"
            . '<li><a href="?modify=categories" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=categories" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h2>Content</h2>' . "\n"
            . '<h3>Entries</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?create=entries" target="content">Create</a></li>' . "\n"
            . '<li><a href="?modify=entries" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=entries" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h3>Segments</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?create=segments" target="content">Create</a></li>' . "\n"
            . '<li><a href="?modify=segments" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=segments" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h3>Comments</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?modify=comments" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=comments" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h2>Structure</h2>' . "\n"
            . '<h3>Templets</h3>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?create=templets" target="content">Create</a></li>' . "\n"
            . '<li><a href="?modify=templets" target="content">Modify</a></li>' . "\n"
            . '<li><a href="?remove=templets" target="content">Remove</a></li>' . "\n"
            . '</ul>' . "\n"
            . '<h2>Administration</h2>' . "\n"
            . '<ul>' . "\n"
            . '<li><a href="?modify=settings" target="content">Settings</a></li>' . "\n"
            . '</ul>' . "\n";
    } elseif($_GET['mode'] == 'preview') {
        header('Location: index.php');
    } elseif($_GET['mode'] == 'logout') {
        session_logout();
    } else {
        $frame = '<frameset cols="150, *" border="0">' . "\n"
            . '<frame name="panel" src="admin.php?mode=panel" />' . "\n"
            . '<frame name="content" src="index.php" />' . "\n"
            . '</frameset>' . "\n";
    }

    html_display_page('Express: ' . db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]), $content . '</div>' . "\n" . admin__html_footer(), (($_GET['mode'] == 'panel') ? 'panel' : ''), $frame);
}

# ADMIN__HTML_FORM #############################################################
# string admin__html_form(string $table, int $id)
function admin__html_form($table, $id) {
    global $EXPRESS, $MYSQL, $TEMPLET_CLASS, $TEMPLET_TYPE, $FETCH;

    $item = db_fetch($table, '', $id);

    $html = '<form action="admin.php?mode=preview" method="post">' . "\n"
        . '<input type="hidden" name="' . ($item ? 'modify' : 'create' ) . '" value="' . $table;

    $html .= '" />' . "\n"
        . ($item ? '<input type="hidden" name="item[id]" value="' . $item['id'] . '" />' . "\n" : '');

    if($table == $EXPRESS[CATEGORIES][TABLE]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_textbox('item[priority]', 'Priority', ($item ? $item['priority'] : '1'));
    } elseif($table == $EXPRESS[COMMENTS][TABLE]) {
        $html .= '<input type="hidden" name="item[entry]" value="' . $item['entry'] . '" />' . "\n"
            . '<input type="hidden" name="item[date]" value="' . $item['date'] . '" />' . "\n"
            . html_textbox('item[name]', 'Name', $item['name'])
            . html_textarea('item[comment]', 'Comment', $item['comment']);
    } elseif($table == $EXPRESS[ENTRIES][TABLE]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_selection_list('item[section]', 'Section', '', $item['section'], $EXPRESS[SECTIONS][TABLE])
            . html_selection_list('item[category]', 'Category', '', $item['category'], $EXPRESS[CATEGORIES][TABLE])
            . html_textbox('item[date]', 'Date', ($item ? $item['date'] : date('Y-m-d H-i-s', time())))
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $EXPRESS[SECTIONS][TABLE]) {
        $radio_list['yes']['id'] = '1';
        $radio_list['yes']['title'] = 'Yes';
        $radio_list['no']['id'] = '0';
        $radio_list['no']['title'] = 'No';
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_textbox('item[priority]', 'Priority', ($item ? $item['priority'] : '1'))
            . html_radio_list('item[index_display]', 'Index Display', $radio_list, ($item['index_display'] ? $item['index_display'] : 0));
    } elseif($table == $EXPRESS[SEGMENTS][TABLE]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_selection_list('item[entry]', 'Entry', '', $item['entry'], $EXPRESS[ENTRIES][TABLE])
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $EXPRESS[TEMPLETS][TABLE]) {
        $default['title'] = 'DEFAULT';
        $default['id'] = '0';
        $html .= html_selection_list('item[class]', 'Class', $TEMPLET_CLASS, $item['class'])
            . html_selection_list('item[section]', 'Section', array($default), $item['section'], $EXPRESS[SECTIONS][TABLE])
            . html_selection_list('item[category]', 'Category', array($default), $item['category'], $EXPRESS[CATEGORIES][TABLE])
            . html_selection_list('item[type]', 'Type', $TEMPLET_TYPE, $item['type'])
            . html_textarea('item[text]', 'Text', $item['text']);
    }

    $html .= '<p class="form"><input type="reset" value="Reset" /><input type="submit" value="Save" /></p>' . "\n"
        . '</form>' . "\n";

    return $html;
}

# ADMIN__HTML_FOOTER ###########################################################
# string admin__html_footer()
function admin__html_footer() {
    global $EXPRESS;

    return '<div id="footer"><p><a href="http://express.sedalu.com/" title="Express">Express</a> (version ' . $EXPRESS[VERSION] . ')</p>' . "\n"
        . '<p>Copyright &copy; 2003-2004 Seth D Lumnah.</p>' . "\n"
        . '<p>All rights reserved.</p></div>';
}

# ADMIN__SELECTION_LIST ########################################################
# string admin__selection_list(string $table[, bool $is_remove])
function admin__selection_list($table, $is_remove = false) {
    global $EXPRESS;

    return '<form action="?mode=preview" method="' . ($is_remove ? 'post' : 'get') . '">' . "\n"
        . '<p>Choose a ' . $EXPRESS[strtoupper($table)][SINGLE] .' to ' . ($is_remove ? 'remove' : 'modify') . '.</p>' . "\n"
        . '<input type="hidden" name="' . ($is_remove ? 'remove' : 'modify') . '" value="' . $table .'" />' . "\n"
        . html_selection_list('id', ucfirst($EXPRESS[strtoupper($table)][TABLE]), '', '', $table)
        . '<p class="form"><input type="submit" value="' . ($is_remove ? 'Remove' : 'Modify') . '" /></p>' . "\n"
        . '</form>' . "\n";
}
?>