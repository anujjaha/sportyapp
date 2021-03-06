@extends ('backend.layouts.app')

@section ('title', isset($title) ? $title : 'Edit News')

@section('page-header')
    <h1>
        News
        <small>Edit</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($item, ['route' => ['admin.news.update', $item], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">News</h3>
                    <div class="box-tools pull-right">
                        @include('common.news.header-buttons', ['listRoute' => 'admin.news.index', 'createRoute' => 'admin.news.create'])
                    </div>
            </div>

            @include('common.news.form')
            
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.news.index', 'Cancel', [], ['class' => 'btn btn-danger btn-xs']) }}
                </div>

                <div class="pull-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success btn-xs']) }}
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    {{ Form::close() }}
@endsection