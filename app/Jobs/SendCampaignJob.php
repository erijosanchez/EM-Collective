<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\Campaign;
use App\Models\CampaignSend;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(public Campaign $campaign) {}

    public function handle(): void
    {
        $campaign = $this->campaign;
        $campaign->update(['status' => 'sending']);

        $recipients = $this->getRecipients($campaign->segment);

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            // Throttle: 1 email cada 100ms para no saturar
            usleep(100000);

            $token = Str::random(40);

            try {
                // Registrar envío
                $send = CampaignSend::create([
                    'campaign_id'    => $campaign->id,
                    'email'          => $recipient['email'],
                    'name'           => $recipient['name'],
                    'tracking_token' => $token,
                    'status'         => 'sending',
                ]);

                Mail::to($recipient['email'], $recipient['name'])
                    ->send(new CampaignMail($campaign, $token));

                $send->update(['status' => 'sent', 'sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                if (isset($send)) {
                    $send->update(['status' => 'failed']);
                }
                $failed++;
            }
        }

        $campaign->update([
            'status'   => 'sent',
            'sent_at'  => now(),
            'sent_count' => $sent,
        ]);
    }

    protected function getRecipients(string $segment): array
    {
        $recipients = [];

        switch ($segment) {
            case 'newsletter':
                NewsletterSubscriber::where('is_active', true)
                    ->select('email', 'name')
                    ->get()
                    ->each(fn($s) => $recipients[] = ['email' => $s->email, 'name' => $s->name ?? '']);
                break;

            case 'registered':
                User::where('is_active', true)
                    ->where('role', 'customer')
                    ->select('email', 'name')
                    ->get()
                    ->each(fn($u) => $recipients[] = ['email' => $u->email, 'name' => $u->name]);
                break;

            case 'buyers':
                User::whereHas('orders', fn($q) => $q->where('payment_status', 'paid'))
                    ->select('email', 'name')
                    ->get()
                    ->each(fn($u) => $recipients[] = ['email' => $u->email, 'name' => $u->name]);
                break;

            default: // 'all'
                // Registrados
                User::where('is_active', true)
                    ->select('email', 'name')
                    ->get()
                    ->each(fn($u) => $recipients[] = ['email' => $u->email, 'name' => $u->name]);

                // Suscriptores de newsletter que no sean usuarios registrados
                NewsletterSubscriber::where('is_active', true)
                    ->whereNotIn('email', User::pluck('email'))
                    ->select('email', 'name')
                    ->get()
                    ->each(fn($s) => $recipients[] = ['email' => $s->email, 'name' => $s->name ?? '']);
                break;
        }

        // Eliminar duplicados por email
        $seen  = [];
        $unique = [];
        foreach ($recipients as $r) {
            if (!in_array($r['email'], $seen)) {
                $seen[]   = $r['email'];
                $unique[] = $r;
            }
        }

        return $unique;
    }
}
