<?php
namespace App;

use App\Mail\Welcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
class EmailService {


    /**
     * Send welcome email to new users
     * @param array $data Email Dynamic Data
     * @return void
     */
    public static function welcome($data){
        Mail::to($data['to'])
        ->send(new Welcome($data));
    }

}