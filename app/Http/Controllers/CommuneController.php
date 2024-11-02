<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use Illuminate\Http\Request;

class CommuneController extends Controller
{

    public function index()
    {
        $communes = Commune::all();
        return response()->json($communes);
    }


    public function show(Commune $commune)
    {
        return response($commune);
    }

    public function store(Request $request)
    {

        $commune = Commune::firstOrCreate($request->all());
        return response($commune);
    }

    public function update(Request $request, Commune $commune)
    {
        $commune->update($request->all());
        return response($commune);
    }

    public function destroy(Commune $commune)
    {
        $commune->delete();
        return response()->json([
            'msg' => 'Commune has been deleted'
        ]);
    }
}
