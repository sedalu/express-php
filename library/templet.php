<?php
################################################################################
# LIBRARY/TEMPLET.PHP                                                          #
################################################################################

$TOKENS[CATEGORY][TOKEN] = 'category';
$TOKENS[CATEGORY][FUNCATION] = 'templet_replace_token__category';
$TOKENS[CATEGORY_ANCHOR][TOKEN] = 'category anchor';
$TOKENS[CATEGORY_ANCHOR][FUNCATION] = 'templet_replace_token__category_anchor';
$TOKENS[COMMENT][TOKEN] = 'comment';
$TOKENS[COMMENT][FUNCATION] = 'templet_replace_token__comment';
$TOKENS[COMMENTS][TOKEN] = 'comments';
$TOKENS[COMMENTS][FUNCATION] = 'templet_replace_token__comments';
$TOKENS[COMMENT_FORM][TOKEN] = 'comment form';
$TOKENS[COMMENT_FORM][FUNCATION] = 'templet_replace_token__comment_form';
$TOKENS[COMMENT_LINK][TOKEN] = 'comment link';
$TOKENS[COMMENT_LINK][FUNCATION] = 'templet_replace_token__comment_link';
$TOKENS[COMMENT_URL][TOKEN] = 'comment url';
$TOKENS[COMMENT_URL][FUNCATION] = 'templet_replace_token__comment_url';
$TOKENS[CONTENT][TOKEN] = 'content';
$TOKENS[CONTENT][FUNCATION] = 'templet_replace_token__content';
$TOKENS[DATE][TOKEN] = 'date';
$TOKENS[DATE][FUNCATION] = 'templet_replace_token__date';
$TOKENS[ENTRY_URL][TOKEN] = 'entry url';
$TOKENS[ENTRY_URL][FUNCATION] = 'templet_replace_token__entry_url';
$TOKENS[ID][TOKEN] = 'id';
$TOKENS[ID][FUNCATION] = 'templet_replace_token__id';
$TOKENS[NAME][TOKEN] = 'name';
$TOKENS[NAME][FUNCATION] = 'templet_replace_token__name';
$TOKENS[SECTION][TOKEN] = 'section';
$TOKENS[SECTION][FUNCATION] = 'templet_replace_token__section';
$TOKENS[SECTION_LINKS][TOKEN] = 'section links';
$TOKENS[SECTION_LINKS][FUNCATION] = 'templet_replace_token__section_links';
$TOKENS[SECTION_URL][TOKEN] = 'section url';
$TOKENS[SECTION_URL][FUNCATION] = 'templet_replace_token__section_url';
$TOKENS[SEGMENT_ANCHOR][TOKEN] = 'segment anchor';
$TOKENS[SEGMENT_ANCHOR][FUNCATION] = 'templet_replace_token__segment_anchor';
$TOKENS[SEGMENTS][TOKEN] = 'segments';
$TOKENS[SEGMENTS][FUNCATION] = 'templet_replace_token__segments';
$TOKENS[SITE_TITLE][TOKEN] = 'site title';
$TOKENS[SITE_TITLE][FUNCATION] = 'templet_replace_token__site_title';
$TOKENS[TEXT][TOKEN] = 'text';
$TOKENS[TEXT][FUNCATION] = 'templet_replace_token__text';
$TOKENS[TIME][TOKEN] = 'time';
$TOKENS[TIME][FUNCATION] = 'templet_replace_token__time';
$TOKENS[TITLE][TOKEN] = 'title';
$TOKENS[TITLE][FUNCATION] = 'templet_replace_token__title';

