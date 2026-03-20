<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateBanner($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner creado correctamente.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validateBanner($request);

        if ($request->hasFile('image')) {
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) Storage::disk('public')->delete($banner->mobile_image);
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner actualizado.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) Storage::disk('public')->delete($banner->image);
        if ($banner->mobile_image) Storage::disk('public')->delete($banner->mobile_image);

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner eliminado.');
    }

    protected function validateBanner(Request $request): array
    {
        $data = $request->validate([
            'title'         => 'required|string|max:150',
            'subtitle'      => 'nullable|string|max:200',
            'button_text'   => 'nullable|string|max:50',
            'button_url'    => 'nullable|string|max:300',
            'image'         => 'nullable|image|max:4096',
            'mobile_image'  => 'nullable|image|max:2048',
            'position'      => 'required|in:hero,mid_home,category',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
            'starts_at'     => 'nullable|date',
            'ends_at'       => 'nullable|date|after:starts_at',
        ]);

        $data['is_active']   = $request->boolean('is_active', true);
        $data['sort_order']  = $data['sort_order'] ?? 0;

        return $data;
    }
}
