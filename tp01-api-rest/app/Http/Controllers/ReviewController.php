<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Delete(
        path: "/api/reviews/{id}",
        summary: "Supprimer une critique",
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID de la critique",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Critique supprimée"),
            new OA\Response(response: 404, description: "Critique non trouvée")
        ]
    )]
    public function destroy(string $id)
    {
        try
        {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->noContent()->setStatusCode(self::NO_CONTENT);
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
