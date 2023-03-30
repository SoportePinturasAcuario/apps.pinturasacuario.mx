<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Http\Requests\Store\SalesOrderRequest;
use Carbon\Carbon;

// Models
use Apps\Models\Store\Article;
use Apps\Models\Store\SalesOrder;
use Apps\User;

// Notifications
use Apps\Notifications\Store\PurchaceOrderStatusChanged;

// Traits
use Apps\Traits\Pa\Article\Search;

class SalesOrderController extends Controller
{
    // Traits
    use Search;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $salesOrders = SalesOrder::with(['user:id,email', 'status:id,name', 'customer:id,folio,name', 'shipping_method:id,name'])
        ->select(['id', 'note', 'user_id', 'discount', 'status_id', 'created_at', 'exported_at', 'customer_id', 'shipping_method_id',])
        ->withCount('articles')
        ->orderBy('id', 'DESC')
        ->limit(100)
        ->get();

        $salesOrders->each(function($salesOrder) {
            $salesOrder->exported_at_for_humans = empty($salesOrder->exported_at) ? null : Carbon::parse($salesOrder->exported_at)->diffForHumans();

            $salesOrder->created_at_for_humans = Carbon::parse($salesOrder->created_at)->diffForHumans();          
        });

        return response()->json([
            'data' => $salesOrders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesOrderRequest $request) {
        $salesOrder = SalesOrder::create($request->all());

        $articles = collect($request->get('articles'))->mapWithKeys(function($article){
            return [
                $article['id'] => collect($article)->only([
                    'price',
                    'amount',
                    'discount',
                    'base_price',
                    'base_discount',
                    'preferential_price',
                    'preferential_discount'
                ])->toArray()
            ];
        })->toArray();        

        $salesOrder->articles()->sync($articles);

        return response()->json([
            'data' => $salesOrder,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function show($sales_order_id)
    {
        $salesOrder = SalesOrder::with([
            'customer',
            'user.collaborator',
            'status',
            'shipping_method',
            'articles'
        ])->find($sales_order_id);

        return response()->json([
            'data' => $salesOrder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function update(SalesOrderRequest $request, $sales_order_id) {

        $salesOrder = SalesOrder::findOrFail($sales_order_id);

        $toSync = collect($request->get('articles'))->mapWithKeys(function($article) {
            return [
                $article['id'] => collect($article)->only([
                    'price',
                    'amount',
                    'discount',
                    'base_price',
                    'base_discount',
                    'preferential_price',
                    'preferential_discount'
                ])->toArray()
            ];
        })->toArray();

        $salesOrder->articles()->sync($toSync);

        $salesOrder->update($request->all());

        return response()->json([
            'data' => $salesOrder,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesOrder $salesOrder)
    {
        //
    }
    
    // Export
    public function export(Request $request) {
        $this->validate($request, [
            'sales_orders' => 'required|array|min:1|max:100',
            'sales_orders.*' => 'required|integer',
        ]);

        $salesOrders = SalesOrder::with(['articles', 'status:id,name', 'shipping_method:id,name', 'user:id,email', 'customer:id,folio,name'])
        ->select(['id', 'exported_at', 'discount' ,'created_at', 'shipping_method_id', 'user_id', 'note', 'customer_id', 'status_id'])
        ->whereIn('id', $request->get('sales_orders'))
        ->get();

        return response()->json([
            'data' => $salesOrders,
        ]);
    }

    public function exported(Request $request){
        SalesOrder::whereIn('id', $request->get('sales_orders'))->update(['exported_at' => Carbon::now()]);
    }

    // Satatus
    public function status_update(Request $request, SalesOrder $salesOrder){
        $this->validate($request, [
            'status_id' => 'required|integer',
            'notify' => 'required|boolean',
        ], [], [
            'notify' => '"notificar"'
        ]);

        $salesOrder->update($request->only('status_id'));

        // $user = User::where([['userable_id', $salesOrder->customer->id], ['userable_type', 'Apps\Customer']])->get()->first();

        $user = $salesOrder->customer->user;

        if ($request->get('notify') && !empty($user)) {
            $user->notify(new PurchaceOrderStatusChanged($salesOrder));
        }

        return response()->json([
            'data' => $salesOrder,
        ]);
    }

    public function format(salesOrder $salesOrder) {
        $articles = $salesOrder->articles->chunk(55);

        return view('Store.SalesOrder.Formats.Salesorder', compact(['salesOrder', 'articles']));
    }

    public function last() {
        return response()->json([
            'data' => SalesOrder::select(['id'])->orderBy('id', 'DESC')->first(),
        ]);
    }
}
