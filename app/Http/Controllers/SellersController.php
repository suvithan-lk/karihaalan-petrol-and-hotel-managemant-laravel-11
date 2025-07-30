<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellersController extends Controller
{
    public function index(){
        $sellers = Seller::all();
        return view('sellers.index',["sellers" => $sellers]);
    }

    public function show(Seller $seller){

        return view('sellers.details', ["seller" => $seller]);
    }

    public function edit(Seller $seller){
        return view('sellers.edit', ["seller" => $seller]);
    }
}
