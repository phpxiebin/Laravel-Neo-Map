@extends('alumni.layouts.app')

{{-- CSS --}}
@section('styles')
    @parent
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/bootstrap-fileinput/fileinput.min.css') }}"
          rel="stylesheet">
    <style>
        li.search-choice {
            border: none !important;
        }
    </style>
@endsection

{{-- JS --}}
@section('script')
    @parent
    <script src="{{ asset('alumni-asset/inspinia/js/allCity.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/bootstrap-fileinput/fileinput.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/bootstrap-fileinput/locales/zh.js') }}"></script>
    <script src="{{ asset('alumni-asset/inspinia/js/plugins/layer/laydate/laydate.js') }}"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $('input[name=logo]').fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
                @if (!empty(resetOssPath($organization->logo)))
                initialPreview: [
                    "<img src='{{ $organization->logo }}?x-oss-process=image/resize,w_330' class='kv-preview-data file-preview-image'/>",
                ],
                @endif
            });

            $('input[name=avatar]').fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
                @if (!empty(resetOssPath($organization->avatar)))
                initialPreview: [
                    "<img src='{{ $organization->avatar }}?x-oss-process=image/resize,w_330' class='kv-preview-data file-preview-image'/>",
                ],
                @endif
            });

            $('input[name=donation_certificate]').fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
                @if (!empty(resetOssPath($organization->donation_certificate)))
                initialPreview: [
                    "<img src='{{ $organization->donation_certificate }}?x-oss-process=image/resize,w_330' class='kv-preview-data file-preview-image'/>",
                ],
                @endif
            });

            $('input[name=certificate]').fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
                @if (!empty(resetOssPath($organization->certificate)))
                initialPreview: [
                    "<img src='{{ $organization->certificate }}?x-oss-process=image/resize,w_330' class='kv-preview-data file-preview-image'/>",
                ],
                @endif
            });

            $(":file").fileinput({
                language: 'zh',
                showUpload: false,
                allowedFileExtensions: ['jpg', 'png', 'gif', 'jpeg'],
                elErrorContainer: '#errorBlock',
            });

            $('#submitBtn').click(function() {
                if(selValArr.length == 0 && $('select[name=coverage]').val() == '900001') {
                    alert('请选择涵盖省份')
                    return;
                }
                console.log($('select[name=coverage2]').val())
                $('button[type=submit]').click()
            })


            $("form").validate({
                debug: false,
                rules: {
                    @if(getCurrentAction()['method'] == 'store')
                    logo: "required",
                    donation_certificate: {
                        required : "#inlineRadio2:checked",
                    },
                    certificate: {
                        required : "#inlineRadio2:checked",
                    },
                    @endif
                    name: {
                        required: true,
                        maxlength: 30
                    },
                    register_date: {
                        required: true,
                    },
                    status: {
                        required: true,
                    },
                    province: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    district: {
                        required: true,
                    },
                    address: {
                        required: true,
                        maxlength: 50
                    },
                    phone: {
                        required: true,
                        isPhone:true
                    },
                    website: {
                        required: true,
                    },
                    principal: {
                        required: true,
                        maxlength: 10
                    },
                    identity: {
                        required: true,
                    },
                    mission: {
                        required: true,
                        maxlength: 200
                    },
                    coverage: "required",
                    intro: {
                        required: true,
                    },
                    total_donation: {
                        number: true
                    },
                    total_times: {
                        number: true
                    }
                }
            })
        });

        var selValArr = []; //select多选框value
        var provinces = regions; //地区js数据
        var sel1 = document.getElementById('sel1');
        var sel2 = document.getElementById('sel2');
        var sel3 = document.getElementById("sel3");

        show("{{ $organization->province or '' }}", "{{ $organization->city or '' }}", "{{ $organization->district or '' }}");

        selcover(); //select多选框事件初始化

        function show(prov, city, district) { //一级select
            for(var i = 0; i < provinces.length; i++){
                var opt = document.createElement('option');
                opt.innerHTML = provinces[i].name;
                opt.value = provinces[i].id;
                sel1.appendChild(opt);
            }
            if(prov) {
                sel1.value = prov;
                show2(prov, city, district);
            }
            sel1.onchange = function (){
                sel1[0].setAttribute("disabled","disabled");
                var specialCity = ['台湾','澳门','香港'];
                if(this.value == specialCity[0] || this.value == specialCity[1] || this.value == specialCity[2]) {
               
                } else{
                    show2(this.value);
                }
            }
        }
        function show2(prov, city, district) { //二级select
            for(var i = 0; i < provinces.length; i++){
                if(provinces[i].id == prov && provinces[i].regions){
                    var cities = provinces[i].regions;
                    sel2.innerHTML = "<option disabled>请选择</option>";
                    for(var j = 0; j < cities.length; j++){
                        var opt = document.createElement('option');
                        opt.innerHTML = cities[j].name;
                        opt.value = cities[j].id;
                        sel2.appendChild(opt);
                    }
                    if(city) {
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
            for(var i=0; i<provinces.length; i++){
                if(provinces[i].id == prov) {
                    var cities = provinces[i].regions;
                    for(var j = 0; j < cities.length; j++){
                        if(cities[j].id == city){
                            var counties = cities[j].regions;
                            sel3.innerHTML = "<option disabled>请选择</option>";
                            for(var k = 0; k < counties.length; k++){
                                var opt = document.createElement("option");
                                opt.innerHTML = counties[k].name;
                                opt.value = counties[k].id;
                                sel3.appendChild(opt);
                            }
                            if(district) {
                                sel3.value = district;
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }
        function clickRadio(e) {
            if(e == 0) {
                $('#checked').hide();
            } else {
                $('#checked').show();
            }
        }
        function selcover() {
            var selCoverage = document.getElementById('selCoverage');
            var chosenSelect = document.getElementById('chosenSelect');
            //把一级地区插入select框内
            for(var i = 0; i < provinces.length; i++){
                var opt = document.createElement('option');
                opt.innerHTML = provinces[i].name;
                opt.value = provinces[i].id;
                chosenSelect.appendChild(opt);
            }
            chosenSelect.onchange = function(evt, params) {
                if(params.selected) {
                    selValArr.push(params.selected);
                } else {
                    for(var i=0; i<selValArr.length; i++) {
                        if(selValArr[i] == params.deselected) {
                            selValArr.splice(i, 1);
                        }
                    }
                }
            }
            //select多选框初始化
            var config = {
                ".chosen-select": {},
                ".chosen-select-deselect": {
                    allow_single_deselect: !0
                },
                ".chosen-select-no-single": {
                    disable_search_threshold: 10
                },
                ".chosen-select-no-results": {
                    no_results_text: "Oops, nothing found!"
                },
                ".chosen-select-width": {
                    width: "95%"
                }
            };
            for(var selector in config) {
                $(selector).chosen(config[selector]);
            }
            //判断多选框显示隐藏事件
            $('#selMultiple').hide();
            selCoverage.onchange = function() {
                if(this.value == '900001') {
                    $('#selMultiple').show();
                } else {
                    $('#selMultiple').hide();
                }
            }
        }
        
    </script>

    <!-- 高德地图根据名称获取经纬度坐标 -->
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=7256e5b3df9c877f78c62077e312aa3a&plugin=AMap.DistrictSearch"></script>
    <script type="text/javascript">
        $(function () {
            //创建地图
            var map = new AMap.Map('container', {
                resizeEnable: true,
            });

//            //定位当前点
//            map.plugin('AMap.Geolocation', function() {
//                geolocation = new AMap.Geolocation({
//                    enableHighAccuracy: true,//是否使用高精度定位，默认:true
//                    timeout: 10000,          //超过10秒后停止定位，默认：无穷大
//                    buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
//                    zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
//                    buttonPosition:'RB'
//                });
//                map.addControl(geolocation);
//                geolocation.getCurrentPosition();
//            });

            // 获取所在城市
            function getCity(){
                var city = $("#sel2").val();
                if(city == '市辖区' || city == '市' || city == '县' || city == null){
                    city = $("#sel1").val();
                }else if(city == '省直辖行政单位' || city == '省直辖县级行政单位'){
                    city = $("#sel3").val();
                }
                return city;
            }

            // 切换省份, 城市
            $("#sel1, #sel2").on("change",function(){
                var city = getCity();
                map.setCity(city);
                console.log("选择的城市:" + city);
            });

            // 定位地点
            AMap.plugin('AMap.Geocoder',function(){
                // 标记方法
                var marker = new AMap.Marker({
                    map:map,
                    bubble:true
                });
                var input = document.getElementById('address');
                input.onchange = function(e){
                    var address = input.value;
                    $('#location').val(''); //预先清除经纬度坐标
                    if(address != ''){
                        var city = getCity();
                        var geocoder = new AMap.Geocoder({
                            city: city
                        });                        geocoder.getLocation(address,function(status,result){
                            if(status=='complete' && result.geocodes.length){
                                marker.setPosition(result.geocodes[0].location);
                                map.setCenter(marker.getPosition());
                                map.setFitView();
                                $('#location').val(result.geocodes[0].location);
                                console.log("当前输入地址:" + address + " 经纬度坐标:" + result.geocodes[0].location);
                            }else{
                                console.log('获取位置失败');
                            }
                        });
                    }else{
                        console.log('地址信息为空');
                    }
                }
                input.onchange();
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
                        <form method="post" class="form-horizontal" enctype="multipart/form-data"
                              action="{{ url('alumni/info/organization') }}" onkeydown="if(event.keyCode==13)return false;">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>机构Logo</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="file" id="test-upload" name="logo">
                                        <div id="errorBlock" class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>机构名称</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="name"
                                                                  value="{{ $organization->name or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>成立日期</label>
                                    <div class="col-sm-9">
                                        <input placeholder="YYYY-MM-DD" class="form-control layer-date" id="register_date"
                                               name="register_date" value="{{ $organization->register_date or '' }}"
                                               onclick="laydate({istime: false, format: 'YYYY-MM-DD'})">
                                        </div>
                                </div>
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
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>联系地址</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="address" id="address"
                                                                  value="{{ $organization->address or '' }}"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-md-10 col-sm-offset-1 text-center" id="container" tabindex="0" style="height: 250px;"></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>联系电话</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="phone"
                                                                 value="{{ $organization->phone or '' }}"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>官方网站</label>
                                    <div class="col-sm-9">
                                        <input id="website" type="url" class="form-control" name="website" value="{{ $organization->website or '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>机构负责人</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="principal"
                                                                  value="{{ $organization->principal or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>负责人身份</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="identity">
                                            @foreach(config('dataconfig.organization_identity') as $key => $value)
                                                <option value="{{ $key }}" {{ $organization->identity == $key? 'selected':''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label">负责人头像</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="file" name="avatar">
                                        <div id="errorBlock" class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label">募捐总善款</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="total_donation"
                                                                 value="{{ $organization->total_donation or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label">募捐总人次</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" id="total_times" name="total_times"
                                                                 value="{{ $organization->total_times or '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>组织使命</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="mission"
                                                                 value="{{ $organization->mission or '' }}"></div>
                                </div>
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>服务区域</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="coverage" id="selCoverage">
                                            <option value="">请选择</option>
                                            <option value="全国" {{ (!empty($organization) && $organization->coverage == "全国") ? 'selected':''}}>全国</option>
                                            <option value="涵盖几个省份" {{ (!empty($organization) && $organization->coverage == "涵盖几个省份") ? 'selected':''}}>涵盖几个省份</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-9" id="selMultiple" style="float:right;">
                                        <select data-placeholder="请选择涵盖的省份" id="chosenSelect" name="coverage2[]" class="chosen-select" multiple style="width:100%;" tabindex="4">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-6"><label class="col-sm-3 control-label"><span class="text-danger">*</span>专注领域</label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline">
                                            <input value="1" id="inlineCheckbox1" {{ strpos($organization->sphere,'1') ===false? '':'checked' }} name="sphere[]" type="checkbox">扶贫救灾</label>
                                        <label class="checkbox-inline">
                                            <input value="2" id="inlineCheckbox2" {{ strpos($organization->sphere,'2') ===false? '':'checked' }} name="sphere[]" type="checkbox">灾害救助</label>
                                        <label class="checkbox-inline">
                                            <input value="3" id="inlineCheckbox3" {{ strpos($organization->sphere,'3') ===false? '':'checked' }} name="sphere[]" type="checkbox">教育助学</label>
                                        <label class="checkbox-inline">
                                            <input value="4" id="inlineCheckbox3" {{ strpos($organization->sphere,'4') ===false? '':'checked' }} name="sphere[]" type="checkbox">助困行动</label>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><span class="text-danger">*</span>机构简介</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="intro"
                                                                 value="{{ $organization->intro or '' }}">
                                </div>
                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label">公募资格</label>
                                    <div class="col-sm-9">
                                        <div class="radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" {{  $organization->public_offering ? '' : 'checked'}} value="0" name="public_offering" onclick="clickRadio(0)">
                                            <label for="inlineRadio1">否</label>
                                        </div>
                                        <div class="radio-inline">
                                            <input type="radio" id="inlineRadio2" {{  $organization->public_offering ? 'checked' : ''}} value="1" name="public_offering" onclick="clickRadio(1)">
                                            <label for="inlineRadio2">是</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="checked" {{ $organization->public_offering ? '' : 'style=display:none'}}>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span class="text-danger">*</span>公开募捐证书</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="file" name="donation_certificate">
                                            <div id="errorBlock" class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-3 control-label"><span class="text-danger">*</span>法人登记证书</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="file" name="certificate">
                                            <div id="errorBlock" class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-5">
                                    <button class="ladda-button btn btn-warning" data-style="zoom-in" type="reset">取消
                                    </button>
                                    <button type="submit" style="display:none"></button>
                                    <button type="button" id="submitBtn" class="ladda-button btn btn-primary" data-style="expand-right">保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection