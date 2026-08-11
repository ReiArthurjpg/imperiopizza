<?php

namespace App\Controllers;

use App\Core\Controller;

class PdvController extends Controller
{
    public function index()
    {
        $this->render('pdv', [
            'title' => 'PDV & Caixa'
        ]);
    }
}
