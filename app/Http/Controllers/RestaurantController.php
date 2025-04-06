<?php

namespace App\Http\Controllers;

use App\Services\RestaurantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort', 'updated_at');
        $order = $request->input('order', 'desc');
        $filters = [
            'name' => $request->input('name'),
            'day' => $request->input('day'),
            'time' => $request->input('time'),
            'sort' => $sortBy,
            'order' => $order,
        ];

        $restaurants = $this->restaurantService->getAllRestaurants($filters, $perPage);

        return response()->json([
            'success' => true,
            'data' => $restaurants,
            'message' => 'Restaurants retrieved successfully',
            'status' => 200
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlllist()
    {
        $restaurants = $this->restaurantService->getAllRestaurantsWithoutPagination();

        return response()->json([
            'success' => true,
            'data' => $restaurants,
            'message' => 'Restaurants retrieved successfully',
            'status' => 200
        ]);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(string $id)
    {
        $restaurant = $this->restaurantService->getRestaurantById($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Restaurant not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $restaurant,
            'message' => 'Restaurant retrieved successfully',
            'status' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createList(Request $request)
    {
        $user = Auth::user();

        if ($user->isClient()) {
            return response()->json([
                'success' => false,
                'message' => 'Clients are not allowed to create restaurants.',
                'status' => 403
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:restaurants',
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.open_time' => 'required|string',
            'schedules.*.close_time' => 'required|string',
        ], [
            'schedules.required' => 'Restaurant schedules must be filled min 1',
            'schedules.min' => 'Restaurant schedules must be filled min 1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Validation failed',
                'status' => 422
            ], 422);
        }

        $restaurant = $this->restaurantService->createRestaurant($request->all());

        return response()->json([
            'success' => true,
            'data' => $restaurant,
            'message' => 'Restaurant created successfully',
            'status' => 201
        ], 201);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->isClient()) {
            return response()->json([
                'success' => false,
                'message' => 'Clients are not allowed to update restaurants.',
                'status' => 403
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required_with:schedules|string',
            'schedules.*.open_time' => 'required_with:schedules|string',
            'schedules.*.close_time' => 'required_with:schedules|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Validation failed',
                'status' => 422
            ], 422);
        }

        $restaurant = $this->restaurantService->updateRestaurant($id, $request->all());

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Restaurant not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $restaurant,
            'message' => 'Restaurant updated successfully',
            'status' => 200
        ]);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $deleted = $this->restaurantService->deleteRestaurant($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Restaurant not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Restaurant deleted successfully',
            'status' => 200
        ]);
    }
}
