<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('student-list', 'Api\StudentsController@index');
Route::post('student-add', 'Api\StudentsController@store');
Route::post('student-update', 'Api\StudentsController@update');
Route::post('student-delete', 'Api\StudentsController@destroy');

Route::post('student-documents-list', 'Api\StudentDocumentsController@index');
Route::post('student-documents-add', 'Api\StudentDocumentsController@store');
Route::post('student-documents-update', 'Api\StudentDocumentsController@update');
Route::post('student-documents-delete', 'Api\StudentDocumentsController@destroy');