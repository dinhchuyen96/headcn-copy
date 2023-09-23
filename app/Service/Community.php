<?php

namespace App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Community {
    public static function  getAmount($str)
    {
        return preg_replace("/([^0-9\\.])/i", "", $str);
    }
    public static function slugify($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    public static function listTopicIdAllowed(){
        if (!Auth::user()->hasRole('administrator')){
            $groupHasUserId = Auth::user()->group_id;
            $listTopicInGroup = GroupUserHasTopic::where(['group_user_id'=>$groupHasUserId])->get();
            if (count($listTopicInGroup) > 0){
                $permissionTopicList = [];
                foreach ($listTopicInGroup as $topicInGroup){
                    $permissionTopicList[] = $topicInGroup->topic_id;
                }
                return $permissionTopicList ;
            }else{
                return [null,''];
            }

        }
        return [];
    }

    static function truncate($string,$length=100,$append="") {
        $string = trim($string);

        if(strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;
    }
    // Anh.ta : hàm này cho phép tạo một file zip rồi tải tất cả file theo url trong db
    public static function downloadMultipleFile($files){
        $zipname = Str::random(20) . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);

        //'storage/'.$file->url
        if (count($files) > 0){
            foreach ($files as $file) {
                $path = 'storage/'.$file->url;
                $zip->addFile($path ,basename($path));
            }

            $zip->close();
            Storage::put('/zip/' . $zipname, $zip);

            return response()->download(public_path( $zipname));
        }

        return false;
    }

    public static function downloadFile($filePath){
        $file = 'storage/'.$filePath;
        return response()->download($file);
    }
}
