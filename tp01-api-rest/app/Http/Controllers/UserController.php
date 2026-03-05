<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
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
