<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

trait FormatTimeAgo
{

    public function formatTimeAgo($date)
    {
        $diff = Carbon::parse($date);
        $locale = App::getLocale();

        $timeUnits = [
            'en' => ['s' => 's', 'm' => 'm', 'h' => 'h', 'd' => 'd', 'mo' => 'mo', 'y' => 'y'],
            'ar' => ['s' => 'ث', 'm' => 'د', 'h' => 'س', 'd' => 'ي', 'mo' => 'ش', 'y' => 'س'],
            'ru' => ['s' => 'с', 'm' => 'м', 'h' => 'ч', 'd' => 'д', 'mo' => 'мес', 'y' => 'г'],
        ];

        $units = $timeUnits[$locale] ?? $timeUnits['en'];

        if ($diff->diffInSeconds() < 60) {
            return floor($diff->diffInSeconds()) . $units['s'];
        } elseif ($diff->diffInMinutes() < 60) {
            return floor($diff->diffInMinutes()) . $units['m'];
        } elseif ($diff->diffInHours() < 24) {
            return floor($diff->diffInHours()) . $units['h'];
        } elseif ($diff->diffInDays() < 30) {
            return floor($diff->diffInDays()) . $units['d'];
        } elseif ($diff->diffInMonths() < 12) {
            return floor($diff->diffInMonths()) . $units['mo'];
        } else {
            return floor($diff->diffInYears()) . $units['y'];
        }
    }

}
