<div class="row">
    @include('files.dashboard_slice', ['files' => $files])
</div>

<script>
    $(function() {
        runPage();
    });
</script>