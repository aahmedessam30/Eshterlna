<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('auth.reset_password') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/forget_password.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">

    <div class="container">
        <div class="row">
            <div class="flex-center position-ref full-height full-width">
                <form class="form-container w-50" id="reset_password">

                    <div class="alert alert-success text-center w-100" style="display: none"></div>
                    <div class="alert alert-danger text-center w-100" style="display: none"></div>

                    <h2>{{ __('auth.forget_password') }}</h2>

                    <div class="col-12 text-center mt-4">
                        <div class="mb-3">
                            <input name="email" class="form-control" placeholder="{{ __('auth.email') }}"
                                value="{{ request()->get('email') }}">
                        </div>

                        <div class="mb-3">
                            <input name="password" class="form-control" type="password"
                                placeholder="{{ __('auth.new_password') }}">
                        </div>

                        <div class="mb-3">
                            <input name="password_confirmation" class="form-control" type="password"
                                placeholder="{{ __('auth.confirm_password') }}">
                        </div>

                        <input name="token" placeholder="token" value="{{ request()->get('token') }}" hidden>

                        <button type="submit" class="btn btn-primary">{{ __('auth.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $("#reset_password").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('reset') }}",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.data.status == true) {
                            $(".alert-success").show();
                            $(".alert-success").html(response.data.message);
                            $('.alert-success').fadeOut(2500);

                            $("input[name='password']").val('');
                            $("input[name='password_confirmation']").val('');
                        } else {
                            $(".alert-danger").show();
                            $(".alert-danger").html(response.data.message);
                            $('.alert-danger').fadeOut(2500);
                        }
                    },
                    error: function(response) {
                        $.each(response.responseJSON.errors, function(key, val) {
                            $('.alert-danger').show();
                            $('.alert-danger').append(val);
                            $('.alert-danger').fadeOut(2500);
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
