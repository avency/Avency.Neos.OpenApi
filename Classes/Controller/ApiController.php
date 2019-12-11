<?php
namespace Avency\Neos\OpenApi\Controller;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\JsonView;
use Neos\FluidAdaptor\View\TemplateView;

/**
* Controller for Api
*
* @Flow\Scope("singleton")
*/
class ApiController extends ActionController
{
    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => TemplateView::class,
        'json' => JsonView::class
    );

    /**
     * @var string
     * @Flow\InjectConfiguration(package="Avency.Neos.OpenApi", path="scanPath")
     */
    protected $scanPath;


    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = array('application/json', 'text/html');


    /**
     * Show Swagger UI Index
     *
     * @return void
     */
    public function indexAction()
    {
    }

    /**
     * Create Api Json
     *
     * @return void
     */
    public function getApiJsonAction()
    {
        $openapi = \OpenApi\scan($this->scanPath);
        $this->view->assign('value', json_decode($openapi->toJson(), true));
    }
}
