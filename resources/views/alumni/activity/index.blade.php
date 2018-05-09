@extends('alumni.layouts.app')
{{-- CSS --}}
@section('styles')
    @parent
    <link href="{{ asset('merchant/css/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('script')
    @parent
    {{--<script type="text/javascript" src="{{ asset('merchant/js/site.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('merchant/js/datatables.min.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/layer/laydate/laydate.js') }}"></script>
    <script type="application/javascript">
        $(function () {
            var oTable = $('#items-table').DataTable($.extend(datatables, {
                ajax: {
                    url: '/alumni/activity/data',
                    data: function (d) {
                        d.title = $('input[name=title]').val();
                        d.province = $('select[name=province]').val();
                        d.address = $('input[name=address]').val();
                        d.status = $('select[name=status]').val();
                        d.begin_date = $('input[name=begin_date]').val();
                        d.end_date = $('input[name=end_date]').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', orderable: false, searchable: false},
                    {data: 'cover', orderable: false, searchable: false},
                    {data: 'title', orderable: false},
                    {data: 'type', orderable: false, searchable: false},
                    {data: 'province', orderable: false, searchable: false},
                    {data: 'address', orderable: false, searchable: false},
                    {data: 'begin_date'},
                    {data: 'status'},
                    {data: 'mark'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            }));

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });
        var start = {
            elem: "#begin_date",
            format: "YYYY-MM-DD hh:mm:ss",
            max: "2099-06-16 23:59:59",
            istime: true,
            istoday: false,
            choose: function (datas) {
                end.min = datas;
                end.start = datas
            }
        };
        var end = {
            elem: "#end_date",
            format: "YYYY-MM-DD hh:mm:ss",
            min: laydate.now(),
            max: "2099-06-16 23:59:59",
            istime: true,
            istoday: false,
            choose: function (datas) {
                start.max = datas
            }
        };
        laydate(start);
        laydate(end);
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper" style="padding-top:20px">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h5>
                        <div class="ibox-tools">
                            <a href="{{ url('alumni/activity/store') }}" class="btn btn-primary btn-xs">
                                <i class="fa fa fa-edit"></i>添加活动
                            </a>
                        </div>
                    </div>

                    <div class="ibox-content">
                        <form class="form-horizontal" id="search-form">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">活动标题</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="title"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">活动地点</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="address"></div>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">活动状态</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="status">
                                        <option value="">请选择</option>
                                        <option value="1">报名中</option>
                                        <option value="2">已结束</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">活动地区</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="province">
                                        <option value="">请选择</option>
                                        @foreach(config('dataconfig.province') as $key =>$value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--<div class="form-group col-sm-2">--}}
                            {{--<label class="col-sm-4 control-label">活动类型</label>--}}
                            {{--<div class="col-sm-8">--}}
                            {{--<select class="form-control" name="type">--}}
                            {{--<option value="">请选择</option>--}}
                            {{--@foreach(config('dataconfig.activity.type') as $key =>$value)--}}
                            {{--<option value="{{ $key }}">{{ $value }}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">开始时间</label>
                                <div class="col-sm-8">
                                    <input placeholder="开始日期" class="form-control layer-date" id="begin_date"
                                           name="begin_date" value="">
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">结束时间</label>
                                <div class="col-sm-8">
                                    <input placeholder="结束时间" class="form-control layer-date" id="end_date"
                                           name="end_date" value="">
                                </div>
                            </div>
                            <div class="col-sm-12 col-sm-offset-10">
                                <button class="btn btn-default" type="reset">重置</button>
                                <button class="btn btn-primary" type="submit">查询</button>
                            </div>
                        </form>

                        <div class="project-list">
                            <table id="items-table" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>活动封面</th>
                                    <th>活动标题</th>
                                    {{--<th>活动摘要</th>--}}
                                    {{--<th>发起机构</th>--}}
                                    <th>活动类型</th>
                                    <th>所在地区</th>
                                    <th>活动地点</th>
                                    <th>活动时间</th>
                                    <th>活动状态</th>
                                    <th>是否置顶</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection