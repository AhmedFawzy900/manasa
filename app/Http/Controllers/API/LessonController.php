<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::with('course')->get(); // Include course details
        return response()->json(['lessons' => $lessons], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pdf_link' => 'nullable|url',
            'video_link' => 'nullable|url',
        ]);

        $lesson = Lesson::create($validatedData);

        return response()->json(['lesson' => $lesson], 201);
    }

    public function show($id)
    {
        $lesson = Lesson::with('course')->findOrFail($id);
        return response()->json(['lesson' => $lesson], 200);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pdf_link' => 'nullable|url',
            'video_link' => 'nullable|url',
        ]);

        $lesson->update($validatedData);

        return response()->json(['lesson' => $lesson], 200);
    }

    public function delete($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully'], 200);
    }
}
