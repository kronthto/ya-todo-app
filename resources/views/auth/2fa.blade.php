@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <p class="alert alert-info">{{ trans('auth.2fa.intro') }}</p>

            <div class="panel panel-default">
                <div class="panel-heading">Verify 2FA</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/verify2fa') }}">
                        {{ csrf_field() }}

                        @if ($qrcode)
                        <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
                            <label for="secret" class="col-md-4 control-label">2FA Secret</label>

                            <div class="col-md-6">
                                <img id="secret" src="{{ $qrcode }}">
                            </div>
                        </div>
                        @endif

                        <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
                            <label for="otp" class="col-md-4 control-label">OTP</label>

                            <div class="col-md-6">
                                <input id="otp" type="number" min="1" max="999999" step="1" class="form-control" name="otp" value="{{ old('otp') }}" required autofocus>

                                @if ($errors->has('otp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('otp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Verify
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
