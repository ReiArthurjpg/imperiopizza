<?php namespace App\Controllers; use App\Core\Controller; class EstoqueController extends Controller { public function index() { $this->render("estoque", ["title" => "Estoque"]); } }
