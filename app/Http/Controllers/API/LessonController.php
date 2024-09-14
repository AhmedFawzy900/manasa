<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'video' => 'nullable|mimes:mp4,mkv,flv,avi|max:100000', // Video validation
                'pdf' => 'nullable|mimes:pdf|max:50000', // PDF validation
            ]);
    
            // Handle file uploads
            $videoPath = $request->hasFile('video') ? $request->video->store('courses_videos') : null;
            $pdfPath = $request->hasFile('pdf') ? $request->pdf->store('courses_pdfs') : null;
    
            // Create a new course with the validated data
            $lesson = Lesson::create([
                'course_id' => $validatedData['course_id'],
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'video_url' => $videoPath,
                'pdf_url' => $pdfPath,
            ]);
    
            // Return a successful response with the created course
            return response()->json(['course' => $lesson], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => 'Validation failed', 'message' => $e->getMessage()], 422);
    
        } catch (\Exception $e) {
            // Handle any other types of exceptions
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function show($id)
    {
        $lesson = Lesson::with('course')->findOrFail($id);
        return response()->json(['lesson' => $lesson], 200);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
    
        // Validate the incoming data
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:50000', // Validate if new PDF is uploaded
            'video' => 'nullable|file|mimes:mp4,mkv,flv,avi|max:100000', // Validate if new video is uploaded
        ]);
    
        // Check if a new PDF file is uploaded
        if ($request->hasFile('pdf')) {
            // Delete the old PDF if it exists
            if ($lesson->pdf_url && \Storage::exists($lesson->pdf_url)) {
                \Storage::delete($lesson->pdf_url);
            }
            // Store the new PDF and update the path
            $pdfPath = $request->pdf->store('courses_pdfs');
            $lesson->pdf = $pdfPath;
        }
    
        // Check if a new video file is uploaded
        if ($request->hasFile('video')) {
            // Delete the old video if it exists
            if ($lesson->video && \Storage::exists($lesson->video)) {
                \Storage::delete($lesson->video);
            }
            // Store the new video and update the path
            $videoPath = $request->video->store('courses_videos');
            $lesson->video = $videoPath;
        }
    
        // Update other fields
        $lesson->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'content' => $request->content,
            // Only update the video and pdf paths if new files were uploaded
            'pdf_url' => isset($pdfPath) ? $pdfPath : $lesson->pdf_url,
            'video_url' => isset($videoPath) ? $videoPath : $lesson->video_url,
        ]);
    
        return response()->json(['lesson' => $lesson], 200);
    }
    

    public function delete($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully'], 200);
    }
}
