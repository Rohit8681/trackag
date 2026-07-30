<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            }
        } catch (Exception $e) {
        }
    }

    /**
     * Send push notification to a specific FCM token.
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @param int|null $userId
     * @return bool
     */
    public function sendNotification($fcmToken, $title, $body, $data = [], $userId = null)
    {
        $data = collect($data)->mapWithKeys(function ($value, $key) {
            return [(string) $key => is_scalar($value) || is_null($value) ? (string) $value : json_encode($value)];
        })->all();

        $notificationLogId = $this->storeNotificationLog($fcmToken, $title, $body, $data, $userId);

        if (!$this->messaging) {
            $this->markNotificationFailed($notificationLogId, 'Firebase messaging is not initialized.');
            return false;
        }

        if (empty($fcmToken)) {
            $this->markNotificationFailed($notificationLogId, 'FCM token is empty.');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            $this->markNotificationSent($notificationLogId);
            
            return true;
        } catch (Exception $e) {
            $this->markNotificationFailed($notificationLogId, $e->getMessage());
            return false;
        }
    }

    private function storeNotificationLog($fcmToken, string $title, string $body, array $data, $userId = null): ?int
    {
        try {
            $connection = $this->notificationLogConnection();

            if (!Schema::connection($connection)->hasTable('notification_logs')) {
                return null;
            }

            $resolvedUserId = $userId ?? ($data['user_id'] ?? null);

            return DB::connection($connection)->table('notification_logs')->insertGetId([
                'user_id' => is_numeric($resolvedUserId) ? (int) $resolvedUserId : null,
                'type' => $data['type'] ?? null,
                'title' => $title,
                'body' => $body,
                'data' => json_encode($data),
                'fcm_token' => $fcmToken,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Exception $e) {
            return null;
        }
    }

    private function markNotificationSent(?int $notificationLogId): void
    {
        $this->updateNotificationLog($notificationLogId, [
            'status' => 'sent',
            'sent_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function markNotificationFailed(?int $notificationLogId, string $errorMessage): void
    {
        $this->updateNotificationLog($notificationLogId, [
            'status' => 'failed',
            'error_message' => $errorMessage,
            'updated_at' => now(),
        ]);
    }

    private function updateNotificationLog(?int $notificationLogId, array $values): void
    {
        if (!$notificationLogId) {
            return;
        }

        try {
            DB::connection($this->notificationLogConnection())
                ->table('notification_logs')
                ->where('id', $notificationLogId)
                ->update($values);
        } catch (Exception $e) {
        }
    }

    private function notificationLogConnection(): string
    {
        if (tenancy()->tenant || !empty(config('database.connections.tenant.database'))) {
            return 'tenant';
        }

        return config('database.default');
    }
}
