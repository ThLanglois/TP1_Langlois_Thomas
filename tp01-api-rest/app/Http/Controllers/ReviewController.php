<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\QueryException;

class ReviewController extends Controller
{
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
