@extends('frontend.homepage.layout')
@section('content')
    <div class="post-catalogue page-wrapper intro-wrapper">
        <span class="image img-cover"><img src="{{ image($postCatalogue->image) }}" alt=""></span>
        
        @include('frontend.component.breadcrumb', ['model' => $postCatalogue, 'breadcrumb' => $breadcrumb])
        <div class="uk-container uk-container-center">
            <div class="panel-body">
               <div class="sub-heading">Xin Chào!</div>
               <h1 class="cat-heading">Chào mừng đến với <strong>Omega</strong> <span>Deco</span></h1>
               <div class="description">
                    {!! $postCatalogue->description !!}
               </div>
            </div>
            
        </div>
        @if(!is_null($widgets['post-catalogue-value']))
            @foreach($widgets['post-catalogue-value']->object as $key => $val)
            @php
                $catName = $val->languages->first()->pivot->name;
            @endphp
            <div class="panel-value">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="welcome">Giá trị cốt lõi</div>
                        <div class="title">mà <strong>Omega Deco</strong> hướng tới</div>
                        <div class="notice">Giá trị cốt lõi của công ty chúng tôi thông qua 3 yếu tố để đem lại sự hài lòng cho khách hàng</div>
                    </div>
                    <div class="panel-body">
                        @if(!is_null($val->posts))
                        <div class="uk-grid uk-grid-large">
                            @foreach($val->posts as $keyPost => $post)
                            @php
                                $name = $post->languages->first()->pivot->name;
                                $description = $post->languages->first()->pivot->description
                            @endphp
                            <div class="uk-width-small-1-1 uk-width-medium-1-3">
                                <div class="value-item">
                                    <span class="text">Giá trị</span>
                                    <span class="pic-1 bg-{{ $keyPost }}"></span>
                                    <span class="pic-2 bg-{{ $keyPost }}"></span>
                                    <span class="pic-3 bg-{{ $keyPost }}"></span>
                                    <span class="pic-4">0{{ $keyPost + 1 }}</span>
                                    <div class="title">{{ $name }}</div>
                                    <div class="description">
                                        {!! $description !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        @if(!is_null($widgets['vision']))
            @foreach($widgets['vision']->object as $key => $val)
            <div class="panel-vision">
                <div class="uk-container uk-container-center">
                    <div class="panel-body">
                        @foreach($val->posts as $keyPost => $post)
                        @php
                            $name = $post->languages->first()->pivot->name;
                            $description = $post->languages->first()->pivot->description;
                            $image = $post->image;
                        @endphp
                        <div class="vision-item">
                            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                                <div class="uk-width-large-1-2">
                                    <div class="info">
                                        <div class="title"><span>{{ $name }}</span></div>
                                        <div class="description">
                                            {!! $description !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-large-1-2">
                                    <div class="vision-image">
                                        <span class="img-cover image"><img src="{{ $image }}" alt="{{ $name }}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        @if(!is_null($widgets['post-catalogue-why']))
            @foreach($widgets['post-catalogue-why']->object as $key => $val)
            @php
                $catName = $val->languages->first()->pivot->name;
                $catDescription = $val->languages->first()->pivot->description;
            @endphp
            <div class="panel-whyus-2 page">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <h2 class="heading-1"><span>{!! $catName !!}</span></h2>
                        <div class="description">{!! $catDescription !!}</div>
                    </div>
                    @if(!is_null($val->posts))
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            @foreach($val->posts as $keyPost => $post)
                            @php
                                $name = $post->languages->first()->pivot->name;
                                $description = $post->languages->first()->pivot->description;
                                $image = $post->image;
                            @endphp
                            <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 ul-width-large-1-4 mb30 wow animate fadeInRight" data-wow-delay="0.<?php echo $keyPost + 1 ?>s">
                                <div class="whyus-item">
                                    <span class="image"><img src="{{ $image }}" alt="{{ $name }}"></span>
                                    <div class="info">
                                        <h3 class="title"><span title="{{ $name }}">{{ $name }}</span></h3>
                                        <div class="description">
                                            {!! $description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @endif


        @if(!is_null($widgets['staff']))
            @foreach($widgets['staff']->object as $key => $val)
            @php
                $catName = $val->languages->first()->pivot->name;
                $catDescription = $val->languages->first()->pivot->description;
            @endphp
            <div class="panel-staff page">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <h2 class="heading-1"><span>{!! $catName !!}</span></h2>
                        <div class="description">{!! $catDescription !!}</div>
                    </div>
                    <div class="panel-body">
                        @if(!is_null($val->posts))
                        <div class="uk-grid uk-grid-medium">
                            @foreach($val->posts as $keyPost => $post)
                            @php
                                $name = $post->languages->first()->pivot->name;
                                $description = $post->languages->first()->pivot->description;
                                $image = image($post->image);
                            @endphp
                            <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb20 wow fadeInLeft" data-wow-delay="0.{{ $keyPost + 1 }}s">
                                <div class="staff-item">
                                    <span class="image img-scaledown"><img src="{{ $image }}" alt="{{ $name }}"></span>
                                    <div class="info">
                                        <div class="title">{{ $name }}</div>
                                        <div class="description">{!! $description !!}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif

    </div>
@endsection

