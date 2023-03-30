<?php 

namespace Apps\Traits\Pa\Article;
use Apps\Models\Store\Article;
use Apps\Models\Store\PriceList;

trait Search {
    public function pt(){
        return Article::where('code', 'regexp', '^(AA|AD|AG|AI|AJ|AK|AM|AQ|AR|AS|AT|AW|AY|EA|EB|ED|EG|EK|EM|ES|ET|EV|EW|HA|HD|HG|IA|MA|MM|MW|PA|PM|PT|RA|RN|SA|SD|SG|TD|TH|TM|V1|VA|VD|VG|VM|VS|XA|X|7A|7B|7C|7D|7E|7F|7G|7I|7K|7L|7M|7P|7R|7S|7T|7V|8A|8B|8C|8D|8E|8F|8G|8H|8I|8L|8M|8P|8R|8S|8T|8U|8V|8X|9I)[A-Z,0-9]*$')
        ->orderBy('code', 'DESC')
        ->get();
    }

    public function articlesAllWithTrashed() {
        $articles = Article::with([
            'color',
            'product',
            'type_of_finish',
            'unitOfMeasurement',
            'category.category',
        ])
        ->orderBy('code', 'ASC')
        ->get();

        return $articles;
    }

    public function articlesActive(){
        $articles = Article::with([
            'color',
            'product',
            'type_of_finish',
            'unitOfMeasurement',
            'category.category',
        ])
        ->orderBy('code', 'ASC')
        ->get();

        return $articles;
    }    

    public function forEcommerce() {
        $articles = $this->articlesActive()->where('special', false)->values();

        return $this->getCategories($articles);
    }

    private function forCustomer($customer) {

        if (!empty($customer->price_list_id)) {
            // obtener artículos de la lista de precios
                $priceList = PriceList::with([
                    'articles.category.category',
                    'articles.unitOfMeasurement'
                ])->find($customer->price_list_id);

            $articlesOnPriceList = $priceList->articles;

            // Quitar los artículos de la lista de precios sobre los artículos activos
                $articlesActiveFiltered = $this->articlesActive()->whereNotIn('id', $articlesOnPriceList->pluck('id'));

            // combinar los artículos de la lp con los artículos disponibles para ecomerce restantes
                $articles = $articlesActiveFiltered->merge($articlesOnPriceList);

                return $this->getCategories($articles);
        }
        
        return $this->forEcommerce();
    }

    public function getCategories($articles){
        return $articles->map(function($article){
            $article->categories = $this->mapArticleCategories($article);

            return $article;
        });
    }

    public function mapArticleCategories($article){
        if (!empty($article->category)) {
            return $this->array_value_recursive('id', $article->category->toArray());
        }

        return [];
    }

    function array_value_recursive($key, array $arr){
        $val = array();

        array_walk_recursive($arr, function($v, $k) use($key, &$val){
            if($k == $key) array_push($val, $v);
        });

        return $val;
    }

    // IMAGES
    public function getDefaultImages($articles) {
        return $articles->map(function($article){
            $article->images = $this->searchImages($this->getPaths($article));
            return $article;
        });
    }

    public function getPaths($article){
        $categories = array_reverse($this->exploreCategory($article->category));

        array_push($categories, str_replace(".", "", $article->contents));

        $path = "";

        $paths = [];

        foreach ($categories as $key => $item) {
            $path = "$path/$item";

            array_push($paths, $path);
        }

        return $paths;
    }

    public function exploreCategory($category){
        $paths = [$category['name']];

        if (!empty($category->category)) {
            $paths = array_merge($paths, $this->exploreCategory($category->category));
        }

        return $paths;
    }

    private function searchImages($routes) {
        $images = [];

        foreach ($routes as $key => $route) {
            $path = "storage/articles$route";
            $base_path = base_path("public/$path");

            if (is_dir($base_path)) {
                foreach (scandir($base_path) as $key => $item) {
                    if (preg_match("/(.png|.jpeg|.jpg)/", $item)) {
                        array_push($images, url("$path/$item"));
                    }
                }
            }
        }

        return $images;
    }
}