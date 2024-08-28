@extends('layouts.adminPage')

@section('title', 'Edit the Product -')


@section('content')
<div class="container mt-4">
    <h1>Edit Product</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $product->title) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price)/100 }}" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" value="{{ old('category_id', $product->category_id)}}" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">Discount (%)</label>
            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', $product->discount) }}">
        </div>
        <div class="mb-3">
            <label for="default_photo" class="form-label">Select Default Photo</label>
            <select name="default_photo" id="default_photo" class="form-select">
                @foreach ($product->images as $image)
                <option value="{{ $image->id }}" {{ $image->is_default ? 'selected' : '' }}>
                    {{ basename($image->image_path) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Add More Photos</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

@endsection
