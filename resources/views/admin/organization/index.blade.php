@extends('admin.layouts.app')
{{-- CSS --}}
@section('styles')
    @parent
    <link href="{{ asset('merchant/css/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('script')
    @parent
    <script type="text/javascript" src="{{ asset('merchant/js/datatables.min.js') }}"></script>
    <script>
        $(function () {
            var oTable = $('#items-table').DataTable($.extend(datatables, {
                ajax: {
                    url: '/admin/organization/data',
                    data: function (d) {
                        d.name = $('input[name=name]').val();
                        d.phone = $('input[name=phone]').val();
                        d.address = $('input[name=address]').val();
                        d.principal = $('input[name=principal]').val();
                        d.public_offering = $('select[name=public_offering]').val();
                        d.sphere = $('select[name=sphere]').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', orderable: false, searchable: false},
                    {data: 'logo', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'public_offering'},
                    {data: 'address', orderable: false, searchable: false},
                    {data: 'phone'},
                    {data: 'sphere'},
                    {data: 'coverage'},
                    {data: 'principal', orderable: false, searchable: false},
                    {data: 'status'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            }));

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });
        });
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
                            @if(entrust('admin-organization-store'))
                                <a href="/admin/organization/store" class="btn btn-primary btn-xs">
                                    <i class="fa fa fa-edit"></i>添加机构
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="ibox-content">
                        <form class="form-horizontal" id="search-form">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">机构名称</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="name"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">公募资格</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="public_offering">
                                        <option value="">请选择</option>
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">联系地址</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="address"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">机构负责人</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="principal"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">联系电话</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="phone"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">专注领域</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="sphere">
                                        <option value="">请选择</option>
                                        @foreach(config('dataconfig.activity.type') as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
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
                                    <th>机构logo</th>
                                    <th>机构名称</th>
                                    <th>公募资格</th>
                                    <th>联系地址</th>
                                    <th>联系电话</th>
                                    <th>专注领域</th>
                                    <th>服务区域</th>
                                    <th>机构负责人</th>
                                    <th>状态管理</th>
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

