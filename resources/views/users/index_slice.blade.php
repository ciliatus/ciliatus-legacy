<div class="row">
    @include('logs.dashboard_slice', ['logs' => $logs])
</div>

<script>
    $(function() {
        runPage();
    });
</script>