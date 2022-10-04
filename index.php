<pre>
<?php

// Constantes à mettre au plus haut du fichier
const ERROR_REQUIRED = "Veuillez renseigner une tâche";
const ERROR_TOO_SHORT = "Veuiller entrer au moins 5 caratères";

// Variable qui stocke le chemin du fichier JSON
$filename = "./data/data.json";

// Initialisation des erreurs avec une chaine de caratère vide
$errors = [
    'task' => '',
];
// $task = '';
$todos = [];

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}


// Condition pour vérifier la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Filtre pour sécuriser les input du formulaire
    $_POST = filter_input_array(INPUT_POST, [
        'task' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_BACKTICK
        ]
    ]);
    // Récupération de la valeur et stocker dans une variable
    $task = $_POST['task'] ?? '';

    // Condition pour la gestion des erreurs
    if (!$task) {
        $errors['task'] = ERROR_REQUIRED;
    } elseif (mb_strlen($task) < 5) {
        $errors['task'] = ERROR_TOO_SHORT;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        $todos = [...$todos, [
            'task' => $task,
            'done' => false,
            'id' => time(),
        ]];
        file_put_contents($filename, json_encode($todos));
        $task = '';
    }
}

// print_r($todos);

?>
</pre>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- async ou defer pour ne pas bloquer l'affichage de la page jusqu'au chargement du script -->
    <script async src="assets/js/index.js"></script>
</head>

<body>
    <div class="container">

        <?php include './includes/header.php' ?>
        <div class="content">
            <div class="todo-container">
                <h2>Ma todo</h2>
                <form class="todo-form" action="/" method="POST">
                    <input type="text" name="task" id="task" value="<?= $task ?? '' ?>">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </form>
                <!-- Affichage des erreurs -->
                <?php if ($errors['task']) : ?>
                    <p class="text-danger"><?= $errors['task'] ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                    <?php foreach ($todos as $task) : ?>
                        <li class="task-item <?= $task['done'] ? 'low-opacity' : '' ?>">
                            <span class="task-name"> <?= $task['task'] ?></span>
                            <a href="/edit-task.php?id=<?= $task['id'] ?>">
                                <button class="btn btn-primary btn-small"><?= $task['done'] ? 'Annuler' : 'Valider' ?></button>
                            </a>
                            <a href="/remove-task.php?id=<?= $task['id'] ?>">
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php include './includes/footer.php' ?>
    </div>

</body>

</html>