<?php namespace App\Controllers; use App\Core\Controller; class ComandaController extends Controller { public function index() { $this->render("comandas", ["title" => "Comandas"]); } }
