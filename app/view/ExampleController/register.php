<?php
?>
<div>
    <form class="authentication-form" method="post" action="/createUser">
        <h1>Register</h1>
        <label>
            Name:
            <input type="text" placeholder="Name..." name="name">
        </label>
        <label>
            Email:
            <input type="email" placeholder="Email..." name="email">
        </label>
        <label>
            Password:
            <input type="password" placeholder="Password..." name="password">
        </label>
        <input class="send" type="submit" value="Register">
        <?php \App\RouterApp::LINK_TO_METHOD("ExampleController", "login", "Already have an account ?"); ?>
    </form>
</div>
