@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['create']['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('widget.store') : route('widget.update', $widget->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        @include('backend.dashboard.component.content', ['offTitle' => true, 'offContent' => true, 'model' => ($widget) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.album', ['model' => ($widget) ?? null])
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nội dung widget</h5>
                    </div>
                    <div class="ibox-content model-list">
                        <div class="labelText">Chọn Module</div>
                        @foreach(__('module.model') as $key => $val)
                        <div class="model-item uk-flex uk-flex-middle">
                            <input 
                                type="radio" 
                                id="{{ $key }}" 
                                class="input-radio" 
                                value="{{ $key }}" 
                                name="model"
                                {{ (old('model', ($widget->model) ?? null) == $key) ? 'checked' : '' }}
                            >
                            <label for="{{ $key }}">{{ $val }}</label>
                        </div>
                        @endforeach

                        <div class="search-model-box">
                            <i class="fa fa-search"></i>
                            <input 
                                type="text" 
                                class="form-control search-model" 
                            >
                            <div class="ajax-search-result"></div>
                        </div>
                        @php
                            $modelItem = old('modelItem', ($widgetItem) ?? null);
                        @endphp
                        <div class="search-model-result">
                            @if(!is_null($modelItem) && count($modelItem))
                                @foreach($modelItem['id'] as $key => $val)
                                <div class="search-result-item" id="model-{{ $val }}" data-modelid="{{ $val }}">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <div class="uk-flex uk-flex-middle">
                                            <span class="image img-cover"><img src="{{ $modelItem['image'][$key] }}" alt=""></span>
                                            <span class="name">{{ $modelItem['name'][$key] }}</span>
                                            <div class="hidden">
                                                <input type="text" name="modelItem[id][]" value="{{ $val }}">
                                                <input type="text" name="modelItem[name][]" value="{{ $modelItem['name'][$key] }}">
                                                <input type="text" name="modelItem[image][]" value="{{ $modelItem['image'][$key] }}">
                                            </div>
                                        </div>
                                        <div class="deleted">
                                            <svg class="svg-next-icon svg-next-icon-size-12" width="12" height="12">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                                    <path d="M18.263 16l10.07-10.07c.625-.625.625-1.636 0-2.26s-1.638-.627-2.263 0L16 13.737 5.933 3.667c-.626-.624-1.637-.624-2.262 0s-.624 1.64 0 2.264L13.74 16 3.67 26.07c-.626.625-.626 1.636 0 2.26.312.313.722.47 1.13.47s.82-.157 1.132-.47l10.07-10.068 10.068 10.07c.312.31.722.468 1.13.468s.82-.157 1.132-.47c.626-.625.626-1.636 0-2.26L18.262 16z">
                                                        
                                                    </path>
                                                </svg>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
               @include('backend.widget.component.aside')
            </div>
        </div>
        
        <div class="text-right mb15">
            <button class="btn btn-primary" type="submit" name="send" value="send">Lưu lại</button>
        </div>
    </div>
</form>
