<?php namespace App\Controllers; use App\Core\Controller; class RelatoriosController extends Controller { public function index() { $this->render("relatorios", ["title" => "Relatórios"]); } }
