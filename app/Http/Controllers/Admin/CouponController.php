<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::active()->parents()->ordered()->get();
        return view('admin.coupons.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);
        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupón creado correctamente.');
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::active()->parents()->ordered()->get();
        return view('admin.coupons.form', compact('coupon', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateCoupon($request, $coupon->id);
        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupón actualizado.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cupón eliminado.');
    }

    protected function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        $codeUnique = 'unique:coupons,code' . ($ignoreId ? ",{$ignoreId}" : '');

        $data = $request->validate([
            'code'                 => "required|string|max:50|{$codeUnique}",
            'description'          => 'nullable|string|max:300',
            'type'                 => 'required|in:percentage,fixed',
            'value'                => 'required|numeric|min:0',
            'min_order_amount'     => 'nullable|numeric|min:0',
            'max_discount'         => 'nullable|numeric|min:0',
            'usage_limit'          => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'category_id'          => 'nullable|exists:categories,id',
            'is_active'            => 'nullable|boolean',
            'starts_at'            => 'nullable|date',
            'expires_at'           => 'nullable|date|after:starts_at',
        ]);

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
