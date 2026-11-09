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
        <div class="col-md-3 form-group">
            <label>Metode Bayar</label>
            <select name="metode_bayar" id="metode_bayar" class="form-control" onchange="toggleJatuhTempo()">
                <option value="tunai">tunai</option>
                <option value="tempo">tempo</option>
            </select>
        </div>

        <div class="col-md-3 form-group" id="form_jatuh_tempo" style="display: none;">
            <label>Jatuh Tempo</label>
            <input type="date" name="tgl_jatuh_tempo" class="form-control"
                value="{{ now()->addDays(30)->format('Y-m-d') }}">
        </div>
    </div>

    <h5 class="mt-4">Item Pesanan</h5>
    <table class="table" id="itemsTable">
        <thead>
            <tr>
                <th>Produk</th>
                <th width="100">Satuan</th> {{-- Tambahan Kolom Satuan --}}
                <th width="120">Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <button type="button" class="btn btn-sm btn-secondary" onclick="addRow()">+ Tambah Baris</button>

    <div class="mt-3 p-3 bg-light rounded border">
        <h5 class="mb-0">Total: Rp <span id="totalText">0</span></h5>
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
                ${products.map(p => `<option value="${p.id}" data-harga="${p.harga}" data-satuan="${p.satuan}">${p.nama}
                </option>`).join('')}
            </select>
        </td>
        <td class="align-middle text-center font-weight-bold satuan-text">
            ${products[0].satuan}
        </td>
        <td>
            <!-- TAMBAHKAN min="0.01" DI SINI -->
            <input type="number" step="1" min="1" name="items[${rowId}][jumlah]" class="form-control" value="1"
                oninput="calcRow(this)">
        </td>
        <td>
            <input type="number" step="0.01" min="0" name="items[${rowId}][harga_satuan]" class="form-control"
                value="${products[0].harga}" oninput="calcRow(this)" readonly>
        </td>
        <td class="align-middle subtotal">0</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); calcTotal()">x</button>
        </td>
        `;
        
        tb.appendChild(tr);
        rowId++;
        calcRow(tr.querySelector('input[name$="[jumlah]"]'));
        }
        
        function onProductChange(sel) {
            const selectedOption = sel.selectedOptions[0];
            const harga = selectedOption.dataset.harga;
            const satuan = selectedOption.dataset.satuan;
            
            const tr = sel.closest('tr');
            
            // Update Harga
            tr.querySelector('input[name$="[harga_satuan]"]').value = harga;
            
            // Update Teks Satuan
            tr.querySelector('.satuan-text').innerText = satuan;
            
            calcRow(sel);
        }
        
        function calcRow(el) {
        const tr = el.closest('tr');
        const inputJumlah = tr.querySelector('input[name$="[jumlah]"]');
        let jumlah = parseFloat(inputJumlah.value) || 0;
        const harga = parseFloat(tr.querySelector('input[name$="[harga_satuan]"]').value) || 0;
        
        // VALIDASI JS: Jika jumlah di bawah 1, paksa menjadi 1
        if (jumlah < 1) { inputJumlah.value=1; jumlah=1; } const sub=jumlah * harga;
            tr.querySelector('.subtotal').innerText=sub.toLocaleString('id-ID'); calcTotal(); 
        }
        
        function calcTotal() {
            let total = 0;
            document.querySelectorAll('#itemsTable tbody tr').forEach(tr => {
                const jumlah = parseFloat(tr.querySelector('input[name$="[jumlah]"]').value) || 0;
                const harga = parseFloat(tr.querySelector('input[name$="[harga_satuan]"]').value) || 0;
                total += jumlah * harga;
            });
            document.getElementById('totalText').innerText = total.toLocaleString('id-ID');
        }
        
        function toggleJatuhTempo() {
            const metode = document.getElementById('metode_bayar').value;
            const formTempo = document.getElementById('form_jatuh_tempo');
            if (metode === 'tempo') {
            formTempo.style.display = 'block';
            } else {
            formTempo.style.display = 'none';
            }
        }
        // Jalankan saat halaman dimuat
        toggleJatuhTempo();
        
        // Panggil addRow() saat halaman pertama kali dimuat
        addRow();
</script>

@endsection