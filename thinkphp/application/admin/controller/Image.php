<?php
namespace app\admin\controller;

class Image
{
    public function index()
    {
        $file = request()->file('FILE');
        $info = $file->move('../public/static/upload');
        if($info){
            $data = [
                'image'=>config('live.host').'/upload/'.$info->getSaveName(),
            ];
            return show(config('code.success'),'success',$data);
        }else{
            // 上传失败获取错误信息
            return show(config('code.error'),$file->getError());
        }
    }
}
