
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th style="width:80px;" class="text-center">{{ __('messages.tableOrder') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }}</th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }}</th>
    </tr>
    </thead>
    <tbody>
        @if(isset(${module}s) && is_object(${module}s))
            @foreach(${module}s as ${module})
            <tr id="{{ ${module}->id }}">
                <td>
                    <input type="checkbox" value="{{ ${module}->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    <div class="uk-flex uk-flex-middle">
                        <div class="image mr5">
                            <div class="img-cover image-{module}"><img src="{{ ${module}->image }}" alt=""></div>
                        </div>
                        <div class="main-info">
                            <div class="name"><span class="maintitle">{{ ${module}->name }}</span></div>
                            <div class="catalogue">
                                <span class="text-danger">{{ __('messages.tableGroup') }} </span>
                                @foreach(${module}->{module}_catalogues as $val)
                                @foreach($val->{module}_catalogue_language as $cat)
                                <a href="{{ route('{module}.index', ['{module}_catalogue_id' => $val->id]) }}" title="">{{ $cat->name }}</a>
                                @endforeach
                                @endforeach
                            </div>
                            
                        </div>
                    </div>
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => ${module}, 'modeling' => '{Module}'])
                <td>
                    <input type="text" name="order" value="{{ ${module}->order }}" class="form-control sort-order text-right" data-id="{{ ${module}->id }}" data-model="{{ $config['model'] }}">
                </td>
                <td class="text-center js-switch-{{ ${module}->id }}"> 
                    <input type="checkbox" value="{{ ${module}->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ (${module}->publish == 2) ? 'checked' : '' }} data-modelId="{{ ${module}->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('{module}.edit', ${module}->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('{module}.delete', ${module}->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  ${module}s->links('pagination::bootstrap-4') }}
