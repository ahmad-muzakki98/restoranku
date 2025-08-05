<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;



use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');
        if ($tableNumber) {
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('customer.menu', compact('items', 'tableNumber'));
    }

    //Cart
    public function cart()
    {
        $cart = Session::get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu tidak ditemukan'
            ]);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => $menu->img,
                'quantity' => 1
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQuantity = $request->input('quantity');

        if ($newQuantity <= 0) {
            return response()->json(['success' => false]);
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $newQuantity;
            Session::put('cart', $cart);
            Session::flash('success', 'Jumlah item berhasil diperbarui');

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function removeCart(Request $request)
    {
        $itemId = $request->input('id');

        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
            Session::flash('success', 'Item berhasil dihapus dari keranjang');

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function clearCart()
    {
        Session::forget('cart');
        // Session::flash('success', 'Keranjang berhasil dikosongkan');
        // return response()->json(['success' => true]);
        return redirect()->route('cart')->with('success', 'Keranjang berhasil dikosongkan');
    }

    // Checkout
    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong');
        }

        $tableNumber = Session::get('tableNumber');

        return view('customer.checkout', compact('cart', 'tableNumber'));
    }

    // public function storeOrder(Request $request)
    // {
    //     $cart = Session::get('cart', []);

    //     // return response()->json($cart);

    //     // Log::debug('Isi cart:', $cart);


    //     $tableNumber = Session::get('tableNumber');

    //     if (empty($cart)) {
    //         return redirect()->route('cart')->with('error', 'Keranjang masih kosong');
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'fullname' => 'required|string|max:255',
    //         'phone' => 'required|string|max:15',
    //     ]);


    //     if ($validator->fails()) {
    //         return redirect()->route('checkout')->withErrors($validator);
    //     }

    //     // if ($validator->fails()) {
    //     //     if ($request->expectsJson()) {
    //     //         return response()->json([
    //     //             'status' => 'error',
    //     //             'message' => 'Validasi gagal',
    //     //             'errors' => $validator->errors()
    //     //         ], 422);
    //     //     } else {
    //     //         return redirect()->route('checkout')->withErrors($validator);
    //     //     }
    //     // }

    //     $total = 0;
    //     foreach ($cart as $item) {
    //         $total += $item['price'] * $item['quantity'];
    //     }

    //     // $itemDetails = [];
    //     $totalAmount = 0;

    //     foreach ($cart as $item) {
    //         $totalAmount += $item['price'] * $item['quantity'];

    //         // if (!isset($item['id'])) {
    //         //     Log::error("Cart item missing ID: " . json_encode($item));
    //         //     continue; // skip item ini
    //         // }

    //         $itemDetails[] = [
    //             'id' => $item['id'],
    //             'price' => (int) ($item['price'] + ($item['price'] * 0.1)),
    //             'quantity' => $item['quantity'],
    //             'name' => substr($item['name'], 0, 50),
    //         ];
    //     }

    //     $user = User::firstorCreate([
    //         'fullname' => $request->input('fullname'),
    //         'phone' => $request->input('phone'),
    //         'role_id' => 4
    //     ]);

    //     $order = Order::create([
    //         'order_code' => 'ORD-' . $tableNumber . '-' . time(),
    //         'user_id' => $user->id,
    //         'subtotal' => $totalAmount,
    //         'tax' => 0.1 * $totalAmount,
    //         'grand_total' => $totalAmount + (0.1 * $totalAmount),
    //         'status' => 'pending',
    //         'table_number' => $tableNumber,
    //         'payment_method' => $request->payment_method,
    //         'note' => $request->note,

    //     ]);

    //     foreach ($cart as $itemId => $item) {
    //         // foreach ($cart as $item) {
    //         OrderItem::create([
    //             'order_id' => $order->id,
    //             'item_id' => $item['id'],
    //             'quantity' => $item['quantity'],
    //             'price' => $item['price'] * $item['quantity'],
    //             'tax' => 0.1 * $item['price'] * $item['quantity'],
    //             'total_price' => ($item['price'] * $item['quantity']) + (0.1 * $item['price'] * $item['quantity']),
    //         ]);
    //     };

    //     Session::forget('cart');

    //     // Implementasi Midtrans Payment Gateway

    //     if ($request->payment_method == 'tunai') {
    //         return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Pesanan berhasil dibuat');
    //     } else {
    //         \Midtrans\Config::$serverKey = config('midtrans.server_key');
    //         \Midtrans\Config::$isProduction = config('midtrans.is_production');
    //         \Midtrans\Config::$isSanitized = true;
    //         \Midtrans\Config::$is3ds = true;
    //         // \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
    //         // \Midtrans\Config::$curlOptions = [
    //         //     CURLOPT_CAINFO => base_path('C:/xampp/php/extras/ssl/cacert.pem'),
    //         // ];

    //         $params = [
    //             'transaction_details' => [
    //                 'order_id' => $order->order_code,
    //                 'gross_amount' => (int) $order->grand_total,
    //             ],
    //             'item_details' => $itemDetails,
    //             'customer_details' => [
    //                 'first_name' => $user->fullname ?? 'Guest',
    //                 'phone' => $user->phone,
    //             ],
    //             'payment_type' => 'qris',
    //         ];

    //         try {
    //             $snapToken = \Midtrans\Snap::getSnapToken($params);
    //             return response()->json([
    //                 'status' => 'success',
    //                 'snap_token' => $snapToken,
    //                 'order_code' => $order->order_code,
    //             ]);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Gagal membuat pesanan. Silakan coba lagi',
    //             ]);
    //         }
    //         // catch (\Exception $e) {
    //         //     return response()->json([
    //         //         'status' => 'error',
    //         //         'message' => 'Gagal membuat pesanan. Silakan coba lagi',
    //         //         'error_detail' => $e->getMessage(), // Tambahkan ini untuk debug
    //         //     ], 500);
    //         // }
    //     }
    // }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        $tableNumber = Session::get('tableNumber');

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Disini validasi dulu.
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:15'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];

            $itemDetails[] = [
                'id'       => $item['id'],
                'price'    => (int) ($item['price'] + ($item['price'] * 0.1)),
                'quantity' => $item['quantity'],
                'name'     => substr($item['name'], 0, 50),
            ];
        }

        $user = \App\Models\User::firstOrCreate(
            ['phone' => $request->phone],
            ['fullname' => $request->fullname, 'role_id' => 4]
        );

        $order = Order::create([
            'order_code' => 'ORD-' . strtoupper(uniqid()),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => $totalAmount * 0.1,
            'grand_total' => $totalAmount + ($totalAmount * 0.1),
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        foreach ($cart as $itemId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'] * $item['quantity'],
                'tax' => ($item['price'] * $item['quantity']) * 0.1,
                'total_price' => ($item['price'] * $item['quantity'] + (($item['price'] * $item['quantity']) * 0.1))
            ]);
        }

        Session::forget('cart');

        if ($request->payment_method === 'tunai') {
            return redirect()->route('checkout.success', ['orderId' => $order->order_code]);
        } else {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $user->fullname ?? 'Guest',
                    'phone' => $user->phone,
                ],
                'payment_type' => 'qris',
                'item_details' => $itemDetails,
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                return response()->json(['snap_token' => $snapToken, 'order_code' => $order->order_code]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    // public function checkoutSuccess($orderId)
    // {
    //     $order = Order::where('order_code', $orderId)->first();

    //     if (!$order) {
    //         return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan');
    //     }

    //     $orderItems = OrderItem::where('order_id', $order->id)->get();

    //     if ($order->payment_method == 'qris') {
    //         $order->status = 'settlement';
    //         $order->save();
    //     }

    //     return view('customer.success', compact('order', 'orderItems'));
    // }
    public function checkoutSuccess(Request $request, $orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Order tidak ditemukan.');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method === 'qris') {
            $order->status = 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));
    }
}
