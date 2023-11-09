@extends('admin.layouts.structure')

@section('header')
    @parent

    <style>

        label {
            min-width: 200px;
            text-align: right;
        }

    </style>

@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>افزودن تگ جدید</h1>
                    <div>
                        <button onclick="document.location.href = '{{route('tags.list')}}'" class="btn btn-primary">بازگشت</button>
                    </div>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages" style="height: auto!important;">

                <form method="post" action="{{isset($newsTags) ? route('tags.update', ['newsTags' => $newsTags]) : route('tags.store')}}">
                    {{ csrf_field() }}
                    <div class="col-xs-12" style="direction: rtl; text-align: center">

                        <div style="margin: 10px">
                            <label for="tag">عنوان تگ</label>
                            <input id="tag" type="text" name="tag" value="{{ isset($newsTags) ? $newsTags->tag : '' }}" />
                        </div>

                        <div style="margin: 10px">
                            <label for="tagEn">عنوان انگلیسی تگ</label>
                            <input id="tagEn" type="text" name="tagEn" value="{{ isset($newsTags) ? $newsTags->tagEn : '' }}" />
                        </div>

                        <div style="margin: 10px">
                            <input type="submit" value="تایید" />
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
@stop
