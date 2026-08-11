<?php namespace App\Controllers; use App\Core\Controller; class CozinhaController extends Controller { public function index() { $this->render("cozinha", ["title" => "Cozinha"]); } }
