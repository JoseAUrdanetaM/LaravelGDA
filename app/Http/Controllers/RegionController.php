<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{

    public function index()
    {
        $regions = Region::all();
        return response()->json($regions);
    }


    public function show(Region $region)
    {
        return response($region);
    }

    public function store(Request $request)
    {

        $region = Region::firstOrCreate($request->all());
        return response($region);
    }

    public function update(Request $request, Region $region)
    {
        $region->update($request->all());
        return response($region);
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return response()->json([
            'msg' => 'Region has been deleted'
        ]);
    }
}
