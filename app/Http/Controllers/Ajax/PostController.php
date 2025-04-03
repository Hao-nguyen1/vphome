<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Repositories\Interfaces\PostRepositoryInterface  as PostRepository;
use Illuminate\Http\Request;


class PostController extends FrontendController
{
   
    protected $postRepository;

    public function __construct(
        PostRepository $postRepository,
    ){
        $this->postRepository = $postRepository;
        parent::__construct(); 
    }

   
    public function video(Request $request){
        $id = $request->input('id');

        $post = $this->postRepository->getPostById($id, $this->language);
        $html = $this->renderVideoHtml($post->video);

        return response()->json([
            'html' => $html
        ]);
        
    }

    private function renderVideoHtml($video){
        $explode = explode('/userfiles/flash/', $video);
        $html = '';
        if(count($explode) == 2){
            $html .= '<video width="100%" height="380" controls>';
                $html .= '<source src="'.$video.'" type="video/mp4">';
            $html .= '</video>';
        }else{
            $html .= $video;
        }
        return $html;
    }

}
