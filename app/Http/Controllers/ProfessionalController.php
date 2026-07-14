<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{

    public function getByCategory(Request $request)
    {
        $categoryId = $request->category_id ?? $request->query('category_id');

        if (!$categoryId) {
            return response()->json(['message' => 'يجب إرسال category_id'], 400);
        }

        $professionals = Professional::where('category_id', $categoryId)->with('user')->get();

        $data = $professionals->map(function ($professional) {
            // حساب متوسط التقييم
            $average = \App\Models\Review::where('professional_id', $professional->professional_id)->avg('rating');
            if ($average === null) {
                $average = 5; // الديفولت إذا ما عندو تقييمات
            }

            return [
                'professional_id'   => $professional->professional_id,
                'user_id'           => $professional->user_id,
                'name'             => $professional->user?->name,
                'average_rating'   => round($average, 2),
                'category_id'       => $professional->category_id,
                'bio'               => $professional->bio,
                'experience_years'  => $professional->experience_years,
                'tool_image'        => $professional->tool_image,
                'governorate_id'    => $professional->governorate_id,
                'professional_status' => $professional->professional_status,
                'created_at'        => $professional->created_at,
                'updated_at'        => $professional->updated_at,

            ];
        });

        return response()->json($data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Professional $professional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professional $professional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professional $professional)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professional $professional)
    {
        //
    }
}
