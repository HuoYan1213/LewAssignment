<?php

$_db = new PDO('mysql:dbname=food', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

function req_trim($key) {
    return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : null;
}

function html_search($key, $value = '', $attr = '') {
    $value = htmlspecialchars($value);
    echo "<input type='search' id='$key' name='$key' value='$value' $attr>";
}

function html_select($key, $items, $selected = '', $default = '- Select One -', $attr = '') {
    echo "<select id='$key' name='$key' $attr>";
    if ($default !== null) {
        echo "<option value=''>$default</option>";
    }
    foreach ($items as $id => $text) {
        $state = ($id === $selected) ? 'selected' : '';
        echo "<option value='$id' $state>$text</option>";
    }
    echo '</select>';
}

?>