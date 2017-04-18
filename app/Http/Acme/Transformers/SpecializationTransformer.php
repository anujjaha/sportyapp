<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class SpecializationTransformer extends Transformer {

    public function transform($data) {
        return [
            'SpecializationId' => $data['id'],
            'SpecializationName' => $data['name'],
        ];
    }

}
