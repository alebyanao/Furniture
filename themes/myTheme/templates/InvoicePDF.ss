<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice - $Order.OrderCode</title>
  <style>
    @page { size: A4; margin: 1.2cm; }
    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 11px;
      line-height: 1.5;
      color: #000;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .header img { max-height: 40px; }
    .company-name { font-size: 16px; font-weight: bold; margin-top: 5px; }
    .invoice-title { font-size: 24px; font-weight: bold; margin-top: 8px; }
    .invoice-number { font-size: 11px; color: #444; margin-top: 3px; }

    .section {
      margin-bottom: 18px;
    }
    .section h6 {
      font-size: 11px;
      font-weight: bold;
      margin: 0 0 6px 0;
      border-bottom: 1px solid #ccc;
      padding-bottom: 3px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      margin: 2px 0;
    }
    .info-label { font-weight: bold; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
      margin-bottom: 15px;
    }
    table th, table td {
      border: 1px solid #999;
      padding: 6px;
      font-size: 11px;
    }
    table th {
      background: #f2f2f2;
      text-align: left;
    }
    .text-end { text-align: right; }
    .text-center { text-align: center; }

    .total-box {
      margin-top: 10px;
      border: 1px solid #999;
      padding: 10px;
    }
    .total-box .info-row { margin: 4px 0; }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 10px;
      color: #555;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <% if $SiteConfig.FooterLogo %>
      <img src="$SiteConfig.FooterLogo.AbsoluteURL" alt="Company Logo">
    <% end_if %>
    <div class="company-name">$SiteConfig.Title</div>
    <div class="invoice-title">INVOICE</div>
    <div class="invoice-number">No: $InvoiceNumber | Tanggal: $InvoiceDate</div>
  </div>

  <!-- Customer & Order -->
  <div class="section">
    <div style="width:48%; float:left;">
      <h6>Tagihan Untuk</h6>
      <div class="info-row"><span class="info-label">Nama:</span> <span>$Member.FirstName $Member.Surname</span></div>
      <% if $ShippingAddress %>
        <div class="info-row"><span class="info-label">Penerima:</span> <span>$ShippingAddress.ReceiverName</span></div>
        <div class="info-row"><span class="info-label">Alamat:</span> <span>$ShippingAddress.Address</span></div>
        <div class="info-row"><span class="info-label">Kota:</span> <span>$ShippingAddress.DistrictName, $ShippingAddress.CityName</span></div>
        <div class="info-row"><span class="info-label">Provinsi:</span> <span>$ShippingAddress.ProvinceName $ShippingAddress.PostalCode</span></div>
        <div class="info-row"><span class="info-label">Telepon:</span> <span>$ShippingAddress.PhoneNumber</span></div>
      <% end_if %>
    </div>
    <div style="width:48%; float:right;">
      <h6>Detail Pesanan</h6>
      <div class="info-row"><span class="info-label">Kode Pesanan:</span> <span>$Order.OrderCode</span></div>
      <div class="info-row"><span class="info-label">Tanggal Pesanan:</span> <span>$Order.CreateAt.Format('Y-m-d')</span></div>
      <div class="info-row"><span class="info-label">Status:</span> <span>$Order.Status</span></div>
      <div class="info-row"><span class="info-label">Metode Bayar:</span> <span>$Order.PaymentMethod</span></div>
      <% if $Order.ShippingCourier %>
        <div class="info-row"><span class="info-label">Kurir:</span> <span>$Order.ShippingCourier</span></div>
      <% end_if %>
    </div>
    <div style="clear:both;"></div>
  </div>

  <!-- Items -->
  <table>
    <thead>
      <tr>
        <th width="5%" class="text-center">No</th>
        <th width="40%">Nama Produk</th>
        <th width="10%" class="text-center">Qty</th>
        <th width="20%" class="text-end">Harga</th>
        <th width="25%" class="text-end">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <% loop $OrderItems %>
        <tr>
          <td class="text-center">$Pos</td>
          <td>$Product.Name</td>
          <td class="text-center">$Quantity</td>
          <td class="text-end">Rp $FormattedPrice</td>
          <td class="text-end"><strong>Rp $FormattedSubtotal</strong></td>
        </tr>
      <% end_loop %>
    </tbody>
  </table>

  <!-- Totals -->
  <div class="total-box">
    <div class="info-row"><span class="info-label">Subtotal Produk:</span> <span>$Order.FormattedTotalPrice</span></div>
    <div class="info-row"><span class="info-label">Ongkos Kirim:</span> <span>$Order.FormattedShippingCost</span></div>
    <% if $PaymentFee %>
      <div class="info-row"><span class="info-label">Biaya Pembayaran:</span> <span>$FormattedPaymentFee</span></div>
    <% end_if %>
    <hr style="border:0;border-top:1px solid #ccc;margin:6px 0;">
    <div class="info-row"><strong>TOTAL:</strong> <strong>$FormattedGrandTotalWithFee</strong></div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>Invoice ini digenerate otomatis pada $InvoiceDate</p>
    <% if $SiteConfig.FooterCopyrightText %>
      <p>$SiteConfig.FooterCopyrightText</p>
    <% end_if %>
  </div>

</body>
</html>
