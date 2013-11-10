<?php
################################################################################
# LIBRARY/HTML.PHP                                                             #
################################################################################

$EXPRESS['version'] = '1.0.1';

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
        . '<p>Copyright &copy; 2003 Seth D Lumnah. All rights reserved.</p>' . "\n";
}

# HTML_GET_SELECTION_LIST ######################################################
# string html_get_selection_list(string $table, string $variable, string $label,
#                                int $value)
function html_get_selection_list($table, $variable, $label, $value) {
    global $DB, $TABLE;
    $html = '<h3><lable for="' . $variable . '">' . $label . '</lable></h3>' . "\n";

    if($items = db_fetch($table, 'id')) {
        $html .= '<select name="' . $variable . '">' . "\n";

        for($i = 1; $i <= mysql_num_rows($items); $i++) {
            $item = mysql_fetch_array($items);
            $html .= '<option value="' . $item['id'] . '"' . (($value == $item['id']) ? ' selected' : '') . '>';

            if($table == $TABLE[TEMPLETS]) {
                $section = db_fetch($TABLE[SECTIONS], 'priority DESC, title ASC', $item['section']);
                $category = db_fetch($TABLE[CATEGORIES], 'priority DESC, title ASC', $item['category']);
                $html .= strtoupper($item['class']) . ': ' . ($item['section'] ? $section['title'] . ': ' : '') . ($item['category'] ? $category['title'] . ': ' : '') . ucwords($item['type']);
            } elseif($table == $TABLE[SETTINGS]) {
                $html .= ucwords($item['id']) . '</option>' . "\n";
            } else {
                $html .= $item['title'] . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n";
    } else {
        $html .= '<p>None available</p>' . "\n";
    }

    return $html;
}

# HTML_FORM ####################################################################
# string html_form(string $table, int $id)
function html_form($table, $id) {
    global $DB, $COLUMN, $MYSQL, $TABLE, $FETCH;
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
            . html_get_selection_list($TABLE[SECTIONS], 'item[section]', 'Section', $item['section'])
            . html_get_selection_list($TABLE[CATEGORIES], 'item[category]', 'Category', $item['category'])
            . html_textbox('item[date]', 'Date', ($item ? $item['date'] : date('Y-m-d H-i-s', time())))
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $TABLE[SECTIONS]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_textbox('item[priority]', 'Priority', ($item ? $item['priority'] : '1'))
            . '<h3><lable for"item[index_display]">Index Display:</lable></h3>' . "\n"
            . '<input type="radio" name="item[index_display]" value="1"' . ($item['index_display'] ? ' checked' : '') . ' />Yes' . "\n"
            . '<input type="radio" name="item[index_display]" value="0"' . (!$item['index_display'] ? ' checked' : '') . ' />No' . "\n";
    } elseif($table == $TABLE[SEGMENTS]) {
        $html .= html_textbox('item[title]', 'Title', $item['title'])
            . html_get_selection_list($TABLE[ENTRIES], 'item[entry]', 'Entry', $item['entry'])
            . html_textarea('item[text]', 'Text', $item['text']);
    } elseif($table == $TABLE[SETTINGS]) {
        $html .= html_textbox('item[value]', ucwords($id), $item);
    } elseif($table == $TABLE[TEMPLETS]) {
        $html .= '<h3><lable for="item[class]">Class:</lable></h3>' . "\n"
            . '<select name="item[class]">' . "\n";
 
        if($classes = db_fetch_column($COLUMN[CLASSES])) {
            for($i = 1; $i <= count($classes); $i++) {
                $html .= '<option value="' . $classes[($i - 1)] . '"' . (($item['class'] == $classes[($i - 1)]) ? ' selected' : '') . '>' . strtoupper($classes[($i - 1)]) . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n"
            . '<h3><lable for="item[section]">Section:</lable></h3>' . "\n"
            . '<select name="item[section]">' . "\n"
            . '<option value="0"' . (($item['section'] == 0) ? ' selected' : '') . '>DEFAULT</option>' . "\n";

        if($sections = db_fetch($TABLE[SECTIONS], 'priority DESC, title ASC')) {
            for($i = 1; $i <= mysql_num_rows($sections); $i++) {
                $section = mysql_fetch_array($sections);
                $html .= '<option value="' . $section['id'] . '"' . (($item['section'] == $section['id']) ? ' selected' : '') . '>' . $section['title'] . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n"
            . '<h3><lable for="item[category]">Category:</lable></h3>' . "\n"
            . '<select name="item[category]">' . "\n"
            . '<option value="0"' . (($item['category'] == 0) ? ' selected' : '') . '>DEFAULT</option>' . "\n";

        if($categories = db_fetch($TABLE[CATEGORIES], 'priority DESC, title ASC')) {
            for($i = 1; $i <= mysql_num_rows($categories); $i++) {
                $category = mysql_fetch_array($categories);
                $html .= '<option value="' . $category['id'] . '"' . (($item['category'] == $category['id']) ? ' selected' : '') . '>' . $category['title'] . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n"
            . '<h3><lable for="item[type]">Type:</lable></h3>' . "\n"
            . '<select name="item[type]">' . "\n";
 
        if($types = db_fetch_column($COLUMN[TYPES])) {
            for($i = 1; $i <= count($types); $i++) {
                $html .= '<option value="' . $types[($i - 1)] . '"' . (($item['type'] == $types[($i - 1)]) ? ' selected' : '') . '>' . ucwords($types[($i - 1)]) . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n"
            . html_textarea('item[text]', 'Text', $item['text']);
    }

    $html .= '<p class="form"><input type="reset" value="Reset" /> <input type="submit" value="Save" /></p>' . "\n"
        . '</form>' . "\n";

    return $html;
}

# HTML_MAKE_LIST ###############################################################
# string html_make_list(string $text)
function html_make_list($text) {
    return '<ul>' . "\n"
        . '<li>' . str_replace("\r\n", '</li>' . "\r\n" . '<li>', trim($text)) . '</li>' . "\n"
        . '</ul>' . "\n";
}

# HTML_MAKE_PARAGRAPHS #########################################################
# string html_make_paragraphs(string $text)
function html_make_paragraphs($text) {
    return '<p>' . str_replace("\r\n", '</p>' . "\r\n" . '<p>', trim($text)) . '</p>' . "\n";
}




# HTML_CHECKBOX ################################################################
# string html_checkbox()

# HTML_LABEL ###################################################################
# string html_label(string $var, string $label)
function html_label($var, $label) {
    return '<h3><label for="' . $var . '">' . $label . '</label></h3>' . "\n";
}

# HTML_SELECTION_LIST ##########################################################
# string html_selection_list(string $var, string $label, array $items[, string
#                            $value])
function html_selection_list($var, $label, $items, $value = '') {
    $html = html_label($var, $label)
        . '<select name="' . $var . '">' . "\n";

    for($i = 0; $i < count($item); $i++) {
        $item = $items[$i];
        $html .= '<option value="' . $item['id'] . '"' . (($value == $item['id']) ? ' selected' : '') . '>' . $item['title'] . '</option>' . "\n";
    }

    return $html . '</select>' . "\n";
}

# HTML_TEXTAREA ################################################################
# string html_textarea(string $var, string $label[, strng $value])
function html_textarea($var, $label, $value='') {
    return html_label($var, $label)
        . '<textarea name="' . $var . '" rows="16" cols="72">' . trim($value) . '</textarea>' . "\n";
}

# HTML_TEXTBOX #################################################################
# string html_textbox(string $var, string $label[, string $value[, bool
#                     $is_pass]])
function html_textbox($var, $label, $value = '', $is_pass = false) {
    return html_label($var, $label)
        . '<input type="' . ($is_pass ? 'password' : 'text') . '" name="' . $var . '" size="32"' . ($value ? ' value="' . $value . '"' : '') . ' />' . "\n";
}
?>