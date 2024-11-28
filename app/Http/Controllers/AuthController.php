<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Authentication"},
 *     summary="User Login",
 *     description="Logs the user into the system and returns a token for further API calls.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="superadmin@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="superadmin123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User logged in successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User logged in successfully"),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="token", type="string", example="your_generated_token_here")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Invalid credentials")
 *         )
 *     )
 * )
 */
public function login(Request $request)
{
    // Validate incoming request
    $incomingFields = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);

    // Attempt to authenticate the user
    if (auth()->attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])) {
        // Generate a token (use Passport or a similar package)
        $user = auth()->user();
        $user_info = $user;
        $token = $user->createToken($user['id'])->accessToken;

        // Return response with success message, user_id, and token
        return response()->json([
            'message' => 'User logged in successfully',
            'user_id' => $user->id,
            'user_info'=> $user,
            'token' => $token,  // Provide the token to the client
        ], 200);
    }

    // If authentication fails, return an unauthorized error
    return response()->json([
        'error' => 'Invalid credentials'
    ], 401);
}


/**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Authentication"},
 *     summary="User Log Out",
 *     description="Logs the user out and invalidates the token.",
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */
public function logout(Request $request)
{
    // Logout from the session
    Auth::logout();

    // Invalidate the session and regenerate CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logout successful'], 200);
}


}
