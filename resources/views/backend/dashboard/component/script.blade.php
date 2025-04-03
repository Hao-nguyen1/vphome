<!-- Mainly scripts -->
<script src="backend/js/bootstrap.min.js"></script>
<script src="backend/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="backend/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="backend/plugins/jquery-ui.js"></script>



<script src="backend/js/inspinia.js"></script>
{{-- <script src="backend/js/plugins/pace/pace.min.js"></script> --}}
<!-- jQuery UI -->
<script src="backend/js/plugins/toastr/toastr.min.js"></script>
@if(isset($config['js']) && is_array($config['js']))
    @foreach($config['js'] as $key => $val)
        {!! '<script src="'.$val.'"></script>' !!}
    @endforeach
@endif

<script src="backend/library/library.js"></script>