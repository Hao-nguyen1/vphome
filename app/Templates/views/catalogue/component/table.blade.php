<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style="width:50px;">
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>{{ __('messages.tableName') }}</th>
        @include('backend.dashboard.component.languageTh')
        <th class="text-center" style="width:100px;">{{ __('messages.tableStatus') }} </th>
        <th class="text-center" style="width:100px;">{{ __('messages.tableAction') }} </th>
    </tr>
    </thead>
    <tbody>
        @if(isset(${module}s) && is_object(${module}s))
            @foreach(${module}s as ${module})
            <tr >
                <td>
                    <input type="checkbox" value="{{ ${module}->id }}" class="input-checkbox checkBoxItem">
                </td>
               
                <td>
                    {{ str_repeat('|----', ((${module}->level > 0)?(${module}->level - 1):0)).${module}->name }}
                </td>
                @include('backend.dashboard.component.languageTd', ['model' => ${module}, 'modeling' => '{Module}'])
                <td class="text-center js-switch-{{ ${module}->id }}"> 
                    <input type="checkbox" value="{{ ${module}->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ (${module}->publish == 2) ? 'checked' : '' }} data-modelId="{{ ${module}->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('{view}.edit', ${module}->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('{view}.delete', ${module}->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  ${module}s->links('pagination::bootstrap-4') }}
