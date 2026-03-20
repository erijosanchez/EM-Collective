<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterWelcomeMail;
use App\Models\CampaignSend;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150',
            'name'  => 'nullable|string|max:100',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower(trim($request->email))],
            [
                'name'              => $request->name,
                'is_active'         => true,
                'unsubscribe_token' => Str::random(40),
            ]
        );

        if (!$subscriber->wasRecentlyCreated && $subscriber->is_active) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ya estás suscrito a nuestro newsletter.']);
            }
            return back()->with('info', 'Ya estás suscrito a nuestro newsletter.');
        }

        if (!$subscriber->wasRecentlyCreated && !$subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        // Enviar email de bienvenida
        try {
            Mail::to($subscriber->email)->queue(new NewsletterWelcomeMail($subscriber));
        } catch (\Exception $e) {
            // No interrumpir el flujo
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => '¡Gracias por suscribirte!']);
        }

        return back()->with('success', '¡Gracias por suscribirte! Te enviamos un cupón de bienvenida.');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if ($subscriber) {
            $subscriber->update(['is_active' => false]);
        }

        return view('shop.newsletter-unsubscribed');
    }

    public function trackOpen(string $token)
    {
        $send = CampaignSend::where('tracking_token', $token)->first();

        if ($send && $send->opened_at === null) {
            $send->update([
                'opened_at' => now(),
                'status'    => 'opened',
            ]);

            // Incrementar contador en la campaña
            $send->campaign?->increment('open_count');
        }

        // Devolver un pixel transparente 1x1
        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'), 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
