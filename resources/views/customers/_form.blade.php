<div class="form-group">
    <label>Nama</label>
    <input name="nama" class="form-control" value="{{ old('nama', $customer->nama ?? '') }}" required>
</div>
<div class="form-group">
    <label>Kategori</label>
    <select name="kategori" class="form-control" required>
        @foreach(['eceran','warung','reseller','katering'] as $k)
        <option value="{{ $k }}" {{ old('kategori', $customer->kategori ?? '') == $k ? 'selected' : '' }}>{{ $k }}
        </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>No WA</label>
    <input name="no_wa" class="form-control" value="{{ old('no_wa', $customer->no_wa ?? '') }}" required>
</div>
<div class="form-group">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required>{{ old('alamat', $customer->alamat ?? '') }}</textarea>
</div>
<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control" required>
        @foreach(['aktif','pasif'] as $s)
        <option value="{{ $s }}" {{ old('status', $customer->status ?? 'aktif') == $s ? 'selected' : '' }}>{{ $s }}
        </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Tanggal Daftar</label>
    <input type="date" name="tgl_daftar" class="form-control"
        value="{{ old('tgl_daftar', isset($customer) ? $customer->tgl_daftar->format('Y-m-d') : now()->format('Y-m-d')) }}"
        required>
</div>