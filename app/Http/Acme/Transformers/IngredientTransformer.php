<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class IngredientTransformer extends Transformer {

    public function transform($data) {
        return [
            'ActiveIngredientsId' => $data['id'],
            'ActiveIngredientsName' => $data['name'],
        ];
    }

}
