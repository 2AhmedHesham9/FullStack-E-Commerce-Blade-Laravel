@extends('layouts.admin')
@section('content')
<style>
    .table-striped th:nth-child(2),
    .table-striped td:nth-child(2) {
        width: 250px;
        /* padding-bottom: 18px; */

    }
    .table-striped th:nth-child(3),
    .table-striped td:nth-child(3) {
        width: fit-content;
        /* padding-bottom: 18px; */

    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Slides</h3>
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
                    <div class="text-tiny">Slider</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name" tabindex="2" value=""
                                aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.slide.add') }}"><i class="icon-plus"></i>Add
                    new</a>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @session('status')
                    <p class=" alert alert-success">{{ session('status') }}</p>
                    @endsession
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th>Image</th>
                                <th>Tagline</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slides as $slide )
                            <tr>
                                <td>{{ $slides->firstItem() + $loop->index }}</td>
                                <td class=" ">
                                    <div class="image">
                                        <img src="{{ asset('uploads/slides')}}/{{ $slide->image }}"
                                            alt="{{ $slide->title }}" class="image">
                                    </div>

                                </td>
                                <td>{{ $slide->tagline }}</td>
                                <td>{{ $slide->title }}</td>
                                <td>{{ $slide->subtitle }}</td>
                                <td style="word-break: break-all"> {{ $slide->link }} </td>
                                <td>
                                    <span class="badge {{ $slide->status == 1? 'bg-success' :'bg-danger' }} fs-4">

                                        {{ $slide->status == 1? "Active" :"Inactive" }}
                                    </span>
                                </td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{ route('admin.slide.edit',['id'=>$slide->id]) }}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{ route('admin.slide.destroy',['id'=>$slide->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $slides->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
            $('.delete').on('click', function(e){
                e.preventDefault();
                var form= $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text: "You want to delete this Slide?",
                    type:"warning",
                    buttons:["No", "Yes"],
                    confirmButtonColor:'#dc3545'
                }).then(function(result){
                    if(result){
                        form.submit();
                    }
                });
            });
        });
</script>

@endpush
