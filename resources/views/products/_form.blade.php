<div class="form-group">
    <label>Nama</label>
    <input name="nama" class="form-control" value="{{ old('nama', $product->nama ?? '') }}" required>
</div>
<div class="form-group">
    <label>Satuan</label>
    <select name="satuan" class="form-control" required>
        @foreach(['kg','butir','peti'] as $s)
        <option value="{{ $s }}" {{ old('satuan', $product->satuan ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Harga</label>
    <input type="number" step="0.01" name="harga" class="form-control" value="{{ old('harga', $product->harga ?? '') }}"
        required>
</div>