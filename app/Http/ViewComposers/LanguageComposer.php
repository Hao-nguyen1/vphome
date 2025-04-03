<?php  
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\Interfaces\LanguageRepositoryInterface  as LanguageRepository;

class LanguageComposer
{

    protected $language;

    public function __construct(
        LanguageRepository $languageRepository,
        $language
    ){
        $this->languageRepository = $languageRepository;
    }

    public function compose(View $view)
    {
        $languages = $this->languageRepository->findByCondition(...$this->agrument());
        $view->with('languages', $languages);
    }

    private function agrument(){
        return [
            'condition' => [
                config('apps.general.defaultPublish')
            ],
            'flag' => true,
            'relation' => [],
            'orderBy' => ['current', 'desc']
        ];
    }

}