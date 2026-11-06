<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class WhatsAppService
{
    protected $ownerPhone;

    public function __construct()
    {
        $this->ownerPhone = env('OWNER_WHATSAPP_PHONE', '255712345678'); // Default owner phone
    }

    public function notifyOwner(string $message)
    {
        // For now, we log the message. 
        // Later this can be integrated with a WhatsApp API provider like Beem, Twilio, or Chat-API.
        Log::info("WHATSAPP NOTIFICATION TO {$this->ownerPhone}: " . $message);
        
        // Example Beem integration (commented out):
        /*
        $client = new Client();
        $client->post('https://apisms.beem.africa/v1/send', [
            'auth' => [env('BEEM_API_KEY'), env('BEEM_SECRET_KEY')],
            'json' => [
                'source_addr' => 'INFO',
                'schedule_time' => '',
                'encoding' => 'AUTO',
                'message' => $message,
                'recipients' => [
                    ['recipient_id' => '1', 'dest_addr' => $this->ownerPhone],
                ],
            ],
        ]);
        */

        return true;
    }

    public function sendOrderNotification($order)
    {
        $itemsContent = "";
        foreach ($order->items as $item) {
            $itemsContent .= "- {$item->product->name} x{$item->quantity}\n";
        }

        $message = "New Order #{$order->id} from {$order->customer_name} ({$order->customer_phone})\n\n";
        $message .= "Items:\n{$itemsContent}\n";
        $message .= "Total: TSh " . number_format($order->total_amount, 2) . "\n";
        $message .= "Notes: {$order->notes}\n";
        $message .= "\nPlease process this order.";

        return $this->notifyOwner($message);
    }
}
