<?php
?>
<div>
    <form class="authentication-form" method="post" action="/loginUser">
        <h1>Login</h1>
        <label>
            Email:
            <input type="email" placeholder="Email..." name="email">
        </label>
        <label>
            Password:
            <input type="password" placeholder="Password..." name="password">
        </label>
        <input class="send" type="submit" value="Login">
        <?php \App\RouterApp::LINK_TO_METHOD("ExampleController", "register", "Do not have any account ?"); ?>
    </form>
</div>
