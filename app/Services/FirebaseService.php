<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = base_path(config('services.firebase.credentials'));
            
            if (file_exists($credentialsPath)) {
                $factory = (new Factory)->withServiceAccount($credentialsPath);
                $this->messaging = $factory->createMessaging();
            } else {
                Log::warning('Firebase credentials file not found at: ' . $credentialsPath);
            }
        } catch (Exception $e) {
            Log::error('Firebase initialization error: ' . $e->getMessage());
        }
    }

    /**
     * Send push notification to a specific FCM token.
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            Log::error('Firebase messaging is not initialized.');
            return false;
        }

        if (empty($fcmToken)) {
            Log::warning('Cannot send notification, FCM token is empty.');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            
            Log::info("Push notification sent successfully to token: {$fcmToken}");
            return true;
        } catch (Exception $e) {
            Log::error('Firebase send notification error: ' . $e->getMessage(), [
                'token' => $fcmToken,
                'title' => $title,
                'body'  => $body,
            ]);
            return false;
        }
    }
}
