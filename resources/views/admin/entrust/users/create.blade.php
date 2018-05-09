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

                    @if(entrust('admin-entrust-users-store'))

                        {!! Form::open(['route' => 'admin.entrust.users.store']) !!}

                        <div class="form-group">
                            {!! Form::label('name', '用户名') !!}
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('password', '密码') !!}
                            {!! Form::text('password', null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
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
@stop
