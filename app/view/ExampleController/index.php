<?php
?>

<div>
    <form method="post" action="/createUser">
        <label>
            Nom:
            <input type="text" name="name" placeholder="Nom...">
        </label>
        <label>
            Email:
            <input type="email" name="email" placeholder="Email...">
        </label>
        <label>
            Mot de passe:
            <input type="password" name="password" placeholder="Mot de passe...">
        </label>
        <input type="submit" value="Envoyer">
        <?php
        if (isset(\App\RouterApp::$DATA["error"])) {
            ?>
                <div class="error">
                    <p> Error: <?= \App\RouterApp::$DATA["error"] ?> </p>
                </div>
            <?php
        }
        ?>
    </form>
</div>
