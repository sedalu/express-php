<?php
################################################################################
# LIBRARY/DATABASE.PHP                                                         #
################################################################################

$COLUMN[CLASSES] = 'class';
$COLUMN[TYPES] = 'type';

@$DB[LINK] = mysql_connect($DB[HOST], $DB[USER], $DB[PASS]);

$FETCH[ENTRY] = 'entry';
$FETCH[ENTRY_COMMENTS] = 'entry:comment';
$FETCH[ENTRY_SEGMENTS] = 'entry:segments';
$FETCH[INDEX_ENTRIES] = 'index:entries';
$FETCH[INDEX_SECTIONS] = 'index:sections';
$FETCH[TEMPLET] = 'templet';

$ORDER[CATEGORIES] = 'priority DESC, title ASC';
$ORDER[COMMENTS] = 'entry ASC, date DESC';
$ORDER[ENTRIES] = 'section ASC, category ASC, date DESC';
$ORDER[SECTIONS] = 'priority DESC, title ASC';
$ORDER[SEGMENTS] = 'entry DESC, title ASC';
$ORDER[TEMPLETS] = 'class ASC, section ASC, category ASC, type ASC';

$SETTING[ADMIN_PASS] = 'admin pass';
$SETTING[ADMIN_USER] = 'admin user';
$SETTING[FORMAT_DATE] = 'format date';
$SETTING[FORMAT_TIME] = 'format time';
$SETTING[INDEX_LIMIT] = 'index limit';
$SETTING[INDEX_TITLE] = 'index title';
$SETTING[SITE_TITLE] = 'site title';

$TABLE[CATEGORIES] = 'categories';
$TABLE[COMMENTS] = 'comments';
$TABLE[ENTRIES] = 'entries';
$TABLE[SECTIONS] = 'sections';
$TABLE[SEGMENTS] = 'segments';
$TABLE[SETTINGS] = 'settings';
$TABLE[TEMPLETS] = 'templets';

$TEMPLET_CLASS[HTML] = 'HTML';

$TEMPLET_TYPE[COMMENT] = 'Comment';
$TEMPLET_TYPE[COMMENT_ENTRY] = 'Comment Entry';
$TEMPLET_TYPE[ENTRY] = 'Entry';
$TEMPLET_TYPE[INDEX_ENTRY] = 'Index Entry';
$TEMPLET_TYPE[PAGE] = 'Page';
$TEMPLET_TYPE[SECTION] = 'Section';
$TEMPLET_TYPE[SECTION_ENTRY] = 'Section Entry';
$TEMPLET_TYPE[SECTION_LINK] = 'Section Link';
$TEMPLET_TYPE[SEGMENT] = 'Segment';

@mysql_select_db($DB[DATABASE], $DB[LINK]);

# DB_CREATE ####################################################################
# bool db_create(string $table, array $item)
function db_create($table, $item) {
    global $DB, $DEMO, $TABLE;

    if(validate_item($table, $item) && !$DEMO) {
        $query = 'INSERT INTO ' . $DB[PREFIX] . $table . ' (';

        foreach($item as $key => $value) {
            $i++;
            $query .= $key;
            $query_values .= '\'' . $value . '\'';

            if($i < count($item)) {
                $query .= ', ';
                $query_values .= ', ';
            }
        }

        if(($table == $TABLE[COMMENTS] || $table == $TABLE[ENTRIES]) && !isset($item['date'])) {
            $query .= ', date';
            $query_values .= ', NOW()';
        }

        $query .= ') VALUES (' . $query_values . ')';

        if(mysql_query($query, $DB[LINK])) {
            return true;
        }
    }

    return false;
}

