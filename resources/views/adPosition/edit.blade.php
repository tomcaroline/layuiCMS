@extends('layout.main') 
@include('layout.editOrAdd')
@section('content')
<script type="text/javascript">
    layui.use(
        ["jquery", "form", "layer", "inputTpl", "request"],
        function() {
            var form = layui.form,
                $ = layui.$,
                layer = layui.layer,
                request = layui.request,
                inputTpl = layui.inputTpl;
            var html = inputTpl.html;
            $(".layui-fluid").append(html);

            var router = layui.router();
            var id = router.search.id;
            request.zqajax({
                type: "GET",
                url: "/backend/ad/position/edit/" + id,
                success: function(data) {
                    var dataAjax = data.data;

                    var jsonData = {
                        content: [
                            {
                                label: "名称",
                                name: "name",
                                verify: "required",
                                autocomplete: "off",
                                value: dataAjax.name
                            },
                            {
                                label: "标识",
                                name: "identity",
                                verify: "required",
                                autocomplete: "off",
                                value: dataAjax.identity
                            },
                            {
                                label: "状态",
                                name: "status",
                                inputType: "checkbox",
                                skin: "switch",
                                layText: "启用|禁用",
                                value: dataAjax.status
                            }
                        ]
                    };
                    inputTpl.render(jsonData);
                }
            });

            form.on("submit(submit)", function(data) {
                var parentLayer = parent.layer;
                var index = parentLayer.getFrameIndex(window.name); //先得到当前iframe层的索引
                data = data.field;
                if (data.status != 1) {
                    data.status = 0;
                }
                data.id = id;
                request.zqajax(
                    {
                        type: "PATCH",
                        url: "/backend/ad/position/edit/" + id,
                        data: data,
                        success: function(data) {
                            if (data.code == 200) {
                                parent.layui.table.reload("tableList", {}); //父级页面表格重载
                                parentLayer.close(index); //再执行关闭
                            } else {
                                layer.msg(JSON.stringify(data.message));
                            }
                        }
                    },
                    true,
                    1
                );

                return false;
            });
        }
    );
</script>
@endsection
