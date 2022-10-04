<?php

$filename = './data/data.json';

$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];

    if (count($todos)) {
        $taskIndex = array_search($id, array_column($todos, 'id'));
        $todos[$taskIndex]['done'] = !$todos[$taskIndex]['done'];
        file_put_contents($filename, json_encode($todos));
    }
}

header('Location: /');
