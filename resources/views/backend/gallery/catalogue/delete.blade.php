@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('gallery.catalogue.destroy', $galleryCatalogue->id) }}" method="post" class="box">
    @include('backend.dashboard.component.destroy', ['model' => ($galleryCatalogue) ?? null])
</form>
