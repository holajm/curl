
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <script language="javascript" src="{{asset('js/jquery-2.1.1.min.js')}}"></script>

    <script>

        function redown(second2,identity) {
            // for (var i =0;i<=second2;i++){
            //     select(i);
            // }
            // alert(second2);
            // for (var i =0;i<=second2;i++){
            //     ajax2relresh(second2,identity,url1)
            //
            // }

            setTimeout(function () {
                // clearInterval(timetest);
                setInterval(function () { //每隔1秒 js刷新一次数据
                    for (var i =0;i<=second2;i++){
                        ajax2relresh(second2,identity)
                    }
                }, 1000);

            },second2*1000)

        }

        function action() {
            var url1 =document.getElementById("url").value;

            var second0 =document.getElementById("secondstart").value;
            // alert(url1+'aaa'+second0);
            if ((url1 != "" && url1 != null )&&( second0 != "" && second0 != null )){

                $.ajax({
                    type: "get",  //用get方法对信息进行打包
                    url: "{{url('choose')}}?url="+url1+"&second="+second0, //传送信息进行验证的地址页面，
                    // dataType: "json",
                    success: function(response) //收到返回值进行的操作
                    {
                        if(response == 0){   //如果接口返回0，弹出0
                            alert("0");
                        }
                        else{
                            alert("正在实时监测，监测时间为"+response+"秒");
                            // var second2 = response;
                            // var start = document.getElementById("start");
                            {{--var identity = "{{session()->pull('identity','default')}}"--}}
                            {{--var secondtime = "{{session()->pull('second','default')}}"--}}

                            // alert(second2 + identity);
                            //$(start).remove(); //删除当前ul内的所有li




                    // div2.appendChild(ul);
                            // div.appendChild(div2);
                        }
                    }
                });


            }else {
                alert('请填写完整数据');
            }
        }

        function ajax2creat(second,identity,url) {
            $.ajax({

                type: "get",  //用get方法对信息进行打包
                url: "{{url('ajaxRefresh')}}?url="+url+"&second="+second+"&identity="+identity, //传送信息进行验证的地址页面，
                dataType: "json",
                success: function(response) //收到返回值进行的操作
                {
                    if(response == 0){   //如果接口返回0，弹出0
                        alert("0");
                    }
                    else{
                        // alert('sucess'+response);
                        // var second2 = response;
                        // var div = document.getElementById("wait");
                        // var start = document.getElementById("start");
                        // $(start).remove(); //删除当前ul内的所有li

                        var div = document.getElementById("wait");
                        var div2 = document.createElement("div");
                        div2.setAttribute("id", "div"+identity);

                        //添加 ul
                        var ul = document.createElement("ul");
                        ul.setAttribute("id", "ul"+identity);

                        $.each(response,function(key,value){

                            var data = document.createElement("li");
                            data.setAttribute("id", key);
                            data.innerHTML = returnName(key)+":\t"+value;
                            ul.appendChild(data);
                        });
                        div2.appendChild(ul);
                        div.appendChild(div2);


                        // div2.appendChild(ul);
                        // div.appendChild(div2);
                    }
                }
            });

        }

        function ajax2relresh (second,identity) {
            $.ajax({

                type: "get",  //用get方法对信息进行打包
                url: "{{url('ajaxRefresh')}}?second="+second+"&identity="+identity, //传送信息进行验证的地址页面，
                dataType: "json",
                success: function(response) //收到返回值进行的操作
                {
                    if(response == 0){   //如果接口返回0，弹出0
                        alert("0");
                    }
                    else{
                        // var second2 = response;
                        // var div = document.getElementById("wait");
                        // var start = document.getElementById("start");
                        // $(start).remove(); //删除当前ul内的所有li

                        // var div = document.getElementById("wait");
                        // var div2 = document.createElement("div");
                        // div2.setAttribute("id", "div"+identity);
                       // $(div).remove(); //删除当前ul内的所有li

                        //添加 ul
                        var ul = document.getElementById("ul"+identity);
                        $(ul).find("li").remove(); //删除当前ul内的所有li
                        $.each(response,function(key,value){

                            var data = document.createElement("li");
                            data.setAttribute("id", key);
                            data.innerHTML = returnName(key)+":\t"+value;
                            ul.appendChild(data);
                        });
                        // div2.appendChild(ul);
                        // div.appendChild(div2);


                        // div2.appendChild(ul);
                        // div.appendChild(div2);
                    }
                }
            });

        }

        function startCreat() {

            setInterval(function () { //每隔1秒 js刷新一次数据
                var identity = "{{session()->pull('identity','default')}}";
                var secondtime = "{{session()->pull('second','default')}}";

                if (identity !== 'default' && secondtime !== 'default'){

                    ajax2creat(secondtime,identity);

                    redown(secondtime,identity);

                }
            }, 200);


            {{--while (1){--}}
                {{--var identity = "{{session()->pull('identity','default')}}";--}}
                {{--var secondtime = "{{session()->pull('second','default')}}";--}}

                {{--if (identity != 'default' && secondtime!='default'){--}}

                    {{--ajax2creat(secondtime,identity);--}}

                    {{--redown(secondtime,identity);--}}

                {{--}--}}
            {{--}--}}


        }

        function waitfor() {

        }

        function returnName(key) {

            var name =new Array({'total_time':'传输总时间',
                'namelookup_time':'域名解析时间',
                'connect_time':'连接完毕时间',
                'pretransfer_time':'传输的时间',
                'starttransfer_time':'从最开始到第一个字节被curl收到的时间',
                'redirect_time':'重定向时间',
                'url':'地址',
                'http_code':'HTTP状态码',
                'redirect_count':'跳转计数',
                'content_type':'内容编码',
                'header_size':'header的大小',
                'request_size':'请求的大小',
                'filetime':'文件创建的时间',
                'ssl_verify_result':'SSL验证结果',
                'size_upload':'上传数据的大小',
                'size_download':'下载数据的大小',
                'speed_download':'下载速度',
                'speed_upload':'上传速度',
                'download_content_length':'下载内容长度',
                'upload_content_length':'上传内容的长度',
                'identity':'标识',
                'times':'次数',
                'created_at':'创建于'});

            return name[key];

            // name['total_time'] = "传输总时间";
            // name['namelookup_time'] = "传输总时间";
            // name['connect_time'] = "传输总时间";
            // name['pretransfer_time'] = "传输总时间";
            // name['starttransfer_time'] = "传输总时间";
            // name['url'] = "传输总时间";
            // name['http_code'] = "传输总时间";
            // name['redirect_count'] = "传输总时间";
            // name['content_type'] = "传输总时间";
            // name['header_size'] = "传输总时间";
            // name['request_size'] = "传输总时间";
            // name['ssl_verify_result'] = "传输总时间";
            // name['size_upload'] = "传输总时间";
            // name['size_download'] = "传输总时间";
            // name['speed_download'] = "传输总时间";
            // name['speed_upload'] = "传输总时间";
            // name['download_content_length'] = "传输总时间";
            // name['upload_content_length'] = "传输总时间";
            // name['identity'] = "传输总时间";
            // name['times'] = "传输总时间";
            // name['created_at'] = "传输总时间";
        }
    </script>
</head>
<body onload="startCreat();">

{{--<form id="choose" action="{{url("choose")}}" method="post">--}}
    {{--{{csrf_field()}}--}}
<div id="start">
    {{--<form id="start0">--}}
    <p>请输入URL <input id="url" name="url" type="text" ></p>
    <p>请输入监测时间（单位//秒）<input id="secondstart" name="secondstart" type="text"></p>
    <P><input id="submit" name="submit" value="开始监测" type="button" onclick="action();"></P>
    {{--</form>--}}
</div>
<div id="wait">
</div>

</body>
</html>
