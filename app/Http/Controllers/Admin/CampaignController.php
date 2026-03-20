<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(20);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateCampaign($request);
        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaña creada correctamente.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.form', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'No puedes editar una campaña ya enviada.');
        }

        $data = $this->validateCampaign($request);
        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaña actualizada.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return back()->with('error', 'No puedes eliminar una campaña en envío.');
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaña eliminada.');
    }

    public function send(Campaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Esta campaña ya fue enviada.');
        }

        if ($campaign->status === 'sending') {
            return back()->with('error', 'Esta campaña ya está en proceso de envío.');
        }

        SendCampaignJob::dispatch($campaign);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaña puesta en cola para envío. Revisa el progreso en unos momentos.');
    }

    protected function validateCampaign(Request $request): array
    {
        return $request->validate([
            'name'     => 'required|string|max:150',
            'subject'  => 'required|string|max:200',
            'content'  => 'required|string',
            'segment'  => 'required|in:all,newsletter,registered,buyers',
        ]);
    }
}
