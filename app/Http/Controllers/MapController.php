<?php

namespace App\Http\Controllers;

use App\Events\MarkerAdded;
use App\Models\MapPoint;
use Illuminate\Http\Request;

class MapController extends Controller {

    public function index() {
        $mapPoints = MapPoint::all();

        return view( 'map.index', compact( 'mapPoints' ) );
    }

    public function addMarker( Request $request ) {
        $validatedData = $request->validate( [
            'latitude'  => 'required',
            'longitude' => 'required',
        ] );

        $newMapPoint = MapPoint::create( $validatedData );

        event( new MarkerAdded( $newMapPoint ) );

        return redirect()->route( 'map.index' );
    }
}
