<?php
/**
 * @var Framework\MVC\View $view
 */
$view->extends('default', 'contents');
?>
<style>
    body {
        background-color: #111111;
        color: #eeeeee;
    }
</style>
<h1><?= lang('home.title') ?></h1>
<p><?= lang('home.description') ?></p>

<input type="email" name="email" id="email">
<input type="password" name="password" id="password">
<button id="send" type="button">Send</button>
<button id="get" type="button">GET</button>

<script>
    document.querySelector("#send").addEventListener("click", () => {
        let email = document.querySelector("#email").value
        let password = document.querySelector("#password").value
        let data = {
            "email": email,
            "password": password
        }

        fetch("<?= route_url('users.create') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json; charset=UTF-8"
            },
            body: JSON.stringify(data)
        }).then(response => response.json()).then(response => console.log(response)).catch(err => console.error(err))
    })
    document.querySelector("#get").addEventListener("click", () => {
        fetch("<?= route_url('users.index') ?>?email=code@codingstep.com.br&password=Aa123456/", {
            method: "GET",
            headers: {
                "Content-Type": "application/json; charset=UTF-8"
            }
        }).then(response => response.json()).then(response => console.log(response)).catch(err => console.error(err))
    })
</script>
