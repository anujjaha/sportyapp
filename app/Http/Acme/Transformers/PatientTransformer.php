<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class PatientTransformer extends Transformer {

    /**
     * 
     * @param type $data
     * @return type
     */
    public function transform($data) {

        return [
            'PatientId' => $data['id'],
            'PatientName' => $this->nulltoBlank($data['name']),
            'PatientAddress' => $this->nulltoBlank($data['address']),
            'PatientAge' => $this->nulltoBlank($data['age']),
            'PatientPhoneNo' => $this->nulltoBlank($data['phone_no']),
            'MedicineText' => $this->nulltoBlank($data['medicine_text']),
            'PatientEmail' => $this->nulltoBlank($data['email']),
            'PatientDisease' => array(
                'DiseaseId' => isset($data['patient_diseases']['id']) ? $this->nulltoBlank($data['patient_diseases']['id']) : "",
                'DiseaseName' => isset($data['patient_diseases']['name']) ? $this->nulltoBlank($data['patient_diseases']['name']) : "",
            ),
            'Medicines' => $this->getProducts($data['patient_products_with_name']),
            'PatientVisitDate' => strtotime($data['visit_date']),
            'PatientNextAppointmentDate' => strtotime($data['appointment_datetime']),
            'PatientDocument' => $this->getPatientData($data['patient_document'], $data['id']),
        ];
    }

    /**
     * Get Product Name and Id
     * @param type $productData
     * @return type
     */
    public function getProducts($productData) {
        $productArray = array();
        foreach ($productData as $pK => $pV) {
            $productArray[$pK]['ProductId'] = $pV['product']['id'];
            $productArray[$pK]['ProductName'] = $pV['product']['brand'];
        }
        return $productArray;
    }

    /**
     * Get Patient Documents Path
     * Created By: Sagar Dave
     * Created At: 01/02/2017 
     * @param type $documentName
     * @param type $patientId
     * @return string
     */
    public function getPatientData($documentName, $patientId) {
        // dd($documentName);
        $patientArray = array();
        foreach ($documentName as $iK => $iV) {
            $patientArray[$iK] = url('/') . '/images/' . config('backend.patients_image_folder') . '/' . $patientId . '/' . $iV['file_name'];
        }
        return $patientArray;
    }

}
