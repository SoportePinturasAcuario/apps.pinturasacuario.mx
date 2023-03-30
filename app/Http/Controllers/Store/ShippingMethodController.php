<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Http\Requests\Store\ShippingMethodRequest;
use Apps\Models\Store\ShippingMethod;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingMethods = ShippingMethod::all();

        return response()->json([
            'data' => $shippingMethods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShippingMethodRequest $request)
    {
        return response()->json([
            'data' => shippingMethod::create([
                'idnetsuite' => $request->get('idnetsuite'),
                'name' => strtoupper($request->get('name')),
            ]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
