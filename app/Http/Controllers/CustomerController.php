<?php

namespace Apps\Http\Controllers;

use Illuminate\Http\Request;
use Apps\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Auth;

use Apps\Models\Store\Customer;

// Traits
use Apps\Traits\Pa\Article\Search;

class CustomerController extends Controller
{
    use Search;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response()->json([
            "success" => true,
            "data" => Customer::all()
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return response()->json([
            "success" => true,
            "data" => $customer
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apps\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {

        $this->validate($request, [
            'criterion' => 'required|string|in:"rfc"',
            'value' => 'required|max:25',
        ]);

        $result = Customer::Where($request->get('criterion'), $request->get('value'))->get();

        return response()->json([
            'data' => $result,
        ]);
    }   

    // Articles
    public function article_index(Request $request, Customer $customer) {
        $articles = $this->forCustomer($customer);

        // $articles = $this->getDefaultImages($articles);

        return response()->json([
            'data' => $articles,
        ]);
    }  

    public function article_show(Request $request, $customer_id, $article_id) {
        // $user = Auth::user();

        $customer = Customer::with('pricelist.articles')->find($customer_id);
        
        $article = $this->forCustomer($customer)->firstWhere('id', $article_id);

        $article->getDefaultImages();

        if (empty($article)) {
            return response()->json([
                'message' => "NOT FOUND",
                'errors' => [
                    'article' => ['Recurso no encontrado.'],
                ]
            ], 404);
        }

        return response()->json([
            'data' => $article,
        ]);
    }
}
