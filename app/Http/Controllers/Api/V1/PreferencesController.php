<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Preferences\SetPreferenceRequest;
use App\Http\Resources\Api\DropDown\PreferenceResource;
use App\Http\Resources\Api\Preference\GetPreferenceResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    use ResponseTrait;

    /**
     * @OA\Post(
     *     path="/api/v1/preferences/set",
     *     summary="Set Preferences",
     *     description="Set Preferences",
     *     operationId="setPreferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/SetPreferenceRequest"
     *        )    
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Preferences set successfully")
     *         )
     *     )
     * )
     */
    public function setPreferences(SetPreferenceRequest $request)
    {
        $user = $request->user();

        $user->categoryPreferences()->detach();
        $user->sourcePreferences()->detach();
        $user->authorPreferences()->detach();
        // more preference types can be added here


        $models = [
            'source' => Source::class,
            'category' => Category::class,
            'author' => Author::class
        ];

        foreach ($request->preference_name as $type) {
            if (!isset($models[$type]) || !isset($request->value[$type])) continue;

            foreach ($request->value[$type] as $id) {
                $user->{$type . 'Preferences'}()->attach($id);
            }
        }

        return $this->successResponse('Preferences set successfully');
    }
     /**
     * @OA\Get(
     *     path="/api/v1/preferences/get",
     *     summary="Get Preferences",
     *     description="Get Preferences",
     *     operationId="getPreferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Preferences",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 ref="#/components/schemas/GetPreferenceResource"
     *             )
     *         )
     *     )
     * )
     */
    public function getPreferences(){
        $user = request()->user();
        return $this->successResponse('Preferences fetched successfully',GetPreferenceResource::make($user));
    }
}
