<div class="btn-group dropleft" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class=" fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
      <a class="dropdown-item" href="" onclick="changeStatus({{$id}}); return false;">{{$is_active ? 'Deactivate' : 'Activate'}}</a>
    </div>
  </div>