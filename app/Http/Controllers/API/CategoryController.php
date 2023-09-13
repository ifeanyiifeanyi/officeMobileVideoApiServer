<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
      // select all categories, limit to 3, in random order
      public function category()
      {
  
          $category = categories::inRandomOrder()->limit(3)->get();
  
          if ($category) {
              return response()->json($category);
          } else {
              return response()->json([
                  'error' => $category->errors()->messages()
              ], 404);
          }
      }
  
      // select first category
      public function firstCategory()
      {
          $firstCategory = categories::select('name')->take(1)->first();
          if ($firstCategory) {
              return response()->json($firstCategory);
          } else {
              return response()->json([
                  'error' => $firstCategory->errors()->messages()
              ], 404);
          }
      }
  
      // second category
      public function secondCategory()
      {
          $secondCategory = categories::select('name')->skip(1)->first();
          if ($secondCategory) {
              return response()->json($secondCategory);
          } else {
              return response()->json([
                  'error' => $secondCategory->errors()->messages()
              ], 404);
          }
      }
  
      // third category
      public function thirdCategory()
      {
          $thirdCategory = categories::select('name')->skip(2)->first();
          if ($thirdCategory) {
              return response()->json($thirdCategory);
          } else {
              return response()->json([
                  'error' => $thirdCategory->errors()->messages()
              ], 404);
          }
      }
  
}
