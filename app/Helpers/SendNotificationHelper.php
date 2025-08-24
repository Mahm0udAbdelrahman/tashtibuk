<?php

namespace App\Helpers;


use Kreait\Firebase\Factory;
use App\Models\ChangeLanguage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;


class SendNotificationHelper
{


// public function sendNotification($data , array $tokens)
// {
//     try {

//         $lang = ChangeLanguage::pluck('language')->first();
//         $factory = (new Factory)
//             ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
//             ->createMessaging();
//         if ($lang === 'ar') {
//             $title = $data['title_ar'];
//             $body = $data['body_ar'];
//         }elseif($lang === 'ru'){
//             $title = $data['title_ru'];
//             $body = $data['body_ru'];
//         }else {
//             $title = $data['title_en'];
//             $body = $data['body_en'];
//         }

//         $notification = Notification::create($title, $body);


//         $message = CloudMessage::new()
//             ->withNotification($notification)
//             ->withData($data);



//         $report = $factory->sendMulticast($message, $tokens);

//         return response()->json([
//             'success' => true,
//             'message' => 'Notification sent successfully',
//             'report' => $report
//         ]);

//     } catch (MessagingException $e) {
//         return response()->json(['error' => $e->getMessage()], 400);
//     }
// }
public function sendNotification($data, array $tokens)
{
    try {
        $filteredTokens = array_filter($tokens, function ($token) {
            return !empty($token);
        });

        $lang = app()->getLocale();

        $factory = (new Factory)
            ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
            ->createMessaging();

        if ($lang === 'ar') {
            $title = $data['title_ar'];
            $body = $data['body_ar'];
        }else{
            $title = $data['title_en'];
            $body = $data['body_en'];
        }

        $notification = Notification::create($title, $body);

        if (!empty($filteredTokens)) {
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            $report = $factory->sendMulticast($message, $filteredTokens);
        } else {
            $report = null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully',
            'report' => $report
        ]);

    } catch (MessagingException $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

}
