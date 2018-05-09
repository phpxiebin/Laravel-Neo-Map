@extends('admin.layouts.app')

{{-- CSS --}}
@section('styles')
    @parent
@endsection

{{-- JS --}}
@section('script')
    @parent
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
                        <form method="post" id="signupForm" class="form-horizontal" action="">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>姓名</label>
                                    <div class="col-sm-9"><input type="text" required="" class="form-control"
                                                                 name="name" id="name"
                                                                 value="{{ $alumnus->name or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>身份类型</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" required="" name="identity">
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
                                                class="text-danger">*</span>所属机构</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="organization_id"
                                                id="organization_id">
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
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>学号</label>
                                    <div class="col-sm-9"><input type="text" required="" class="form-control"
                                                                 name="stud_no" id="stud_no"
                                                                 value="{{ $alumnus->stud_no or '' }}"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>班级</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" required="" name="class" id="class">
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
                                                class="text-danger">*</span>手机</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="phone"
                                                                 id="phone"
                                                                 value="{{ $alumnus->phone or '' }}"></div>
                                </div>
                            </div>
                            @if(getCurrentAction()['method'] != 'store')
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">是否修改密码(默认隐藏效果)</label>
                                    <div class="col-sm-2">
                                        <input type="radio" name="isedited" value="0" onclick="clickRadio(0)"
                                               checked="checked"/>否
                                        <input type="radio" name="isedited" id="isedited" value="1"
                                               onclick="clickRadio(1)"/>是
                                    </div>
                                </div>
                            @endif
                            <div id="checked" @if(getCurrentAction()['method'] != 'store')style="display:none"@endif>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>新密码</label>
                                        <div class="col-sm-9">
                                            <input id="password" name="password" class="form-control" type="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>重复新密码</label>
                                        <div class="col-sm-9">
                                            <input id="password_confirmation" name="password_confirmation"
                                                   class="form-control" type="password">
                                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请再次输入您的密码</span>
                                        </div>
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
    <script>
        function clickRadio(e) {
            if (e == 0) {
                $('#checked').hide();
            } else {
                $('#checked').show();
            }
        }
        $(document).ready(function () {
            $("#signupForm").validate({
                debug: false,
                rules: {
                    name: {
                        required: true,
                        maxlength: 30
                    },
                    organization_id: "required",
                    identity: "required",
                    class: "required",
                    stud_no: "required",
                    phone: {
                        required: true,
                        isMobile:true
                    },
                    @if(getCurrentAction()['method'] != 'store')
                    password: {
                        required: "#isedited:checked",
                        rangelength: [6, 20]
                    },
                    password_confirmation: {
                        required: "#isedited:checked",
                        equalTo: "#password",
                        rangelength: [6, 20]
                    },
                    @else
                    password: {
                        required: true,
                        rangelength: [6, 20]
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                        rangelength: [6, 20]
                    }
                    @endif
                }
            });
        });
        //        $.validator.setDefaults({
        //            highlight: function(e) {
        //                $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        //            },
        //            success: function(e) {
        //                e.closest(".form-group").removeClass("has-error").addClass("has-success")
        //            },
        //            errorElement: "span",
        //            errorPlacement: function(e, r) {
        //                e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())
        //            },
        //            errorClass: "help-block m-b-none",
        //            validClass: "help-block m-b-none"
        //        }),
        //                $().ready(function() {
        //                    var e = "<i class='fa fa-times-circle'></i> ";
        //                    $("#signupForm").validate({
        //                        rules: {
        //                            phone: "required",
        //                            name: {
        //                                required: !0,
        //                                minlength: 2
        //                            },
        //                            password: {
        //                                required: !0,
        //                                minlength: 5
        //                            },
        //                            password_confirmation: {
        //                                required: !0,
        //                                minlength: 5,
        //                                equalTo: "#password"
        //                            },
        //                            identity: "required:!''",
        //                        },
        //                        messages: {
        //                            username: {
        //                                required: e + "请输入您的用户名",
        //                                minlength: e + "用户名必须两个字符以上"
        //                            },
        //                            password: {
        //                                required: e + "请输入您的密码",
        //                                minlength: e + "密码必须5个字符以上"
        //                            },
        //                            confirm_password: {
        //                                required: e + "请再次输入密码",
        //                                minlength: e + "密码必须5个字符以上",
        //                                equalTo: e + "两次输入的密码不一致"
        //                            },
        //                            phone: e + "请输入您的联系电话",
        //                            identity: e + "请选择您的身份",
        //                        }
        //                    })
        //                });
    </script>
@endsection