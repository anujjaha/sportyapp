@extends ('backend.layouts.app')

@section ('title', 'Posts Management')

@section('page-header')
    <h1>Posts Management</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Posts Management</h3>

            <div class="box-tools pull-right">
                
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">

                @foreach($posts as $post)
                    @if($post->is_wowza == 1)
                         @continue 
                    @endif
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <img width="60" height="60" style="border-radius: 25%;" src="{{ URL::to('/').'/uploads/users/'.$post->user->image }}">
                                <br>
                                <span>

                                {{$post->user->name}}
                                </span>
                            </div>
                            <div class="col-md-9">
                                @if(isset($post->image))
                                    <img width="300" height="200" src="{{ URL::to('/').'/uploads/posts/'.$post->image  }}"> 
                                @else
                                    {{ $post->description  }}
                                @endif
                            </div>
                            <br>
                            <p>
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i>{{ count($post->post_likes) }}
                                <i class="fa fa-commenting-o" aria-hidden="true"></i>{{ count($post->post_comments) }}
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">History</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            {!! history()->renderType('News') !!}
        </div>
    </div>
@endsection
