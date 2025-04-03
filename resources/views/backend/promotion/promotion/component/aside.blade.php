<div class="ibox slide-setting slide-normal">
    <div class="ibox-title">
        <h5>Cài đặt cơ bản</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <div for="" class="control-label text-left">Tên Widget <span class="text-danger">(*)</span></div>
                    <input 
                        type="text"
                        name="name"
                        value="{{ old('name', ($widget->name) ?? '' ) }}"
                        class="form-control"
                        placeholder=""
                        autocomplete="off"
                    >
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-row">
                    <div for="" class="control-label text-left">Từ khóa Widget<span class="text-danger">(*)</span></div>
                    <input 
                        type="text"
                        name="keyword"
                        value="{{ old('keyword', ($widget->keyword) ?? '' ) }}"
                        class="form-control"
                        placeholder=""
                        autocomplete="off"
                    >
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ibox short-code">
    <div class="ibox-title">
        <h5>Short Code</h5>
    </div>
    <div class="ibox-content">
        <textarea name="short_code" class="textarea form-control">{{ old('short_code', ($widget->short_code) ?? null) }}</textarea>
    </div>
</div>