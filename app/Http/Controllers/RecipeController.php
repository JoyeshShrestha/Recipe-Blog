<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;
use App\Models\Recipe;
class RecipeController extends Controller
{
 
   /**
 * @OA\Post(
 *     path="/api/recipe/add",
  *     tags={"Recipe Management"},
 *     summary="Add a new Recipe",
 *     description="This is the Add Recipe API",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"recipe_name", "description", "subtitle","image"},
 *             @OA\Property(property="recipe_name", type="string", example="Jhol Momo Recipe"),
 *             @OA\Property(property="description", type="string", example="Ingredients:

*For Momos:

*    2 cups all-purpose flour
*    1/2 cup water (for kneading)
*    1 cup minced chicken/vegetable filling
*    1/4 tsp salt
*    1 tbsp oil

*For Jhol (Soup):

*    3 medium tomatoes, boiled and peeled
*    2 dried red chilies (adjust spice level)
*    1 tsp sesame seeds, roasted
*    1 tsp cumin seeds, roasted
*    2 garlic cloves
*    1-inch piece ginger
*    1 tbsp mustard oil
*    2 cups warm water
*    Salt to taste

*Instructions:

*1. Momos:

*    Knead flour, water, and salt into a soft dough; rest for 20 minutes.
*    Roll dough thin, cut small circles, fill with your choice of filling, and shape into momos.
*    Steam for 10–12 minutes until cooked.
*
*2. Jhol:
*
*    Blend tomatoes, chilies, sesame seeds, cumin, garlic, and ginger into a smooth paste.
*    Heat mustard oil, add the paste, and sauté for 2 minutes.
*    Add warm water and salt, simmer for 5 minutes.
*
*3. Assemble:
*
*    Place momos in a bowl and pour jhol over them.
*    Garnish with chopped cilantro and serve hot."),
 *             @OA\Property(property="subtitle", type="string", example="Jhol momo for excellent weather condition"),
 *             @OA\Property(property="image", type="string", example="https://enq69sdfnv7.exactdn.com/wp-content/uploads/2024/02/JHOL-MOMO-SOUP-RECIPE-3-1.png?strip=all&lossy=1&ssl=1")
 *          
 *             )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recipe added successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Recipe Added successfully"),
 *             @OA\Property(property="recipe_id", type="integer", example=1)
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

 public function addRecipe(Request $request)
 {
     $incomingFields = $request->validate([
         'recipe_name' => ['required', Rule::unique('recipe', 'recipe_name')],
         'description' => ['required', 'string', 'max:10000'],
         'subtitle' => ['required', 'min:6', 'max:50'],
         'image' => ['required', 'url']
     ]);

     $recipe = new Recipe();
     $recipe->recipe_name = $request->recipe_name;
     $recipe->description = $request->description;
     $recipe->subtitle = $request->subtitle;
     $recipe->image = $request->image;
     $recipe->save();

     return response()->json([
         'status' => true,
         'message' => 'New Recipe Registered successfully',
         'data' => $recipe,
     ], 200);
 }



/**
 * @OA\Get(
 *     path="/api/recipe",
 *     tags={"Recipe Management"},
 *     summary="Get all Recipe",
 *     description="Fetch all recipe details.",
 *     @OA\Response(
 *         response=200,
 *         description="All recipes fetched successfully",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="recipe_name", type="string", example="Chocolate Cake"),
 *                 @OA\Property(property="description", type="string", example="A delicious chocolate cake recipe."),
 *                 @OA\Property(property="subtitle", type="string", example="Easy to prepare and tasty."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No recipes found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No recipes found")
 *         )
 *     )
 * )
 */
public function getAllRecipe()
{
    // Fetch all recipes
    $recipes = Recipe::all(); // Fetch all records

    // If no recipes are found, return a 404 response
    if ($recipes->isEmpty()) {
        return response()->json([
            'error' => 'No recipes found'
        ], 404);
    }

    // Return a success response with the recipes' details
    return response()->json([
        'recipes' => $recipes,
    ], 200);
}


