@extends('admin.layouts.app')
@include('flash::message')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper" style="padding-top:20px">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>错误!</strong> <br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(auth('admin')->user()->may('admin-entrust-permissions-update'))

                            {!! Form::model($permission, ['route' => ['admin.entrust.permissions.update']]) !!}

                            <div class="form-group">
                                {!! Form::hidden('id', $permission->id) !!}
                                {!! Form::label('name', '规则名称') !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('display_name', '名称') !!}
                                {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', '简介') !!}
                                {!! Form::text('description', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::submit('更新', ['class' => 'btn btn-primary']) !!}
                            </div>

                            {!! Form::close() !!}

                        @else
                            <div class="alert alert-danger">
                                <strong>没有权限!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
