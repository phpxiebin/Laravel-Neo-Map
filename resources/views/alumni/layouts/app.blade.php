<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>国际公益学院校友平台</title>

    <link href="{{ asset('alumni-asset/inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('alumni-asset/inspinia/css/style.css') }}" rel="stylesheet">
    <style type="text/css">
        #items-table_filter{
            display: none;
        }
    </style>

    @yield('styles')
</head>

<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            <!-- 引入导航 -->
            @include('alumni/layouts/nav')
        </div>

        <!-- 引入面包屑 -->
        {!! Breadcrumbs::render(Route::currentRouteName()) !!}

        <!-- 加载内容 -->
        @yield('content')

        <!-- 引入底部 -->
        @include('alumni/layouts/footer')
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{ asset('alumni-asset/inspinia/js/jquery-2.1.1.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('alumni-asset/inspinia/js/inspinia.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/pace/pace.min.js') }}"></script>

<!-- Ladda -->
<script src="{{ asset('alumni-asset/inspinia/js/plugins/ladda/spin.min.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/ladda/ladda.min.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/ladda/ladda.jquery.min.js') }}"></script>

<!-- Sweet alert -->
<script src="{{ asset('alumni-asset/inspinia/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- Toastr script -->
<script src="{{ asset('alumni-asset/inspinia/js/plugins/toastr/toastr.min.js') }}"></script>

<!-- Validate -->
<script src="{{ asset('alumni-asset/inspinia/js/plugins/validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/validate/messages_zh.min.js') }}"></script>
<script src="{{ asset('alumni-asset/inspinia/js/plugins/validate/jquery.metadata.js') }}"></script>


<script>

        // Bind normal buttons
        $('.ladda-button').ladda('bind', {timeout: 1000});

        // 删除确认模态框
        $('.wrapper').on('click', 'a.del-confirm', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            swal({
                title: $(this).data('title') || '是否确认删除?',
                text: $(this).data('text') || '此操作是删除操作，请谨慎操作',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    window.location.href = url;
                }
            });
        });

        //操作提示
        @if(!empty(session('message', '')))
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 1500
            };
            @if(session('status', 0) == 0)
                toastr.success('操作提示', '{{ session('message') }}');
            @else
                toastr.error('操作提示', '{{ session('message') }}');
            @endif
        @endif

        //手机号验证
        jQuery.validator.addMethod("isMobile", function(value, element) {
            var length = value.length;
            var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
            return this.optional(element) || (length == 11 && mobile.test(value));
        }, "请正确填写您的手机号码");

        // 联系电话(手机/电话皆可)验证
        jQuery.validator.addMethod("isPhone", function(value,element) {
            var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
            var tel = /^\d{3,4}-?\d{7,9}$/;
            return this.optional(element) || (tel.test(value) || mobile.test(value));
        }, "请正确填写您的联系电话");

        var datatables = {
            serverSide: true,
            processing: true,
            lengthMenu: [[10, 15, 50, -1], [10, 15, 50, "全部"]],
            pagingType: "full_numbers",
            language: {
                "sProcessing": "处理中...",
                "sLengthMenu": "显示 _MENU_ 项结果",
                "sZeroRecords": "没有匹配结果",
                "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                "sInfoPostFix": "",
                "sSearch": "搜索:",
                "sUrl": "",
                "sEmptyTable": "表中数据为空",
                "sLoadingRecords": "载入中...",
                "sInfoThousands": ",",
                "oPaginate": {
                    "sFirst": "首页",
                    "sPrevious": "上页",
                    "sNext": "下页",
                    "sLast": "末页"
                },
                "oAria": {
                    "sSortAscending": ": 以升序排列此列",
                    "sSortDescending": ": 以降序排列此列"
                }
            },
            order: [],
        };

</script>
@yield('script')
</body>
</html>
