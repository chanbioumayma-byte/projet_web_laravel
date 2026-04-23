@extends('layouts.app')
@section('title', 'Modifier : ' . $product->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">

        <div class="mb-4">
            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <h2 class="fw-bold">✏️ Modifier le produit</h2>
        </div>

        <div class="card p-4 shadow-sm">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-medium">Titre *</label>
                    <input type="text" name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $product->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Description *</label>
                    <textarea name="description" rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Prix (DT) *</label>
                        <div class="input-group">
                            <input type="number" name="price" step="0.01" min="0.01"
                                class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text">DT</span>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Catégorie *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Image</label>
                    @if($product->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($product->image) }}"
                            alt="Image actuelle" class="rounded shadow-sm"
                            style="max-height:120px;object-fit:cover;">
                        <div class="form-text">Image actuelle — uploader une nouvelle image pour la remplacer.</div>
                    </div>
                    @endif
                    <input type="file" name="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/webp">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill fw-medium">
                        <i class="bi bi-check-circle me-1"></i> Mettre à jour
                    </button>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection