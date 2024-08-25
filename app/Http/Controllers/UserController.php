<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User Login",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="superadmin", type="string", required=true),
     *             @OA\Property(property="password123", type="string", required=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object",  ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),

     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $validatedData['username'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user, // Opsional, untuk mengirim data user ke frontend
        ]);
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get a list of users",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),

     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all()->except(1);
        return response()->json($users);
    }

    /**
 * @OA\Post(
 *     path="/storeUsers",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="johndoe"),
 *             @OA\Property(property="fullname", type="string", example="John Doe"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="role", type="string", enum={"owner", "manager"}, example="manager"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,

 *         description="Unprocessable Entity",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="errors", type="object")

 *         )
 *     )
 * )
 */
    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'fullname' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:owner,manager',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'fullname' => $validatedData['fullname'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'] ?? null,
        ]);

        return response()->json(['user' => $user], 201);
    }

    /**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user details by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
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

 *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),

 *         )
 *     )
 * )
 */
    public function detailUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update user information",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username",type="string", nullable=true),
     *             @OA\Property(property="fullname", type="string", nullable=true),
     *             @OA\Property(property="password", type="string", nullable=true),
     *             @OA\Property(property="role", type="string", enum={"owner", "manager"}, nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usernot found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         )
     *     )
     * )
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
            'fullname' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
            'role' => 'nullable|in:owner,manager',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['user' => $user], 200);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         )
     *     )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
