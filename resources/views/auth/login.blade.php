@extends('layouts.app')
<style>
    .fa {
        padding: 20px;
        font-size: 30px;
        /* width: 65px; */
        text-align: center;
        text-decoration: none;
        /* margin: 5px 2px; */
        border-radius: 50%;
    }

    .social {

        max-width: 65px !important;
        /* padding: 10px !important; */
    }

    .fa:hover {
        transform: scale(1.1)
    }

    .fa-google {
        background: #dd4b39;
        color: white;
    }

    .btn-facebook {
        border: 3px solid #3B5998 !important;
        max-width: fit-content !important;
        padding: 10px !important;
    }

    .btn-google {
        border: 3px solid #dd4b39 !important;
        max-width: fit-content !important;
        padding: 10px !important;
    }

    .fa-facebook {
        background: #3B5998;
        color: white;
    }
</style>
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab"
                    href="#tab-item-login" role="tab" aria-controls="tab-item-login" aria-selected="true">Login</a>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                <div class="login-form">
                    <form method="POST" action="{{ route('login') }}" name="login-form" class="needs-validation"
                        novalidate="">
                        @csrf
                        <div class="form-floating mb-3">
                            <input class="form-control form-control_gray  @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required="" autocomplete="email" autofocus="">
                            <label for="email">Email address *</label>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="pb-3"></div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password"
                                class="form-control form-control_gray @error('password') is-invalid @enderror "
                                name="password" required="" autocomplete="current-password">
                            <label for="customerPasswodInput">Password *</label>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Log In</button>

                        <div class="customer-option mt-4 text-center">
                            <span class="text-secondary">No account yet?</span>
                            <a href="{{ route('register') }}" class="btn-text js-show-register">Create Account</a>
                        </div>
                        <div class="customer-option mt-3 text-center">
                            {{-- <a class="btn   btn-social btn-facebook social" href="{{ route('facebook.login') }}">
                                <span class="fa fa-facebook fs-4  "></span>
                            </a> --}}
                            <a class="btn  btn-social btn-google social" href="{{ route('google.login') }}">
                                <span class="fa fa-google fs-4 "></span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection