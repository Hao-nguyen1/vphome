@extends('frontend.homepage.layout')
@section('content')
    <div class="post-catalogue page-wrapper intro-wrapper">
        @if(!empty($postCatalogue->image))
        <span class="image img-cover"><img src="{{ image($postCatalogue->image) }}" alt=""></span>
        @endif
        @include('frontend.component.breadcrumb', ['model' => $postCatalogue, 'breadcrumb' => $breadcrumb])
        <div class="uk-container uk-container-center">
            <div class="project-container">
                <h1 class="heading-6"><span>{{ $postCatalogue->name }}</span></h1>
                <div class="project-list">
                    @if(!is_null($posts))
                    <div class="uk-grid uk-grid-medium">
                        @foreach($posts as  $key => $post)
                        @php
                            $name = $post->languages->first()->pivot->name;
                            $description = $post->languages->first()->pivot->description;
                            $image = image($post->image);
                            $canonical = write_url($post->languages->first()->pivot->canonical);
                        @endphp
                        <div class="uk-width-medium-1-2 uk-width-large-1-3 mb20">
                            <div class="project-item-1">
                                <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                <div class="info">
                                    <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                    <div class="description">
                                        {!! $description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @include('frontend.component.pagination', ['model' => $posts])
                </div>
            </div>
        </div>

    </div>
@endsection

