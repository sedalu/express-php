<?php
################################################################################
# LIBRARY/HTML.PHP                                                             #
################################################################################

$EXPRESS['version'] = '1.0.3' . ($DEMO ? ' Demo' : '');

# HTML_ADMIN_FRAME #############################################################
# string html_admin_frame()
function html_admin_frame() {
    return '<frameset cols="150, *" border="0">' . "\n"
            . '<frame name="panel" src="admin.php?mode=panel" />' . "\n"
            . '<frame name="content" src="index.php" />' . "\n"
            . '</frameset>' . "\n";
}

# HTML_ADMIN_PANEL #############################################################
# string html_admin_panel()
function html_admin_panel() {
    $html = '<div><a href="index.php" target="content">Preview</a> || <a href="?mode=logout" target="_parent">Logout</a></div>' . "\n"
//            . '<h2>Assistants</h2>' . "\n"
//            . '<ul>' . "\n"
//Assistant code
//            . '<li><a href="assistants/recipe" target="content">Recipe Assistant</a></li>' . "\n"
//End assistant code
//            . '</ul>' . "\n"
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
//            . '<li><a href="documentation" target="content">Documentation</a></li>' . "\n"
            . '<li><a href="?modify=settings" target="content">Settings</a></li>' . "\n"
            . '</ul>' . "\n";

    return $html;
}

# HTML_ADMIN_SETTINGS ##########################################################
# string html_admin_settings()
function html_admin_settings() {
    global $DEMO, $SETTING, $TABLE;

    $html = '<form action="admin.php?mode=preview" method="post">' . "\n"
        . '<input type="hidden" name="modify" value="settings" />' . "\n"
        . '<h3>Login</h3>' . "\n"
        . html_textbox('setting[' . $SETTING[ADMIN_USER] . ']', 'Username', (!$DEMO ? db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_USER]) : ''))
        . html_textbox('setting[' . $SETTING[ADMIN_PASS] . ']', 'Password', (!$DEMO ? db_fetch($TABLE[SETTINGS], '', $SETTING[ADMIN_PASS]) : ''), true)
        . '<h3>Format</h3>' . "\n"
        . html_textbox('setting[' . $SETTING[FORMAT_DATE] . ']', 'Date', db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_DATE]))
        . html_textbox('setting[' . $SETTING[FORMAT_TIME] . ']', 'Time', db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_TIME]))
        . '<h3>Miscellanea</h3>' . "\n"
        . html_textbox('setting[' . $SETTING[SITE_TITLE] . ']', 'Title', db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]))
        . html_textbox('setting[' . $SETTING[INDEX_TITLE] . ']', 'Index Title', db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_TITLE]))
        . html_textbox('setting[' . $SETTING[INDEX_LIMIT] . ']', 'Index Limit', db_fetch($TABLE[SETTINGS], '', $SETTING[INDEX_LIMIT]))
        . '<p class="form"><input type="reset" value="Reset" /><input type="submit" value="Save" /></p>' . "\n"
        . '</form>' . "\n";

    return $html;
}

# HTML_DISPLAY_PAGE ############################################################
# string html_display_page(string $title, string $style, string $body[, $frame])
function html_display_page($title, $body, $style = '', $frame = '') {
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"
        . '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n"
        . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">' . "\n"
        . '<head>' . "\n"
        . '<title>' . $title . '</title>' . "\n"
        . '<link rel="stylesheet" type="text/css" href="' . (($style == 'assistant') ? '../' : '') . 'css/express.css" />' . "\n"
        . '</head>' . "\n"
        . $frame
        . '<body' . (($style == 'panel') ? ' id="panel"' : '' ) . '>' . "\n"
        . $body
        . '</body>' . "\n"
        . '</html>';
}

# HTML_FOOTER ##################################################################
# string html_footer()
function html_footer() {
    global $EXPRESS;

    return '</div>' . "\n"
        . '<div id="footer"><p><a href="http://express.sedalu.com/" title="Express">Express</a> (version ' . $EXPRESS['version'] . ')</p>' . "\n"
        . '<p>Copyright &copy; 2003 Seth D Lumnah.</p>' . "\n"
        . '<p>All rights reserved.</p>' . "\n";
}

