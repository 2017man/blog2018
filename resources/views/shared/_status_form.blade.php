<form action="{{ route('statuses.store') }}" method="POST">
    @include('vendor.ueditor.assets')
    @include('shared._errors')
    {{ csrf_field() }}

    {{--<textarea id="container" class="form-control" rows="3" placeholder="聊聊新鲜事儿..." name="content">{{ old('content') }}</textarea>--}}
    <div id="ueditor" class="col-lg-12">

    <!-- 编辑器容器 -->
        <script id="container" name="content" type="text/plain">{{ old('content') }}</script>


    </div>
    <button type="submit" class="btn btn-primary pull-right">发布</button>

</form>


<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
    ue.ready(function () {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
</script>



