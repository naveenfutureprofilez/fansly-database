<?php
namespace App\Http\Controllers\Api;

use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use App\User;

class SettingsController extends BaseController {

    public function seoSettings(){
        $options = [
            'seo' => [
                'title'     => Options::get_option('seo_title'),
                'desc'      => Options::get_option('seo_desc'),
                'keywords'  => Options::get_option('seo_keys'),
            ],
            'config' => [
                'min_tip'   => Options::get_option('minTipAmount'),
                'max_tip'   => Options::get_option('maxTipAmount'),
            ]
        ];
        return response()->json($options);
    }
}