<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers\Transformer;

class ProductTransformer extends Transformer {

    /**
     * transform
     * Product List - if object given, convert it into array
     * 
     * @param type $data
     * @return type
     */
    public function transform($data) {
        if (!is_array($data)) {
            $data = (array) $data;
        }
        return [
            'ProductId' => isset($data['id']) ? $data['id'] : "",
            'ProductName' => isset($data['brand']) ? $data['brand'] : "",
            //'ManufacturerId' => isset($data['manufacturer_id']) ? $data['manufacturer_id'] : "",
            //'ManufacturerName' => isset($data['name']) ? $data['name'] : "",
            'ProductPic' => $this->getImage($data['image']),
            'ProductPrice' => isset($data['price']) ? $data['price'] : "",
            'Manufacturer' => array(
                'ManufacturerId' => isset($data['manufacturer_id']) ? $data['manufacturer_id'] : "",
                'ManufacturerName' => isset($data['manufacturer_name']) ? $data['manufacturer_name'] : "",
                'ManufacturerEmail' => isset($data['manufacturer_email']) ? $data['manufacturer_email'] : "",
            )
        ];
    }

    public function substituteTransformerCollection(array $items){
        return array_map([$this, 'substituteTransformer'], $items);
    }

    public function substituteTransformer($data) {
        if (!is_array($data)) {
            $data = (array) $data;
        }
        return [
            'ProductId' => isset($data['id']) ? $data['id'] : "",
            'ProductName' => isset($data['name']) ? $data['name'] : "",
            //'ManufacturerId' => isset($data['manufacturer_id']) ? $data['manufacturer_id'] : "",
            //'ManufacturerName' => isset($data['name']) ? $data['name'] : "",
            'ProductPic' => $this->getImage($data['image']),
            'ProductBrochure' => $this->getBrochure($data['brochure']),
            'ProductYotubeUrl' => isset($data['url']) ? $data['url'] : "",
            'ProductPrice' => isset($data['price']) ? $data['price'] : "",
            'Manufacturer' => array(
                'ManufacturerId' => isset($data['manufacturer_id']) ? $data['manufacturer_id'] : "",
                'ManufacturerName' => isset($data['manufacturer_name']) ? $data['manufacturer_name'] : "",
                'ManufacturerEmail' => isset($data['manufacturer_email']) ? $data['manufacturer_email'] : "",
            )
        ];
    }

    public function getBrochure($brochure){        
        return $brochure?url('/').'/images/product/brochure'.'/'.$brochure:"";
    }

    public function getImage($img){        
        return $img?url('/').'/images/product/image'.'/'.$img:"";
    }

    /**
     * productDetailCollection
     * array of products
     * 
     * @param array $items
     * @return type
     */
    public function productDetailCollection(array $items) {
        return array_map([$this, 'productDetail'], $items);
    }

    /**
     * productDetail
     * Single Product detail
     * 
     * @param type $data
     * @return type
     */
    public function productDetail($data) {
        return [
            'ProductId' => isset($data['id']) ? $data['id'] : "",
            'ProductName' => isset($data['brand']) ? $data['brand'] : "",
            'ProductPic' => isset($data['image']) ? url('/') . '/images/product/image/' . $data['image'] : "",
            'ProductPrice' => isset($data['price']) ? $data['price'] : "",
            'ProductSideEffects' => isset($data['side_effects']) ? $data['side_effects'] : "",
            'ProductDescription' => isset($data['shell']) ? $data['shell'] : "",
            'ProductBrochure' => $this->getBrochure($data['brochure']),
            'ProductYotubeUrl' => isset($data['url']) ? $data['url'] : "",
            'ProductIngredient' => (!empty($data['ingredient'])) ? $data['ingredient'] : array(),
            'ProductDisease' => (!empty($data['disease'])) ? $data['disease'] : array(),
            'ProductSpecialty' => (!empty($data['specialization'])) ? $data['specialization'] : array(),
            'ProductRecommendation' => (!empty($data['poduct_recommendation'])) ? $data['poduct_recommendation'] : 0,
            'ProductRecommended' => (!empty($data['poduct_recommended'])) ? 1 : 0,
//              'ProductState' => (!empty($data['state'])) ? $data['state'] : array(),
//            'ProductCity' => (!empty($data['city'])) ? $data['city'] : array(),
            'Manufacturer' => array(
                'ManufacturerId' => isset($data['manufacturer_id']) ? $data['manufacturer_id'] : "",
                'ManufacturerName' => isset($data['manufacturer_name']) ? $data['manufacturer_name'] : "",
                'ManufacturerEmail' => isset($data['manufacturer_email']) ? $data['manufacturer_email'] : "",
                'ManufacturerMobileNumber' => isset($data['manufacturer_mobile_number']) ? $data['manufacturer_mobile_number'] : "",
            )
        ];
    }

    public function productRecommendation($data) {
        return [
            'ProductRecommendation' => $data ? $data : 0
        ];
    }

}
