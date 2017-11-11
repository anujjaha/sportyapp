<div class="box-body">
    <div class="form-group">
        {{ Form::label('news', 'News :', ['class' => 'col-lg-2 control-label']) }}

        <div class="col-lg-10">
            {{ Form::textarea('news', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
</div>

<div class="box-body">
    <div class="form-group">
        {{ Form::label('upload_image', 'Image :', ['class' => 'col-lg-2 control-label']) }}

        <div class="col-lg-10">
            {{ Form::file('news_image', null, ['class' => 'form-control']) }}
        </div>
    </div>
</div>
