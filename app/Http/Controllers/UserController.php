<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


/**
 * @OA\Info(
 *      title="Laravel ourfirstapp",
 *      version="1.0.0"    
 * )
 */

class UserController extends Controller
{

   /**
 * @OA\Post(
 *     path="/api/register",
  *     tags={"User Management"},
 *     summary="Register User",
 *     description="This is the User Register API",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name", "email", "password","role_id"},
 *             @OA\Property(property="name", type="string", example="admin"),
 *             @OA\Property(property="email", type="string", example="admin@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="admin123"),
 *             @OA\Property(property="role_id", type="int", example=1)
 *          
 *             )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User registered successfully"),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Validation failed")
 *         )
 *     )
 * )
 */

    public function register(Request $request){
        $incomingFields = $request->validate([
            'name'=>['required','min:3','max:20', Rule::unique('users','name')],
            'email'=>['required','email', Rule::unique('users','email')],
            'password'=>['required','min:6','max:20'],
            'role_id'=>['required','exists:roles,id']

        ]);

   
        $incomingFields['password']=bcrypt($incomingFields['password']);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Hash password
        
        $user->role_id = $request->role_id; // Assign role_id from the request
        $user->save();
       

        // auth()->login($user);
       
        
        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 200);
    }



   /**
 * @OA\Delete(
 *     path="/api/users/delete/{id}",
 *     tags={"User Management"},
 *     summary="Delete a user",
 *     description="Delete a user by their ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not found")
 *         )
 *     )
 * )
 */
public function deleteUser($id)
{
    // Find the user by ID
    $user = User::find($id);

    if (!$user) {
        // Return a 404 response if user is not found
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }

    // Delete the user
    $user->delete();

    // Return a success response
    return response()->json([
        'message' => 'User deleted successfully'
    ], 200);
}


  /**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     tags={"User Management"},
 *     summary="Get a user by ID",
 *     description="Fetch a user's details by their unique ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user to fetch",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Superadmin"),
 *                 @OA\Property(property="email", type="string", example="superadmin@gmail.com"),
 *                 @OA\Property(property="created_at", type="string", example="2024-11-22T07:46:58.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", example="2024-11-22T07:46:58.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not found")
 *         )
 *     )
 * )
 */
public function getUser($id)
{
    // Find the user by ID
    $user = User::find($id);
    if (!$user) {
        // Return a 404 response if user is not found
        return response()->json([
            'error' => 'User not found'
        ], 404);
    }
    $role = $user->role;
    // Return a success response with the user details
    return response()->json([
        'user' => $user,
      

    ], 200);
}


/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"User Management"},
 *     summary="Get all users",
 *     description="Fetch all users' details.",
 *     @OA\Response(
 *         response=200,
 *         description="All users fetched successfully",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Superadmin"),
 *                 @OA\Property(property="email", type="string", example="superadmin@gmail.com"),
 *                 @OA\Property(property="created_at", type="string", example="2024-11-22T07:46:58.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", example="2024-11-22T07:46:58.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No users found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No users found")
 *         )
 *     )
 * )
 */

public function getAllUser()
{
    // Fetch all users
    $users = User::with('role')->get();
    

    // If no users are found, return a 404 response
    if ($users->isEmpty()) {
        return response()->json([
            'error' => 'No users found'
        ], 404);
    }

    // Return a success response with the users' details
    return response()->json([
        'users' => $users,
    ], 200);
}



}
