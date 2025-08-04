@extends('customer.layouts.master')

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Checkout</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item active text-primary">Silakan isi detail pemesanan Anda</li>
        </ol>
    </div>
    <!-- Single Page Header End -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Detail Pembayaran</h1>
            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nama Lengkap<sup>*</sup></label>
                                    <input type="text" name='fullname' class="form-control"
                                        placeholder="Masukkan nama Anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor WhatsApp<sup>*</sup></label>
                                    <input type="text" name="phone" class="form-control"
                                        placeholder="Masukkan nomor WhatsApp Anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor Meja<sup>*</sup></label>
                                    <input type="text" class="form-control"
                                        value="{{ $tableNumber ?? 'Tidak ada nomor meja' }}" disabled required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-item">
                                    <textarea name="text" class="form-control" spellcheck="false" cols="30" rows="5"
                                        placeholder="Catatan pesanan (Opsional)"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <br><br>
                                <h4 class="mb-4">Detail Pesanan</h4>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Gambar</th>
                                            <th scope="col">Menu</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subTotal = 0;
                                        @endphp
                                        @foreach (session('cart') as $item)
                                            @php
                                                $itemTotal = $item['price'] * $item['quantity'];
                                                $subTotal += $itemTotal;
                                            @endphp
                                            <tr>
                                                <th scope="row">
                                                    <div class="d-flex align-items-center mt-2">
                                                        {{-- <img src="https://images.unsplash.com/photo-1591325418441-ff678baf78ef"
                                                            class="img-fluid rounded-circle"
                                                            style="width: 100px; height: 90px; object-fit: cover;"
                                                            alt=""> --}}
                                                        <img src="{{ asset('img_item_upload/' . $item['image']) }}"
                                                            class="img-fluid me-5 rounded-circle"
                                                            style="width: 80px; height: 80px;" alt=""
                                                            onerror="this.onerror=null;this.src='{{ $item['image'] }}';">
                                                    </div>
                                                </th>
                                                {{-- <td class="py-5">Ichiraku Ramen</td>
                                            <td class="py-5">Rp25.000,00</td>
                                            <td class="py-5">1</td>
                                            <td class="py-5">Rp25.000,00</td> --}}
                                                <td class="py-5">{{ $item['name'] }}</td>
                                                <td class="py-5">{{ 'Rp' . number_format($item['price'], 0, ',', '.') }}
                                                </td>
                                                <td class="py-5">{{ $item['quantity'] }}</td>
                                                <td class="py-5">
                                                    {{ 'Rp' . number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                                <th scope="row">
                                                    <div class="d-flex align-items-center mt-2">
                                                        <img src="https://images.unsplash.com/photo-1543392765-620e968d2162?q=80&w=1987&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA==" class="img-fluid rounded-circle" style="width: 100px; height: 90px; object-fit: cover;" alt="">
                                                    </div>
                                                </th>
                                                <td class="py-5">Beef Burger</td>
                                                <td class="py-5">Rp40.000,00</td>
                                                <td class="py-5">1</td>
                                                <td class="py-5">Rp40.000,00</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <div class="d-flex align-items-center mt-2">
                                                        <img src="https://images.unsplash.com/photo-1579954115545-a95591f28bfc" class="img-fluid rounded-circle" style="width: 100px; height: 90px; object-fit: cover;" alt="">
                                                    </div>
                                                </th>
                                                <td class="py-5">Big Banana</td>
                                                <td class="py-5">Rp20.000,00</td>
                                                <td class="py-5">1</td>
                                                <td class="py-5">Rp20.000,00</td>
                                            </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @php
                        $tax = $subTotal * 0.1;
                        $total = $subTotal + $tax;
                    @endphp

                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="row g-4 align-items-center py-3">
                            <div class="col-lg-12">
                                <div class="bg-light rounded">
                                    <div class="p-4">
                                        <h3 class="display-6 mb-4">Total <span class="fw-normal">Pesanan</span></h3>
                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="mb-0 me-4">Subtotal</h5>
                                            <p class="mb-0">{{ 'Rp' . number_format($subTotal, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0 me-4">Pajak (10%)</p>
                                            <div class="">
                                                <p class="mb-0">{{ 'Rp' . number_format($tax, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                                        <h4 class="mb-0 ps-4 me-4">Total</h4>
                                        <h5 class="mb-0 pe-4">{{ 'Rp' . number_format($total, 0, ',', '.') }}</h5>
                                    </div>

                                    <div class="py-4 mb-4 d-flex justify-content-between">
                                        <h5 class="mb-0 ps-4 me-4">Metode Pembayaran</h5>
                                        <div class="mb-0 pe-4 mb-3 pe-5">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input bg-primary border-0"
                                                    id="qris" name="payment_method" value="qris">
                                                <label class="form-check-label" for="qris">QRIS</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input bg-primary border-0"
                                                    id="cash" name="payment_method" value="tunai">
                                                <label class="form-check-label" for="cash">Tunai</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit"
                                        class="btn border-secondary py-3 text-uppercase text-primary">Konfirmasi
                                        Pesanan</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
