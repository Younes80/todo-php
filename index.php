<pre>
<?php

// Constantes à mettre au plus haut du fichier
const ERROR_REQUIRED = "Veuillez renseigner une tâche";
const ERROR_TOO_SHORT = "Veuiller entrer au moins 5 caratères";



// initialisation des erreurs avec une chaine de caratère vide
$errors = [
    'task' => '',
];

// Condition pour vérifier la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Filtre pour sécuriser les input du formulaire
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // Récupération de la valeur et stocker dans une variable
    $task = $_POST['task'];

    // Condition pour la gestion des erreurs
    if (!$task) {
        $errors['task'] = ERROR_REQUIRED;
    } elseif (mb_strlen($task) < 5) {
        $errors['task'] = ERROR_TOO_SHORT;
    }
}

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
                    <input type="text" name="task" id="task">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </form>
                <?php if ($errors['task']) : ?>
                    <p class="text-danger"><?= $errors['task'] ?></p>
                <?php endif; ?>
                <div class="todo-list"></div>
            </div>
        </div>
        <?php include './includes/footer.php' ?>
    </div>

</body>

</html>