<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class CurlController extends Controller
{

    static $NUM = 0;
    //
    public function showindex(){

        return view('select');


    }


    /**
     * 初始化curl
     * @param $url
     * @return mixed
     */
    public function curl($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $info = curl_getinfo($ch);
//        $this->printTime($info);
//        $this->addDatabase($info);
        return $info;
    }

    /**
     * @param $url
     * @param $second
     * @param $identity
     */
    public function selectWithTime($url,$second,$identity){
//        for($i = 0;$i<$second;$i++){
//            $this->addDatabase($curlInfo);
//            sleep(1);
//        }


        $i = 0;
        while($i<$second){
            $curlInfo = $this->curl($url);

            $this->addDatabase($curlInfo,$identity,$i+1);
            $i++;
            sleep(1);
        }
    }

    /**
     * @param $curlInfo
     * @param $identity
     * @param $second
     */
    public function addDatabase($curlInfo,$identity,$second){

        $bool = DB::table('curlInfo') -> insert([
                'total_time' => $curlInfo['total_time'],
                'namelookup_time' => $curlInfo['namelookup_time'],
                'connect_time' => $curlInfo['connect_time'],
                'pretransfer_time' => $curlInfo['pretransfer_time'],
                'starttransfer_time' => $curlInfo['starttransfer_time'],
                'redirect_time' => $curlInfo['redirect_time'],
                'url' => $curlInfo['url'],
                'http_code' => $curlInfo['http_code'],
                'redirect_count' => $curlInfo['redirect_count'],
                'content_type' => $curlInfo['content_type'],
                'header_size' => $curlInfo['header_size'],
                'request_size' => $curlInfo['request_size'],
                'filetime' => $curlInfo['filetime'],
                'ssl_verify_result' => $curlInfo['ssl_verify_result'],
                'size_upload' => $curlInfo['size_upload'],
                'size_download' => $curlInfo['size_download'],
                'speed_download' => $curlInfo['speed_download'],
                'speed_upload' => $curlInfo['speed_upload'],
                'download_content_length' => $curlInfo['download_content_length'],
                'upload_content_length' => $curlInfo['upload_content_length'],
                'identity' => $identity,
                'times' => $second,
                'created_at' => NOW(),

            ]
        );
    }


    function ajaxRefresh(Request $request) {

        $identity = $request->input('identity');
        $data = $this->selectDatabase($identity);

        if($data!="0"){
            echo json_encode($data);
        }

    }

    /**
     * @param $identity
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     */
    public function selectDatabase($identity){

        $data = DB::table("curlInfo")
            ->where('identity',$identity)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($data) {
            return $data;
        }else{
            return 0;
        }
    }

//    public function who($url){
//
//        $bool = DB::table('curlInfo')
//            ->where('url',$url)
//            ->first();
//
//        if ($bool){
//
//            return $url.NOW();
//        }else{
//
//
//        }
//    }


    public function start(Request $request){
//        $ip = $this->get_ip();
        //$ipv4 = ip2long($ip);
//        dd($ip) ;

        $url = $request->input('url');
        $second = $request->input('second');
        $identity = $url.NOW();
        Session::put('identity',$identity);
        Session::put('second',$second);
        echo $second;
        $this->selectWithTime($url,$second,$identity);
        }




    //不同环境下获取真实的IP
    function get_ip()
    {
        //判断服务器是否允许$_SERVER
        if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        //不允许就使用getenv获取
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
//            return $this->inet_itop($realip) . "\n";
        //echo inet_itop(inet_ptoi('::FFFF:FFFF')) . "\n";

        return inet_pton($realip);
        }

    /**
     * @param $curlInfo
     */
    public function printTime($curlInfo) {
        $total_time = $curlInfo['total_time'];      //获得用秒表示的上一次传输总共的时间，包括DNS解析、TCP连接等。
        $namelookup_time = $curlInfo['namelookup_time'];      //获得用秒表示的从最开始到域名解析完毕的时间。
        $connect_time = $curlInfo['connect_time'];      //获得用秒表示的从最开始直到对远程主机（或代理）的连接完毕的时间。
        $pretransfer_time = $curlInfo['pretransfer_time'];      //获得用秒表示的从最开始直到文件刚刚开始传输的时间。
        $starttransfer_time = $curlInfo['starttransfer_time'];      //获得用秒表示的从最开始到第一个字节被curl收到的时间。
        $redirect_time = $curlInfo['redirect_time'];      //获得所有用秒表示的包含了所有重定向步骤的时间，包括DNS解析、连接、传输前（pretransfer)和在最后的一次传输开始之前。
        $url = $curlInfo['url'];
        $http_code = $curlInfo['http_code']; //HTTP状态码
        $redirect_count= $curlInfo['redirect_count'];//跳转计数。
        $content_type= $curlInfo['content_type']; //内容编码
        $header_size= $curlInfo['header_size']; //header的大小。
        $request_size =$curlInfo['request_size']; //:请求的大小。
        $filetime =$curlInfo['filetime']; //:文件创建的时间。。
        $ssl_verify_result =$curlInfo['ssl_verify_result']; //:SSL验证结果。。
        $size_upload =$curlInfo['size_upload']; //:上传数据的大小。。
        $size_download =$curlInfo['size_download']; //:下载数据的大小。。
        $speed_download =$curlInfo['speed_download']; //:下载速度
        $speed_upload =$curlInfo['speed_upload']; //:上传速度
        $download_content_length =$curlInfo['download_content_length']; //:下载内容的长度
        $upload_content_length =$curlInfo['upload_content_length']; //:上传内容的长度


        echo "1. 总共的传输时间（total_time）为：" . $total_time . " 秒<br>";

        echo "2. 直到DNS解析完成时间（namelookup_time）为：" . $namelookup_time . " 秒<br>";

        echo "3. 建立连接时间（connect_time）为：" . $connect_time . " 秒<br>";

        echo "4. 传输前耗时（pretransfer_time）为：" . $pretransfer_time . " 秒<br>";

        echo "5. 开始传输（starttransfer_time）为：" . $starttransfer_time . " 秒<br>";

        echo "6. 重定向时间（redirect_time）为：" . $redirect_time . " 秒<br>";

        echo "7. url 为：" . $url . "<br>";
        echo "8. HTTP状态码 为：" . $http_code . "<br>";
        echo "9. header的大小。  为：" . $header_size . "<br>";
        echo "10. 跳转计数 为：" . $redirect_count . "<br>";
        echo "11. 内容编码 为：" . $content_type . "<br>";
        echo "12. 请求的大小 为：" . $request_size . "<br>";
        echo "13. 文件创建的时间 为：" . $filetime . "<br>";
        echo "12. SSL验证结果 为：" . $ssl_verify_result . "<br>";
        echo "12. 上传数据的大小 为：" . $size_upload . "<br>";
        echo "12. 下载数据的大小 为：" . $size_download . "<br>";
        echo "12. 下载速度 为：" . $speed_download . "<br>";
        echo "12. 上传速度 为：" . $speed_upload . "<br>";
        echo "12. 下载内容的长度 为：" . $download_content_length . "<br>";
        echo "12. 上传内容的长度 为：" . $upload_content_length . "<br>";
//        echo "12. 下载速度 为：" . $speed_download . "<br>";

    }

    /**
     * Converts human readable representation to a 128 bit int
     * which can be stored in MySQL using DECIMAL(39,0).
     *
     * Requires PHP to be compiled with IPv6 support.
     * This could be made to work without IPv6 support but
     * I don't think there would be much use for it if PHP
     * doesn't support IPv6.
     *
     * @param string $ip IPv4 or IPv6 address to convert
     * @return string 128 bit string that can be used with DECIMNAL(39,0) or false
     */

    function inet_ptoi($ip)
    {
        // make sure it is an ip
        if (filter_var($ip, FILTER_VALIDATE_IP) === false)
            return false;

        $parts = unpack('N*', inet_pton($ip));

        // fix IPv4
        if (strpos($ip, '.') !== false)
            $parts = array(1=>0, 2=>0, 3=>0, 4=>$parts[1]);

        foreach ($parts as &$part)
        {
            // convert any unsigned ints to signed from unpack.
            // this should be OK as it will be a PHP float not an int
            if ($part < 0)
                $part += 4294967296;
        }

        // Use BCMath if available
        if (function_exists('bcadd'))
        {
            $decimal = $parts[4];
            $decimal = bcadd($decimal, bcmul($parts[3], '4294967296'));
            $decimal = bcadd($decimal, bcmul($parts[2], '18446744073709551616'));
            $decimal = bcadd($decimal, bcmul($parts[1], '79228162514264337593543950336'));
        }
        // Otherwise use the pure PHP BigInteger class
        else
        {
            $decimal = new Math_BigInteger($parts[4]);
            $part3   = new Math_BigInteger($parts[3]);
            $part2   = new Math_BigInteger($parts[2]);
            $part1   = new Math_BigInteger($parts[1]);

            $decimal = $decimal->add($part3->multiply(new Math_BigInteger('4294967296')));
            $decimal = $decimal->add($part2->multiply(new Math_BigInteger('18446744073709551616')));
            $decimal = $decimal->add($part1->multiply(new Math_BigInteger('79228162514264337593543950336')));

            $decimal = $decimal->toString();
        }

        return $decimal;
    }


/**
 * Converts a 128 bit int to a human readable representation.
 *
 * Requires PHP to be compiled with IPv6 support.
 * This could be made to work without IPv6 support but
 * I don't think there would be much use for it if PHP
 * doesn't support IPv6.
 *
 * @param string $decimal 128 bit int
 * @return string IPv4 or IPv6
 */

    function inet_itop($decimal)
    {
        $parts = array();

        // Use BCMath if available
        if (function_exists('bcadd'))
        {
            $parts[1] = bcdiv($decimal, '79228162514264337593543950336', 0);
            $decimal  = bcsub($decimal, bcmul($parts[1], '79228162514264337593543950336'));
            $parts[2] = bcdiv($decimal, '18446744073709551616', 0);
            $decimal  = bcsub($decimal, bcmul($parts[2], '18446744073709551616'));
            $parts[3] = bcdiv($decimal, '4294967296', 0);
            $decimal  = bcsub($decimal, bcmul($parts[3], '4294967296'));
            $parts[4] = $decimal;
        }
        // Otherwise use the pure PHP BigInteger class
        else
        {
            $decimal = new Math_BigInteger($decimal);
            list($parts[1],) = $decimal->divide(new Math_BigInteger('79228162514264337593543950336'));
            $decimal = $decimal->subtract($parts[1]->multiply(new Math_BigInteger('79228162514264337593543950336')));
            list($parts[2],) = $decimal->divide(new Math_BigInteger('18446744073709551616'));
            $decimal = $decimal->subtract($parts[2]->multiply(new Math_BigInteger('18446744073709551616')));
            list($parts[3],) = $decimal->divide(new Math_BigInteger('4294967296'));
            $decimal = $decimal->subtract($parts[3]->multiply(new Math_BigInteger('4294967296')));
            $parts[4] = $decimal;

            $parts[1] = $parts[1]->toString();
            $parts[2] = $parts[2]->toString();
            $parts[3] = $parts[3]->toString();
            $parts[4] = $parts[4]->toString();
        }

        foreach ($parts as &$part)
        {
            // convert any signed ints to unsigned for pack
            // this should be fine as it will be treated as a float
            if ($part > 2147483647)
                $part -= 4294967296;
        }

        $ip = inet_ntop(pack('N4', $parts[1], $parts[2], $parts[3], $parts[4]));

        // fix IPv4 by removing :: from the beginning
        if (strpos($ip, '.') !== false)
            return substr($ip, 2);

        return $ip;
    }

}





