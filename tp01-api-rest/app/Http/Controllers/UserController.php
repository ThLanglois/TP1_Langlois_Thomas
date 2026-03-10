<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Post(
        path: "/api/users",
        summary: "Créer un utilisateur",
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name", "last_name", "email", "phone"],
                properties: [
                    new OA\Property(property: "first_name", type: "string", example: "Thomas"),
                    new OA\Property(property: "last_name", type: "string", example: "Langlois"),
                    new OA\Property(property: "email", type: "string", example: "thomas@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "4181234567")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Utilisateur créé"),
            new OA\Response(response: 422, description: "Données invalides")
        ]
    )]
    public function store(StoreUserRequest $request)
    {
        try
        {
            $user = User::create($request->validated());
            return (new UserResource($user))->response()->setStatusCode(self::CREATED); // IA: Comment utiliser les constantes dans le fichier parent Controller.php?
        }
        catch(Exception $ex)
        {
            abort(self::SERVER_ERROR, 'Server error'); 
        }
    }

    #[OA\Put(
        path: "/api/users/{id}",
        summary: "Mettre à jour un utilisateur",
        tags: ["Users"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID de l'utilisateur",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name", "last_name", "email", "phone"],
                properties: [
                    new OA\Property(property: "first_name", type: "string", example: "Thomas"),
                    new OA\Property(property: "last_name", type: "string", example: "Langlois"),
                    new OA\Property(property: "email", type: "string", example: "thomas@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "4181234567")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Utilisateur mis à jour"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé"),
            new OA\Response(response: 422, description: "Données invalides")
        ]
    )]
    public function update(UpdateUserRequest $request, string $id)
    {
        try
        {
            $user = User::findOrFail($id);
            $user->update($request->validated());
            return (new UserResource($user))->response()->setStatusCode(self::OK);
        }
        catch(QueryException $ex)
        {
            abort(self::NOT_FOUND, 'Invalid Id');
        }
        catch(Exception $ex)
        {
            abort(self::SERVER_ERROR, 'server error');
        }
    }
}
