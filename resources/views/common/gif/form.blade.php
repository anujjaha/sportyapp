<div class="box-body">
    <div class="form-group">
        {{ Form::label('name', 'Select Image :', ['class' => 'col-lg-2 control-label']) }}

        <div class="col-lg-10">
            {{ Form::file('gif', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
    </div>
</div>
