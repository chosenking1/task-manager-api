<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('approve-task/{task}', function ($task) {
    // return redirect("http://localhost:3000/task/$task/approveTask");
    return redirect("https://taskmanagerview-production.up.railway.app/task/$task/approveTask");
    
})->name('approve.task');

// Route::get('approve-task/{task}', function ($task) {
//     return redirect('https://www.google.com');
// })->name('approve.task');

Route::get('task/{task}', function ($task) {
    // return redirect("http://localhost:3000/task/$task");
    return redirect("https://taskmanagerview-production.up.railway.app/task/$task");
})->name('task.approved');


 