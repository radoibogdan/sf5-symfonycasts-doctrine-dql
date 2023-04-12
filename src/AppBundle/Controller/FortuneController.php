<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class FortuneController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        # Enable filter globally = ACTIVATED NOW
        # AppBundle/EventListener/BeforeRequestListener.php

        # Enable filter / Controller
        # /** @var EntityManager $em */
        # $em = $this->getDoctrine()->getManager();
        # # Enable filter from config/config.yml
        # $filter = $em->getFilters()
        #     ->enable('fortune_cookies_discontinued');
        # $filter->setParameter('discontinued', true);

        $categoryRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Category');

        $search = $request->query->get('q');
        if ($search) {
            $categories = $categoryRepository->search($search);
        } else {
            $categories = $categoryRepository->findAllOrdered();
        }

        return $this->render('fortune/homepage.html.twig',[
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/{id}", name="category_show")
     */
    public function showCategoryAction($id)
    {
        $categoryRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Category');

        $category = $categoryRepository->findWithFortunesJoin($id);

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $fortunesData =$this->getDoctrine()
            ->getRepository('AppBundle:FortuneCookie')
            ->getDetailedNumberPrintedForCategory($category);

        $fortunesPrinted = $fortunesData['fortunesPrinted'];
        $fortunesAverage = $fortunesData['fortunesAverage'];
        $name = $fortunesData['name'];

        return $this->render('fortune/showCategory.html.twig',[
            'category' => $category,
            'fortunesPrinted' => $fortunesPrinted,
            'fortunesAverage' => $fortunesAverage,
            'categoryName' => $name,
        ]);
    }
}
