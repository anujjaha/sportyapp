<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class DiseasesTransformer extends Transformer {

    public function transform($data) {
        return [
            'DiseaseId' => $data['id'],
            'DiseaseName' => $data['name'],
        ];
    }

}
