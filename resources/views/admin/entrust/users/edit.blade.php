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

                        @if(auth('admin')->user()->may('admin-entrust-users-update'))

                            {!! Form::model($adminUser, ['route' => ['admin.entrust.users.update']]) !!}

                            <div class="form-group">
                                {!! Form::hidden('id', $adminUser->id) !!}
                                {!! Form::label('name', '用户名') !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('display_name', '职位名称') !!}
                                {!! Form::text('display_name', null, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                            </div>

                            <div class="form-group">
                                <select name="role_id">
                                @foreach($roles as $v)
                                <option value="{{$v->id}}">{{$v->display_name}}</option>
                                @endforeach
                                </select>
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
