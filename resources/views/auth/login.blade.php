@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    {{-- session has status --}}
    @if (Session::has('status') || Session::has('error'))
        <div class="notif-info" data-status="{{ Session::get('status') }}"></div>
        <div class="notif-error" data-status="{{ Session::get('error') }}"></div>
    @endif

    <marquee width="1000" height="80" direction="left" scrollamount="15" style='color:rgba(6, 6, 6, 0.926); font-weight:bold;'>
        <font size = "7">E-LEARNING SMK NEGERI 1 BENER MERIAH </font>
    </marquee>


    {{-- <style type="text/css"> --}}
    <style type="text">
        body {
            background-image: url("assets/image/logo.png");
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="wrap d-md-flex">

                    <div id="bannerLogin"
                        class="text-wrap p-4 p-lg-5 text-center d-flex
                        align-items-center order-md-last">

                        <div class="text w-100">
                            <img src="{{ asset('assets/image/logo.png') }}" class="img-form" alt="logo">

                            <h2> {{ config('app.name') }}</h2>

                            <p style="line-height: 1.5">
                                {{-- <b>{{ config('app.name') }}</b>  --}}
                                E-Learning ini merupakan salah satu media pembelajaran 
                                untuk meningkatkan proses pembelajaran di SMK Negeri 1 Bener Meriah.
                            </p>
                        </div>
                    </div>

                    <div class="login-wrap p-4 p-lg-5">

                        <div class="w-100 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="title">
                                Login
                            </h3>
                        </div>

                        <hr>

                        <form id="formLogin" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="label" for="name">Id LOGIN</label>
                                <input type="number" class="form-control @error('no_induk') is-invalid @enderror"
                                    name="no_induk" placeholder="Masukkan nomor induk anda" value="{{ old('no_induk') }}">

                                @error('no_induk')
                                    <span class="invalid-feedback no_induk" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="label" for="password">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password anda">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="form-control btn btn-primary submit px-3 mb-2
                                        font-weight-bold text-uppercase">
                                    Login
                                </button>
                            </div>

                            <hr>
                            <div class="form-group d-sm-flex">
                                <div class="w-50 text-left">

                                    {{-- <label class="checkbox-wrap checkbox-primary mb-0">
                                        Remember Me
                                        <input name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label> --}}
                                </div>
                                {{-- <div class="w-50 text-sm-right">
                                    <a href="javascript:void(0)">Forgot Password</a>
                                </div> --}}
                            </div>
                        </form>

                    </div>
                </div> <!-- .wrap -->
            </div> <!-- .col-md-12 -->
        </div> <!-- .row -->
    </div> <!-- .container -->
@endsection

@section('footer')



@endsection

@push('js')
    <script>
        // sweetalert2 toast
        const SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // show notif
        const notifInfo = $('.notif-info').data('status');
        const notifError = $('.notif-error').data('status');
        if (notifInfo) {
            SwalToast.fire({
                icon: 'info',
                title: notifInfo
            })
        } else if (notifError) {
            SwalToast.fire({
                icon: 'error',
                title: notifError
            })
        }

        $(document).ready(function() {
            // validate jquery form with jquery validate
            $('#formLogin').validate({
                rules: {
                    no_induk: {
                        required: true,
                        minlength: 8,
                        maxlength: 15
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    no_induk: {
                        required: "Silahkan masukkan nomer induk",
                        minlength: "Nomer Induk Harus 8 karakter",
                        maxlength: "Nomer Induk Harus 15 karakter"
                    },
                    password: {
                        required: "Silahkan masukkan password",
                    }
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback font-weight-bold");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                    $('.invalid-feedback.no_induk').text('');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                }
            }); // end validate

            // show modal user
            $('#buttonModalUser').on('click', function() {
                $('#modalUser').modal('show');
            });
        });
    </script>
@endpush
