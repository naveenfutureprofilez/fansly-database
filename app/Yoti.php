<?php
namespace App;

use Carbon\Carbon;
use Yoti\YotiClient;
use Yoti\Exception\ActivityDetailsException;
use Yoti\Exception\PemFileException;
use Illuminate\Support\Facades\Storage;

/**
 * Yoti Integration
 * Used to age verifications
 * @version 3.4.0
 */
class Yoti {

    private $SDK_ID;
    private $API_KEY;
    private $KEY_FILE_PATH;
    private $client;

    public function __construct()
    {
        // $this->SDK_ID = '2c570867-8d4e-4903-a130-3c43068df377';
        // $this->SDK_ID = 'e1cecac4-63ce-45f5-a226-10a7fb92d789';
        $this->SDK_ID = 'ee304540-26fd-4a98-92d7-3fc4791c8241';
        $this->API_KEY = '6AZLebqhMVkeDo8TVvLEAyT5wz8=';
        // $this->client = new YotiClient($this->SDK_ID, Storage::path('yoti/yoti.pem'));
        $this->client = new YotiClient($this->SDK_ID, Storage::path('yoti/yoti-id.pem'));
        
    }

    
    /**
     * Get User Profile using Token
     * Token from front end onetime
     * 
     * @param string $token Token from JS library
     * @return mixed $profile Profile details Object
     */
    public function getProfile($token){
        try {
            $activity = $this->client->getActivityDetails($token);
            $profile = $activity->getProfile();
            $resp = [
                'status' => true,
                'profile' => $profile
            ];
        } catch (PemFileException $e) {
            $resp = [
                'status' => false,
                'msg'    => $e->getMessage()
            ];
        } catch (ActivityDetailsException $e){
            $resp = [
                'status' => false,
                'msg'    => $e->getMessage()
            ];
        }

        return $resp;
    }

}