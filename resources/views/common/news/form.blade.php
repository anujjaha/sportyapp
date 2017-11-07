<div class="box-body">
    <div class="form-group">
        {{ Form::label('news', 'News :', ['class' => 'col-lg-2 control-label']) }}

        <div class="col-lg-10">
            {{ Form::textarea('news', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
</div>
