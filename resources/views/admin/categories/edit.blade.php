@extends('layouts.adminPage')

@section('title', 'Edit the Category -')


@section('content')
<div class="container mt-4">
    <h1>Edit Category</h1>
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
        </div>

        <div class="mb-3">
            <label for="products" class="form-label">Select Products</label>
            <select class="form-control" id="products" name="products[]" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                        @if($product->category_id == $category->id) selected @endif>
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
</div>
@endsection