# DB_CREATE_TABLE ##############################################################
# bool db_create_table(string $table[, bool $drop])
function db_create_table($table, $drop = false) {
    global $DB, $DEMO, $TABLE, $TEMPLET_CLASS, $TEMPLET_TYPE;

    if(validate_table($table) && !$DEMO) {
        if($drop) {
            $query = 'DROP TABLE IF EXISTS `' . $DB[PREFIX] . $table . '`';
            mysql_query($query, $DB[LINK]);
        }

        $query = 'CREATE TABLE `' . $DB[PREFIX] . $table . '` (';

        if($table == $TABLE[CATEGORIES]) {
            $query .= '`id` int(10) unsigned NOT NULL auto_increment,'
                . '`title` tinytext NOT NULL,'
                . '`priority` tinyint(3) unsigned NOT NULL default \'0\','
                . 'PRIMARY KEY (`id`)';
        } elseif($table == $TABLE[COMMENTS]) {
            $query .= '`id` int(10) unsigned NOT NULL auto_increment,'
                . '`entry` int(10) unsigned NOT NULL default \'0\','
                . '`date` datetime NOT NULL default \'0000-00-00 00:00:00\','
                . '`name` tinytext NOT NULL,'
                . '`comment` text NOT NULL,'
                . 'PRIMARY KEY (`id`)';
        } elseif($table == $TABLE[ENTRIES]) {
            $query .= '`id` int(10) unsigned NOT NULL auto_increment,'
                . '`section` tinyint(3) unsigned NOT NULL default \'0\','
                . '`category` int(10) unsigned NOT NULL default \'0\','
                . '`date` datetime NOT NULL default \'0000-00-00 00:00:00\','
                . '`title` tinytext NOT NULL,'
                . '`text` text NOT NULL,'
                . 'PRIMARY KEY (`id`),'
                . 'KEY `section` (`section`),'
                . 'KEY `category` (`category`)';
        } elseif($table == $TABLE[SECTIONS]) {
            $query .= '`id` tinyint(3) unsigned NOT NULL auto_increment,'
                . '`title` tinytext NOT NULL,'
                . '`priority` tinyint(3) unsigned NOT NULL default \'0\','
                . '`index_display` tinyint(1) unsigned NOT NULL default \'0\','
                . 'PRIMARY KEY (`id`)';
        } elseif($table == $TABLE[SEGMENTS]) {
            $query .= '`id` int(10) unsigned NOT NULL auto_increment,'
                . '`entry` int(10) unsigned NOT NULL default \'0\','
                . '`title` tinytext NOT NULL,'
                . '`text` text NOT NULL,'
                . 'PRIMARY KEY  (`id`),'
                . 'KEY `entry` (`entry`)';
        } elseif($table == $TABLE[SETTINGS]) {
            $query .= '`id` varchar(255) NOT NULL default \'\','
                . '`value` varchar(255) NOT NULL default \'\','
                . 'PRIMARY KEY (`id`)';
        } elseif($table == $TABLE[TEMPLETS]) {
            $query .= '`id` int(10) unsigned NOT NULL auto_increment,'
                . '`type` enum(\'' . $TEMPLET_TYPE[COMMENT] . '\',\'' . $TEMPLET_TYPE[COMMENT_ENTRY] . '\',\'' . $TEMPLET_TYPE[ENTRY] . '\',\'' . $TEMPLET_TYPE[INDEX_ENTRY] . '\',\'' . $TEMPLET_TYPE[PAGE] . '\',\'' . $TEMPLET_TYPE[SECTION] . '\',\'' . $TEMPLET_TYPE[SECTION_ENTRY] . '\',\'' . $TEMPLET_TYPE[SECTION_LINK] . '\',\'' . $TEMPLET_TYPE[SEGMENT] . '\') NOT NULL default \'' . $TEMPLET_TYPE[COMMENT] . '\','
                . '`section` tinyint(3) unsigned NOT NULL default \'0\','
                . '`category` int(10) unsigned NOT NULL default \'0\','
                . '`class` enum(\'' . $TEMPLET_CLASS[HTML] . '\') NOT NULL default \'' . $TEMPLET_CLASS[HTML] . '\','
                . '`text` text NOT NULL,'
                . 'PRIMARY KEY (`id`),'
                . 'UNIQUE KEY `UNIQUE` (`type`,`section`,`category`,`class`)';
        }

        $query .= ') TYPE=MyISAM' . (($table != $TABLE[SETTINGS]) ? ' PACK_KEYS=0 AUTO_INCREMENT=1' : '');

        return mysql_query($query, $DB[LINK]);
    }

    return false;
}

# DB_FETCH #####################################################################
# mixed db_fetch(string $from[, string $order[, mixed $value1[, mixed $value2[,
#                mixed $value3[, mixed $value4]]]])
function db_fetch($from, $order = '', $value1 = '', $value2 = '', $value3 = '', $value4 = '') {
    global $DB, $FETCH, $INDEX, $SETTING, $TABLE;
    $query = 'SELECT * FROM ' . $DB[PREFIX];

    if(validate_table($from)) {
        $query .= $from . ($value1 ? ' WHERE id = \'' . $value1 . '\'' : '');
    } elseif($from == $FETCH[ENTRY]) {
        $query .= $TABLE[ENTRIES] . ' WHERE section = ' . $value1 . ' AND category = ' . $value2;
    } elseif($from == $FETCH[ENTRY_COMMENTS]) {
        $query .= $TABLE[COMMENTS] . ' WHERE entry = ' . $value1;
    } elseif($from == $FETCH[ENTRY_SEGMENTS]) {
        $query .= $TABLE[SEGMENTS] . ' WHERE entry = ' . $value1;
    } elseif($from == $FETCH[INDEX_ENTRIES]) {
        $index_sections = db_fetch($FETCH[INDEX_SECTIONS]);
        $query .= $TABLE[ENTRIES] . ' WHERE ';

        for($i = 1; $i <= mysql_num_rows($index_sections); $i++) {
            $index_section = mysql_fetch_array($index_sections);
            $query .= ' section = ' . $index_section['id'];

            if($i < mysql_num_rows($index_sections)) {
                $query .= ' OR';
            }
        }
    } elseif($from == $FETCH[INDEX_SECTIONS]) {
        $query .= $TABLE[SECTIONS] . ' WHERE index_display = 1';
    } elseif($from == $FETCH[TEMPLET]) {
        $query .= $TABLE[TEMPLETS] . ' WHERE type = \'' . $value1 . '\' AND (section = ' . ($value2 ? $value2 : 0) . ' OR section = 0) AND (category = ' . ($value3 ? $value3 : 0) . ' OR category = 0) AND class = \'' . ($value4 ? $value4 : 'HTML') . '\'';
    } else {
        return false;
    }

    if($order || $from == $FETCH[TEMPLET]) {
        $query .= ' ORDER BY ' . (($from == $FETCH[TEMPLET]) ? 'section DESC, category DESC' : $order);
    }

    if($from == $FETCH[INDEX_ENTRIES]) {
        $limit = db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_LIMIT]);
        $query .= ' LIMIT ' . $limit;
    } elseif($from == $FETCH[TEMPLET]) {
        $query .= ' LIMIT 1';
    }

    $result = mysql_query($query, $DB[LINK]);

    if(mysql_num_rows($result)) {
        if(validate_table($from) && $value1) {
            $item = mysql_fetch_array($result);

            if($from == $TABLE[SETTINGS]) {
                return $item['value']; 
            }

            return $item;
        } elseif($from == $FETCH[TEMPLET]) {
            return mysql_fetch_array($result);
        } else {
            return $result;
        }
    } else {
        return false;
    }
}

