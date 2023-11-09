@extends('admin.layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="{{URL::asset('js/DataTable/jquery.dataTables.js')}}" defer></script>

@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>تگ ها</h1>
                    <div>
                        <button onclick="document.location.href = '{{route('tags.new')}}'" class="btn btn-primary">افزودن تگ جدید</button>
                    </div>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages" style="height: auto!important;">

                <div class="col-xs-12" style="direction: rtl;">

                    @if(count($tags) == 0)
                        <p>تگی موجود نیست</p>
                    @else
                        <div>
                            <table id="mainTable" class="table">

                                <thead class="thead-dark" style="background: black; color: white;">
                                    <tr>
                                        <th style="text-align: right">عنوان </th>
                                        <th style="text-align: right">عنوان انگلیسی </th>
                                        <th style="min-width: 100px"></th>
                                    </tr>
                                </thead>

                                <tbody id="tBody">
                                    @foreach($tags as $item)
                                        <tr id="news_{{$item->id}}" style="text-align: right">
                                            <td>{{$item->tag}}</td>
                                            <td>{{$item->tagEn}}</td>
                                            <td style="display: flex">
                                                <a href="{{route('tags.edit', ['newsTags' => $item->id])}}">
                                                    <button class="btn btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th style="text-align: right">عنوان </th>
                                        <th style="text-align: right">عنوان انگلیسی </th>
                                        <th style="text-align: right">اخرین ویرایش</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>
@stop
