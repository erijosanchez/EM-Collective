<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ─── Dashboard cuenta ─────────────────────────────────────────────────

    public function index()
    {
        $user          = auth()->user();
        $recentOrders  = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $totalOrders   = Order::where('user_id', $user->id)->count();
        $totalSpent    = Order::where('user_id', $user->id)->where('payment_status', 'paid')->sum('total');
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        return view('shop.account.index', compact(
            'user',
            'recentOrders',
            'totalOrders',
            'totalSpent',
            'wishlistCount'
        ));
    }

    // ─── Mis pedidos ──────────────────────────────────────────────────────

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('shop.account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $order->load('items.product');
        return view('shop.account.order-show', compact('order'));
    }

    // ─── Wishlist ─────────────────────────────────────────────────────────

    public function wishlist()
    {
        $products = auth()->user()
            ->wishlist()
            ->with(['category', 'images', 'variants'])
            ->paginate(12);

        return view('shop.account.wishlist', compact('products'));
    }

    public function wishlistToggle(int $productId)
    {
        $added = Wishlist::toggle(auth()->id(), $productId);

        if (request()->ajax()) {
            return response()->json([
                'added'   => $added,
                'message' => $added ? 'Agregado a wishlist' : 'Eliminado de wishlist',
            ]);
        }

        return back()->with('success', $added ? 'Agregado a tu lista de deseos.' : 'Eliminado de tu lista de deseos.');
    }

    // ─── Direcciones ──────────────────────────────────────────────────────

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('shop.account.addresses', compact('addresses'));
    }

    public function addressStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'phone'      => 'required|string|max:20',
            'dni'        => 'nullable|string|max:12',
            'department' => 'required|string|max:60',
            'province'   => 'required|string|max:60',
            'district'   => 'required|string|max:60',
            'address'    => 'required|string|max:200',
            'reference'  => 'nullable|string|max:200',
            'is_default' => 'nullable|boolean',
            'label'      => 'nullable|string|max:40',
        ]);

        $data['user_id']    = auth()->id();
        $data['is_default'] = $request->boolean('is_default');

        Address::create($data);
        return back()->with('success', 'Dirección guardada.');
    }

    public function addressDestroy(Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->delete();
        return back()->with('success', 'Dirección eliminada.');
    }

    public function addressSetDefault(Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Dirección predeterminada actualizada.');
    }

    // ─── Perfil ───────────────────────────────────────────────────────────

    public function profile()
    {
        return view('shop.account.profile', ['user' => auth()->user()]);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'gender'    => 'nullable|in:male,female,other',
            'newsletter' => 'nullable|boolean',
        ]);

        $data['newsletter'] = $request->boolean('newsletter');
        $user->update($data);

        return back()->with('success', 'Perfil actualizado.');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
