@extends('layouts.frontend')

@section('body_class', 'login-page')

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">@lang('labels.user.password_reset')</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        @component('components.fieldset', [
                            'name' => 'email',
                            'title' => trans('validation.attributes.email'),
                            'horizontal' => true,
                            'label_cols' => 4
                        ])
                            @component('components.input-group', [
                                'left' => '<i class="icon-user"></i>'
                            ])
                                <input type="email" name="email" placeholder="@lang('validation.attributes.email')"
                                       class="form-control" v-validate="'required|email'" value="{{ old('email') }}">
                            @endcomponent
                        @endcomponent

                        @component('components.fieldset', [
                                'name' => 'password',
                                'title' => trans('validation.attributes.password'),
                                'horizontal' => true,
                                'label_cols' => 4
                            ])
                            <input type="password" name="password" placeholder="@lang('validation.attributes.password')"
                                   class="form-control" v-validate="'required'" data-toggle="password-strength-meter">
                        @endcomponent

                        @component('components.fieldset', [
                            'name' => 'password_confirmation',
                            'title' => trans('validation.attributes.password_confirmation'),
                            'horizontal' => true,
                            'label_cols' => 4
                        ])
                            <input type="password" name="password_confirmation"
                                   placeholder="@lang('validation.attributes.password_confirmation')"
                                   class="form-control" v-validate="'required'">
                        @endcomponent

                        <div class="form-group row">
                            <div class="col-md-8 ml-auto">
                                <button class="btn btn-primary">
                                    @lang('labels.user.password_reset')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
