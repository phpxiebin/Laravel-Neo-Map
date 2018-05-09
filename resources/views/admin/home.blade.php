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
    <div class="wrapper" style="padding-top:20px">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox-content m-b-sm border-bottom">
                    <div class="p-xs">
                        <div class="pull-left m-r-md">
                            <i class="fa fa-globe text-navy mid-icon"></i>
                        </div>
                        <h2>欢迎使用 国际公益学院校友管理后台</h2>
                        <span>使命愿景: 慈善引领社会文明</span>
                    </div>
                    <div class="edit_con_original edit-con-original">
                        <p align="center">
                            <img style="padding-bottom:1em;padding-top:1em;padding-left:1em;padding-right:1em;" alt="" src="http://www.cgpi.org.cn/Upload/image_thum/20160917/20160917152819_6872.jpg" width="824" height="467">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection