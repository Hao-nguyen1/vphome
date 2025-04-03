@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('post.catalogue.destroy', $postCatalogue->id) }}" method="post" class="box">
    @include('backend.dashboard.component.destroy', ['model' => ($postCatalogue) ?? null])
</form>