# TEMPLET_REPLACE_TOKENS #######################################################
# string templet_replace_tokens(string $templet, array $values)
function templet_replace_tokens($templet, $values) {
    global $TOKENS;

    $templet['text'] = stripslashes($templet['text']);

    if(substr_count($templet['text'], '<!-- ' . $TOKENS[SITE_TITLE][TOKEN] . ' -->')) {
        $templet['text'] = str_replace('<!-- ' . $TOKENS[SITE_TITLE][TOKEN] . ' -->', call_user_func($TOKENS[SITE_TITLE][FUNCATION], $templet, $values), $templet['text']);
    }

    if(substr_count($templet['text'], '<!-- ' . $TOKENS[TITLE][TOKEN] . ' -->')) {
        $templet['text'] = str_replace('<!-- ' . $TOKENS[TITLE][TOKEN] . ' -->', call_user_func($TOKENS[TITLE][FUNCATION], $templet, $values), $templet['text']);
    }

    foreach($TOKENS as $token => $value) {
        if(function_exists($TOKENS[$token][FUNCATION])) {
            if(substr_count($templet['text'], '<!-- ' . $TOKENS[$token][TOKEN] . ' -->')) {
               $templet['text'] = str_replace('<!-- ' . $TOKENS[$token][TOKEN] . ' -->', call_user_func($TOKENS[$token][FUNCATION], $templet, $values), $templet['text']);
            }
        }
    }

    return $templet['text'];
}

# TEMPLET_REPLACE_TOKEN__CATEGORY ##############################################
# string templet_replace_token__category(string $templet, array $values)
function templet_replace_token__category($templet, $values) {
    global $TABLE;

    $category = db_fetch($TABLE[CATEGORIES], '', $values['category']);

    return $category['title'];
}

# TEMPLET_REPLACE_TOKEN__CATEGORY_ANCHOR #######################################
# string templet_replace_token__category_anchor(string $templet, array $values)
function templet_replace_token__category_anchor($templet, $values) {
    return 'category_' . ($values['category'] ? $values['category'] : $values['id']);
}

# TEMPLET_REPLACE_TOKEN__COMMENT ###############################################
# string templet_replace_token__comment(string $templet, array $values)
function templet_replace_token__comment($templet, $values) {
    return $values['comment'];
}

# TEMPLET_REPLACE_TOKEN__COMMENTS ##############################################
# string templet_replace_token__comments(string $templet, array $values)
function templet_replace_token__comments($templet, $values) {
    return $values['comments'];
}

# TEMPLET_REPLACE_TOKEN__COMMENT_FORM ##########################################
# string templet_replace_token__comment_form(string $templet, array $values)
function templet_replace_token__comment_form($templet, $values) {
    return '<form action="entry.php?id=' . $values['id'] . '#comments" method="post">' . "\n"
        . '<lable for="comment[name]">Name:</lable><br />' . "\n"
        . '<input type="text" name="comment[name]" size="32" /><br />' . "\n"
        . '<br />' . "\n"
        . '<label for="comment[comment]">Comment:</label><br />' . "\n"
        . '<textarea name="comment[comment]" rows="15" cols="72">' . "\n"
        . '</textarea><br />' . "\n"
        . '<br />' . "\n"
        . '<input type="reset" value="Clear" /> <input type="submit" value="Comment" />' . "\n"
        . '</form>' . "\n";
}

# TEMPLET_REPLACE_TOKEN__COMMENT_LINK ##########################################
# string templet_replace_token__comment_link(string $templet, array $values)
function templet_replace_token__comment_link($templet, $values) {
    global $FETCH;

    $comments = db_fetch($FETCH[ENTRY_COMMENTS], '', $values['id']);

    return '<a href="' . ($comments ? 'entry' : 'comment') . '.php?id=' . $values['id'] . ($comments ? '#comments' : '') . '">Comment' . ($comments ? 's (' . mysql_num_rows($comments) . ')' : '') . '</a>';
}

# TEMPLET_REPLACE_TOKEN__COMMENT_URL ###########################################
# string templet_replace_token__comment_url(string $templet, array $values)
function templet_replace_token__comment_url($templet, $values) {
    return 'comment.php?id=' . $values['id'];
}

