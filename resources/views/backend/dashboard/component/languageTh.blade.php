@foreach($languages as $language)
@if(session('app_locale') === $language->canonical) 
    @continue
@endif
<th class="text-center"><span class="image img-scaledown laguange-flag"><img src="{{ image($language->image) }}" alt=""></span></th>
@endforeach