@extends('layouts.frontend')

@section('title', trans('labels.frontend.titles.contact'))

@section('body_class', 'page-contact')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d317718.69319292053!2d-0.3817765050863085!3d51.528307984912544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondres%2C+Royaume-Uni!5e0!3m2!1sfr!2sfr!4v1496781964517"
                    height="550" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-6">
            <form action="{{ route('contact') }}" method="POST">
                {{ csrf_field() }}

                @component('components.fieldset', [
                    'name' => 'name',
                    'title' => trans('validation.attributes.name'),
                ])
                    <input name="name" placeholder="@lang('validation.attributes.name')" class="form-control" v-validate="'required'" value="{{ old('name') }}">
                @endcomponent

                <div class="row">
                    <div class="col-sm-6">
                        @component('components.fieldset', [
                            'name' => 'postal_code',
                            'title' => trans('validation.attributes.postal_code'),
                        ])
                            <input name="postal_code" placeholder="@lang('validation.attributes.postal_code')" class="form-control" value="{{ old('postal_code') }}">
                        @endcomponent
                    </div>
                    <div class="col-sm-6">
                        @component('components.fieldset', [
                            'name' => 'city',
                            'title' => trans('validation.attributes.city'),
                        ])
                            <input name="city" placeholder="@lang('validation.attributes.city')" class="form-control" value="{{ old('city') }}">
                        @endcomponent
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        @component('components.fieldset', [
                            'name' => 'email',
                            'title' => trans('validation.attributes.email'),
                        ])
                            @component('components.input-group', [
                                'left' => '<i class="icon-envelope"></i>'
                            ])
                                <input type="email" name="email" placeholder="@lang('validation.attributes.email')" class="form-control" v-validate="'required|email'" value="{{ old('email') }}">
                            @endcomponent
                        @endcomponent
                    </div>
                    <div class="col-sm-6">
                        @component('components.fieldset', [
                            'name' => 'phone',
                            'title' => trans('validation.attributes.phone'),
                        ])
                            <input type="tel" name="phone" placeholder="@lang('validation.attributes.phone')" class="form-control" value="{{ old('phone') }}">
                        @endcomponent
                    </div>
                </div>

                @component('components.fieldset', [
                    'name' => 'message',
                    'title' => trans('validation.attributes.message'),
                ])
                    <textarea name="message" placeholder="@lang('validation.attributes.message')" class="form-control" rows="5" v-validate="'required'">
                        {{ old('message') }}
                    </textarea>
                @endcomponent

                <div class="form-group">
                    {!! app('captcha')->display($attributes = [], $lang = app()->getLocale()); !!}
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" value="@lang('buttons.send')">
                </div>
            </form>
        </div>
    </div>
@endsection