# HTML_FORM ####################################################################
# string html_form(string $table, int $id)
function html_form($table, $id) {
    global $DB, $COLUMN, $MYSQL, $TABLE, $TEMPLET_CLASS, $TEMPLET_TYPE, $FETCH;
$item = db_fetch($table, '', $id);
    $html = '<h2>'. (($id != 'new' && $item) ? 'Modify' : 'Create') . ' ';

    if($table == $TABLE[CATEGORIES]) {
        $html .= 'Category';
    } elseif($table == $TABLE[COMMENTS]) {
        $html .= 'Comment';
    } elseif($table == $TABLE[ENTRIES]) {
        $html .= 'Entry';
    } elseif($table == $TABLE[SECTIONS]) {
        $html .= 'Section';
    } elseif($table == $TABLE[SEGMENTS]) {
        $html .= 'Segment';
    } elseif($table == $TABLE[SETTINGS]) {
        $html .= 'Setting';
    } elseif($table == $TABLE[TEMPLETS]) {
        $html .= 'Templet';
    }

    $html .= '</h2>'. "\n"
        . '<form action="admin.php?mode=preview" method="post">' . "\n"
        . '<input type="hidden" name="' . ($item ? 'modify' : 'create' ) . '" value="' . $table;

    $html .= '" />' . "\n"
        . ($item ? '<input type="hidden" name="item[id]" value="' . (($table == $TABLE[SETTINGS]) ? $id : $item['id']) . '" />' . "\n" : '');

    if($table == $TABLE[CATEGORIES]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_textbox('item[priority]', 'Priority', ($item ? $item['priority'] : '1'));
    } elseif($table == $TABLE[COMMENTS]) {
        $html .= '<input type="hidden" name="item[entry]" value="' . $item['entry'] . '" />' . "\n"
            . '<input type="hidden" name="item[date]" value="' . $item['date'] . '" />' . "\n"
            . html_textbox('item[name]', 'Name', $item['name'])
            . html_textarea('item[comment]', 'Comment', $item['comment']);
    } elseif($table == $TABLE[ENTRIES]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_selection_list('item[section]', 'Section', '', $item['section'], $TABLE[SECTIONS])
            . html_selection_list('item[category]', 'Category', '', $item['category'], $TABLE[CATEGORIES])
            . html_textbox('item[date]', 'Date', ($item ? $item['date'] : date('Y-m-d H-i-s', time())))
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $TABLE[SECTIONS]) {
        $radio_list['yes']['id'] = '1';
        $radio_list['yes']['title'] = 'Yes';
        $radio_list['no']['id'] = '0';
        $radio_list['no']['title'] = 'No';
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_textbox('item[priority]', 'Priority', ($item ? $item['priority'] : '1'))
            . html_radio_list('item[index_display]', 'Index Display', $radio_list, ($item['index_display'] ? $item['index_display'] : 0));
    } elseif($table == $TABLE[SEGMENTS]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_selection_list('item[entry]', 'Entry', '', $item['entry'], $TABLE[ENTRIES])
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $TABLE[SETTINGS]) {
        $html .= html_textbox('item[value]', ucwords($id), $item);
    } elseif($table == $TABLE[TEMPLETS]) {
        $default['title'] = 'DEFAULT';
        $default['id'] = '0';
        $html .= html_selection_list('item[class]', 'Class', $TEMPLET_CLASS, $item['class'])
            . html_selection_list('item[section]', 'Section', array($default), $item['section'], $TABLE[SECTIONS])
            . html_selection_list('item[category]', 'Category', array($default), $item['category'], $TABLE[CATEGORIES])
            . html_selection_list('item[type]', 'Type', $TEMPLET_TYPE, $item['type'])
            . html_textarea('item[text]', 'Text', $item['text']);
    }

    $html .= '<p class="form"><input type="reset" value="Reset" /><input type="submit" value="Save" /></p>' . "\n"
        . '</form>' . "\n";

    return $html;
}

# HTML_LABEL ###################################################################
# string html_label(string $var, string $label)
function html_label($var, $label) {
    return '<label for="' . $var . '">' . $label . '</label>' . "\n";
}

