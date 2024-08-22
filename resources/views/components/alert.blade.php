@if ($type === 'success')
    <div class="alert alert-success">
@elseif ($type === 'error')
    <div class="alert alert-danger">
@else
    <div class="alert alert-info">
@endif
    {{ $message }}
</div>
