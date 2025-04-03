@csrf
@method('DELETE')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">Thông tin chung</div>
                <div class="panel-description">
                    <p>{{ __('messages.generalTitle') }} <span class="text-danger">{{ $model->name }}</span></p>
                    <p>{{ __('messages.generalDescription') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label for="" class="control-label text-left">{{ __('messages.tableName') }} <span class="text-danger">(*)</span></label>
                                <input 
                                    type="text"
                                    name="name"
                                    value="{{ old('name', ($model->name) ?? '' ) }}"
                                    class="form-control"
                                    placeholder=""
                                    autocomplete="off"
                                    readonly
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-right mb15">
        <button class="btn btn-danger" type="submit" name="send" value="send">{{ __('messages.deleteButton') }}</button>
    </div>
</div>