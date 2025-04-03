<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>
            <input type="checkbox" value="" id="checkAll" class="input-checkbox">
        </th>
        <th>Tên nhóm</th>
        <th>Từ khóa</th>
        <th>Danh sách Hình Ảnh</th>
        <th class="text-center">Tình Trạng</th>
        <th class="text-center">Thao tác</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($slides) && is_object($slides))
            @foreach($slides as $slide)
            <tr >
                <td>
                    <input type="checkbox" value="{{ $slide->id }}" class="input-checkbox checkBoxItem">
                </td>
                <td>
                    {{ $slide->name }}
                </td>
                <td>
                    {{ $slide->keyword }}
                </td>
                <td>
                    <div class="sortui ui-sortable table-slide clearfix">
                        @foreach($slide->item[$config['language']] as $item)
                        <li class="ui-state-default">
                            <span class="image img-cover"><img src="{{ image($item['image'])  }}" alt=""></span>
                            <div class="hidden">
                                <input type="text" name="slide[id][]" value="{{ $slide->id }}">
                                <input type="text" name="slide[name][]" value="{{ $item['name'] }}">
                                <input type="text" name="slide[image][]" value="{{ $item['image'] }}">
                                <input type="text" name="slide[alt][]" value="{{ $item['alt'] }}">
                                <input type="text" name="slide[description][]" value="{{ $item['description'] }}">
                                <input type="text" name="slide[canonical][]" value="{{ $item['canonical'] }}">
                                <input type="text" name="slide[window][]" value="{{ $item['window'] }}">
                            </div>
                        </li>
                        @endforeach
                    </div>
                </td>
                
                <td class="text-center js-switch-{{ $slide->id }}"> 
                    <input type="checkbox" value="{{ $slide->publish }}" class="js-switch status " data-field="publish" data-model="{{ $config['model'] }}" {{ ($slide->publish == 2) ? 'checked' : '' }} data-modelId="{{ $slide->id }}" />
                </td>
                <td class="text-center"> 
                    <a href="{{ route('slide.edit', $slide->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('slide.delete', $slide->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{  $slides->links('pagination::bootstrap-4') }}
