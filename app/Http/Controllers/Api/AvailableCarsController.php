<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @group Доступные автомобили
 *
 * API для получения списка доступных автомобилей для пользователя по ID
 */
class AvailableCarsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/available-cars",
     *     operationId="getAvailableCarsByUserId",
     *     tags={"Available Cars"},
     *     summary="Получить доступные автомобили по ID пользователя",
     *     description="Возвращает список автомобилей, доступных пользователю на выбранное время",
     *     security={},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="start_time",
     *         in="query",
     *         description="Время начала брони",
     *         required=true,
     *         @OA\Schema(type="string", format="date-time", example="2025-11-10T09:00:00")
     *     ),
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         description="Время окончания брони",
     *         required=true,
     *         @OA\Schema(type="string", format="date-time", example="2025-11-10T18:00:00")
     *     ),
     *     @OA\Parameter(
     *         name="model",
     *         in="query",
     *         description="Фильтр по модели автомобиля",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Фильтр по категории комфорта",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список доступных автомобилей",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="car_model", type="object",
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="comfort_category", type="object",
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 ),
     *                 @OA\Property(property="driver", type="object",
     *                     @OA\Property(property="name", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $userId = $request->query('id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $start = $request->query('start_time');
        $end = $request->query('end_time');
        $model = $request->query('model');
        $category = $request->query('category');

        $allowedCategories = $user->position
            ? $user->position->comfortCategories->pluck('id')->toArray()
            : [];

        $query = Car::with(['carModel.comfortCategory', 'driver'])
            ->whereHas('carModel', function ($q) use ($allowedCategories, $model, $category) {
                if ($allowedCategories) {
                    $q->whereIn('comfort_category_id', $allowedCategories);
                }
                if ($model) {
                    $q->where('name', 'like', "%{$model}%");
                }
                if ($category) {
                    $q->whereHas('comfortCategory', function ($qq) use ($category) {
                        $qq->where('name', 'like', "%{$category}%");
                    });
                }
            })
            ->whereDoesntHave('bookings', function ($q) use ($start, $end) {
                if ($start && $end) {
                    $q->where(function ($qq) use ($start, $end) {
                        $qq->whereBetween('start_time', [$start, $end])
                            ->orWhereBetween('end_time', [$start, $end])
                            ->orWhere(function ($sub) use ($start, $end) {
                                $sub->where('start_time', '<=', $start)
                                    ->where('end_time', '>=', $end);
                            });
                    });
                }
            });

        return response()->json($query->get());
    }
}
