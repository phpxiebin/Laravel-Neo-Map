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
                        @if(auth('admin')->user()->may('admin-entrust-roles-update'))
                            @if (isset($role))
                                {!! Form::model($role, ['route' => ['admin.entrust.roles.update']]) !!}
                                <div class="form-group">
                                    {!! Form::hidden('id', $role->id) !!}
                                    {!! Form::label('name', '名称') !!}
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('display_name', '展示名称') !!}
                                    {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', '简介') !!}
                                    {!! Form::text('description', null, ['class' => 'form-control']) !!}
                                </div>

                                <div class="form-group">
                                    @foreach ($datas as $displayName => $rows)
                                        <div><strong>{!! $displayName !!}</strong></div>
                                        @foreach ($rows as $row)
                                            @if ($row['isset'] == 1)
                                                {!! $row['description'] !!} {!! Form::checkbox('permission_role[]', $row['id'], true) !!}&nbsp;&nbsp;
                                            @else
                                                {!! $row['description'] !!} {!! Form::checkbox('permission_role[]', $row['id']) !!}&nbsp;&nbsp;
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    {!! Form::submit('更新', ['class' => 'btn btn-primary']) !!}
                                </div>
                                {!! Form::close() !!}
                            @endif
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
