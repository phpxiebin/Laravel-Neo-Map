@extends('alumni.layouts.app')

{{-- CSS --}}
@section('styles')
    @parent
@endsection

{{-- JS --}}
@section('script')
    @parent
    <script type="application/javascript">
        $(document).ready(function () {
            $("#signupForm").validate({
                debug: false,
                rules: {
                    name: {
                        required: true,
                        maxlength: 30
                    },
                    identity : "required",
                    class : "required",
                    stud_no : "required",
                    password_old: {
                        required: "#isedited:checked",
                        rangelength: [6, 20]
                    },
                    password: {
                        required: "#isedited:checked",
                        rangelength: [6, 20]
                    },
                    password_confirmation: {
                        required: "#isedited:checked",
                        equalTo: "#password",
                        rangelength: [6, 20]
                    }
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper" style="padding-top:20px">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="ibox-content">
                        <form method="post" id="signupForm" class="form-horizontal"
                              action="{{ url('alumni/info/alumnus') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>姓名</label>
                                    <div class="col-sm-9"><input type="text" class="form-control"
                                                                 name="name" id="name"
                                                                 value="{{ $alumnus->name or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>身份类型</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="identity">
                                            <option value="">请选择</option>
                                            @foreach(config('dataconfig.organization_identity') as $key => $value)
                                                @if(isset($alumnus->identity))
                                                    <option value="{{ $key }}" {{ $alumnus->identity == $key? 'selected=selected':''}}>{{$value}}</option>
                                                @else
                                                    <option value="{{ $key }}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>班级</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="class" id="class">
                                            <option value="">请选择</option>
                                            @foreach(config('dataconfig.alumnus_class') as $key => $value)
                                                @if(isset($alumnus->class))
                                                    <option value="{{ $key }}" {{ $alumnus->class == $key? 'selected=selected':''}}>{{$value}}</option>
                                                @else
                                                    <option value="{{ $key }}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>学号</label>
                                    <div class="col-sm-9"><input type="text" class="form-control"
                                                                 name="stud_no" id="stud_no"
                                                                 value="{{ $alumnus->stud_no or '' }}"></div>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>手机</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="phone"
                                                                 value="{{ $alumnus->phone or '' }}" readonly></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">是否修改密码(默认隐藏效果)</label>
                                <div class="col-sm-2">
                                    <input type="radio" name="isedited" value="0" onclick="clickRadio(0)"
                                           checked="checked"/>否
                                    <input type="radio" id="isedited" name="isedited" value="1" onclick="clickRadio(1)"/>是
                                </div>
                            </div>
                            <div id="checked" style="display:none">
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>旧密码</label>
                                        <div class="col-sm-9"><input type="password" class="form-control"
                                                                     name="password_old"></div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>新密码</label>
                                        <div class="col-sm-9"><input type="password" class="form-control"
                                                                     name="password" id="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>重复新密码</label>
                                        <div class="col-sm-9"><input type="password" class="form-control"
                                                                     name="password_confirmation"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-5">
                                    <button class="ladda-button btn btn-warning" data-style="zoom-in" type="reset">取消
                                    </button>
                                    <button class="ladda-button btn btn-primary" data-style="expand-right"
                                            type="submit">保存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        function clickRadio(e) {
            if (e == 0) {
                $('#checked').hide();
            } else {
                $('#checked').show();
            }
        }
    </script>
@endsection