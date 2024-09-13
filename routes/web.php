<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyController;

class Person {
    public $id;
    public $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

Route::get('/', function () {return view('welcome');});
Route::get('/createInt', [MyController::class, 'createIntent']);
Route::get('/link', [MyController::class, 'link']);
Route::get('/sponsor', [MyController::class, 'sponsor']);


Route::post('/submitLink', [MyController::class, 'submitLink']);
Route::post('/createPaymentIntent', [MyController::class, 'createPaymentIntent']);
Route::post('/createPaymentMethod', [MyController::class, 'createPaymentMethod']);
Route::post('/attach', [MyController::class, 'attach']);


// React API sample
Route::get('/api/create-link', function () {
  $array = [
    new Person(1, "John"),
    new Person(2, "Mark")
  ];
  return response()->json($array);
});