<?php

namespace Apps\Http\Controllers\Store;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Apps\Http\Requests\Store\PurchaceOrderRequest;
use Carbon\Carbon;

// Requests
use Apps\Http\Requests\Store\PurchaceOrder\StructureRequest;

// Models
use Apps\Models\Store\SalesOrder;
use Apps\Models\Store\Article;
use Apps\Models\Store\Customer;

// Traits
use Apps\Traits\Pa\Article\Search;

class PurchaceOrderController extends Controller
{
    use Search;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = Auth::user();

        $customer = Customer::with([
            'salesOrders:id,shipping_method_id,customer_id,status_id,created_at,note',
            'salesOrders.status:id,name', 
            'salesOrders.shipping_method:id,name',
            'salesOrders' => function($query) {
                $query->orderBy('id', 'DESC')->limit(35)
                ->withCount('articles');
            }
        ])->findOrFail($user->userable_id);

        $purchaceOrders = $customer->salesOrders->map(function($salesOrder) {
            
            $salesOrder->created_at_for_humans = Carbon::parse($salesOrder->created_at)->diffForHumans();

            return $salesOrder;
        });

        return response()->json([
            'data' => array_reverse($purchaceOrders->toArray()),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaceOrderRequest $request) {
        $user = Auth::user();

        $customer = $user->userable;

        $request_data = array_merge($request->only('note', 'shipping_method_id'),[
            'user_id' => $user->id,
            'customer_id' => $customer->id,
        ]);

        $articleForCustomer = $this->forCustomer($customer);

        $articles = $articleForCustomer->whereIn('id', array_column($request->get('articles'), 'id'));

        $articleForCustomer = $articleForCustomer->reduce(function($acc, $article) {
            
            $acc[$article['id']] = $article;

            return $acc;
        }, []);

        $toSync = collect($request->get('articles'))->mapWithKeys(function($item) use ($articleForCustomer) {
            // $article = $articleForCustomer->firstWhere('id', $item['id']);

            return [
                $item['id'] => array_merge($this->mapArticleToAttach($articleForCustomer[$item['id']]), ['amount' => $item['amount']])
            ];
        })->toArray();   

        $purchaceOrder = $customer->salesOrders()->create($request_data);

        $purchaceOrder->articles()->sync($toSync);
        
        return response()->json([
            'data' => $purchaceOrder,
        ]);
    }    
    
    /**
     * Display the specified resource.
     *
     * @param  \Apps\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function show($purchace_order_id) {
        $purchaceOrder = SalesOrder::with(['customer', 'status', 'shipping_method', 'articles'])->findOrFail($purchace_order_id);

        $this->authorize('owner', $purchaceOrder);

        return response()->json([
            'data' => $purchaceOrder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\SalesOrder  $salesOrder
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaceOrderRequest $request, $purchace_order_id) {
        $purchaceOrder = SalesOrder::with([
            'articles',
            'shipping_method',
            'customer.priceList',
        ])->findOrFail($purchace_order_id);

        $this->authorize('owner', $purchaceOrder);

        $articleForCustomer = $this->forCustomer($purchaceOrder->customer)->reduce(function($acc, $article) {
            $acc[$article['id']] = $article;

            return $acc;
        }, []);

        $articlesForPurchaceOrder = $purchaceOrder->articles->reduce(function($acc, $article) {
            $acc[$article->id] = $article;

            return $acc;
        }, []);

        $toSync = collect($request->get('articles'))->mapWithKeys(function($item) use ($articleForCustomer, $articlesForPurchaceOrder) {
            if (isset($articlesForPurchaceOrder[$item['id']])) {
                return [ 
                    $item['id'] => ['amount' => $item['amount']] 
                ];
            } else {
                return [
                    $item['id'] => array_merge($this->mapArticleToAttach($articleForCustomer[$item['id']]), ['amount' => $item['amount']])
                ];
            }       
        })->toArray();

        if ($purchaceOrder->status_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => [
                    'status' => [
                        'El estado de este registro no permite realizar esta acción. Estado actual: ' . $purchaceOrder->status->name
                    ],
                ]
            ], 422);
        }

        $purchaceOrder->articles()->sync($toSync);

        $purchaceOrder->update($request->only(['shipping_method_id', 'note']));

        // $purchaceOrder->articles;

        // dd(
        //     $purchaceOrder->subTotal(),
        //     $purchaceOrder->discountAmount(),
        //     $purchaceOrder->ivaAmount(),
        //     $purchaceOrder->total(),

        //     $toSync,
        // );

        return response()->json([
            'data' => $purchaceOrder,
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



    private function mapArticleToAttach($article) {

        $price = $article['base_price'];
        $discount = $article['base_discount'];

        $preferential_price = 0;
        $preferential_discount = 0;        

        if (isset($article['pivot'])) {
            $pivot = $article['pivot'];

            $preferential_price = $pivot['preferential_price'];
            $preferential_discount = $pivot['preferential_discount'];

            $price = $preferential_price;
            $discount = $preferential_discount;
        }

        return [
            'price' => $price,
            'discount' => $discount,
            'base_price' => $article['base_price'],
            'base_discount' => $article['base_discount'],
            'preferential_price' => $preferential_price,
            'preferential_discount' => $preferential_discount,
        ];
    }


    public function structurevalidation(StructureRequest $request) {
        $articles = $request->get('articles');

        $amounts = array_reduce($request->get('articles'), function($carry, $item) {
            $carry[$item['codigo']] = $item['cantidad'];
            return $carry;
        }, []);

        $codes = array_map(function($article) {
            return $article['codigo'];
        }, $request->get('articles'));

        $articles = Article::whereIn('code', $codes)->get();

        $data = [];

        $errors = [];

        foreach ($articles as $key => $article) {
            $article->amount = $amounts[$article->code];

            if (!empty($article->box_capacity)) {
                if (($article->amount % $article->box_capacity) != 0) {
                    array_push($errors, "La cantidad para: $article->code debe ser multiplo de: $article->box_capacity");
                }
            }

            array_push($data, $article->only(['id', 'amount']));
        }

        if (count($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => [
                    'articles' => $errors,
                ]
            ], 422);
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
