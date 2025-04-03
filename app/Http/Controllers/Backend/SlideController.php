<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\SlideServiceInterface  as SlideService;
use App\Repositories\Interfaces\SlideRepositoryInterface as SlideRepository;
use App\Models\Language;
use App\Http\Requests\Slide\StoreSlideRequest;
use App\Http\Requests\Slide\UpdateSlideRequest;

class SlideController extends Controller
{
    protected $slideService;
    protected $slideRepository;
    protected $language;

    public function __construct(
        SlideService $slideService,
        SlideRepository $slideRepository,
    ){
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request){
        $this->authorize('modules', 'slide.index');
        $slides = $this->slideService->paginate($request);
        // dd($slides);
      
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/slide.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Slide'
        ];
        $config['seo'] = __('messages.slide');
        $config['language'] = $this->language;
        $template = 'backend.slide.slide.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slides'
        ));
    }

    public function create(){
        $this->authorize('modules', 'slide.create');
        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['method'] = 'create';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));
    }

    public function store(StoreSlideRequest $request){
        if($this->slideService->create($request, $this->language)){
            return redirect()->route('slide.index')->with('success','Thêm mới bản ghi thành công');
        }
        return redirect()->route('slide.index')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function edit($id){
        $this->authorize('modules', 'slide.edit');
        $slide = $this->slideRepository->findById($id);
        $slideItem = $this->slideService->converSlideArray($slide->item[$this->language]);


        $config = $this->config();
        $config['seo'] = __('messages.slide');
        $config['method'] = 'edit';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'slide',
            'slideItem',
        ));
    }

    public function update($id, UpdateSlideRequest $request){
        if($this->slideService->update($id, $request, $this->language)){
            return redirect()->route('slide.index')->with('success','Cập nhật bản ghi thành công');
        }
        return redirect()->route('slide.index')->with('error','Cập nhật bản ghi không thành công. Hãy thử lại');
    }

    public function delete($id){
        $this->authorize('modules', 'slide.destroy');
        $config['seo'] = __('messages.slide');
        $slide = $this->slideRepository->findById($id);
        $template = 'backend.slide.slide.delete';
        return view('backend.dashboard.layout', compact(
            'template',
            'slide',
            'config',
        ));
    }

    public function destroy($id){
        if($this->slideService->destroy($id)){
            return redirect()->route('slide.index')->with('success','Xóa bản ghi thành công');
        }
        return redirect()->route('slide.index')->with('error','Xóa bản ghi không thành công. Hãy thử lại');
    }

    private function config(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/slide.js',
                
            ]
        ];
    }

}
