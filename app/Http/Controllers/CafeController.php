<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\User;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    /**
 * @OA\Get(
 *     path="/Icafes",
 *     summary="Get a list of cafes",
 *     tags={"Cafes"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Cafe")),

 *         )
 *     )
 * )
 */
    public function index()
    {
        $cafes = Cafe::all();
        return response()->json($cafes);
    }

    /**
     * @OA\Post(
     *     path="/storeCafes",
     *     summary="Create a new cafe",
     *     tags={"Cafes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", required=true),
     *             @OA\Property(property="address", type="string", required=true),
     *             @OA\Property(property="phone_number", type="string", format="phone", required=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cafe created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Cafe"),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^\+62[0-9]{9,15}$/',
        ]);

        $cafe = Cafe::create(array_merge($validatedData, ['manager_id' => null]));

        return response()->json($cafe, 201);
    }

    /**
     * @OA\Get(
     *     path="/detailCafes/{cafeId}",
     *     summary="Get details of a specific cafe",
     *     tags={"Cafes"},
     *     @OA\Parameter(
     *         name="cafeId",
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
     *             @OA\Property(property="cafe", type="object", ref="#/components/schemas/Cafe"),
     *             @OA\Property(property="managers", type="array", @OA\Items(ref="#/components/schemas/User")),
     *         )
     *     )
     * )
     */
    public function show(Cafe $cafe)
    {
        $managers = User::where('role', 'manager')->get(['id', 'fullname']);

        $response = [
            'cafe' => $cafe,
            'managers' => $managers,
        ];

        return response()->json($response);
    }

    /**
     * @OA\Put(
     *     path="/updateCafes/{cafeId}",
     *     summary="Update a cafe",
     *     tags={"Cafes"},
     *     @OA\Parameter(
     *         name="cafeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string",  nullable=true),
     *             @OA\Property(property="address", type="string", nullable=true),
     *             @OA\Property(property="phone_number", type="string", format="phone", nullable=true),
     *             @OA\Property(property="manager_id", type="integer", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cafe updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Cafe"),
     *         )
     *     )
     * )
     */
    public function update(Request $request, Cafe $cafe)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|regex:/^\+62[0-9]{9,15}$/',
            'manager_id' => 'nullable|exists:users,id', // Validasi manager_id
        ]);

        if (isset($validatedData['manager_id'])) {
            $user = User::findOrFail($validatedData['manager_id']);
            $user->role = 'manager';
            $user->save();

            $cafe->manager_id = $user->id;
        }

        $cafe->update($validatedData);

        return response()->json($cafe);
    }

    /**
     * @OA\Delete(
     *     path="/deleteCafes/{cafeId}",
     *     summary="Delete a cafe",
     *     tags={"Cafes"},
     *     @OA\Parameter(
     *         name="cafeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cafe deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         )
     *     )
     * )
     */
    public function destroy(Cafe $cafe)
    {
        $cafe->delete();

        return response()->json(['message' => 'Cafe deleted successfully']);
    }
}
