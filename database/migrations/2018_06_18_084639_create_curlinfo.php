<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurlinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curlInfo',function (Blueprint $table){
            $table->increments('id')->comment('id');
            $table->text('total_time')->comment('上一次传输总共的时间');
            $table->text('connect_time')->comment('从最开始直到对远程主机（或代理）的连接完毕的时间');
            $table->text('namelookup_time')->comment('从最开始到域名解析完毕的时间');
            $table->text('pretransfer_time')->comment('获得用秒表示的从最开始直到文件刚刚开始传输的时间');
            $table->text('starttransfer_time')->comment('从最开始到第一个字节被curl收到的时间');
            $table->text('redirect_time')->comment('包含了所有重定向步骤的时间');
            $table->text('url')->comment('链接');
            $table->text('http_code')->comment('HTTP状态码');
            $table->text('redirect_count')->comment('跳转计数');
            $table->string('content_type')->comment('内容编码');
            $table->text('header_size')->comment('header的大小');
            $table->text('request_size')->comment('请求的大小');
            $table->text('filetime')->comment('文件创建的时间');
            $table->text('ssl_verify_result')->comment('SSL验证结果');
            $table->text('size_upload')->comment('上传数据的大小');
            $table->text('size_download')->comment('下载数据的大小');
            $table->text('speed_download')->comment('下载速度');
            $table->text('speed_upload')->comment('上传速度');
            $table->text('download_content_length')->comment('下载内容的长度');
            $table->text('upload_content_length')->comment('上传内容的长度');
            $table->text('identity')->comment('标识');
            $table->integer('times')->comment('次数');
            $table->timestamp('created_at')->comment('创建时间');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('curlInfo');
    }
}
