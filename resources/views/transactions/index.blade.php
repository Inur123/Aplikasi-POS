<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir - Transaksi</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-lg font-semibold text-gray-900">POS Kasir</h1>
                        <p class="text-xs text-gray-500">Kasir001 - Shift Malam</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Shift dimulai: 20:57</p>
                        <p class="text-sm font-medium text-green-600">Shift Aktif</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm hover:bg-red-200">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Menu Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Menu Makanan</h2>
                        <div class="flex space-x-2">
                            <!-- Tombol Semua -->
                            <a href="{{ route('transactions.index') }}"
                                class="px-3 py-1 rounded-lg text-sm transition
                    {{ request('category') ? 'bg-gray-100 text-gray-700' : 'bg-indigo-100 text-indigo-700' }}">
                                Semua
                            </a>

                            <!-- Tombol berdasarkan kategori -->
                            @foreach ($categories as $category)
                                <a href="{{ route('transactions.index', ['category' => $category->id]) }}"
                                    class="px-3 py-1 rounded-lg text-sm transition
                        {{ request('category') == $category->id ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @forelse ($products as $product)
                            <div onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                                class="border border-gray-200 rounded-lg p-3 hover:shadow-md cursor-pointer transition-shadow">
                                <div
                                    class="aspect-square bg-gray-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="object-cover w-full h-full rounded-lg">
                                    @else
                                        <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                                    @endif
                                </div>
                                <h3 class="font-medium text-sm text-gray-900">{{ $product->name }}</h3>
                                <p class="text-indigo-600 font-semibold">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 col-span-4">Tidak ada produk ditemukan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Cart & Payment -->
            <div class="space-y-4">
                <!-- Cart -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pesanan</h3>
                    <div id="cart-items-container" class="mb-4">
                        <div id="cart-items"
                            class="space-y-3 max-h-[15rem] overflow-y-auto [&::-webkit-scrollbar]:w-[6px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-track]:rounded [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded [&::-webkit-scrollbar-thumb:hover]:bg-gray-400">
                            <!-- Items will be added here dynamically -->
                            <p id="empty-cart-message" class="text-gray-500 text-center py-8">Belum ada item dipilih</p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2 mt-2">
                            <span>Total:</span>
                            <span id="total">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembayaran</h3>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="selectPaymentMethod('cash')" id="cash-btn" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-money-bill text-green-600 mb-1"></i>
                                <div>Tunai</div>
                            </button>
                            <button onclick="selectPaymentMethod('card')" id="card-btn" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-credit-card text-blue-600 mb-1"></i>
                                <div>Kartu</div>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="selectPaymentMethod('qris')" id="qris-btn" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-qrcode text-purple-600 mb-1"></i>
                                <div>QRIS</div>
                            </button>
                            <button onclick="selectPaymentMethod('transfer')" id="transfer-btn" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                                <i class="fas fa-university text-indigo-600 mb-1"></i>
                                <div>Transfer</div>
                            </button>
                        </div>

                        <div id="payment-input" class="hidden">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                            <input type="number" id="amount" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <button id="process-payment"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 disabled:bg-gray-300"
                            disabled onclick="processPayment()">
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script>
    let cart = [];
    let selectedPaymentMethod = null;

    // Add to cart function
    function addToCart(productId, productName, productPrice) {
        const existingItem = cart.find(item => item.id === productId);

        if (existingItem) {
            existingItem.quantity += 1;
            existingItem.subtotal = existingItem.quantity * existingItem.price;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1,
                subtotal: productPrice
            });
        }

        updateCartDisplay();
    }

    // Update cart display
    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        const processPaymentBtn = document.getElementById('process-payment');

        // Clear the cart display
        cartItemsContainer.innerHTML = '';

        if (cart.length === 0) {
            emptyCartMessage.style.display = 'block';
            subtotalElement.textContent = 'Rp 0';
            totalElement.textContent = 'Rp 0';
            processPaymentBtn.disabled = true;
            return;
        }

        emptyCartMessage.style.display = 'none';

        // Calculate totals
        let subtotal = 0;

        // Add each item to the cart display
        cart.forEach(item => {
            subtotal += item.subtotal;

            const itemElement = document.createElement('div');
            itemElement.className = 'flex justify-between items-center p-2 bg-gray-50 rounded';
            itemElement.innerHTML = `
                <div>
                    <p class="font-medium">${item.name}</p>
                    <p class="text-sm text-gray-500">Rp ${formatNumber(item.price)}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-2 py-1 bg-gray-200 rounded" onclick="decreaseQuantity(${item.id}, event)">-</button>
                    <span>${item.quantity}</span>
                    <button class="px-2 py-1 bg-gray-200 rounded" onclick="increaseQuantity(${item.id}, event)">+</button>
                    <button class="px-2 py-1 bg-red-100 text-red-600 rounded ml-2" onclick="removeItem(${item.id}, event)">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            `;

            cartItemsContainer.appendChild(itemElement);
        });

        // Update totals
        subtotalElement.textContent = `Rp ${formatNumber(subtotal)}`;
        totalElement.textContent = `Rp ${formatNumber(subtotal)}`;

        // Enable payment button if payment method selected and cart not empty
        processPaymentBtn.disabled = !(selectedPaymentMethod && cart.length > 0);
    }

    // Helper function to format numbers
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Cart item quantity functions
    function increaseQuantity(productId, event) {
        event.stopPropagation();
        const item = cart.find(item => item.id === productId);
        if (item) {
            item.quantity += 1;
            item.subtotal = item.quantity * item.price;
            updateCartDisplay();
        }
    }

    function decreaseQuantity(productId, event) {
        event.stopPropagation();
        const item = cart.find(item => item.id === productId);
        if (item && item.quantity > 1) {
            item.quantity -= 1;
            item.subtotal = item.quantity * item.price;
            updateCartDisplay();
        }
    }

    function removeItem(productId, event) {
        event.stopPropagation();
        cart = cart.filter(item => item.id !== productId);
        updateCartDisplay();
    }

    // Payment method selection
    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;

        // Reset all buttons
        document.getElementById('cash-btn').classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
        document.getElementById('card-btn').classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
        document.getElementById('qris-btn').classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
        document.getElementById('transfer-btn').classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');

        // Highlight selected button
        document.getElementById(`${method}-btn`).classList.add('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');

        // Show/hide payment input
        const paymentInput = document.getElementById('payment-input');
        if (method === 'cash') {
            paymentInput.classList.remove('hidden');
            // Auto-fill with total amount
            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('amount').value = total;
        } else {
            paymentInput.classList.add('hidden');
        }

        // Enable/disable process payment button
        document.getElementById('process-payment').disabled = cart.length === 0;
    }

    // Process payment
    async function processPayment() {
        try {
            if (cart.length === 0) {
                alert('Keranjang belanja kosong!');
                return;
            }

            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran!');
                return;
            }

            // For cash payment, validate amount
            let paymentAmount;
            const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);

            if (selectedPaymentMethod === 'cash') {
                const amountInput = document.getElementById('amount');
                paymentAmount = parseFloat(amountInput.value);

                if (isNaN(paymentAmount)) {
                    alert('Masukkan jumlah pembayaran yang valid!');
                    return;
                }

                if (paymentAmount < subtotal) {
                    alert('Jumlah pembayaran kurang dari total!');
                    return;
                }
            } else {
                paymentAmount = subtotal;
            }

            // Prepare data for AJAX request
            const transactionData = {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity
                })),
                payment_method: selectedPaymentMethod,
                payment: paymentAmount
            };

            // Send AJAX request
            const response = await fetch('{{ route("transactions.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(transactionData)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
            }

            if (data.success) {
                alert(`Transaksi berhasil! No. Invoice: ${data.invoice}`);
                resetTransaction();
            } else {
                throw new Error(data.message || 'Transaksi gagal diproses');
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        }
    }

    // Reset transaction after successful payment
    function resetTransaction() {
        cart = [];
        selectedPaymentMethod = null;
        updateCartDisplay();
        document.getElementById('process-payment').disabled = true;
        document.getElementById('payment-input').classList.add('hidden');
        document.getElementById('amount').value = '';

        // Reset payment method buttons
        const paymentButtons = ['cash', 'card', 'qris', 'transfer'];
        paymentButtons.forEach(method => {
            document.getElementById(`${method}-btn`).classList.remove(
                'bg-indigo-100', 'text-indigo-700', 'border-indigo-300'
            );
        });
    }
</script>
</body>

</html>
