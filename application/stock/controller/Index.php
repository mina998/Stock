<?php
namespace app\stock\controller;
use think\Controller;
use think\Validate;
use think\Request;
use app\stock\model\Main;
use app\stock\model\Lists;

class Index extends Controller{

    //添加
    public function add(){

    	$request = Request::instance();
    
        if ($request::instance()->isPost()) {

            $data = [];

            $post = $request->param();

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');

            if ($file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->rule('uniqid')->move('uploads/images');


                if($info){
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    $post['image'] =  $info->getFilename(); 
                }else{
                    // 上传失败获取错误信息
                    return $this->success($file->getError());
                }

            }

            $validate = new Validate(['huohao' => 'require|max:30']);

            if (!$validate->check($post)) {

                return $this->success($validate->getError());
            }
            

            $post['color'] = explode("\n", $post['color']);
            $post['sizes'] = explode("\n", $post['sizes']);

            foreach ($post['color'] as $key => $val) {

                foreach ($post['sizes'] as $k => $size) {
                    
                    $data['huohao']= $post['huohao'];
                    $data['name']  = $post['name'];
                    $data['image'] = isset($post['image']) ? $post['image'] : '';
                    $data['color'] = trim($val);
                    $data['sizes'] = trim($size);

                    if(Main::where('huohao',$data['huohao'])->where('color',$data['color'])->where('sizes',$data['sizes'])->find()){

                        continue;

                    }elseif (Main::create($data) == null) {
                        
                        return $this->error('失败');
                    }

                }
            }

            return $this->success('添加成功');

    	}

    	return $this->fetch();
    }

    //库记录
    public function index(){

        $data  = Main::order('id')->select();

        $this->assign('data',$data);

        return $this->fetch();
    }

    //进出记录
    public function lists($type=1){

        $param = Request::instance()->param();

        $validate = new Validate(['start' => 'date', 'end' => 'date']);

        if (!$validate->check($param)) {

            return $this->success($validate->getError());
        }

        $param['start'] = isset($param['start']) ? $param['start'] : '';
        $param['end']   = isset($param['end']) ? $param['end'] : date('Y-m-d',time());

        $time = $param['start'].','.$param['end'];

        $list = Lists::where('type',$type)->where('addtime', 'between', $time)->relation('main')->select();

        $this->assign('data',$list);

        return $this->fetch();
    }

    //添加进出记录
    public function update(){

         $request = Request::instance();

         if ($request->isAjax()) {

            $data = $request->param();

            $validate = new Validate(['in' => 'require|number|between:1,9999','type' =>'require|number|between:0,1']);

            if (!$validate->check($data)) {

                exit($validate->getError());
            }

            $lsits       = new Lists();

            $lsits->mid  = $data['id'];
            $lsits->num  = $data['in'];
            $lsits->type = $data['type'];

            $lsits->addtime  = date('Y-m-d',time());

            $num = $data['type'] ? $data['in'] + $data['num'] : $data['num'] - $data['in'];

            if ($lsits->save() && Main::where('id',$data['id'])->update(['num'=>$num])) {
                
                echo $num;

            }else{

                echo $lsits->getError();
            }

         }	
    }

    //修改
    public function modify($id){


        $request = Request::instance();
    
        if ($request::instance()->isPost()) {

            $post = $request->param();

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('image');

            if ($file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->rule('uniqid')->move('uploads/images');


                if($info){
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    $post['image'] =  $info->getFilename(); 
                }else{
                    // 上传失败获取错误信息
                    return $this->success($file->getError());
                }

            }

            $validate = new Validate(['huohao' => 'require|max:30']);

            if (!$validate->check($post)) {

                return $this->success($validate->getError());
            }

            if(Main::update($post) == null){

                return $this->error('更新失败','index/index');

            }else{

                return $this->success('更新成功','index/index');
            }


        }


        $main = Main::get($id);

        $this->assign('row',$main);
        return $this->fetch();
    }

    public function upmark(){

        if (Request::instance()->isAjax()) {

            $data = Request::instance()->param();
            
            $obj = Main::update($data);

            return $obj->mark;
        }
    }

    public function selurls($id){

        if (Request::instance()->isAjax()) {

            $string = Main::get($id)->urls;

            $array  = explode("\n",$string);

            $urls   = '<ul>';

            foreach ($array as $key => $url) {

                if (trim($url) == true) {

                    $urls .= '<li><a href="'.$url.'" target="_blank">'.$url.'</a></li>';
                }
            }

            return $urls.'</ul>';
        }
    }

}