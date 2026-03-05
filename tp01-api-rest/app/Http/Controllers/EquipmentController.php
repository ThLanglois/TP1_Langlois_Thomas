<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Equipment;
use App\Http\Resources\EquipmentResource;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        try
        {
            return EquipmentResource::collection(Equipment::all())->response()->setStatusCode(self::OK);
        }
        catch(Exception $ex)
        {
            abort(self::SERVER_ERROR, 'Server error');
        }
    }

    public function show(string $id)
    {
        try
        {
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(self::OK);
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

    public function popularity(string $id)
    {
        try
        {
            $equipment = Equipment::findOrFail($id);

            $rentalCount = $equipment->rentals()->count(); // https://laravel.com/docs/12.x/queries#aggregates

            $avgRating = Review::whereIn( // https://laravel.com/docs/12.x/queries#where-clauses
                    'rental_id',
                    $equipment->rentals()->pluck('id') // https://laravel.com/docs/12.x/queries#retrieving-a-list-of-column-values
                )->avg('rating'); // https://laravel.com/docs/12.x/queries#aggregates

            $avgRating = $avgRating ?? 0; // si null, c'est 0

            $popularity = ($rentalCount * 0.6) + ($avgRating * 0.4);

            return response()->json([ // https://laravel.com/docs/12.x/responses#json-responses
                'popularity' => $popularity
            ])->setStatusCode(self::OK);
        }
        catch (QueryException $ex)
        {
            abort(self::NOT_FOUND, 'Invalid Id');
        }
        catch (Exception $ex)
        {
            abort(self::SERVER_ERROR, 'server error');
        }
    }   

    public function averageRentalPrice(Request $request, string $id)
    {
        try
        {
            Equipment::findOrFail($id);

            $minDate = $request->query('minDate'); // https://laravel.com/docs/12.x/requests#retrieving-input-from-the-query-string
            $maxDate = $request->query('maxDate');
            
            // validation format date: AI juste pour ces deux if en-dessous (comment valider un format de date en PHP?). Il m'a aussi donné ce code d'erreur (422)
            if ($minDate && strtotime($minDate) === false) {
                abort(self::VALIDATION_ERROR, 'Invalid minDate format');
            }

            if ($maxDate && strtotime($maxDate) === false) {
                abort(self::VALIDATION_ERROR, 'Invalid maxDate format');
            }

            // validation minDate <= maxDate
            if ($minDate && $maxDate && $minDate > $maxDate) {
                abort(self::VALIDATION_ERROR, 'minDate must be <= maxDate');
            }

            $query = DB::table('rentals')->where('equipment_id', $id); // https://laravel.com/docs/12.x/queries#where-clauses

            if ($minDate) {
                $query->where('start_date', '>=', $minDate); // https://laravel.com/docs/12.x/queries#where-clauses
            }

            if ($maxDate) {
                $query->where('start_date', '<=', $maxDate);
            }

            $avg = $query->avg('total_price'); // https://laravel.com/docs/12.x/queries#aggregates

            return response()->json([ // https://laravel.com/docs/12.x/responses#json-responses
                'avg_total_price' => $avg
            ])->setStatusCode(self::OK);
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
