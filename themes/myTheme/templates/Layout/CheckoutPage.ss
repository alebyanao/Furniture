<main class="container my-4">
    <h3 class="mb-4">Proses Pembayaran</h3>
    
    <% if $Session.CheckoutError %>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        $Session.CheckoutError
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <% end_if %>

    <% if $Session.CheckoutMessage %>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        $Session.CheckoutMessage
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <% end_if %>
     
    <% if CartItems && CartItems.Count > 0 %>
    <form method="post" action="$BaseHref/checkout/process-order" target="_blank" id="checkoutForm">
        <!-- Alamat Pengiriman -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Alamat Pengiriman</div>
            <div class="card-body">
                <% if ShippingAddress %>
                    <p class="mb-1"><strong>$ShippingAddress.ReceiverName</strong></p>
                    <p class="mb-1">$ShippingAddress.Address</p>
                    <p class="mb-0">$ShippingAddress.DistrictName, $ShippingAddress.CityName, $ShippingAddress.ProvinceName $ShippingAddress.PostalCode</p>
                    <p class="mb-0">$ShippingAddress.PhoneNumber</p>
                    <a href="$BaseHref/checkout/detail-alamat" class="btn btn-link p-0 mt-2">Ubah Alamat</a>
                <% else %>
                <p class="mb-3">Belum ada alamat yang tersedia</p>
                    <a href="$BaseHref/checkout/detail-alamat" class="btn btn-primary mt-2">Tambah Alamat</a>
                <% end_if %>
            </div>
        </div>

        <!-- Produk di Keranjang dengan Quantity Controls -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Produk Dipesan</span>
                <small class="text-muted">Total: <span id="checkout-total-items">$TotalItems</span> item(s)</small>
            </div>
            <div class="card-body">
                <% loop CartItems %>
                <div class="cart-item-checkout" data-item-id="$ID">
                    <div class="row align-items-center border-bottom pb-3 mb-3">
                        <!-- Product Image & Details -->
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <a href="$BaseHref/list-product/view/$Product.ID" class="text-decoration-none text-black me-3">
                                <% if $Product.Image %>
                                    <img src="$Product.Image.FitMax(80,80).URL" class="rounded" alt="$Product.Name" width="80" />
                                <% else %>
                                    <img src="https://picsum.photos/80?random=$Product.ID" class="rounded" alt="$Product.Name" width="80" />
                                <% end_if %>
                                </a>
                                <div>
                                    <h6 class="mb-1">$Product.Name</h6>
                                    <small class="text-muted">Berat: $Product.Weight gr | Stok: $Product.Stock</small>
                                    <div class="text-primary fw-bold">$Product.DisplayPrice</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="col-6 col-md-2 text-center">
                            <div class="p-2">
                                <small class="d-md-none text-muted">Qty:</small>
                                <div class="input-group rounded-pill border overflow-hidden flex-shrink-0" style="width: 120px; max-width: 120px;">
                                    <button class="btn btn-outline-secondary border-0 px-2" type="button" 
                                            onclick="changeCheckoutQuantity($ID, -1, $Product.Stock)">-</button>
                                    
                                    <input type="number" 
                                        class="form-control text-center border-0 px-1 quantity-input-checkout" 
                                        value="$Quantity" 
                                        min="1" 
                                        max="$Product.Stock" 
                                        data-item-id="$ID"
                                        data-original-value="$Quantity"
                                        onchange="updateCheckoutQuantity(this, $Product.Stock)"
                                        style="font-size: 14px;">
                                        
                                    <button class="btn btn-outline-secondary border-0 px-2" type="button" 
                                            onclick="changeCheckoutQuantity($ID, 1, $Product.Stock)">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="col-md-3">
                            <div class="text-end">
                            <h4 class="fw-bold item-subtotal" style="color: #c4965c;">$FormattedSubtotal</h4>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-md-1">
                            <div class="text-end">
                                <a href="$BaseHref/checkout/remove-item/$ID" 
                                   class="btn btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus item ini dari checkout?')">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <% end_loop %>
            </div>
        </div>

        <!-- Pilih Kurir -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Pilih Kurir</div>
            <div class="card-body">
                <% if ShippingAddress %>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Kurir</label>
                        <select id="courierSelect" class="form-select">
                            <option value="">Pilih kurir</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                            <option value="jnt">J&T</option>
                            <option value="sicepat">SiCepat</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Berat Total (gram)</label>
                        <input type="text" id="totalWeight" class="form-control" value="$TotalWeight" readonly>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="checkOngkirBtn" class="btn btn-primary mt-4">
                            <span class="btn-text">Cek Ongkir</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
                <div id="ongkirResults" class="d-none">
                    <h6>Pilih Layanan Pengiriman:</h6>
                    <div id="ongkirOptions"></div>
                </div>
                <% else %>
                <p class="text-muted">Tambahkan alamat terlebih dahulu untuk melihat ongkir</p>
                <% end_if %>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Metode Pembayaran</div>
            <div class="card-body">
                <% if PaymentMethods && PaymentMethods.Count > 0 %>
                <div class="row">
                    <% loop PaymentMethods %>
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input payment-method" type="radio" 
                                   name="paymentMethod" value="$paymentMethod" 
                                   data-fee="$totalFee" id="payment_$paymentMethod">
                            <label class="form-check-label w-100" for="payment_$paymentMethod">
                                <div class="d-flex justify-content-between">
                                    <span>$paymentName</span>
                                    <small class="text-muted">+ $formattedFee</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    <% end_loop %>
                </div>
                <% else %>
                <p class="text-muted">Metode pembayaran tidak tersedia</p>
                <% end_if %>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Ringkasan Pembayaran</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal (<span id="checkout-summary-items">$TotalItems</span> items)</span>
                    <span id="subtotalAmount">$FormattedTotalPrice</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Ongkir</span>
                    <span id="shippingCost">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Pembayaran</span>
                    <span id="paymentFee">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between fw-bold border-top pt-2">
                    <span>Total</span>
                    <span id="totalCost">$FormattedTotalPrice</span>
                </div>
                
                <% if ShippingAddress %>
                    <input type="hidden" name="SecurityID" value="$SecurityID">
                    <input type="hidden" name="shippingCost" id="hiddenShippingCost" value="0">
                    <input type="hidden" name="courierService" id="hiddenCourierService" value="">
                    <button type="submit" id="submitOrderBtn" class="btn btn-success w-100 mt-3" disabled>
                        Lanjutkan ke Pembayaran
                    </button>
                <% else %>
                    <button type="button" class="btn btn-secondary w-100 mt-3" disabled>
                        Tambahkan Alamat Terlebih Dahulu
                    </button>
                <% end_if %>
            </div>
        </div>
    </form>
    <% else %>
    <div class="text-center py-5">
        <div class="mb-4">
            <svg width="64" height="64" fill="#ddd" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
        </div>
        <h5 class="mb-3">Keranjang anda kosong</h5>
        <p class="text-muted mb-4">Silahkan tambahkan produk dahulu</p>
        <a href="$BaseHref/shop" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #c4965c;">Belanja Sekarang</a>
    </div>
    <% end_if %>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedShippingCost = 0;
    let selectedPaymentFee = 0;

    // Checkout quantity management functions
    window.changeCheckoutQuantity = function(itemId, delta, maxStock) {
        const input = document.querySelector(`input[data-item-id="${itemId}"]`);
        let value = parseInt(input.value) || 1;
        
        value += delta;
        if (value < 1) value = 1;
        if (value > maxStock) {
            alert('Quantity melebihi stok yang tersedia! Maksimal: ' + maxStock);
            value = maxStock;
        }

        input.value = value;
        updateCheckoutQuantity(input, maxStock);
    };

    window.updateCheckoutQuantity = function(input, maxStock) {
        let value = parseInt(input.value) || 1;
        const originalValue = parseInt(input.getAttribute('data-original-value')) || 1;
        
        // Validate quantity
        if (value < 1) {
            value = 1;
            input.value = value;
        }
        if (value > maxStock) {
            alert('Quantity melebihi stok yang tersedia! Maksimal: ' + maxStock);
            value = maxStock;
            input.value = value;
        }

        // Only update if value changed
        if (value !== originalValue) {
            const itemId = input.getAttribute('data-item-id');
            updateQuantityOnServer(itemId, value, input);
        }
    };

    function updateQuantityOnServer(itemId, quantity, inputElement) {
        // Disable input while updating
        inputElement.disabled = true;
        
        $.ajax({
            url: '$BaseHref/checkout/update-quantity',
            method: 'POST',
            data: {
                cartItemID: itemId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update original value
                    inputElement.setAttribute('data-original-value', quantity);
                    
                    if (response.item_removed) {
                        // Remove item from DOM
                        $(inputElement).closest('.cart-item-checkout').fadeOut(300, function() {
                            $(this).remove();
                            updateCheckoutTotals(response);
                        });
                    } else {
                        // Update item subtotal
                        const itemContainer = $(inputElement).closest('.cart-item-checkout');
                        itemContainer.find('.item-subtotal').text(response.formatted_subtotal);
                        updateCheckoutTotals(response);
                    }
                } else {
                    alert(response.error);
                    // Revert to original value
                    inputElement.value = inputElement.getAttribute('data-original-value');
                }
            },
            error: function() {
                alert('Gagal mengupdate quantity. Silakan coba lagi.');
                // Revert to original value
                inputElement.value = inputElement.getAttribute('data-original-value');
            },
            complete: function() {
                inputElement.disabled = false;
            }
        });
    }

    function updateCheckoutTotals(response) {
        // Update total items display
        $('#checkout-total-items').text(response.total_items);
        $('#checkout-summary-items').text(response.total_items);
        
        // Update subtotal
        $('#subtotalAmount').text(response.formatted_total_price);
        
        // Update total weight for shipping calculation
        $('#totalWeight').val(response.total_weight);
        
        // Recalculate total with shipping and payment fees
        updateTotal();
        
        // Reset shipping selection if weight changed significantly
        if (selectedShippingCost > 0) {
            $('#ongkirResults').addClass('d-none');
            selectedShippingCost = 0;
            $('#hiddenShippingCost').val('0');
            $('#hiddenCourierService').val('');
            $('#shippingCost').text('Rp 0');
            updateTotal();
            checkFormValidity();
        }
    }

    // Event handler untuk tombol cek ongkir
    $('#checkOngkirBtn').click(function() {
        const courier = $('#courierSelect').val();
        const districtId = '$ShippingAddress.SubDistricID';
        const totalWeight = $('#totalWeight').val();
        
        if (!courier) {
            alert('Pilih kurir terlebih dahulu');
            return;
        }
        
        if (!districtId) {
            alert('Alamat pengiriman tidak valid');
            return;
        }
        
        // Tampilkan loading state
        $(this).prop('disabled', true);
        $(this).find('.btn-text').text('Loading...');
        $(this).find('.spinner-border').removeClass('d-none');
        
        // Request ke server untuk cek ongkir
        $.ajax({
            url: '$BaseHref/checkout/api/check-ongkir',
            method: 'POST',
            data: {
                district_id: districtId,
                courier: courier,
                weight: totalWeight
            },
            success: function(response) {
                displayOngkirResults(response);
            },
            error: function() {
                alert('Gagal mengecek ongkir. Silakan coba lagi.');
            },
            complete: function() {
                // Reset button state
                $('#checkOngkirBtn').prop('disabled', false);
                $('#checkOngkirBtn').find('.btn-text').text('Cek Ongkir');
                $('#checkOngkirBtn').find('.spinner-border').addClass('d-none');
            }
        });
    });
    
    // Fungsi untuk menampilkan hasil ongkir
    function displayOngkirResults(data) {
        const resultsDiv = $('#ongkirResults');
        const optionsDiv = $('#ongkirOptions');
        
        optionsDiv.empty();
        
        if (data && data.length > 0) {
            data.forEach(function(service) {
                const serviceHtml = `
                    <div class="form-check mb-2">
                        <input class="form-check-input shipping-option" type="radio" 
                               name="shippingOption" value="${service.cost}" 
                               data-service="${service.service}" 
                               data-description="${service.description}"
                               data-etd="${service.etd}">
                        <label class="form-check-label d-flex justify-content-between w-100">
                            <span>${service.service} - ${service.description} (${service.etd})</span>
                            <strong>Rp ${formatNumber(service.cost)}</strong>
                        </label>
                    </div>
                `;
                optionsDiv.append(serviceHtml);
            });
            
            resultsDiv.removeClass('d-none');
        } else {
            optionsDiv.html('<p class="text-muted">Tidak ada layanan pengiriman tersedia</p>');
            resultsDiv.removeClass('d-none');
        }
    }
    
    // Event handler ketika memilih layanan pengiriman
    $(document).on('change', '.shipping-option', function() {
        selectedShippingCost = parseInt($(this).val());
        const service = $(this).data('service');
        const description = $(this).data('description');
        const etd = $(this).data('etd');
        
        // Update hidden fields
        $('#hiddenShippingCost').val(selectedShippingCost);
        $('#hiddenCourierService').val(`${service} - ${description} (${etd})`);
        
        updateTotal();
        checkFormValidity();
    });

    // Event handler untuk payment method
    $(document).on('change', '.payment-method', function() {
        selectedPaymentFee = parseInt($(this).data('fee')) || 0;
        updateTotal();
        checkFormValidity();
    });

    // Update total calculation
    function updateTotal() {
        $.ajax({
            url: '$BaseHref/checkout/api/calculate-total',
            method: 'POST',
            data: {
                shipping_cost: selectedShippingCost,
                payment_fee: selectedPaymentFee
            },
            success: function(response) {
                $('#shippingCost').text(response.formatted_shipping_cost);
                $('#paymentFee').text(response.formatted_payment_fee);
                $('#totalCost').text(response.formatted_total_cost);
                $('#subtotalAmount').text(response.formatted_subtotal);
            },
            error: function() {
                console.error('Gagal menghitung total');
            }
        });
    }

    // Check if form is valid to enable submit button
    function checkFormValidity() {
        const hasShipping = selectedShippingCost > 0;
        const hasPayment = $('input[name="paymentMethod"]:checked').length > 0;
        
        if (hasShipping && hasPayment) {
            $('#submitOrderBtn').prop('disabled', false);
        } else {
            $('#submitOrderBtn').prop('disabled', true);
        }
    }

    // Form validation before submit
    $('#checkoutForm').submit(function(e) {
        if (!$('input[name="paymentMethod"]:checked').length) {
            e.preventDefault();
            alert('Pilih metode pembayaran');
            return false;
        }

        if (selectedShippingCost <= 0) {
            e.preventDefault();
            alert('Pilih layanan pengiriman');
            return false;
        }

        // Show loading state on submit
        $('#submitOrderBtn').prop('disabled', true).text('Memproses...');

        setTimeout(function() {
            location.reload();
        }, 2000);
    });
    
    // Fungsi helper untuk format number
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
});
</script>