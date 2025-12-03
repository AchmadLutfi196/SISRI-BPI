@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        });
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'warning',
            title: '{{ session('warning') }}'
        });
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: 'info',
            title: '{{ session('info') }}'
        });
    });
</script>
@endif
