@php
    $publish = request('publish') ?: old('publish');
@endphp
<select name="publish" class="form-control setupSelect2 ml10">
    @foreach(__('messages.publish') as $key => $val)
    <option {{ ($publish == $key)  ? 'selected' : '' }} value="{{ $key }}">{{ $val }}</option>
    @endforeach
</select>