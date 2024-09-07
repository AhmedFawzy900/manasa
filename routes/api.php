<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// login route
Route::post('/login', [UserController::class, 'login']);

// protected route
Route::middleware('auth:sanctum')->group(function () {
    // user routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/register', [UserController::class, 'register']);
    Route::put('/users/updateUser/{id}', [UserController::class, 'update']);
    Route::delete('/users/deleteUser/{id}', [UserController::class, 'delete']);
    Route::delete('/user/force-delete/{id}', [UserController::class, 'forceDelete']);
    Route::patch('/users/{id}/restore', [UserController::class, 'restore']);
    Route::post('/logout', [UserController::class, 'logout']);

    // categories routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories/store', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'delete']);

    // Subcategories routes
    Route::get('/subcategories', [SubcategoryController::class, 'index']);
    Route::post('/subcategories/store', [SubcategoryController::class, 'store']);
    Route::get('/subcategories/{id}', [SubcategoryController::class, 'show']);
    Route::put('/subcategories/update/{id}', [SubcategoryController::class, 'update']);
    Route::delete('/subcategories/delete/{id}', [SubcategoryController::class, 'delete']);

    // courses routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses/store', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/update/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/delete/{id}', [CourseController::class, 'delete']);

    // lessons routes 
    Route::get('/lessons', [LessonController::class, 'index']);
    Route::post('/lessons/store', [LessonController::class, 'store']);
    Route::get('/lessons/{id}', [LessonController::class, 'show']);
    Route::put('/lessons/update/{id}', [LessonController::class, 'update']);
    Route::delete('/lessons/delete/{id}', [LessonController::class, 'delete']);

    // enrollment routes
    Route::get('/enrollments', [EnrollmentController::class, 'index']);
    Route::post('/enrollments/store', [EnrollmentController::class, 'store']);
    Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']);
    Route::put('/enrollments/update/{id}', [EnrollmentController::class, 'update']);
    Route::delete('/enrollments/delete/{id}', [EnrollmentController::class, 'delete']);
});
