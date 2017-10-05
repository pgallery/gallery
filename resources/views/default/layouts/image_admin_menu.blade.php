
<center>

<a href="{{ route('install-image', ['id' => $image['id']]) }}" class="btn btn-success btn-xs">
    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
</a>

<a href="{{ route('rebuild-image', ['id' => $image['id']]) }}" class="btn btn-success btn-xs">
    <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
</a>

<a href="{{ route('rotate-image', ['option' => 'left', 'id' => $image['id']]) }}" class="btn btn-success btn-xs">
    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> 
</a>

<a href="{{ route('rotate-image', ['option' => 'right', 'id' => $image['id']]) }}" class="btn btn-success btn-xs">
    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
</a>

<a href="{{ route('rotate-image', ['option' => 'top', 'id' => $image['id']]) }}" class="btn btn-success btn-xs">
    <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
</a>

<a href="{{ route('delete-image', ['id' => $image['id']]) }}" data-toggle="confirmation" data-title="Удалить фотографию?" class="btn btn-danger btn-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
</a>
    
    
    
</center>