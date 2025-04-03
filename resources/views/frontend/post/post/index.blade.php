@extends('frontend.homepage.layout')
@section('content')

<div class="post-detail">
    @if(!empty($postCatalogue->image))
    <span class="image img-cover"><img src="{{ image($postCatalogue->image) }}" alt=""></span>
    @endif
    @include('frontend.component.breadcrumb', ['model' => $postCatalogue, 'breadcrumb' => $breadcrumb])
    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium">
            <div class="uk-width-large-3-4">
                <div class="detail-wrapper">
                    <h1 class="post-title">{{ $post->name }}</h1>
                    <div class="description">
                        {!! $post->description !!}
                    </div>
                    <div class="content">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
            <div class="uk-width-large-1-4">
                @include('frontend.component.post-aside')
            </div>
        </div>
    </div>
</div>

@endsection
