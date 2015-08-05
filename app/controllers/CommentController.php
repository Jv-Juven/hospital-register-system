<?php

class CommentController extends BaseController{

    public function get_user_comments(){
        
        // disgusting
        // 怎么改进
        $comments_per_page = Input::get( 'comments_per_page', 10 );

        $comments = Comment::select( 'comments.id', 'comments.content', 'comments.created_at', 'register_records.doctor_id' )
                           ->join( 'register_records', 'comments.record_id', '=', 'register_records.id' )
                           ->where( 'user_id', Session::get( 'user.id' ) )
                           ->paginate( $comments_per_page );

        //return Response::json($comments->toArray());die();

        if ( !isset( $comments ) ){
            return Response::json(array( 'error_code' => 1, 'message' => '无评价记录' ));
        }

        foreach ( $comments as $comment ){
            $doctor = Doctor::find( $comment['doctor_id'] );

            $comment['doctor'] = array(
                'id'            => $doctor->id,
                'name'          => $doctor->name,
                'photo'         => $doctor->photo,
                'title'         => $doctor->title->name,
                'department'    => $doctor->department->name,
                'hospital'      => $doctor->department->hospital->name
            );

            unset( $comment['doctor_id'] );
        }

        return Response::json(array( 'error_code' => 0, 'total' => $comments->count(), 'comments' => $comments->toArray() ));
    }

    public function get_doctor_comments(){
        $comments_per_page = Input::get( 'comments_per_page', 10 );

        $comments = Comment::select( 'comments.id', 'comments.content', 'users.real_name as user_name' )
                           ->join( 'users', 'comments.user_id', '=', 'users.id' )
                           ->where( 'doctor_id', Input::get( 'doctor_id' ) )
                           ->paginate( $comments_per_page );

        if ( !isset( $comments ) ){
            return Response::json(array( 'error_code' => 1, 'message' => '无评价记录' ));
        }

        return Response::json(array( 'error_code' => 0, 'total' => $comments->count(), 'comments' => $comments ));
    }

    public function add_comment(){
        $record = RegisterRecord::find( Input::get( 'record_id' ) );

        if ( !isset( $record ) ){
            return Response::json(array( 'error_code' => 2, 'message' => '无该记录' ));
        }

        if ( $record->user_id != Session::get( 'user.id' ) ){
            return Response::json(array( 'error_code' => 3, 'message' => '无效记录' ));
        }

        if ( !Input::has( 'content' ) ){
            return Response::json(array( 'error_code' => 4, 'message' => '请输入评价' ));
        }

        $comment = new Comment();
        $comment->record_id = $record->id;
        $comment->doctor_id = $record->doctor_id;
        $comment->user_id = $record->user_id;

        if ( !$comment->save() ){
            return Response::json(array( 'error_code' => 1, 'message' => '添加失败' ));
        }

        return Response::json(array( 'error_code' => 0, 'message' => '添加成功' ));
    }

    public function modify_comment(){

    }

    public function delete_comment(){

    }
}
/*

*/