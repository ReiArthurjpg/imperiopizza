<?php
$html = file_get_contents('imperial_controle_comandas_v4_integrado.html');
preg_match('/<style>(.*?)<\/style>/s', $html, $matches);
if(isset($matches[1])) {
    @mkdir('front/public/assets/css', 0777, true);
    file_put_contents('front/public/assets/css/app.css', $matches[1]);
    echo 'CSS extracted';
} else {
    echo 'CSS not found';
}
