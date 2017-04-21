@if((sizeof($files) > 0) || (sizeof($directories) > 0))

<div class="row">

  @foreach($items as $item)
  <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 img-row">
    <?php $item_name = $item->name; ?>
    <?php $thumb_src = $item->thumb; ?>
    <?php $folder_path = $item->path; ?>

    @if($item->is_file)
    <div class="thumbnail clickable" onclick="useFile('{{ $item_name }}')">
      <div class="square" id="{{ $item_name }}" data-url="{{ $item->url }}">
    @else
    <div class="thumbnail clickable">
      <div data-id="{{ $folder_path }}" class="folder-item square">
    @endif
        @if($thumb_src)
        <img src="{{ $thumb_src }}">
        @else
        <div class="icon-container">
          <i class="fa {{ $item->icon }} fa-5x"></i>
        </div>
        @endif
      </div>
    </div>

    <div class="caption text-center">
      <div class="btn-group">
        @if($item->is_file)
        <button type="button" data-id="{{ $folder_path }}" class="item_name btn btn-default btn-xs folder-item">
        @else
        <button type="button" onclick="useFile('{{ $item_name }}')" class="item_name btn btn-default btn-xs">
        @endif
          {{ $item_name }}
        </button>
        <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="javascript:rename('{{ $item_name }}')"><i class="fa fa-edit fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-rename') }}</a></li>
          @if($item->is_file)
          <li><a href="javascript:download('{{ $item_name }}')"><i class="fa fa-download fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-download') }}</a></li>
          <li class="divider"></li>
          @if($thumb_src)
          <li><a href="javascript:fileView('{{ $item_name }}', '{{ $item->updated }}')"><i class="fa fa-image fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-view') }}</a></li>
          <li><a href="javascript:resizeImage('{{ $item_name }}')"><i class="fa fa-arrows fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-resize') }}</a></li>
          <li><a href="javascript:cropImage('{{ $item_name }}')"><i class="fa fa-crop fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-crop') }}</a></li>
          <li class="divider"></li>
          @endif
          @endif
          <li><a href="javascript:trash('{{ $item_name }}')"><i class="fa fa-trash fa-fw"></i> {{ Lang::get('laravel-filemanager::lfm.menu-delete') }}</a></li>
        </ul>
      </div>
    </div>

  </div>
  @endforeach

</div>

@else
<p>{{ Lang::get('laravel-filemanager::lfm.message-empty') }}</p>
@endif
