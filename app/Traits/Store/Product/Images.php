<?php 

namespace Apps\Traits\Store\Product;

trait Images {

    // IMAGES
    public function getDefaultImages($products) {
        return $products->map(function($product){

            $product->images = $this->searchImages($this->getPaths($product));

            return $product;
        });
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

    private function getPaths($product) {
        $category_name = "";

        if ($product->category) {
            $category_name = $product->category->name;
        }

        return ["/$category_name/$product->name/DEFAULT"];
    }
}