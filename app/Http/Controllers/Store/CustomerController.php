<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Carbon\Carbon;

// Models
use Apps\User;
use Apps\Models\Store\Customer;
use Apps\Http\Requests\Store\CustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with(['priceList:id,name', 'user:userable_id,userable_type,email'])->get();
        
        return  response()->json([
            'data' => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request) {

        $customer = Customer::create($request->all());

        return response()->json([
            'data' => $customer,
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
        $customer = Customer::with([
            'user:userable_id,userable_type,email',
            'priceList:id,name',
            'salesOrders.user:id,email',
            'salesOrders.status:id,name',
            'salesOrders.shipping_method:id,name'
        ])->findOrFail($id);

        $customer->salesOrders->each(function($salesOrder) {
            $salesOrder->exported_at_for_humans = empty($salesOrder->exported_at) ? null : Carbon::parse($salesOrder->exported_at)->diffForHumans();

            $salesOrder->created_at_for_humans = Carbon::parse($salesOrder->created_at)->diffForHumans();          
        });
        
        return  response()->json([
            'data' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id) {
        $customer = Customer::findOrFail($id);

        $customer->update($request->only(['rfc', 'name', 'folio', 'discount', 'price_list_id']));

        return  response()->json([
            'data' => $customer,
        ]);        
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
