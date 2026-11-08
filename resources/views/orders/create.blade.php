@extends('layouts.app')
@section('content')
<h3>Pesanan Baru</h3>
<form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group">
            <label>Pelanggan</label>
            <select name="customer_id" class="form-control" required>
                @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label>Tgl Order</label>
            <input type="date" name="tgl_order" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-3 form-group">
            <label>Tgl Kirim</label>
            <input type="date" name="tgl_kirim" class="form-control">
        </div>
        <div class="col-md-6 form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="diproses">diproses</option>
                <option value="dikirim">dikirim</option>
                <option value="selesai">selesai</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label>Metode Bayar</label>
            <select name="metode_bayar" class="form-control">
                <option value="tunai">tunai</option>
                <option value="tempo">tempo</option>
            </select>
        </div>
    </div>

    <h5>Item</h5>
    <table class="table" id="itemsTable">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <button type="button" class="btn btn-sm btn-secondary" onclick="addRow()">+ Baris</button>
    <div class="mt-3">
        <strong>Total: Rp <span id="totalText">0</span></strong>
    </div>
    <button class="btn btn-primary mt-3">Simpan Pesanan</button>
</form>

<script>
    const products = @json($products);
let rowId = 0;

function addRow() {
    const tb = document.querySelector('#itemsTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="items[${rowId}][product_id]" class="form-control" onchange="onProductChange(this)">
                ${products.map(p => `<option value="${p.id}" data-harga="${p.harga}">${p.nama}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" step="0.01" name="items[${rowId}][jumlah]" class="form-control" value="1" oninput="calcRow(this)"></td>
        <td><input type="number" step="0.01" name="items[${rowId}][harga_satuan]" class="form-control" value="${products[0].harga}" oninput="calcRow(this)"></td>
        <td class="subtotal">0</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); calcTotal()">x</button></td>
    `;
    tb.appendChild(tr);
    rowId++;
    calcRow(tr.querySelector('input[name$="[jumlah]"]'));
}

function onProductChange(sel) {
    const harga = sel.selectedOptions[0].dataset.harga;
    const tr = sel.closest('tr');
    tr.querySelector('input[name$="[harga_satuan]"]').value = harga;
    calcRow(sel);
}

function calcRow(el) {
    const tr = el.closest('tr');
    const jumlah = parseFloat(tr.querySelector('input[name$="[jumlah]"]').value) || 0;
    const harga = parseFloat(tr.querySelector('input[name$="[harga_satuan]"]').value) || 0;
    const sub = jumlah * harga;
    tr.querySelector('.subtotal').textContent = sub.toLocaleString('id-ID');
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
        const jumlah = parseFloat(tr.querySelector('input[name$="[jumlah]"]').value) || 0;
        const harga = parseFloat(tr.querySelector('input[name$="[harga_satuan]"]').value) || 0;
        total += jumlah * harga;
    });
    document.getElementById('totalText').textContent = total.toLocaleString('id-ID');
}

addRow();
</script>
@endsection