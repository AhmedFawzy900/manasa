<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('subcategory')->get(); // Include subcategory details
        return response()->json(['courses' => $courses], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $course = Course::create([
            'subcategory_id' => $request->subcategory_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image->store('courses_images'),
        ]);

        return response()->json(['course' => $course], 201);
    }

    public function show($id)
    {
        $course = Course::with('subcategory')->findOrFail($id);
        return response()->json(['course' => $course], 200);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validatedData = $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $course->update($validatedData);

        return response()->json(['course' => $course], 200);
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
