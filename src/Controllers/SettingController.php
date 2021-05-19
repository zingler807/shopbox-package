<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        if (!$settings) { return [];  }
        return $settings;
    }

    public function update(Request $request)
    {

      $validatedData = $request->validate([
          'email' => 'nullable|email',
          'facebook' => 'nullable|url|sometimes',
          'twitter' => 'nullable|url|sometimes',
          'instagram' => 'nullable|url|sometimes',
          'linkedin' => 'nullable|url|sometimes'
      ],[
          'email.email' => 'Please enter a valid email address',
          'facebook.url' => 'Please enter a valid url ie https://facebook.com/',
          'twitter.url' => 'Please enter a valid url ie https://twitter.com/',
          'instagram.url' => 'Please enter a valid url ie https://instagram.com/',
          'linkedin.url' => 'Please enter a valid url ie https://linkedin.com/',
      ]);

      $setting = Setting::updateOrCreate(
          ['id' => 1],
          $request->all()
      );

      return $setting;

    }
}
