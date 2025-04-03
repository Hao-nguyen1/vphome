@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['create']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('post.destroy', $post->id) }}" method="post" class="box">
   @include('backend.dashboard.component.destroy', ['model' => $post])
</form>
