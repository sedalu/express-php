<?php
################################################################################
# INSTALL.PHP                                                                  #
################################################################################
require_once('library/librarian.php');

if($DB[DATABASE] && $_POST['user'] && $_POST['pass']) {
    db_create_table($TABLE[CATEGORIES], true);
    db_create_table($TABLE[COMMENTS], true);
    db_create_table($TABLE[ENTRIES], true);
    db_create_table($TABLE[SECTIONS], true);
    db_create_table($TABLE[SEGMENTS], true);
    db_create_table($TABLE[SETTINGS], true);
    db_create_table($TABLE[TEMPLETS], true);
    db_create($TABLE[CATEGORIES], array('title' => 'Category', 'priority' => 1));
    db_create($TABLE[SECTIONS], array('title' => 'Section', 'priority' => 1, 'index_display' => 1));
    db_create($TABLE[ENTRIES], array('section' => 1, 'category' => 1, 'title' => 'Welcome to Express', 'text' => 'Express is a content management system designed to be totally customizable.'));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[SITE_TITLE], 'value' => $_POST['title']));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[INDEX_LIMIT], 'value' => '5'));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[INDEX_TITLE], 'value' => 'Index Title'));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[FORMAT_DATE], 'value' => 'F jS, Y'));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[FORMAT_TIME], 'value' => 'g:i:s A T'));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[ADMIN_PASS], 'value' => $_POST['pass']));
    db_create($TABLE[SETTINGS], array('id' => $SETTING[ADMIN_USER], 'value' => $_POST['user']));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[PAGE], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<?xml version="1.0" encoding="utf-8"?>\r\n<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n<title><!-- site title -->: <!-- title --></title>\r\n<link rel="stylesheet" type="text/css" href="css/style_sample.css" />\r\n</head>\r\n<body>\r\n<div id="header"><!-- site title --></div>\r\n<div id="navigation"><a href="index" title="Home">Home</a> |<!-- section links -->| <a href="mailto:user@doman.tld" title="Email">Email</a></div>\r\n<div id="content"><h1><!-- title --></h1>\r\n<!-- content --></div>\r\n<div id="footer"><p>Powered by <a href="http://express.sedalu.com/" title="Express">Express</a></p></div>\r\n</body>\r\n</html>'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[COMMENT], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h3><!-- name --></h3>\n<div><!-- date --> | <!-- time --></div>\n<p><!-- comment --></p>'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a name="<!-- category anchor -->"></a><!-- title --></h2>\n<!-- content -->'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[COMMENT_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><!-- title --></h2>\n<p><!-- text --></p>\n<h2>Leave Comment</h2>\n<!-- comment form -->'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<div><!-- date --> | <a href="<!-- comment url -->">Comment</a></div>\n<p><!-- text --></p>\n<!-- segments -->\n<h2><a name="comments"></a>Comments</h2>\n<div><a href="<!-- comment url -->">Comment</a></div>\n<!-- comments -->'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[INDEX_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a href="<!-- entry url -->" name="<!-- title -->"><!-- title --></a></h2>\n<div><!-- date --> | <a href="<!-- section url -->#<!-- category anchor -->"><!-- section -->: <!-- category --></a> | <!-- comment link --></div>\n<p><!-- text --></p>'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION_ENTRY], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h3><a href="<!-- entry url -->"><!-- title --></a></h3>\n<div><!-- date --> | <!-- comment link --></div>\n<p><!-- text --></p>'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SECTION_LINK], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => ' <a href="<!-- section url -->" title="<!-- title -->"><!-- title --></a> |'));
    db_create($TABLE[TEMPLETS], array('type' => $TEMPLET_TYPE[SEGMENT], 'section' => '0', 'category' => '0', 'class' => $TEMPLET_CLASS[HTML], 'text' => '<h2><a name="<!-- segment anchor -->"></a><!-- title --></h2>\n<p><!-- text --></p>'));
    header('Location: admin.php');
} else {
    $content = '<div id="content"><h1>Express: Install</h1>' . "\n"
    . '<form action="install.php" method="post">' . "\n"
    . '<h2>Instructions</h2>' . "\n"
    . '<p>Before installation can start, you must change \'/librarian/config.php\' to reflect your situation.</p>' . "\n"
    . '<p>Next fill in the values below.</p>' . "\n"
    . '<h2>Settings</h2>' . "\n"
    . '<p>The username and password you desire to use to login to administer your setup.</p>' . "\n"
    . html_textbox('user', 'Username')
    . html_textbox('pass', 'Password', '', true)
    . '<p>The title you wish to give to your Express site.</p>' . "\n"
    . html_textbox('title', 'Site Title')
    . '<p class="form"><input type="reset" value="Reset" /> <input type="submit" value="Install" /></p>' . "\n"
    . '</form></div>' . "\n"
    . html_footer();
    html_display_page('Express: Install', $content);
}
?>