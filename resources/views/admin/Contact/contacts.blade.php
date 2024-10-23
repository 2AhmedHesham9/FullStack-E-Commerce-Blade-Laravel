@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Messages</h3>
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
                    <div class="text-tiny">Messages</div>
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
                <a class="tf-button style-1 w208" href="#"><i class="icon-plus"></i>Add
                    new</a>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">

                    @Session('status')
                    <p class=" alert alert-success  ">{{ Session('status') }}</p>
                    @endSession

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>

                                <th>Name</th>
                                <th>Phone</th>
                                <th style="width: 200px">Email</th>

                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($contacts->count() > 0)
                            @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contacts->firstItem()+ $loop->index }}</td>

                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->phone }}</td>

                                <td style="text-decoration: underline"> <a href="mailto:{{ $contact->email }}">{{
                                        $contact->email }}</a></td>
                                <td>{{ $contact->comment }}</td>
                                <td> {{ $contact->created_at }}</td>

                                <td>
                                    <div class="list-icon-function">
                                        {{-- {{ route('admin.coupon.destroy',['id'=>$contact->id]) }} --}}
                                        <form action="{{ route('admin.contact.destroy',['contact'=>$contact->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $contacts->links('pagination::bootstrap-5') }}
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
                    text: "You want to delete this Coupon?",
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

// for delete
        $(function() {
            $('.delete').on('click', function(e){
                e.preventDefault();
                var form= $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text: "You want to delete this Message?",
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