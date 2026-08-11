<?php namespace App\Controllers; use App\Core\Controller; class ConfigController extends Controller { public function index() { $this->render("config", ["title" => "Configurações"]); } }