# TEMPLET_REPLACE_TOKEN__CONTENT ###############################################
# string templet_replace_token__content(string $templet, array $values)
function templet_replace_token__content($templet, $values) {
    return $values['content'];
}

# TEMPLET_REPLACE_TOKEN__DATE ##################################################
# string templet_replace_token__date(string $templet, array $values)
function templet_replace_token__date($templet, $values) {
    global $SETTING, $TABLE;

    return date(db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_DATE]), strtotime($values['date']));
}

# TEMPLET_REPLACE_TOKEN__ENTRY_URL #############################################
# string templet_replace_token__entry_url(string $templet, array $values)
function templet_replace_token__entry_url($templet, $values) {
    return 'entry.php?id=' . $values['id'];
}

# TEMPLET_REPLACE_TOKEN__ID ####################################################
# string templet_replace_token__id(string $templet, array $values)
function templet_replace_token__id($templet, $values) {
    return $values['id'];
}

# TEMPLET_REPLACE_TOKEN__NAME ##################################################
# string templet_replace_token__name(string $templet, array $values)
function templet_replace_token__name($templet, $values) {
    return $values['name'];
}

# TEMPLET_REPLACE_TOKEN__SECTION ###############################################
# string templet_replace_token__section(string $templet, array $values)
function templet_replace_token__section($templet, $values) {
    global $TABLE;

    $section = db_fetch($TABLE[SECTIONS], '', $values['section']);

    return $section['title'];
}

# TEMPLET_REPLACE_TOKEN__SECTION_LINKS #########################################
# string templet_replace_token__section_links(string $templet, array $values)
function templet_replace_token__section_links($templet, $values) {
    global $FETCH, $TABLE, $TEMPLET_TYPE;

    if($sections = db_fetch($TABLE[SECTIONS], 'priority DESC, title ASC')) {
        for($i = 1; $i <= mysql_num_rows($sections); $i++) {
            $section = mysql_fetch_array($sections);
            $section_link = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SECTION_LINK], $section['id']);
            $section_links .= templet_replace_tokens($section_link, $section);
        }
    }

    return $section_links;
}

# TEMPLET_REPLACE_TOKEN__SECTION_URL ###########################################
# string templet_replace_token__section_url(string $templet, array $values)
function templet_replace_token__section_url($templet, $values) {
    return 'section.php?id=' . ($values['section'] ? $values['section'] : $values['id']);
}

# TEMPLET_REPLACE_TOKEN__SEGMENT_ANCHOR ########################################
# string templet_replace_token__segment_anchor(string $templet, array $values)
function templet_replace_token__segment_anchor($templet, $values) {
    return 'segment_' . $values['id'];
}

# TEMPLET_REPLACE_TOKEN__SEGMENTS ##############################################
# string templet_replace_token__segments(string $templet, array $values)
function templet_replace_token__segments($templet, $values) {
    return $values['segments'];
}

# TEMPLET_REPLACE_TOKEN__SITE_TITLE ############################################
# string templet_replace_token__site_title(string $templet, array $values)
function templet_replace_token__site_title($templet, $values) {
    global $SETTING, $TABLE;
    return db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]);
}

# TEMPLET_REPLACE_TOKEN__TEXT ##################################################
# string templet_replace_token__text(string $templet, array $values)
function templet_replace_token__text($templet, $values) {
    return $values['text'];
}

# TEMPLET_REPLACE_TOKEN__TIME ##################################################
# string templet_replace_token__time(string $templet, array $values)
function templet_replace_token__time($templet, $values) {
    global $SETTING, $TABLE;
    return date(db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_TIME]), strtotime($values['date']));
}

# TEMPLET_REPLACE_TOKEN__TITLE #################################################
# string templet_replace_token__title(string $templet, array $values)
function templet_replace_token__title($templet, $values) {
    return $values['title'];
}
?>