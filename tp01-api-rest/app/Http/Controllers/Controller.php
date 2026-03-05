<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // IA: Comment avoir des constantes dans le fichier Controller.php pour que tous les enfants puissent y accéder?
    protected const OK = 200;
    protected const CREATED = 201;
    protected const NO_CONTENT = 204;
    protected const NOT_FOUND = 404;
    protected const SERVER_ERROR = 500;
    protected const VALIDATION_ERROR = 422;
}
