<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\FuelRequest;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Mail;
use App\Mail\FuelRequestApproved;
use App\Mail\FuelRequestRejected;
use App\Mail\FuelRequestAssigned;
use App\Mail\FuelDispensed;
use App\Mail\RequestCompleted;
use App\Mail\CreditLimitAlert;
use App\Mail\LowStockAlert;
use App\Mail\ReceiptVerification;
use App\Mail\PaymentReceived;

class NotificationService
{
    public function sendFuelRequestApproved(FuelRequest $fuelRequest)
    {
        $user = $fuelRequest->client->user;

        // Create in-app notification
        $this->createNotification(
            $user->id,
            'request_approved',
            'Fuel Request Approved',
            "Your fuel request for {$fuelRequest->vehicle->plate_number} has been approved.",
            $fuelRequest
        );

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new FuelRequestApproved($fuelRequest));
        }
    }

    public function sendFuelRequestRejected(FuelRequest $fuelRequest, $reason)
    {
        $user = $fuelRequest->client->user;

        // Create in-app notification
        $this->createNotification(
            $user->id,
            'request_rejected',
            'Fuel Request Rejected',
            "Your fuel request for {$fuelRequest->vehicle->plate_number} has been rejected. Reason: {$reason}",
            $fuelRequest
        );

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new FuelRequestRejected($fuelRequest, $reason));
        }
    }

    public function sendFuelRequestAssigned(FuelRequest $fuelRequest, User $pumper)
    {
        // Create in-app notification for pumper
        $this->createNotification(
            $pumper->id,
            'request_assigned',
            'Fuel Request Assigned',
            "You have been assigned to dispense fuel for request #{$fuelRequest->id}.",
            $fuelRequest
        );

        // Send email notification
        if ($pumper->email) {
            Mail::to($pumper->email)->send(new FuelRequestAssigned($fuelRequest, $pumper));
        }
    }

    public function sendFuelDispensed(FuelRequest $fuelRequest)
    {
        $user = $fuelRequest->client->user;
        $manager = $fuelRequest->station->manager;

        // Create in-app notification for client
        $this->createNotification(
            $user->id,
            'fuel_dispensed',
            'Fuel Dispensed',
            "Fuel has been dispensed for your request #{$fuelRequest->id}.",
            $fuelRequest
        );

        // Create in-app notification for manager
        if ($manager) {
            $this->createNotification(
                $manager->id,
                'fuel_dispensed',
                'Fuel Dispensed',
                "Fuel has been dispensed for request #{$fuelRequest->id}.",
                $fuelRequest
            );
        }

        // Send email notifications
        if ($user->email) {
            Mail::to($user->email)->send(new FuelDispensed($fuelRequest));
        }

        if ($manager && $manager->email) {
            Mail::to($manager->email)->send(new FuelDispensed($fuelRequest));
        }
    }

    public function sendRequestCompleted(FuelRequest $fuelRequest)
    {
        $user = $fuelRequest->client->user;

        // Create in-app notification
        $this->createNotification(
            $user->id,
            'request_completed',
            'Request Completed',
            "Your fuel request for {$fuelRequest->vehicle->plate_number} has been completed.",
            $fuelRequest
        );

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new RequestCompleted($fuelRequest));
        }
    }

    public function sendCreditLimitAlert(User $user, $currentBalance, $creditLimit, $percentage)
    {
        // Create in-app notification
        $this->createNotification(
            $user->id,
            'credit_limit_alert',
            'Credit Limit Alert',
            "Your credit utilization is at {$percentage}%. Current balance: $" . number_format($currentBalance, 2) . " of $" . number_format($creditLimit, 2)
        );

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new CreditLimitAlert($user, $currentBalance, $creditLimit, $percentage));
        }
    }

    public function sendLowStockAlert($station, $fuelType, $currentLevel, $threshold)
    {
        $manager = $station->manager;

        if ($manager) {
            // Create in-app notification
            $this->createNotification(
                $manager->id,
                'low_stock_alert',
                'Low Stock Alert',
                "Low stock alert for {$fuelType} at {$station->name}. Current level: " . number_format($currentLevel, 2) . "L"
            );

            // Send email notification
            if ($manager->email) {
                Mail::to($manager->email)->send(new LowStockAlert($station, $fuelType, $currentLevel, $threshold));
            }
        }
    }

    public function sendReceiptVerification($receipt, $action)
    {
        $user = $receipt->client->user;

        $title = $action === 'verified' ? 'Receipt Verified' : 'Receipt Rejected';
        $message = $action === 'verified'
            ? "Your receipt #{$receipt->receipt_number} has been verified and approved."
            : "Your receipt #{$receipt->receipt_number} has been rejected. Please contact support.";

        // Create in-app notification
        $this->createNotification($user->id, 'receipt_' . $action, $title, $message);

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new ReceiptVerification($receipt, $action));
        }
    }

    public function sendPaymentReceived($payment)
    {
        $user = $payment->client->user;

        // Create in-app notification
        $this->createNotification(
            $user->id,
            'payment_received',
            'Payment Received',
            "Payment of $" . number_format($payment->amount, 2) . " has been received and processed."
        );

        // Send email notification
        if ($user->email) {
            Mail::to($user->email)->send(new PaymentReceived($payment));
        }
    }

    private function createNotification($userId, $type, $title, $message, $fuelRequest = null)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'fuel_request_id' => $fuelRequest ? $fuelRequest->id : null,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $fuelRequest ? [
                'request_id' => $fuelRequest->id,
                'client_id' => $fuelRequest->client_id,
                'station_id' => $fuelRequest->station_id,
                'vehicle_plate' => $fuelRequest->vehicle->plate_number
            ] : []
        ]);

        // Broadcast real-time notification
        broadcast(new NotificationSent($notification));

        return $notification;
    }

    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead($notificationId, $userId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
