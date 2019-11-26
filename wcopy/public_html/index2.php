<?php
require '../bootloader.php';

use App\App;

$createForm = new \App\Participants\Views\CreateForm();
$updateForm = new \App\Participants\Views\UpdateForm();
$navigation = new \App\Views\Navigation();
$footer = new \App\Views\Footer();

if (!App::$session->userLoggedIn()) {
    header('Location: /login.php');
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="media/css/normalize.css">
        <link rel="stylesheet" href="media/css/milligram.min.css">
        <link rel="stylesheet" href="media/css/style.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
    <body>
        <header>
            <?php print $navigation->render(); ?>
        </header>

        <main>
            <section class="wrapper">
                <div class="block">
                    <h1>Dalyvių Tvarkyklė:</h1>                    
                    <?php print $createForm->render(); ?>
                </div>
                <div class="block">
                    <div id="person-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nr.</th>
                                    <th>Vardas</th>
                                    <th>Pavardė</th>
                                    <th>Miestas</th>
                                    <th>Trinimas</th>
                                    <th>Redagavimas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows Are Dynamically Populated -->
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </section>

            <!-- Update Modal -->
            <div id="update-modal" class="modal">
                <div class="wrapper">
                    <span class="close">&times;</span>
                    <?php print $updateForm->render(); ?>
                </div>
            </div>            
        </main>

        <!-- Footer -->        
        <footer>
            <?php print $footer->render(); ?>
        </footer>

        <script defer src="media/js/app.js"></script>
    </body>
</html>
