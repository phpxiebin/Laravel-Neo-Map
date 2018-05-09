@extends('admin.layouts.app')
{{-- CSS --}}
@section('styles')
    @parent
    <link href="{{ asset('merchant/css/datatables.min.css') }}" rel="stylesheet">
    <style>
        #_spinner{
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -35px;
            margin-top: -100px;
            z-index: 2;
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="spiner-example" id="_spinner">
            <div class="sk-spinner sk-spinner-three-bounce">
                <div class="sk-bounce1"></div>
                <div class="sk-bounce2"></div>
                <div class="sk-bounce3"></div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="wrapper" style="padding-top:20px">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h5>
                        <div class="ibox-tools">
                            {{--<a href="#" class="btn btn-primary btn-xs">--}}
                                {{--<i class="fa fa fa-upload"></i>--}}
                                {{--<span onclick="javascript:$('#addFile').click();">校友导入</span>--}}
                                {{--<input type="hidden" value="{{ csrf_token() }}" id="token">--}}
                                {{--<input type="file" multiple style="display: none;" onchange="uploadFile()" id="addFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />--}}
                            {{--</a>--}}
                            @if(entrust('admin-alumnus-store'))
                            <a href="/admin/alumnus/store" class="btn btn-primary btn-xs">
                                <i class="fa fa fa-edit"></i>添加校友
                            </a>
                                @endif
                        </div>
                    </div>

                    <div class="ibox-content">
                        <form class="form-horizontal" id="search-form">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">班级</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="class">
                                        <option value="">请选择</option>
                                        @foreach(config('dataconfig.alumnus_class') as $key =>$value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">机构名称</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="organization_name">
                                        <option value="">请选择</option>
                                        @foreach($organization as $value)
                                            @if(isset($value))
                                                <option value="{{ $value->id }}" {{ $value->id ==
                                                    (isset($alumnus)?$alumnus->organization_id : '')? 'selected=selected':''}}>
                                                    {{$value->name}}</option>
                                            @else
                                                <option value="{{ $value->id }}">{{$value->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">专业领域</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="sphere">
                                        <option value="">请选择</option>
                                        @foreach(config('dataconfig.sphere_name') as $key =>$value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">公募资格</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="public_offering">
                                        <option value="">请选择</option>
                                        <option value="0">无资格</option>
                                        <option value="1">有资格</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">姓名</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="name"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">学号</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="stud_no"></div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 control-label">激活状态</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="status">
                                        <option value="">请选择</option>
                                        <option value="0">未激活</option>
                                        <option value="1">已激活</option>
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
                                    <th>学号</th>
                                    <th>姓名</th>
                                    <th>身份</th>
                                    <th>所属机构</th>
                                    <th>专注领域</th>
                                    <th>公募资格</th>
                                    <th>班级</th>
                                    <th>创建时间</th>
                                    <th>激活状态</th>
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

@section('script')
    @parent
    <script type="text/javascript" src="{{ asset('merchant/js/datatables.min.js') }}"></script>
    <script>
        $(function () {
            var oTable = $('#items-table').DataTable($.extend(datatables, {
                ajax: {
                    url : '/admin/alumnus/data',
                    data: function (d) {
                        d.name = $('input[name=name]').val();
                        d.status = $('select[name=status]').val();
                        d.class = $('select[name=class]').val();
                        d.stud_no = $('input[name=stud_no]').val();
                        d.sphere = $('select[name=sphere]').val();
                        d.public_offering = $('select[name=public_offering]').val();
                        d.organization_name = $('select[name=organization_name]').val();
                    }
                },
                columns: [
                    {data: 'DT_Row_Index', orderable: false, searchable: false},
                    {data: 'stud_no', orderable: false},
                    {data: 'name', orderable: false, searchable: false},
                    {data: 'identity', orderable: false, searchable: false},
                    {data: 'organization_name', orderable: false, searchable: false},
                    {data: 'organization_detail.sphere', orderable: false, searchable: false},
                    {data: 'organization_detail.public_offering', orderable: false, searchable: false},
                    {data: 'class', orderable: false, searchable: false},
                    {data: 'created_at'},
                    {data: 'status'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            }));

            $('#search-form').on('submit', function (e) {
                oTable.draw();
                e.preventDefault();
            });

        });
        function uploadFile() {
            var self = $('#addFile');
            var fileType = ['xls', 'xlsx', 'csv'];
            if ( !RegExp("\.(" + fileType.join("|") + ")$", "i").test(self[0].value.toLowerCase()) ) {
                swal("格式错误！","请选择表格","error")
                self[0].value = "";
                return;
            }
            self.wrap('<form enctype="multipart/form-data" id="fileForm"/>');
            var formData = new FormData();
            formData.append('filedata', self[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').val()
                },
                url: 'alumnus/excel/import',
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function(){
                    $('#_spinner').show();
                },
                success: function(res) {
                    $('#_spinner').hide();
                    self.unwrap();

                    if(res.respCode == 0) {
                        swal("太帅了！","上传成功","success")
                    } else {
                        swal("很遗憾！",res.respMsg,"error")
                    }
                },
                error: function() {
                    $('#_spinner').hide();
                    swal("很遗憾！","上传失败","error")
                }
            })
        };
    </script>
@endsection