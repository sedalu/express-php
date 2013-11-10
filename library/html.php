<?php
################################################################################
# LIBRARY/HTML.PHP                                                             #
################################################################################

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