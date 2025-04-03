@if(!isset($offTitle))
<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.title') }}<span class="text-danger">(*)</span></label>
            <input 
                type="text"
                name="translate_name"
                value="{{ old('translate_name', ($model->name) ?? '' ) }}"
                class="form-control"
                placeholder=""
                autocomplete="off"
            >
        </div>
    </div>
</div>
@endif
@if(!isset($offDescription))
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.description') }} </label>
            <textarea name="translate_description" class="ck-editor" id="ckDescription_1" data-height="100">{{ old('description', ($model->description) ?? '') }}</textarea>
        </div>
    </div>
</div>
@endif
@if(!isset($offContent))
<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <label for="" class="control-label text-left">{{ __('messages.content') }} </label>
                <a href="" class="multipleUploadImageCkeditor" data-target="ckContent_1">{{ __('messages.upload') }}</a>
            </div>
            <textarea
                name="translate_content"
                class="form-control ck-editor"
                placeholder=""
                autocomplete="off"
                id="ckContent_1"
                data-height="500"
            >{{ old('content', ($model->content) ?? '' ) }}</textarea>
        </div>
    </div>
</div>
@endif