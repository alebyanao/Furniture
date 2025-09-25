<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4">$Title</h2>

            <% if $Orders %>
                <div class="row g-4">
                    <% loop $Orders %>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <!-- Card Header -->
                                <div class="card-header bg-light d-flex justify-content-between align-items-center border-0">
                                    <div>
                                        <span class="fw-bold"># $OrderCode</span>
                                    </div>
                                    <div class="text-end">
                                        $StatusLabel.RAW
                                        $PaymentStatusLabel.RAW
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-1"><i class="bi bi-calendar3 me-2 text-muted"></i><strong>Tanggal:</strong> $CreateAt.Nice</li>
                                        <li class="mb-1"><i class="bi bi-cash-coin me-2 text-muted"></i><strong>Total:</strong> $FormattedGrandTotal</li>
                                        <li class="mb-1"><i class="bi bi-truck me-2 text-muted"></i><strong>Kurir:</strong> $ShippingCourier</li>

                                        <% if $ExpiresAt && $canBePaid %>
                                            <li class="mb-1 text-danger">
                                                <i class="bi bi-hourglass-split me-2"></i>
                                                <strong>Batas Bayar:</strong> $ExpiresAt.Nice
                                            </li>
                                        <% end_if %>

                                        <% if $TrackingNumber %>
                                            <li class="mb-0">
                                                <i class="bi bi-upc-scan me-2 text-muted"></i>
                                                <strong>Resi:</strong> <span class="text-primary">$TrackingNumber</span>
                                            </li>
                                        <% end_if %>
                                    </ul>
                                </div>

                                <!-- Card Footer / Actions -->
                                <div class="card-footer bg-white border-0">
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="$BaseHref/order/detail/$ID" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>

                                        <% if $canBePaid %>
                                            <a href="$BaseHref/payment/initiate/$ID" target="_blank" class="btn btn-sm btn-success">
                                                <i class="bi bi-credit-card me-1"></i> Bayar
                                            </a>
                                        <% end_if %>

                                        <% if $canBeCancelled %>
                                            <a href="$BaseHref/order/cancel/$ID"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Yakin batalkan pesanan ini?')">
                                                <i class="bi bi-x-circle me-1"></i> Batalkan
                                            </a>
                                        <% end_if %>

                                        <% if $Status == 'shipped' %>
                                            <a href="$BaseHref/order/complete/$ID"
                                               class="btn btn-sm btn-outline-success"
                                               onclick="return confirm('Pesanan sudah diterima?')">
                                                <i class="bi bi-check2-circle me-1"></i> Diterima
                                            </a>
                                        <% end_if %>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <% end_loop %>
                </div>
            <% else %>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-clipboard-data" style="font-size: 64px; color: #ddd;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Belum Ada Pesanan</h5>
                    <p class="text-muted mb-4">Mulai berbelanja sekarang untuk melihat pesanan di sini</p>
                    <a href="$BaseHref/cart"
                       class="btn text-white fw-bold px-4 rounded-pill"
                       style="background-color: #c4965c;">
                        Order Sekarang
                    </a>
                </div>
            <% end_if %>
        </div>
    </div>
</div>
