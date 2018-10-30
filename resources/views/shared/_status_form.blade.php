<form action="{{ route('statuses.store') }}" method="POST">
    @include('shared._errors')
    {{ csrf_field() }}
    <div id="mdeditor">
      <textarea id="container" class="form-control" name="content" placeholder="聊聊新鲜事儿..." style="width:100%;display:none;">{{ old('content') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary pull-right">发布</button>

</form>





