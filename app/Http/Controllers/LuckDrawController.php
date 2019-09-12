<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class LuckDrawController extends Controller
{
    public function index()
    {
    	$uid = Auth::id();
        $status = DB::table('prize')->where('uid',$uid)->first();
        if(!empty($status)){
            echo "<script>alert('你已经抽过奖了');history.go(-1)</script>";die;
        }
        $rand_num = mt_rand(1,10000);
        $time = time();

        $level = $rand_num;
        $one_arr = [1];
        $two_arr = [2,3];
        $three_arr = [4,5,6];
        $four_arr = [7,8,9,10,11,12,13,14,15,16];
        if(in_array($rand_num, $one_arr)){
            $level = 1;
        }elseif(in_array($rand_num, $two_arr)){
            $level = 2;
        }elseif(in_array($rand_num, $three_arr)){
            $level = 3;
        }elseif(in_array($rand_num, $four_arr)){
            $level = 4;
        }

        $db_level = DB::table('prize')->where('level',$level)->where('level','>',0)->count();
        if($db_level >= 1 && $level == 1){
            $level = 0;
            echo "<script>alert('恒遗憾,一等奖被别人抢光了');history.go(-1)</script>";die;
        }elseif($db_level >= 2 && $level == 2){
            $level = 0;
            echo "<script>alert('恒遗憾,二等奖被别人抢光了');history.go(-1)</script>";die;
        }elseif($db_level >= 3 && $level == 3){
            $level = 0;
            echo "<script>alert('恒遗憾,三等奖被别人抢光了');history.go(-1)</script>";die;
        }elseif($db_level >= 10 && $level == 4){
            $level = 0;
            echo "<script>alert('恒遗憾,阳光普照奖被别人抢光了');history.go(-1)</script>";die;
        }
        DB::table('prize')->insert([
            'uid'=>$uid,
            'level'=>$level,
            'add_time'=>$time
        ]);
        $prize_arr = ['一等奖','二等奖','三等奖','阳光普照奖'];
        if($level > 4 || $level == 0){
            echo "<script>alert('恒遗憾,未中奖');history.go(-1)</script>";die;
        }
        echo "<script>alert('恭喜你,中了".$prize_arr[$level]."');history.go(-1)</script>";die;
    }
}
