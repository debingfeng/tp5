<?php
namespace app\youdao\controller;
use think\Controller;
use app\youdao\model\Note;
use think\Request;

class Index extends Controller
{

    public function index()
    {
        $request = Request::instance();

        $get = $request->get();

        $page = isset($get['page']) ? $get['page'] : 1;

        $pageSize = isset($get['pagesize']) ? $get['pagesize'] : 10;

        $start = ($page - 1)*$pageSize + 1;

        // 实例化模型，保存至数据库
        $note = new Note;

        $pageTotal = ceil(($note->count('id')) /$pageSize);

        $list = $note->where(['level'=>0,'status'=>['>',0]])
            ->order('id', 'desc')
            ->limit($start,$pageSize)
            ->select();
        return json(array('code'=>200,'msg'=>'获取成功','data'=>array(
            'pageTotal'=> $pageTotal,
            'list'=> $list
        )));
    }
    public function latest()
    {
        $request = Request::instance();

        $get = $request->get();

        $page = isset($get['page']) ? $get['page'] : 1;

        $pageSize = isset($get['pagesize']) ? $get['pagesize'] : 10;

        $start = ($page - 1)*$pageSize + 1;

        // 实例化模型，保存至数据库
        $note = new Note;

        $pageTotal = ceil(($note->count('id')) /$pageSize);

        $list = $note->where(['status'=>['>',0],'type'=>1])
            ->limit($start,$pageSize)
            ->order('modify_time', 'desc')
            ->select();
        return json(array('code'=>200,'msg'=>'获取成功','data'=>array(
            'pageTotal'=> $pageTotal,
            'list'=> $list
        )));
    }
    public function findItem()
    {
        // 获取客户端提交的数据
        $post = Request::instance()->only(['id'],'param');
        // 实例化模型，保存至数据库
        $note = new Note;
        $list = $note->where(['status'=>['>',0],'level'=>$post['id']])
            ->order('modify_time', 'desc')
            ->select();
        return json($list);
    }
    public function detail()
    {
        // 获取客户端提交的数据
        $post = Request::instance()->only(['id'],'param');
        // 实例化模型，保存至数据库
        $note = new Note;
        $list = $note->where(['id'=>$post['id'],'status'=>['>',0]])
            ->field(['id','title','content','modify_time','type'])
            ->select();
        return json($list);
    }
    // 添加数据
    public function add(){
        $request = Request::instance();
        if (!$request->isPost()) {
            return json(array('code'=>110,'desc'=>'非法请求','data'=>null));
        }
        // 获取客户端提交的数据
        $post = $request->param();
        $post['insert_time'] = time();
        $post['modify_time'] = time();
        if (isset($post['content'])) {
            $post['count'] = strlen($post['content']);
        }


        // 验证客户端传输的数据
        $validate = validate('Note');
        if( !$validate->check($post) ){
            return json(array('code'=>110,'desc'=>'参数出错','data'=>[$validate->getError()]));
        }
        // 实例化模型，保存至数据库
        $note = new Note;
        $note->data($post);
        $result = $note->allowField(true)->data($post)->save();

        if ( $result ) {
            return json(array('code'=>100,'desc'=>'添加成功','data'=>['id'=>$note->id]));
        } else {
            return json(array('code'=>101,'desc'=>'添加失败','data'=>null));
        }

    }
    // 更新数据
    public function update(){

        // 获取客户端提交的数据
        $post = Request::instance()->only(['title','content','id'],'param');

        $data['modify_time'] = time();
        $data['title'] = $post['title'];
        $data['content'] = $post['content'];
        $post['count'] = strlen($post['content']);
        // 验证客户端传输的数据
        $validate = validate('Note');
        if( !$validate->check($data) ){
            return json(array('code'=>110,'desc'=>'参数出错','data'=>[$validate->getError()]));
        }
        // 实例化模型，保存至数据库
        $note = new Note;
        $note->data($post);
        $result = $note->where('id', $post['id'])->update($data);

        if ( $result ) {
            return json(array('code'=>100,'desc'=>'更新数据成功','data'=>['id'=>$note->id]));
        } else {
            return json(array('code'=>101,'desc'=>'更新数据失败','data'=>null));
        }

    }
    public function search() {

        $request = Request::instance();

        $key = $request->post('key');

        $page = 1;

        $pageSize = 10;

        $start = ($page - 1)*$pageSize + 1;

        // 实例化模型，保存至数据库
        $note = new Note;

        $pageTotal = ceil(($note->count('id')) /$pageSize);

        $list = $note->where(['type'=>[2,1],'status'=>['>',0]])
            ->where('type','<',3)
            ->order('id', 'desc')
            ->limit($start,$pageSize)
            ->select();

        return json(array('code'=>200,'msg'=>'获取成功','data'=>array(
            'pageTotal'=> $pageTotal,
            'list'=> $list
        )));

    }
    public function del() {
        // 获取客户端提交的数据
        $post = Request::instance()->only(['id'],'param');
        // 验证客户端传输的数据
        if( empty($post['id'])  ){
            return json(array('code'=>110,'desc'=>'参数出错','data'=>["必传参数未传"]));
        }
        $post['modify_time'] = time();
        $post['status'] = 0;
        // 实例化模型，保存至数据库
        $note = new Note;
        $note->data($post);
        $result = $note->where('id', $post['id'])->update($post);

        if ( $result ) {
            return json(array('code'=>100,'desc'=>'删除成功','data'=>['id'=>$note->id]));
        } else {
            return json(array('code'=>101,'desc'=>'删除失败','data'=>null));
        }
    }
}
