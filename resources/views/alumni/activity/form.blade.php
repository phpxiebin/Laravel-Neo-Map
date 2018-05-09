@extends('alumni.layouts.app')

{{-- CSS --}}
@section('styles')
    @parent
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/bootstrap-fileinput/fileinput.min.css') }}"
          rel="stylesheet">
@endsection

{{-- JS --}}
@section('script')
    @parent
    <script src="{{ asset('alumni-asset/inspinia/js/allCity.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/bootstrap-fileinput/fileinput.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/bootstrap-fileinput/locales/zh.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/layer/laydate/laydate.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $(":file").fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
                @if (isset($activity->cover))
                initialPreview: [
                    "<img src='{{ $activity->cover }}?x-oss-process=image/resize,w_330' class='kv-preview-data file-preview-image'/>",
                ],
                @endif
            });

            var ue = UE.getEditor('details', {initialFrameHeight: 400});
            ue.ready(function () {
                //设置编辑器的内容
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });

            var validator = $("form").submit(function () {
                UE.getEditor('details').sync();
            }).validate({
                debug: false,
                ignore: "",
                rules: {
                    @if(getCurrentAction()['method'] == 'store')
                    cover: {
                        required: true,
                    },
                    @else
                    status: "required",
                    @endif
                    title: {
                        required: true,
                        maxlength: 50
                    },
                    type: "required",
                    begin_date: "required",
                    end_date: "required",
                    province: "required",
                    city: "required",
                    district: "required",
                    address: {
                        required: true,
                        maxlength: 60
                    },
                    display: "required",
                    digest: {
                        maxlength: 60
                    },
                    number_limitation: {
                        digits: true
                    },
                    details: "required"
                },
                errorPlacement: function (label, element) {
                    if (element.is("textarea")) {
                        console.log(element.next());
                    }
                    label.insertAfter(element.is("textarea") ? element.next() : element);
                }
            });
            validator.focusInvalid = function () {
                if (this.settings.focusInvalid) {
                    try {
                        var toFocus = $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []);
                        if (toFocus.is("textarea")) {
                            UE.getEditor('details').focus()
                        } else {
                            toFocus.filter(":visible").focus();
                        }
                    } catch (e) {
                    }
                }
            }
        });
        var start = {
            elem: "#begin_date",
            format: "YYYY-MM-DD hh:mm:ss",
            min: laydate.now(),
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

        var provinces = regions;
        var sel1 = document.getElementById('sel1');
        var sel2 = document.getElementById('sel2');
        var sel3 = document.getElementById("sel3");

        show("{{ $activity->province or '' }}", "{{ $activity->city or '' }}", "{{ $activity->district or '' }}");

        function show(prov, city, district) { //一级select
            for (var i = 0; i < provinces.length; i++) {
                var opt = document.createElement('option');
                opt.innerHTML = provinces[i].name;
                opt.value = provinces[i].id;
                sel1.appendChild(opt);
            }
            if (prov) {
                sel1.value = prov;

                var specialCity = ['710000', '810000', '820000']; //台湾，香港，澳门
                if (prov == specialCity[0] || prov == specialCity[1] || prov == specialCity[2]) {
                    sel2.value = '';
                    sel3.value = '';
                    sel2.setAttribute("disabled", "true");
                    sel3.setAttribute("disabled", "true");
                } else {
                    show2(prov, city, district);
                }
            }
            sel1.onchange = function () {
                sel1[0].setAttribute("disabled", "disabled");
                var specialCity = ['710000', '810000', '820000']; //台湾，香港，澳门
                if (this.value == specialCity[0] || this.value == specialCity[1] || this.value == specialCity[2]) {
                    sel2.value = '';
                    sel3.value = '';
                    sel2.setAttribute("disabled", "true");
                    sel3.setAttribute("disabled", "true");
                } else {
                    show2(this.value);
                    sel2.removeAttribute("disabled");
                    sel3.removeAttribute("disabled");
                }
            }
        }

        function show2(prov, city, district) { //二级select
            for (var i = 0; i < provinces.length; i++) {
                if (provinces[i].id == prov && provinces[i].regions) {
                    var cities = provinces[i].regions;
                    sel2.innerHTML = "<option disabled>请选择</option>";
                    for (var j = 0; j < cities.length; j++) {
                        var opt = document.createElement('option');
                        opt.innerHTML = cities[j].name;
                        opt.value = cities[j].id;
                        sel2.appendChild(opt);
                    }
                    if (city) {
                        sel2.value = city;
                        show3(prov, city, district);
                    } else {
                        show3(prov, cities[0].id);
                    }

                    break;
                }
            }
            sel2.onchange = function () {
                show3(prov, this.value);
            }
        }

        function show3(prov, city, district) { //三级select
            for (var i = 0; i < provinces.length; i++) {
                if (provinces[i].id == prov) {
                    var cities = provinces[i].regions;
                    for (var j = 0; j < cities.length; j++) {
                        if (cities[j].id == city) {
                            var counties = cities[j].regions;
                            sel3.innerHTML = "<option disabled>请选择</option>";
                            for (var k = 0; k < counties.length; k++) {
                                var opt = document.createElement("option");
                                opt.innerHTML = counties[k].name;
                                opt.value = counties[k].id;
                                sel3.appendChild(opt);
                            }
                            if (district) {
                                sel3.value = district;
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }
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
                        <form method="post" class="form-horizontal" enctype="multipart/form-data" action="">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>活动封面</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="file" name="cover">
                                        <div id="errorBlock" class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>活动标题</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="title"
                                                                 value="{{ $activity->title or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>活动类型</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="type">
                                            <option value="">请选择</option>
                                            @foreach(config('dataconfig.activity.type') as $key =>$value)
                                                <option value="{{ $key }}" {{isset($activity)&&$activity->type==$key?'selected=selected':''}}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>活动时间</label>
                                    <div class="col-sm-9">
                                        <input placeholder="开始日期" class="form-control layer-date" id="begin_date"
                                               name="begin_date" value="{{ $activity->begin_date or '' }}">
                                        <input placeholder="结束日期" class="form-control layer-date" id="end_date"
                                               name="end_date" value="{{ $activity->end_date or '' }}">
                                    </div>
                                </div>

                                @if(getCurrentAction()['method'] != 'store')
                                    <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                    class="text-danger">*</span>活动状态</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="status">
                                                <option value="">请选择</option>
                                                <option value="1" {{ isset($activity)&&$activity->status == 1?"selected=selected":'' }}>
                                                    报名中
                                                </option>
                                                <option value="2" {{ isset($activity)&&$activity->status == 2?'selected=selected':'' }}>
                                                    已结束
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>所在地域</label>
                                    <div class="col-sm-3">
                                        <select class="form-control col-sm-6" id="sel1" name="province">
                                            <option value="">请选择</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="sel2" name="city">
                                            <option value="">请选择</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="sel3" name="district">
                                            <option value="">请选择</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span
                                                class="text-danger">*</span>活动地点</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="address"
                                                                 value="{{ $activity->address or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label">是否隐藏</label>
                                    <div class="col-sm-9">
                                        <input type="radio" name="display"
                                               value="0" {{ empty($activity->display)?"checked='checked'":'' }}/>否
                                        <input type="radio" name="display"
                                               value="1" {{ isset($activity)&&$activity->display == 1?"checked='checked'":'' }}/>是
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label">活动摘要</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="digest"
                                                                 value="{{ $activity->digest or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label">活动人数</label>
                                    <div class="col-sm-9"><input type="text" class="form-control"
                                                                 name="number_limitation"
                                                                 value="{{ $activity->number_limitation or '' }}"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-12"><label class="col-sm-1 control-label"><span
                                                class="text-danger">*</span>活动详情</label>
                                    <div class="col-sm-11 edui-default" id="ueditor">
                                    @include('vendor.ueditor.assets')
                                    <!-- 加载编辑器的容器 -->
                                        <textarea id="details" name="details"
                                                  type="text/plain"> {!! $activity->details or '' !!}</textarea>
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
@endsection