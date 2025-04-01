<?php

namespace App\Http\Controllers;

use App\Services\RestaurantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        
        $filters = [
            'name' => $request->input('name'),
            'day' => $request->input('day'),
            'time' => $request->input('time')
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'opening_hours' => 'required|array',
            'opening_hours.*.days' => 'required|string',
            'opening_hours.*.hours' => 'required|string'
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'opening_hours' => 'required|array',
            'opening_hours.*.days' => 'required|string',
            'opening_hours.*.hours' => 'required|string'
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
