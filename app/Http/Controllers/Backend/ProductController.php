<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(){
        $response = Http::get("https://60c18de74f7e880017dbfd51.mockapi.io/api/v1/jabar-digital-services/product");
        $bodyResponse = json_decode($response->body(), true);
        return response()->json([
            "data" => $bodyResponse
        ]);
    }
}