# HTML_RADIO_LIST ##############################################################
# string html_radio_list(string $var, string $label, array $items[, string
#                            $value])
function html_radio_list($var, $label, $items, $value = '') {
    $html = html_label($var, $label);

    if(is_array($items)) {
        $i = 1;

        foreach($items as $item) {
            $html .= '<input type="radio" name="' . $var . '" value="' . (is_array($item) ? $item['id'] : $item) . '"' . (($value == (is_array($item) ? $item['id'] : $item)) ? ' checked' : '') . ' />' . (is_array($item) ? $item['title'] : $item);
            $i++;

            if($i <= count($items)) {
                $html .= '<br />' . "\n";
            }
        }
    }

    return $html . "\n";
}

# HTML_SELECTION_LIST ##########################################################
# string html_selection_list(string $var, string $label, array $items[, string
#                            $value[, string $table]])
function html_selection_list($var, $label, $items, $value = '', $table = '') {
    global $ORDER, $TABLE;

    $html = html_label($var, $label);

    if(is_array($items) || db_fetch($table)) {
        $html .= '<select name="' . $var . '">' . "\n";
    
        if(is_array($items)) {
            foreach($items as $item) {
                $html .= '<option value="' . (is_array($item) ? $item['id'] : $item) . '"' . (($value == (is_array($item) ? $item['id'] : $item)) ? ' selected' : '') . '>' . (is_array($item) ? $item['title'] : $item) . '</option>' . "\n";
            }
        }
    
        if($items = db_fetch($table, $ORDER[strtoupper($table)])) {
            for($i = 1; $i <= mysql_num_rows($items); $i++) {
                $item = mysql_fetch_array($items);
                $html .= '<option value="' . $item['id'] . '"' . (($value == $item['id']) ? ' selected' : '') . '>';

                if($table == $TABLE[COMMENTS]) {
                    $entry = db_fetch($TABLE[ENTRIES], '', $item['entry']);
                    $section = db_fetch($TABLE[SECTIONS], '', $entry['section']);
                    $category = db_fetch($TABLE[CATEGORIES], '', $entry['category']);
                    $html .= ($section['title'] ? $section['title'] : '') . ': ' . ($category['title'] ? $category['title'] : '') . ': ' . ($entry['title'] ? $entry['title'] : '') . ': ' . $item['name'] . ' (' . $item['date'] . ')';
                } elseif($table == $TABLE[ENTRIES]) {
                    $section = db_fetch($TABLE[SECTIONS], '', $item['section']);
                    $category = db_fetch($TABLE[CATEGORIES], '', $item['category']);
                    $html .= ($section['title'] ? $section['title'] : '') . ': ' . ($category['title'] ? $category['title'] : '') . ': ' . $item['title'];
                } elseif($table == $TABLE[SEGMENTS]) {
                    $entry = db_fetch($TABLE[ENTRIES], '', $item['entry']);
                    $section = db_fetch($TABLE[SECTIONS], '', $entry['section']);
                    $category = db_fetch($TABLE[CATEGORIES], '', $entry['category']);
                    $html .= ($section['title'] ? $section['title'] : '') . ': ' . ($category['title'] ? $category['title'] : '') . ': ' . ($entry['title'] ? $entry['title'] : '') . ': ' . $item['title'];
                } elseif($table == $TABLE[TEMPLETS]) {
                    $section = db_fetch($TABLE[SECTIONS], '', $item['section']);
                    $category = db_fetch($TABLE[CATEGORIES], '', $item['category']);
                    $html .= strtoupper($item['class']) . ': ' . ($item['section'] ? $section['title'] . ': ' : '') . ($item['category'] ? $category['title'] . ': ' : '') . ucwords($item['type']);
                } else {
                    $html .= $item['title'];
                }

                $html .= '</option>' . "\n";
            }
        }
    
        $html .= '</select>';
    } else {
        $html .= '<p>None available.</p>';
    }

    return $html . "\n";
}

# HTML_TEXTAREA ################################################################
# string html_textarea(string $var, string $label[, strng $value])
function html_textarea($var, $label, $value='') {
    return html_label($var, $label)
        . '<textarea name="' . $var . '" rows="16" cols="72">' . htmlspecialchars($value) . '</textarea>' . "\n";
}

# HTML_TEXTBOX #################################################################
# string html_textbox(string $var, string $label[, string $value[, bool
#                     $is_pass]])
function html_textbox($var, $label, $value = '', $is_pass = false) {
    return html_label($var, $label)
        . '<input type="' . ($is_pass ? 'password' : 'text') . '" name="' . $var . '" size="32"' . ($value ? ' value="' . $value . '"' : '') . ' />' . "\n";
}
?>