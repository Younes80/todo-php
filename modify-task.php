<?php
$filename = './data/data.json';
$_POST = filter_input_array(INPUT_POST, [
    'id' => FILTER_VALIDATE_INT,
    'action' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
]);
$id = $_POST['id'] ?? '';
$action = $_POST['action'] ?? '';

if ($id && $action) {
    $todos = json_decode(file_get_contents($filename), true) ?? [];
    if (count($todos)) {
        $taskIndex = array_search($id, array_column($todos, 'id'));
        if ($action === 'remove') array_splice($todos, $taskIndex, 1);
        if ($action === 'edit') $todos[$taskIndex]['done'] = !$todos[$taskIndex]['done'];
        file_put_contents($filename, json_encode($todos));
    }
}
header('Location: /');
