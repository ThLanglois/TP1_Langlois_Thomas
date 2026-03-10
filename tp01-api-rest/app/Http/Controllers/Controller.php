<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API TP1 - Location d'équipements",
    version: "1.0.0",
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    // IA: Comment avoir des constantes dans le fichier Controller.php pour que tous les enfants puissent y accéder?
    protected const OK = 200;
    protected const CREATED = 201;
    protected const NO_CONTENT = 204;
    protected const NOT_FOUND = 404;
    protected const SERVER_ERROR = 500;
    protected const VALIDATION_ERROR = 422;
}
