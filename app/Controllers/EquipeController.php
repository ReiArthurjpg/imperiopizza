<?php namespace App\Controllers; use App\Core\Controller; class EquipeController extends Controller { public function index() { $this->render("equipe", ["title" => "Equipe"]); } }
