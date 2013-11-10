<?php
################################################################################
# LIBRARY/TEMPLET.PHP                                                          #
################################################################################

$TOKEN[CATEGORY] = '<!-- category -->';
$TOKEN[CATEGORY_ANCHOR] = '<!-- category anchor -->';
$TOKEN[COMMENT] = '<!-- comment -->';
$TOKEN[COMMENTS] = '<!-- comments -->';
$TOKEN[SITE_TITLE] = '<!-- site title -->';
$TOKEN[TITLE] = '<!-- title -->';

# TEMPLET_COMPLETE #############################################################
# string templet_complete(array $templet)
function templet_complete($templet, $values) {
    global $DB, $FETCH, $SETTING, $TABLE, $TEMPLET_TYPE, $TOKEN;

    $text = stripslashes($templet['text']);

    if(substr_count($text, $TOKEN[CATEGORY])) {
        $category = db_fetch($TABLE[CATEGORIES], 'priority DESC, title ASC', $values['category']);
        $text = str_replace($TOKEN[CATEGORY], $category['title'], $text);
    }

    if(substr_count($text, $TOKEN[CATEGORY_ANCHOR])) {
        if(!$values['category']) {
            $values['category'] = $values['id'];
        }

        $text = str_replace($TOKEN[CATEGORY_ANCHOR], 'category_' . $values['category'], $text);
    }

    if(substr_count($text, $TOKEN[COMMENT])) {
        $text = str_replace($TOKEN[COMMENT], $values['comment'], $text);
    }

    if(substr_count($text, $TOKEN[COMMENTS])) {
        $text = str_replace($TOKEN[COMMENTS], $values['comments'], $text);
    }

    if(substr_count($text, '<!-- comment form -->')) {
        $text = str_replace('<!-- comment form -->', '<form name="comment" action="comment" method="post">' . "\n"
            . '<input type="hidden" name="entry" value="' . $values['id'] . '" />' . "\n"
            . '<lable for="name">Name:</lable><br />' . "\n"
            . '<input type="text" name="name" size="32" /><br />' . "\n"
            . '<br />' . "\n"
            . '<label for="comment">Comment:</label><br />' . "\n"
            . '<textarea name="comment" rows="15" cols="72">' . "\n"
            . '</textarea><br />' . "\n"
            . '<br />' . "\n"
            . '<input type="reset" value="Clear" /> <input type="submit" value="Comment" />' . "\n"
            . '</form>' . "\n", $text);
    }

    if(substr_count($text, '<!-- comment link -->')) {
        $comment_str = 'Comment';
        $comments = db_fetch($FETCH[ENTRY_COMMENTS], '', $values['id']);

        if($comments) {
            $comment_str .= 's (' . mysql_num_rows($comments) . ')';
            $comment_url = 'entry.php?id=' . $values['id'] . '#comments';
        } else {
            $comment_url = 'comment.php?id=' . $values['id'];
        }

        $text = str_replace('<!-- comment link -->', '<a href="' . $comment_url . '">' . $comment_str . '</a>', $text);
    }

    if(substr_count($text, '<!-- comment url -->')) {
        $text = str_replace('<!-- comment url -->', 'comment.php?id=' . $values['id'], $text);
    }

    if(substr_count($text, '<!-- content -->')) {
        $text = str_replace('<!-- content -->', $values['content'], $text);
    }

    if(substr_count($text, '<!-- date -->')) {
        $text = str_replace('<!-- date -->', date(db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_DATE]), strtotime($values['date'])), $text);
    }

    if(substr_count($text, '<!-- entry url -->')) {
        $text = str_replace('<!-- entry url -->', 'entry.php?id=' . $values['id'], $text);
    }

    if(substr_count($text, '<!-- id -->')) {
        $text = str_replace('<!-- id -->', $values['id'], $text);
    }

    if(substr_count($text, '<!-- name -->')) {
        $text = str_replace('<!-- name -->', $values['name'], $text);
    }

    if(substr_count($text, '<!-- section -->')) {
        $section = db_fetch($TABLE[SECTIONS], 'priority DESC, title ASC', $values['section']);
        $text = str_replace('<!-- section -->', $section['title'], $text);
    }

    if(substr_count($text, '<!-- section links -->') && ($sections = db_fetch($TABLE[SECTIONS], 'priority DESC, title ASC'))) {
        $section_link = db_fetch($FETCH[TEMPLET], '', $TEMPLET_TYPE[SECTION_LINK]);

        for($i = 1; $i <= mysql_num_rows($sections); $i++) {
            $section_links .= templet_complete($section_link, mysql_fetch_array($sections));
        }

        $text = str_replace('<!-- section links -->', $section_links, $text);
    }

    if(substr_count($text, '<!-- section url -->')) {
        if(!$values['section']) {
            $values['section'] = $values['id'];
        }

        $text = str_replace('<!-- section url -->', 'section.php?id=' . $values['section'], $text);
    }

    if(substr_count($text, '<!-- segments -->')) {
        $text = str_replace('<!-- segments -->', $values['segments'], $text);
    }

    if(substr_count($text, '<!-- segment anchor -->')) {
        $text = str_replace('<!-- segment anchor -->', 'segment_' . $values['id'], $text);
    }

    if(substr_count($text, $TOKEN[SITE_TITLE])) {
        $text = str_replace($TOKEN[SITE_TITLE], db_fetch($TABLE[SETTINGS], '', $SETTING[SITE_TITLE]), $text);
    }

    if(substr_count($text, '<!-- text -->')) {
        $text = str_replace('<!-- text -->', $values['text'], $text);
    }

    if(substr_count($text, '<!-- time -->')) {
        $time = db_fetch($TABLE[SETTINGS], '', $SETTING[FORMAT_TIME]);
        $text = str_replace('<!-- time -->', date($time, strtotime($values['date'])), $text);
    }

    if(substr_count($text, $TOKEN[TITLE])) {
        $text = str_replace($TOKEN[TITLE], $values['title'], $text);
    }

    return $text;
}
?>