# DB_FETCH_COLUMN ##############################################################
# mixed db_fetch_column(string $column)
function db_fetch_column($column) {
    global $DB, $COLUMN, $TABLE;

    if($column == $COLUMN[CLASSES] || $column == $COLUMN[TYPES]) {
        $query = 'SHOW COLUMNS FROM ' . $DB[PREFIX] . $TABLE[TEMPLETS] . ' LIKE \'' . $column . '\'';
        $result = mysql_query($query, $DB[LINK]);

        if(mysql_num_rows($result)) {
            $row = mysql_fetch_array($result);
            return explode("','", preg_replace("/(enum|set)\('(.+?)'\)/", "\\2", $row[1]));
        }
    }

    return false;
}

# DB_MODIFY ####################################################################
# db_modify(string $table, array $item)
function db_modify($table, $item) {
    global $DB, $DEMO;

    if(validate_item($table, $item) && !$DEMO) {
        $query = 'UPDATE ' . $DB[PREFIX] . $table . ' SET ';

        foreach($item as $key => $value) {
            $i++;
            $query .= $key . ' = \'' . $value . '\'';

            if($i < count($item)) {
                $query .= ', ';
            }
        }

        $query .= ' WHERE id = \'' . $item['id'] . '\'';

        if(mysql_query($query, $DB[LINK])) {
            return true;
        }
    }

    return false;
}

# DB_REMOVE ####################################################################
# bool db_remove(string $table, int $item)
function db_remove($table, $item) {
    global $DB, $DEMO;

    if(validate_table($table) && !$DEMO) {
        $query = 'DELETE FROM ' . $DB[PREFIX] . $table . ' WHERE id = ' . $item;

        if(mysql_query($query, $DB[LINK])) {
            return true;
        }
    }

    return false;
}

# VALIDATE_COLUMN ##############################################################
# bool validate_column(string $column, string $item)
function validate_column($column, $item) {
    global $COLUMN;

    if($column == $COLUMN[CLASSES] || $column == $COLUMN[TYPES]) {
        foreach(db_fetch_column($column) as $valid_value) {
            if($item == $valid_value) {
                return true;
            }
        }
    }

    return false;
}

# VALIDATE_ITEM ################################################################
# bool validate_item(string $table, array $item)
function validate_item($table, $item) {
    global $COLUMN, $TABLE;

    if(validate_table($table)) {
        if($table == $TABLE[CATEGORIES]) {
            if(isset($item['title']) && $item['priority'] >= 0) {
                return true;
            }
        } elseif($table == $TABLE[COMMENTS]) {
            if(is_array(db_fetch($TABLE[ENTRIES], '', $item['entry']))) {
                return true;
            }
        } elseif($table == $TABLE[ENTRIES]) {
            if(is_array(db_fetch($TABLE[SECTIONS], '', $item['section']))) {
                return true;
            }
        } elseif($table == $TABLE[SECTIONS]) {
            if(isset($item['title']) && $item['priority'] >= 0) {
                return true;
            }
        } elseif($table == $TABLE[SEGMENTS]) {
            if(is_array(db_fetch($TABLE[ENTRIES], '', $item['entry']))) {
                return true;
            }
        } elseif($table == $TABLE[SETTINGS]) {
            if(isset($item['value'])) {
                return true;
            }
        } elseif($table == $TABLE[TEMPLETS]) {
            if((is_array(db_fetch($TABLE[SECTIONS], '', $item['section'])) || $item['section'] == 0) && (is_array(db_fetch($TABLE[CATEGORIES], '', $item['category'])) || $item['category'] == 0) && validate_column($COLUMN[TYPES], $item['type']) && validate_column($COLUMN[CLASSES], $item['class'])) {
                return true;
            }
        }
    }

    return false;
}

# VALIDATE_TABLE ###############################################################
# bool validate_table(string $table)
function validate_table($table) {
    global $TABLE;

    foreach($TABLE as $valid_table) {
        if($table == $valid_table) {
            return true;
        }
    }

    return false;
}
?>