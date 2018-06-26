<form action="{{ route('statuses.store') }}" method="POST">

    @include('shared._errors')
    {{ csrf_field() }}

    {{--<textarea id="container" class="form-control" rows="3" placeholder="聊聊新鲜事儿..." name="content">{{ old('content') }}</textarea>--}}
    <div id="ueditor" class="col-lg-12">
        @include('UEditor::head')
        <script id="container" name="content" type="text/plain">
    这里写你的博客内容...(功能还在完善中...)
        </script>

    </div>
    <button type="submit" class="btn btn-primary pull-right">发布</button>

</form>


<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>