 /**
 * @OA\Get(
 *     path="/api/recipe/{id}",
 *     tags={"Recipe Management"},
 *     summary="Get a recipe by ID",
 *     description="Fetch a recipe's details by its unique ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the recipe to fetch",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recipe fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="recipe", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="recipe_name", type="string", example="Chocolate Cake"),
 *                 @OA\Property(property="description", type="string", example="A delicious chocolate cake recipe."),
 *                 @OA\Property(property="subtitle", type="string", example="Easy to prepare and tasty."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recipe not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Recipe not found")
 *         )
 *     )
 * )
 */
public function getRecipe($id)
{
    // Find the recipe by ID
    $recipe = Recipe::find($id);

    // If the recipe is not found, return a 404 response
    if (!$recipe) {
        return response()->json([
            'error' => 'Recipe not found'
        ], 404);
    }

    // Return the recipe details
    return response()->json([
        'recipe' => $recipe,
    ], 200);
}


   /**
 * @OA\Delete(
 *     path="/api/recipe/delete/{id}",
 *     tags={"Recipe Management"},
 *     summary="Delete a Recipe",
 *     description="Delete a recipe by their ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the Recipe to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recipe deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Recipe deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recipe not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Recipe not found")
 *         )
 *     )
 * )
 */
public function deleteRecipe($id)
{
    // Find the user by ID
    $recipe =Recipe::find($id);

    if (!$recipe) {
        // Return a 404 response if user is not found
        return response()->json([
            'error' => 'Recipe not found'
        ], 404);
    }

    // Delete the user
    $recipe->delete();

    // Return a success response
    return response()->json([
        'message' => 'Recipe deleted successfully'
    ], 200);
}


/**
 * @OA\Put(
 *     path="/api/recipe/update/{id}",
 *     tags={"Recipe Management"},
 *     summary="Update a specific recipe",
 *     description="Update a specific recipe's details by its unique ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the recipe to update",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="recipe_name", type="string", example="Chocolate Cake"),
 *             @OA\Property(property="description", type="string", example="A delicious chocolate cake recipe."),
 *             @OA\Property(property="subtitle", type="string", example="Easy to prepare and tasty."),
 *             @OA\Property(property="image", type="string", example="https://example.com/image.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recipe updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="recipe_name", type="string", example="Updated Chocolate Cake"),
 *             @OA\Property(property="description", type="string", example="A new description for the updated recipe."),
 *             @OA\Property(property="subtitle", type="string", example="Even tastier now!"),
 *             @OA\Property(property="image", type="string", example="https://example.com/new-image.jpg"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-23T07:46:58.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recipe not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No recipe with that ID found")
 *         )
 *     )
 * )
 */
public function updateRecipe(Request $request, $id)
{
    // Fetch the user by ID
    $recipe = Recipe::find($id);

    // If the user is not found, return a 404 response
    if (!$recipe) {
        return response()->json([
            'error' => 'No user with that ID found'
        ], 404);
    }

    // Validate the incoming request
    $validatedData = $request->validate([
        'recipe_name' => ['required', Rule::unique('recipe', 'recipe_name')->ignore($recipe->id)],
        'description' => ['required', 'string', 'max:10000'],
        'subtitle' => ['required', 'min:6', 'max:50'],
        'image' => ['required', 'url']
    ]);

    // Update the user details
    $recipe->update([
        'recipe_name' => $validatedData['recipe_name'],
        'description' => $validatedData['description'],
        'subtitle' => $validatedData['subtitle'],
        'image' => $validatedData['image'],
    ]);

    // Return a success response with the updated user's details
    return response()->json($recipe, 200);
}

/**
 * @OA\Get(
 *     path="/api/latestrecipe",
 *     tags={"Recipe Management"},
 *     summary="Get the latest recipe",
 *     description="Fetch the most recent recipe added to the system.",
 *     @OA\Response(
 *         response=200,
 *         description="Latest recipe fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="recipe", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="recipe_name", type="string", example="Chocolate Cake"),
 *                 @OA\Property(property="description", type="string", example="A delicious chocolate cake recipe."),
 *                 @OA\Property(property="subtitle", type="string", example="Easy to prepare and tasty."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-22T07:46:58.000000Z")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No recipes found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No recipes found")
 *         )
 *     )
 * )
 */
public function getLatestRecipe()
{
    // Find the latest recipe by ordering by created_at in descending order
    $latestRecipe = Recipe::orderBy('created_at', 'desc')->first();

    // If no recipe is found, return a 404 response
    if (!$latestRecipe) {
        return response()->json([
            'error' => 'No recipes found'
        ], 404);
    }

    // Return the latest recipe details
    return response()->json([
        'recipe' => $latestRecipe,
    ], 200);
}


}




 
