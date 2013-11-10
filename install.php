<?php
################################################################################
# INSTALL.PHP                                                                  #
################################################################################
require_once('library/librarian.php');

if($DB[DATABASE] && $_POST['user'] && $_POST['pass']) {
    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[CATEGORIES] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[CATEGORIES] . '` ('
        . '`id` int(10) unsigned NOT NULL auto_increment,'
        . '`title` tinytext NOT NULL,'
        . '`priority` tinyint(3) unsigned NOT NULL default \'0\','
        . 'PRIMARY KEY  (`id`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'INSERT INTO `' . $DB[PREFIX] . $TABLE[CATEGORIES] . '` (`id`, `title`, `priority`) VALUES (1, \'Category\', 0)';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[COMMENTS] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[COMMENTS] . '` ('
        . '`id` int(10) unsigned NOT NULL auto_increment,'
        . '`entry` int(10) unsigned NOT NULL default \'0\','
        . '`date` datetime NOT NULL default \'0000-00-00 00:00:00\','
        . '`name` tinytext NOT NULL,'
        . '`comment` text NOT NULL,'
        . 'PRIMARY KEY  (`id`),'
        . 'KEY `entry` (`entry`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[ENTRIES] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[ENTRIES] . '` ('
        . '`id` int(10) unsigned NOT NULL auto_increment,'
        . '`section` tinyint(3) unsigned NOT NULL default \'0\','
        . '`category` int(10) unsigned NOT NULL default \'0\','
        . '`date` datetime NOT NULL default \'0000-00-00 00:00:00\','
        . '`title` tinytext NOT NULL,'
        . '`text` text NOT NULL,'
        . 'PRIMARY KEY  (`id`),'
        . 'KEY `section` (`section`),'
        . 'KEY `category` (`category`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'INSERT INTO `' . $DB[PREFIX] . $TABLE[ENTRIES] . '` (`id`, `section`, `category`, `date`, `title`, `text`) VALUES (1, 1, 1, NOW(), \'Welcome to Express\', \'Express is a content management system designed to be totally customizable.\')';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[SECTIONS] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[SECTIONS] . '` ('
        . '`id` tinyint(3) unsigned NOT NULL auto_increment,'
        . '`title` tinytext NOT NULL,'
        . '`priority` tinyint(3) unsigned NOT NULL default \'0\','
        . '`index_display` tinyint(1) unsigned NOT NULL default \'0\','
        . 'PRIMARY KEY  (`id`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'INSERT INTO `' . $DB[PREFIX] . $TABLE[SECTIONS] . '` (`id`, `title`, `priority`, `index_display`) VALUES (1, \'Section\', 1, 1)';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[SEGMENTS] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[SEGMENTS] . '` ('
        . '`id` int(10) unsigned NOT NULL auto_increment,'
        . '`entry` int(10) unsigned NOT NULL default \'0\','
        . '`title` tinytext NOT NULL,'
        . '`text` text NOT NULL,'
        . 'PRIMARY KEY  (`id`),'
        . 'KEY `entry` (`entry`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[SETTINGS] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[SETTINGS] . '` ('
        . '`id` varchar(255) NOT NULL default \'\','
        . '`value` varchar(255) NOT NULL default \'\','
        . 'PRIMARY KEY  (`id`)'
        . ') TYPE=MyISAM';
    mysql_query($query, $DB[LINK]);

    $query = 'INSERT INTO `' . $DB[PREFIX] . $TABLE[SETTINGS] . '` (`id`, `value`) VALUES (\'' . $SETTING[SITE_TITLE] . '\', \'' . $_POST['title']. '\'),'
        . '(\'' . $SETTING[INDEX_LIMIT] . '\', \'5\'),'
        . '(\'' . $SETTING[INDEX_TITLE] . '\', \'Index Title\'),'
        . '(\'' . $SETTING[FORMAT_DATE] . '\', \'F jS, Y\'),'
        . '(\'' . $SETTING[FORMAT_TIME] . '\', \'g:i:s A T\'),'
        . '(\'' . $SETTING[ADMIN_PASS] . '\', \'' . $_POST['pass'] . '\'),'
        . '(\'' . $SETTING[ADMIN_USER] . '\', \'' . $_POST['user'] . '\')';
    mysql_query($query, $DB[LINK]);

    $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $TABLE[TEMPLETS] . '`';
    mysql_query($query, $DB[LINK]);

    $query = 'CREATE TABLE `' . $DB[PREFIX] . $TABLE[TEMPLETS] . '` ('
        . '`id` int(10) unsigned NOT NULL auto_increment,'
        . '`type` enum(\'' . $TEMPLET_TYPE[COMMENT] . '\',\'' . $TEMPLET_TYPE[COMMENT_ENTRY] . '\',\'' . $TEMPLET_TYPE[ENTRY] . '\',\'' . $TEMPLET_TYPE[INDEX_ENTRY] . '\',\'' . $TEMPLET_TYPE[PAGE] . '\',\'' . $TEMPLET_TYPE[SECTION] . '\',\'' . $TEMPLET_TYPE[SECTION_ENTRY] . '\',\'' . $TEMPLET_TYPE[SECTION_LINK] . '\',\'' . $TEMPLET_TYPE[SEGMENT] . '\') NOT NULL default \'' . $TEMPLET_TYPE[COMMENT] . '\','
        . '`section` tinyint(3) unsigned NOT NULL default \'0\','
        . '`category` int(10) unsigned NOT NULL default \'0\','
        . '`class` enum(\'' . $TEMPLET_CLASS[HTML] . '\') NOT NULL default \'' . $TEMPLET_CLASS[HTML] . '\','
        . '`text` text NOT NULL,'
        . 'PRIMARY KEY  (`id`),'
        . 'UNIQUE KEY `UNIQUE` (`type`,`section`,`category`,`class`)'
        . ') TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1';
    mysql_query($query, $DB[LINK]);

    $query = 'INSERT INTO `' . $DB[PREFIX] . $TABLE[TEMPLETS] . '` (`id`, `type`, `section`, `category`, `class`, `text`) VALUES (1, \'' . $TEMPLET_TYPE[PAGE] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<?xml version="1.0" encoding="utf-8"?>\r\n<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n<title><!-- site title -->: <!-- title --></title>\r\n<link rel="stylesheet" type="text/css" href="css/style_sample.css" />\r\n</head>\r\n<body>\r\n<div id="header"><!-- site title --></div>\r\n<div id="navigation"><a href="index" title="Home">Home</a> |<!-- section links -->| <a href="mailto:user@doman.tld" title="Email">Email</a></div>\r\n<div id="content"><h1><!-- title --></h1>\r\n<!-- content --></div>\r\n<div id="footer"><p><a href-"http://express.sedalu.com/" title="Express">Express</a> copyright &copy; 2003 Seth D Lumnah.</p>\r\n<p>All rights reserved.</p></div>\r\n</body>\r\n</html>\'),'
        . '(2, \'' . $TEMPLET_TYPE[COMMENT] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h3><!-- name --></h3>\n<div><!-- date --> | <!-- time --></div>\n<p><!-- comment --></p>\'),'
        . '(3, \'' . $TEMPLET_TYPE[SECTION] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h2><a name="<!-- category anchor -->"></a><!-- title --></h2>\n<!-- content -->\'),'
        . '(4, \'' . $TEMPLET_TYPE[COMMENT_ENTRY] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h2><!-- title --></h2>\n<p><!-- text --></p>\n<h2>Leave Comment</h2>\n<!-- comment form -->\'),'
        . '(5, \'' . $TEMPLET_TYPE[ENTRY] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<div><!-- date --> | <a href="<!-- comment url -->">Comment</a></div>\n<p><!-- text --></p>\n<!-- segments -->\n<h2><a name="comments"></a>Comments</h2>\n<div><a href="<!-- comment url -->">Comment</a></div>\n<!-- comments -->\'),'
        . '(6, \'' . $TEMPLET_TYPE[INDEX_ENTRY] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h2><a href="<!-- entry url -->" name="<!-- title -->"><!-- title --></a></h2>\n<div><!-- date --> | <a href="<!-- section url -->#<!-- category anchor -->"><!-- section -->: <!-- category --></a> | <!-- comment link --></div>\n<p><!-- text --></p>\'),'
        . '(7, \'' . $TEMPLET_TYPE[SECTION_ENTRY] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h3><a href="<!-- entry url -->"><!-- title --></a></h3>\n<div><!-- date --> | <!-- comment link --></div>\n<p><!-- text --></p>\'),'
        . '(8, \'' . $TEMPLET_TYPE[SECTION_LINK] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \' <a href="<!-- section url -->" title="<!-- title -->"><!-- title --></a> |\'),'
        . '(9, \'' . $TEMPLET_TYPE[SEGMENT] . '\', 0, 0, \'' . $TEMPLET_CLASS[HTML] . '\', \'<h2><a name="<!-- segment anchor -->"></a><!-- title --></h2>\n<p><!-- text --></p>\')';
    mysql_query($query, $DB[LINK]);
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