(function ($, w) {
    var modal = function (conf) {
        if (typeof conf != "object") {
            console.log("参数格式错误,必须是object类型");
            return;
        }
        ;
        return modal.self = new modal.fn.init(conf);
    };
    var dataTable = function (conf) {
        if (typeof conf != "object") {
            console.log("参数格式错误,必须是object类型");
            return;
        }
        ;
        return dataTable.self = new dataTable.fn.init(conf);
    };
    modal.Event = {
        clientList: [],
        listen: function (key, fn) {
            if (!this.clientList[key])
                this.clientList[key] = [];
            this.clientList[key].push(fn);
        },
        troggle: function () {
            var key = Array.prototype.shift.call(arguments);
            fns = this.clientList[key];
            if (!fns || fns.length === 0)
                return false;
            for (var i = 0, fn; fn = fns[i++];) {
                fn.apply(this, arguments);
            }
        },
        remove: function (key, fn) {
            var fns = this.clientList[key];
            if (!fns || fns.length === 0)
                return false;
            if (!fn) {
                fns && (fns.length = 0);
            } else {
                for (var i = fns.length - 1; i >= 0; i--) {
                    var _fn = fns[i];
                    if (_fn === fn) {
                        fns.splice(i, 1);
                    }
                }
            }
        }
    };
    modal.ajax = function (conf) {
        var type = "get";
        if (conf.type == "post") {
            type = "post";
        }
        var _this = this;
        $.ajax({
            url: conf.url,
            type: conf.type,
            data: conf.data,
            dataType: "json",
            success: function (data) {
                if (typeof conf.success == "function") {
                    conf.success(data);
                } else {
                    _this.then(data);
                }
            }
        });
    };

    modal.fn = modal.prototype = {
        vertion: 1.0,
        createHtml: function (title, body, footer) {
            var modal = ['<div class="modal fade" id="myModal' + this.id + '" tabindex="-1">'];
            modal.push('<div class="modal-dialog">');
            modal.push('<div class="modal-content">');
            modal.push('<div class="modal-header">');
            modal.push('<button type="button" class="close" data-dismiss="modal">');
            modal.push('<span>×</span></button>');
            modal.push('<h4 class="modal-title">' + title + '</h4></div>');
            modal.push('<div class="modal-body">' + body + '<div class="modal-footer">' + footer + '</div>');
            modal.push('</div></div></div>');
            var modal_html = modal.join("");
            $("body").append($(modal_html));
        },
        init: function (conf) {
            this.id = Math.floor(Math.random(1, 10) * 100000000);
            this.createHtml(conf.title, conf.body, conf.footer);
            this.scanClose();
        },
        show: function () {
            $('#myModal' + this.id).modal('show');
        },
        hide: function () {
            $('#myModal' + this.id).modal('hide');
        },
        scanClose: function () {
            var _this = this;
            $('#myModal' + this.id + " .modal-footer").find('button[close="true"]').on("click", function () {
                _this.hide();
            })
        },
        on: function (key, fun) {
            modal.Event.listen(key, fun);
            return this;
        },
        troggle: function (key, data) {
            modal.Event.troggle(key, data);
            return this;
        },
    };
    modal.fn.ajax = function (conf) {
        modal.ajax.call(this, conf);
        return this;
    };
    modal.fn.then = function (data) {
        if (data.status == "success") {
            this.hide();
            $.messager.show({
                title: data.title,
                msg: data.content,
                timeout: 1000,
                showType: 'slide'
            });
            
        } else if (data.status == "error") {
            $.messager.alert(data.title, data.content,data.status);
        }
    };
    modal.fn.init.prototype = modal.fn;
    dataTable.conf={
        serverSide: true,
        searching:true,
        dom: '<"top">rt<"bottom"ip><"clear">',
        paging: true,
        language: {
            search: "查询",
            lengthMenu: "每页 _MENU_ 条记录",
            zeroRecords: "没有找到记录",
            info: "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
            infoEmpty: "无记录",
            infoFiltered: "(从 _MAX_ 条记录过滤)"
        },
    };
    dataTable.fn = dataTable.prototype = {
        init: function (conf) {
            var baseconfig=this.scanconfig(conf);
            dataTable.instance=this.instance(conf,baseconfig);
            this.columnDefsevent(conf,conf.columnDefs[0].render());
        },
        scanconfig: function (conf) {
            if(!conf.id){
                console.log("请输入要绑定的id元素");
                return;
            }
            if(!conf.ajax){
                console.log("请输入ajax地址");
                return;
            }
            if(!conf.columns){
                console.log("请输入要初始化的字段");
                return;
            }
            var baseconfig=dataTable.conf;
            baseconfig.ajax=conf.ajax;
            baseconfig.columns=conf.columns;
            if(conf.columnDefs){
                baseconfig.columnDefs=conf.columnDefs;
            }
            return baseconfig;
        },
        on: function (key, fun) {
            modal.Event.listen(key, fun);
            return this;
        },
        troggle: function (key, data) {
            modal.Event.troggle(key, data);
            return this;
        }
    };
    dataTable.fn.columnDefsevent=function(conf,columns){
        var arr=[];
        var _this=this;
        $(columns).each(function(index,item){
            var type=$(item).attr("_type");
            if(type !==undefined){
                var nodeName=$(item).prop("nodeName");
                    $(conf.id).delegate(nodeName,"click", function () {
                        if($(this).attr("_type")==type){
                            var data=$(this).attr("_data");
                            _this.troggle(type,data);
                        }
                    });
            }
        });
    };
    dataTable.fn.instance=function(conf,baseconf){
        return $(conf.id).DataTable(baseconf);
    };
    dataTable.fn.getinstance=function(){
        return dataTable.instance;
    };
    dataTable.fn.search=function(value){
        dataTable.instance.search(value).draw();
    }
    dataTable.fn.init.prototype = dataTable.fn;
    var yii = {
        modal: modal,
        dataTable: dataTable
    };
    w.yii = yii;
})($, window)