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
                        <p class="text-xs text-gray-500">Shift dimulai: {{ date('H:i') }}</p>
                        <p class="text-sm font-medium text-green-600">Shift Aktif</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm hover:bg-red-200 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Menu Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Menu Makanan</h2>
                        <div class="flex space-x-2 flex-wrap">
                            <!-- All Button -->
                            <a href="{{ route('transactions.index') }}"
                                class="px-3 py-1 rounded-lg text-sm transition
                    {{ !request('category') ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                Semua
                            </a>

                            <!-- Category Buttons -->
                            @foreach ($categories as $category)
                                <a href="{{ route('transactions.index', ['category' => $category->id]) }}"
                                    class="px-3 py-1 rounded-lg text-sm transition
                        {{ request('category') == $category->id ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                   <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
    @forelse ($products as $product)
        <div onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
            class="border border-gray-200 rounded-lg p-3 hover:shadow-md cursor-pointer transition-all duration-200">
            <div class="aspect-square bg-gray-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="object-cover w-full h-full rounded-lg">
                @else
                    <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                @endif
            </div>
            <h3 class="font-medium text-sm text-gray-900 truncate">{{ $product->name }}</h3>
            <p class="text-indigo-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
    @empty
        <div class="col-span-full text-center py-8">
            <i class="fas fa-box-open text-gray-400 text-4xl mb-2"></i>
            <p class="text-gray-500">Tidak ada produk ditemukan.</p>
        </div>
    @endforelse
</div>
                </div>
            </div>

            <!-- Cart & Payment -->
            <div class="space-y-4">
                <!-- Cart -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan</h3>
                        <button onclick="clearCart()" id="clear-cart-btn" class="text-red-600 text-sm hover:text-red-800 hidden">
                            <i class="fas fa-trash mr-1"></i>Kosongkan
                        </button>
                    </div>

                    <div id="cart-items-container" class="mb-4">
                        <div id="cart-items"
                            class="space-y-3 max-h-[15rem] overflow-y-auto [&::-webkit-scrollbar]:w-[6px] [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-track]:rounded [&::-webkit-scrollbar-thumb]:bg-gray-300 [&::-webkit-scrollbar-thumb]:rounded [&::-webkit-scrollbar-thumb:hover]:bg-gray-400">
                            <!-- Items will be added here dynamically -->
                            <div id="empty-cart-message" class="text-center py-8">
                                <i class="fas fa-shopping-cart text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500">Belum ada item dipilih</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-sm mb-1">
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
                            <button onclick="selectPaymentMethod('cash')" id="cash-btn"
                                class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm transition-all">
                                <i class="fas fa-money-bill text-green-600 mb-1 block"></i>
                                <div>Tunai</div>
                            </button>
                            <button onclick="selectPaymentMethod('card')" id="card-btn"
                                class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm transition-all">
                                <i class="fas fa-credit-card text-blue-600 mb-1 block"></i>
                                <div>Kartu</div>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="selectPaymentMethod('qris')" id="qris-btn"
                                class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm transition-all">
                                <i class="fas fa-qrcode text-purple-600 mb-1 block"></i>
                                <div>QRIS</div>
                            </button>
                            <button onclick="selectPaymentMethod('transfer')" id="transfer-btn"
                                class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm transition-all">
                                <i class="fas fa-university text-indigo-600 mb-1 block"></i>
                                <div>Transfer</div>
                            </button>
                        </div>

                        <div id="payment-input" class="hidden">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                            <input type="tel" id="amount"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan jumlah pembayaran">
                            <div id="change-display" class="mt-2 text-sm text-gray-600 hidden">
                                Kembalian: <span id="change-amount" class="font-semibold text-green-600">Rp 0</span>
                            </div>
                        </div>

                        <button id="process-payment"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all"
                            disabled onclick="processPayment()">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 text-center">
            <i class="fas fa-spinner fa-spin text-indigo-600 text-3xl mb-3"></i>
            <p class="text-gray-700">Memproses pembayaran...</p>
        </div>
    </div>

   <script>
    let cart = [];
    let selectedPaymentMethod = null;

    // Add to cart function (no stock check)
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
        showNotification(`${productName} ditambahkan ke keranjang`, 'success');
    }

    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        const processPaymentBtn = document.getElementById('process-payment');
        const clearCartBtn = document.getElementById('clear-cart-btn');

        cartItemsContainer.innerHTML = '';

        if (cart.length === 0) {
            if (emptyCartMessage) {
                emptyCartMessage.style.display = 'block';
                cartItemsContainer.appendChild(emptyCartMessage);
            }
            subtotalElement.textContent = 'Rp 0';
            totalElement.textContent = 'Rp 0';
            processPaymentBtn.disabled = true;
            if (clearCartBtn) clearCartBtn.classList.add('hidden');
            return;
        }

        if (clearCartBtn) clearCartBtn.classList.remove('hidden');

        let subtotal = 0;

        cart.forEach(item => {
            subtotal += item.subtotal;

            const itemElement = document.createElement('div');
            itemElement.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
            itemElement.innerHTML = `
                <div class="flex-1">
                    <p class="font-medium text-sm">${item.name}</p>
                    <p class="text-xs text-gray-500">Rp ${formatNumber(item.price)} × ${item.quantity}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition"
                            onclick="decreaseQuantity(${item.id}, event)" ${item.quantity <= 1 ? 'disabled' : ''}>
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="w-8 text-center font-medium">${item.quantity}</span>
                    <button class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition"
                            onclick="increaseQuantity(${item.id}, event)">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                    <button class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center hover:bg-red-200 transition ml-2"
                            onclick="removeItem(${item.id}, event)">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            `;

            cartItemsContainer.appendChild(itemElement);
        });

        subtotalElement.textContent = `Rp ${formatNumber(subtotal)}`;
        totalElement.textContent = `Rp ${formatNumber(subtotal)}`;
        processPaymentBtn.disabled = !(selectedPaymentMethod && cart.length > 0);

        if (selectedPaymentMethod === 'cash') {
            calculateChange();
        }
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

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
        showNotification('Item dihapus dari keranjang', 'info');
    }

    function clearCart() {
        if (cart.length === 0) return;

        if (confirm('Yakin ingin mengosongkan keranjang?')) {
            cart = [];
            updateCartDisplay();
            showNotification('Keranjang dikosongkan', 'info');
        }
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;

        const buttons = ['cash', 'card', 'qris', 'transfer'];
        buttons.forEach(btn => {
            const btnElement = document.getElementById(`${btn}-btn`);
            if (btnElement) {
                btnElement.classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
            }
        });

        const selectedBtn = document.getElementById(`${method}-btn`);
        if (selectedBtn) {
            selectedBtn.classList.add('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
        }

        const paymentInput = document.getElementById('payment-input');
        const changeDisplay = document.getElementById('change-display');

        if (method === 'cash') {
            if (paymentInput) paymentInput.classList.remove('hidden');
            const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
            const amountInput = document.getElementById('amount');
            if (amountInput) amountInput.value = total;
            calculateChange();
        } else {
            if (paymentInput) paymentInput.classList.add('hidden');
            if (changeDisplay) changeDisplay.classList.add('hidden');
        }

        const processPaymentBtn = document.getElementById('process-payment');
        if (processPaymentBtn) {
            processPaymentBtn.disabled = cart.length === 0;
        }
    }

    function calculateChange() {
        const amountInput = document.getElementById('amount');
        const changeDisplay = document.getElementById('change-display');
        const changeAmount = document.getElementById('change-amount');

        if (!amountInput || !changeDisplay || !changeAmount) return;

        const total = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const payment = parseFloat(amountInput.value) || 0;
        const change = payment - total;

        if (payment > 0) {
            changeDisplay.classList.remove('hidden');
            changeAmount.textContent = `Rp ${formatNumber(Math.max(0, change))}`;
            changeAmount.className = change >= 0 ? 'font-semibold text-green-600' : 'font-semibold text-red-600';
        } else {
            changeDisplay.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.addEventListener('input', calculateChange);
        }

        const messages = document.querySelectorAll('#success-message, #error-message');
        messages.forEach(message => {
            setTimeout(() => {
                if (message) message.style.display = 'none';
            }, 5000);
        });
    });

    async function processPayment() {
        try {
            if (cart.length === 0) {
                showNotification('Keranjang belanja kosong!', 'error');
                return;
            }

            if (!selectedPaymentMethod) {
                showNotification('Silakan pilih metode pembayaran!', 'error');
                return;
            }

            let paymentAmount;
            const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);

            if (selectedPaymentMethod === 'cash') {
                const amountInput = document.getElementById('amount');
                paymentAmount = parseFloat(amountInput.value);

                if (isNaN(paymentAmount) || paymentAmount <= 0) {
                    showNotification('Masukkan jumlah pembayaran yang valid!', 'error');
                    amountInput.focus();
                    return;
                }

                if (paymentAmount < subtotal) {
                    showNotification(`Jumlah pembayaran kurang! Kurang Rp ${formatNumber(subtotal - paymentAmount)}`, 'error');
                    amountInput.focus();
                    return;
                }
            } else {
                paymentAmount = subtotal;
            }

            const loadingModal = document.getElementById('loading-modal');
            if (loadingModal) loadingModal.classList.remove('hidden');

            const transactionData = {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity
                })),
                payment_method: selectedPaymentMethod,
                payment: paymentAmount
            };

            const response = await fetch('{{ route("transactions.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(transactionData)
            });

            const data = await response.json();

            if (loadingModal) loadingModal.classList.add('hidden');

            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
            }

            if (data.success) {
                showNotification('Transaksi berhasil diproses!', 'success');
                resetTransaction();
            } else {
                throw new Error(data.message || 'Transaksi gagal diproses');
            }
        } catch (error) {
            const loadingModal = document.getElementById('loading-modal');
            if (loadingModal) loadingModal.classList.add('hidden');
            console.error('Error:', error);
            showNotification(error.message, 'error');
        }
    }

    function resetTransaction() {
        cart = [];
        selectedPaymentMethod = null;
        updateCartDisplay();

        const processPaymentBtn = document.getElementById('process-payment');
        if (processPaymentBtn) processPaymentBtn.disabled = true;

        const paymentInput = document.getElementById('payment-input');
        if (paymentInput) paymentInput.classList.add('hidden');

        const changeDisplay = document.getElementById('change-display');
        if (changeDisplay) changeDisplay.classList.add('hidden');

        const amountInput = document.getElementById('amount');
        if (amountInput) amountInput.value = '';

        const paymentButtons = ['cash', 'card', 'qris', 'transfer'];
        paymentButtons.forEach(method => {
            const btn = document.getElementById(`${method}-btn`);
            if (btn) {
                btn.classList.remove('bg-indigo-100', 'text-indigo-700', 'border-indigo-300');
            }
        });
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

        switch(type) {
            case 'success':
                notification.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
                break;
            case 'error':
                notification.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                break;
            case 'info':
                notification.classList.add('bg-blue-100', 'text-blue-800', 'border', 'border-blue-200');
                break;
            default:
                notification.classList.add('bg-gray-100', 'text-gray-800', 'border', 'border-gray-200');
        }

        notification.innerHTML = `
            <div class="flex items-center">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-current opacity-70 hover:opacity-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
</script>

</body>

</html>
