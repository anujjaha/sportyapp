<?php

namespace App\Http\Acme\Transformers;

use App\Http\Acme\Transformers;

class UserTransformer extends Transformer {

    public function transform($data) {
        return [
            'UserId' => $data['id'],
            'UserToken' => $data['user_token'],
            'Name' => $this->nulltoBlank($data['name']),
            'Email' => $this->nulltoBlank($data['email']),
            'MobileNumber' => $this->nulltoBlank($data['mobile_number']),
            'Specialty' => array(
                'SpecializationId' => $this->nulltoBlank($data['specialization_id']),
                'SpecializationName' => $this->nulltoBlank($data['specialization_name']),
            ),
            'Degree' => $this->nulltoBlank($data['degree']),
            'OtherAssociation' => $this->nulltoBlank($data['other_association']),            
            'RegistrationNo' => $this->nulltoBlank($data['registration_no']),
            'Gender' => $this->nulltoBlank($data['gender']),
            'Age' => $this->nulltoBlank($data['age']),
            'YearOfPractice' => $this->nulltoBlank($data['year_of_practice']),
            'City' => array(
                'CityId' => $this->nulltoBlank($data['city_id']),
                'CityName' => $this->nulltoBlank($data['city_name']),
            ),
            'State' => array(
                'StateId' => $this->nulltoBlank($data['state_id']),
                'StateName' => $this->nulltoBlank($data['state_name']),
            ),
            'ProfilePhoto' => $this->getUserImage($data['profile_photo']),
            'ClinicAddress' => $this->nulltoBlank($data['address']),
            'Pincode' => $this->nulltoBlank($data['pincode']),
            'IsNotification' => $data['is_notification'],
            'IsProfileSet' => $data['is_profile_set'],
            'OTP' => $data['otp'],
            'QuickBlocksId' => isset($data['quick_blocks_id']) ? $data['quick_blocks_id'] : "",
            'Status' => $data['status']
        ];
    }
    
    /*
     * get image with path
     */
    
    public function getUserImage($profilePhoto){        
        return $profilePhoto?url('/').'/images/'.config('access.users.image_folder').'/'.$profilePhoto:"";
    }
    /**
     * Create by:Shakil Mansuri
     * Create at:01/12/16
     * @param array $items
     */
    public function NotificationList(array $items){
        return array_map([$this, 'NotificationDetail'], $items);
    }

    /**
     * Create by:Shakil Mansuri
     * Create at:01/12/16
     * @param array $items
     */
    public function NotificationDetail($data){
        return [
            'Id' => $data['id'],
            'Message' => $data['message'],
            'Type' => $data['type'],
            'Data' => $data['data'],
            'CreatedDateTime' => strtotime($data['created_at']),
        ];
    }

    /**
     * usersList
     * array of users
     * 
     * @param array $items
     * @return type
     */
    public function usersList(array $items) {
        return array_map([$this, 'userDetail'], $items);
    }

    /**
     * userDetail
     * Single user detail
     * 
     * @param type $data
     * @return type
     */
    public function userDetail($data) {
        return [
            'UserId' => isset($data['id']) ? $data['id'] : "",
            'QuickBlocksId' => isset($data['quick_blocks_id']) ? $data['quick_blocks_id'] : "",
            'MobileNumber' => isset($data['mobile_number']) ? $data['mobile_number'] : "",
            'Name' => isset($data['username']) ? $data['username'] : "",
            'Specialty' => isset($data['specialty']) ? $data['specialty'] : "",
            'ProfilePhoto' => isset($data['profile_photo'])?$this->getUserImage($data['profile_photo']):""
        ];
    }

    /**
     * manufacturerList
     * array of manufacturers
     * 
     * @param array $items
     * @return type
     */
    public function manufacturerList(array $items) {
        return array_map([$this, 'manufacturerDetail'], $items);
    }

    /**
     * manufacturerDetail
     * Single manufacturer Detail
     * 
     * @param type $data
     * @return type
     */
    public function manufacturerDetail($data) {
        return [
            'ManufacturerId' => isset($data['id']) ? $data['id'] : "",
            'ManufacturerName' => isset($data['username']) ? $data['username'] : "",
        ];
    }
    
    /*
     * User Detail and it's parameters
     */
    public function singleUserDetail($data){        
        return [
            'UserId' => $data['id'],            
            'Name' => $this->nulltoBlank($data['name']),
            'Email' => $this->nulltoBlank($data['email']),
            'MobileNumber' => $this->nulltoBlank($data['mobile_number']),
            'Specialty' => array(
                'SpecializationId' => $this->nulltoBlank($data['specialization_id']),
                'SpecializationName' => $this->nulltoBlank($data['specialization_name']),
            ),
            'Degree' => $this->nulltoBlank($data['degree']),
            'OtherAssociation' => $this->nulltoBlank($data['other_association']),            
            'RegistrationNo' => $this->nulltoBlank($data['registration_no']),
            'Gender' => $this->nulltoBlank($data['gender']),
            'Age' => $this->nulltoBlank($data['age']),
            'YearOfPractice' => $this->nulltoBlank($data['year_of_practice']),
            'City' => array(
                'CityId' => $this->nulltoBlank($data['city_id']),
                'CityName' => $this->nulltoBlank($data['city_name']),
            ),
            'State' => array(
                'StateId' => $this->nulltoBlank($data['state_id']),
                'StateName' => $this->nulltoBlank($data['state_name']),
            ),
            'ProfilePhoto' => $this->getUserImage($data['profile_photo']),
            'ClinicAddress' => $this->nulltoBlank($data['address']),
            'Pincode' => $this->nulltoBlank($data['pincode']),
            'IsNotification' => $data['is_notification'],
            'IsProfileSet' => $data['is_profile_set'],
            'QuickBlocksId' => isset($data['quick_blocks_id']) ? $data['quick_blocks_id'] : "",
        ];
    }
    
    public function transformStateCollection(array $items) {
        return array_map([$this, 'getState'], $items);
    }

    public function getState($data){
        return [
            'StateId' => $data['id'],
            'StateName' => $this->nulltoBlank($data['name']),
        ];
    }
    public function transformCityCollection(array $items) {
        return array_map([$this, 'getCity'], $items);
    }

    public function getCity($data){
        return [
            'CityId' => $data['id'],
            'CityName' => $this->nulltoBlank($data['name']),
        ];
    }
}
