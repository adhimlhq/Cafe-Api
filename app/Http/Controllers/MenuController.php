<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
 * @OA\Get(
 *     path="/menu",
 *     summary="Get a list of menus",
 *     tags={"Menus"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Menu")),

 *         )
 *     )
 * )
 */
    public function index()
    {
        $cafes = Menu::all();
        return response()->json($cafes);
    }

    public function store(Request $request, Cafe $cafe)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|between:0,999999.99',
            'is_recommendation' => 'required|boolean',
        ]);

        $userId = Auth::id();

        $cafe = Cafe::where('manager_id', $userId)->first();

        if (!$cafe) {
            return response()->json(['error' => 'Unauthorized to add menu to this cafe.'], 403);
        }

        $menu = $cafe->menus()->create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'is_recommendation' => $validatedData['is_recommendation'],
        ]);

        return response()->json($menu, 201);
    }

    /**
     * @OA\Post(
     *     path="/storeCafes",
     *     summary="Create a new menu for a cafe",
     *     tags={"Menus"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", required=true),
     *             @OA\Property(property="price", type="number", format="float", required=true),
     *             @OA\Property(property="is_recommendation", type="boolean", required=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Menu created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Menu"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to add menu to this cafe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *         )
     *     )
     * )
     */
    public function indexMenu()
    {
        $userId = Auth::id();

        $cafe = Cafe::where('manager_id', $userId)->first();

        if (!$cafe) {
            return response()->json(['error' => 'Cafe not found for the manager.'], 404);
        }

        $menus = $cafe->menus;

        return response()->json($menus);
    }

    /**
     * @OA\Get(
     *     path="/cafes/{cafeId}/menus/{menuId}/detail",
     *     summary="Get details of a specific menu",
     *     tags={"Menus"},
     *     @OA\Parameter(
     *         name="cafeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="menuId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Menu"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to view this menu",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *         )
     *     )
     * )
     */
    public function detailMenu(Cafe $cafe, Menu $menu)
    {
        $userId = Auth::id();

        $cafe = Cafe::where('manager_id', $userId)->first();

        if (!$cafe || $menu->cafe_id != $cafe->id) {
            return response()->json(['error' => 'Unauthorized to view this menu.'], 403);
        }

        return response()->json($menu);
    }

    /**
     * @OA\Put(
     *     path="/cafes/{cafeId}/menus/{menuId}/update",
     *     summary="Update a menu",
     *     tags={"Menus"},
     *     @OA\Parameter(
     *         name="cafeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="menuId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", nullable=true),
     *             @OA\Property(property="price", type="number", format="float", nullable=true),
     *             @OA\Property(property="is_recommendation", type="boolean", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Menu updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Menu"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to edit this menu",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *         )
     *     )
     * )
     */
    public function update(Request $request, Cafe $cafe, Menu $menu)
    {
        $userId = Auth::id();

        $cafe = Cafe::where('manager_id', $userId)->first();

        if ($menu->cafe_id != $cafe->id) {
            return response()->json(['error' => 'Unauthorized to edit this menu.'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|between:0,999999.99',
            'is_recommendation' => 'nullable|boolean',
        ]);

        $menu->update($validatedData);

        return response()->json($menu);
    }

    /**
     * @OA\Delete(
     *     path="/cafes/{cafeId}/menus/{menuId}/delete",
     *     summary="Delete a menu",
     *     tags={"Menus"},
     *     @OA\Parameter(
     *         name="cafeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="menuId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Menu deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to delete this menu",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *         )
     *     )
     * )
     */
    public function destroy(Cafe $cafe, Menu $menu)
    {
        $userId = Auth::id();

        if ($cafe->manager_id !== $userId) {
            return response()->json(['error' => 'Unauthorized to delete this menu.'], 403);
        }

        if ($menu->cafe_id !== $cafe->id) {
            return response()->json(['error' => 'Unauthorized to delete this menu.'], 403);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully'], 200);
    }
}
