@extends('layouts.app')
@section('title', 'Ajouter un produit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">

        <div class="mb-4">
            <h2 class="fw-bold">➕ Ajouter un produit</h2>
            <p class="text-muted">Remplissez le formulaire pour mettre en vente votre produit.</p>
        </div>

        <div class="card p-4 shadow-sm">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label fw-medium">Titre du produit *</label>
                    <input type="text" id="title" name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" required
                        placeholder="Ex: Laptop Dell Inspiron, T-shirt coton...">
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-medium">Description *</label>
                    <textarea id="description" name="description" rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        required placeholder="Décrivez votre produit en détail...">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-medium">Prix (DT) *</label>
                        <div class="input-group">
                            <input type="number" id="price" name="price" step="0.01" min="0.01"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" required placeholder="0.00">
                            <span class="input-group-text">DT</span>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label fw-medium">Catégorie *</label>
                        <select id="category_id" name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Choisir une catégorie...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-medium">
                        Image du produit <span class="text-muted">(optionnel)</span>
                    </label>
                    <input type="file" id="image" name="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">JPG, PNG ou WebP — max. 2 Mo</div>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    {{-- Prévisualisation --}}
                    <div id="preview-container" class="mt-2 d-none">
                        <img id="img-preview" src="" alt="Aperçu"
                            class="rounded" style="max-height:150px;object-fit:cover;">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill fw-medium">
                        <i class="bi bi-check-circle me-1"></i> Publier le produit
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('img-preview').src = ev.target.result;
            document.getElementById('preview-container').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush