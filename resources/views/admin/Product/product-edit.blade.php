@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Update Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.products') }}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Update product</div>
                </li>
            </ul>
        </div>
        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
            action="{{ route('admin.product.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}">

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                        value="{{ $product->name }}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('name')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0"
                        value="{{ $product->slug}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('slug')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id">
                                <option value="">Choose category</option>
                                @foreach ($categories as $category )
                                <option value="{{ $category->id }}" @selected($product->category_id == $category->id)
                                    >{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id">
                                <option value="">Choose Brand</option>

                                @foreach ($brands as $brand )

                                <option value="{{ $brand->id }}" @selected($product->brand_id == $brand->id)>
                                    {{$brand->name}}
                                </option>

                                @endforeach


                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0"
                        aria-required="true" required="">{{ $product->short_description}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('short_description')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                    </div>
                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0"
                        aria-required="true" required="">{{ $product->description }}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('description')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        @if($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{ asset('uploads/products') }}/{{ $product->image }}" class="effect8"
                                alt="$product->name">
                        </div>
                        @else
                        <div class="item" id="imgpreview" style="display: none">
                            <img src=" " class="effect8" alt="$product->name">
                        </div>
                        @endif

                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                        browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        @if($product->images)
                        @foreach ( explode(",",$product->images) as $image)
                        <div class="item gitems">
                            <img src="{{ asset('uploads/products') }}/{{  trim($image)   }} "
                                alt="{{ $product->name }}" />
                        </div>
                        @endforeach
                        @endif
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span class="tf-color">click to
                                        browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                            </label>

                        </div>
                    </div>
                </fieldset>
                @error('images[]')
                <span class=" alert alert-danger text-center">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price"
                            tabindex="0" value="{{ $product->regular_price }}" aria-required="true" required="">
                    </fieldset>
                    @error('regular_price')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0"
                            value="{{ $product->sale_price  }}" aria-required="true">
                    </fieldset>
                    @error('sale_price')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0"
                            value="{{ $product->SKU }}" aria-required="true" required="">
                    </fieldset>
                    @error('SKU')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    {{-- <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0"
                            value="{{ $product->quantity  }}" aria-required="true" required="">
                    </fieldset>
                    @error('quantity')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror --}}
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock" {{ $product->stock_status == "instock" ? "selected":""
                                    }}>InStock</option>
                                <option value="outofstock" {{ $product->stock_status == "outofstock" ? "selected":""
                                    }}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select name="featured">
                                <option value="0" {{ $product->featured == 0? "selected":"" }}>No</option>
                                <option value="1" {{ $product->featured == 1? "selected":"" }}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured')
                    <span class=" alert alert-danger text-center">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <fieldset class="name">
                    <div class="body-title mb-10">Size And Quantity</div>
                    <div id="sizes-container">
                        @foreach ($product->sizes as $proSize )

                        <div class="row">
                            <fieldset class="name">
                            <div class="body-title mb-10">Size:</div>
                            <div class="select mb-10">
                                <select name="sizes[]" required class="select">
                                    <option value="">Select Size</option>
                                    @foreach ($sizes as $size )

                                    <option value="{{ $size->id }}" @selected($size->id == $proSize->pivot->size_id )
                                        >{{ $size->name }}</option>

                                    @endforeach

                                </select>
                            </div>
                            </fieldset>
                            <fieldset class="name">
                            <div class="mb-10">

                                <div class="body-title mb-10" for="quantity">Quantity:</div>
                                <input type="number" name="quantities[]" min="1" required
                                value="{{ $proSize->pivot->quantity }}">
                            </div>
                        </fieldset>


                            <br>
                            {{-- <button type="button" class="remove-size-btn">Remove</button> --}}

                        </div>
                        @endforeach
                    </div>
                    <br>
                    <button type="button" id="add-size-btn">Add Another Size</button>

                </fieldset>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Update product</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>



@endsection
@push('scripts')
<script>
    $(function() {
    $("#myFile").on("change", function(e){
        const photoInp = $("#myFile");
        const [file] = this.files;
        if(file){
            $("#imgpreview img").attr("src",URL.createObjectURL(file));
            $("#imgpreview").show();
        }
    });

    $("#gFile").on("change", function(e){
        const photoInp = $("#gFile");
        const gphotos = this.files;
        $.each(gphotos, function(key, val){
            $("#galUpload").prepend(`<div class="item gitems"><img src="${ URL.createObjectURL(val) }" /> </div>`);
        });
    });

    $("input[name='name']").on("change", function(){
        $("input[name='slug']").val(StringToSlug($(this).val()));
    });


    });
    function StringToSlug(Text){
        return Text.toLowerCase()
        .replace(/[^\w ]+/g,"")
        .replace(/ +/g,"-");
    }
</script>
<script>
    document.getElementById('add-size-btn').addEventListener('click', function() {
    var container = document.getElementById('sizes-container');

    var sizeRow = document.createElement('div');
    sizeRow.classList.add('size-row');

    sizeRow.innerHTML = `
        <div class="row    d-flex  align-items-center mt-10">
        <div class="col-5">
            <label for="size">Size:</label>
                <div class="select  ">
                    <select name="sizes[]" required class="select">
                    <option value="">Select Size</option>
                    @foreach ($sizes as $size )
                    <option value="{{ $size->id }}">{{ $size->name }}</option>

                    @endforeach

                    </select>
                </div>
        </div>
        <div class="col-4">

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantities[]" min="1" required>
        </div>

        <div class="col-1">
        <br>
        <button type="button" class="remove-size-btn">X</button>
        </div>
        </div>
    `;

    container.appendChild(sizeRow);
});

// Remove size row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-size-btn')) {
        e.target.closest('.size-row').remove();
    }
});

</script>
@endpush
