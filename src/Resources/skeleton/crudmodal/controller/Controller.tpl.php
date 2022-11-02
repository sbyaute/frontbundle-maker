<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use <?= $form_create_full_class_name ?>;
use <?= $form_update_full_class_name ?>;
<?php if (isset($repository_full_class_name)): ?>
use <?= $repository_full_class_name ?>;
<?php endif ?>
use Symfony\Bundle\FrameworkBundle\Controller\<?= $parent_class_name ?>;
use App\Repository\OrganismeRepository;
use App\Form\DeleteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("<?= $route_path ?>")
 */
class <?= $class_name ?> extends <?= $parent_class_name; ?><?= "\n" ?>
{

    /** @var <?= $repository_class_name ?> */
    private $<?= $repository_var ?>;
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(<?= $repository_class_name ?> $<?= $repository_var ?>, 
                                EntityManagerInterface $entityManager, 
                                TranslatorInterface $translator) 
    {
        $this-><?= $repository_var ?> = $<?= $repository_var ?>;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="<?= $route_name ?>_index", methods={"GET","POST"})
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $<?= $entity_var_singular ?> = new <?= $entity_class_name ?>();
        $form = $this->createForm(<?= $form_create_class_name ?>::class, $<?= $entity_var_singular ?>);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($<?= $entity_var_singular ?>);
                $this->entityManager->flush();
            } catch (\Throwable $th) {
                $this->addFlash('error', $this->translator->trans('<?= $entity_var_singular ?>.new.error',[],'<?= $entity_var_singular ?>'));
                return $this->redirectToRoute('<?= $route_name ?>_index');
            }
            $this->addFlash('success', $this->translator->trans('<?= $entity_var_singular ?>.new.success',[],'<?= $entity_var_singular ?>'));

            return $this->redirectToRoute('<?= $route_name ?>_index');
        }

        return $this->render('<?= $templates_path ?>/index.html.twig', [
            'tableData' => $serializer->serialize($this-><?= $repository_var ?>->findAll(), JsonEncoder::FORMAT),
            'modal' => [
                '<?= $entity_var_singular ?>' => $<?= $entity_var_singular ?>,
                'form'  => $form->createView(),
                'title' => $this->translator->trans('<?= $entity_var_singular ?>.new.modaltitle', [], '<?= $entity_var_singular ?>'),
                'requiredFields' => true,
            ]
        ]);
    }

    /**
     * @Route("/delete-<?= $route_name ?>", name="delete_<?= $route_name ?>", methods={"POST"})
     */
    public function delete(Request $request, <?= $repository_class_name ?> $<?= $repository_var ?>)
    {
        $<?= $entity_var_singular ?> = $<?= $repository_var ?>->find(
            $request->get('delete_form_<?= $entity_var_singular ?>')['id']
        );

        try {
            $this->entityManager->remove($<?= $entity_var_singular ?>);
            $this->entityManager->flush();
        } catch (\Throwable $th) {
            $this->addFlash('error', $this->translator->trans('<?= $entity_var_singular ?>.delete.error',[],'<?= $entity_var_singular ?>'));

            return $this->redirectToRoute('<?= $route_name ?>_index');
        }

        $this->addFlash('success', $this->translator->trans('<?= $entity_var_singular ?>.delete.success',[],'<?= $entity_var_singular ?>'));

        return $this->redirectToRoute('<?= $route_name ?>_index');
    }

    /**
     * @Route("/{<?= $entity_identifier ?>}", name="<?= $route_name ?>_show", methods={"GET","POST"})
     */
    public function show(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>): Response
    {
        $<?= $entity_var_singular ?>Clone = clone $<?= $entity_var_singular ?>;

        $form = $this->createForm(<?= $form_update_class_name ?>::class, $<?= $entity_var_singular ?>);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($<?= $entity_var_singular ?>);
                $this->entityManager->flush();
            } catch (\Throwable $th) {
                $this->addFlash('error', $this->translator->trans('<?= $entity_var_singular ?>.edit.error',[],'<?= $entity_var_singular ?>'));
                return $this->redirectToRoute('<?= $route_name ?>_index');
            }
            $this->addFlash('success', $this->translator->trans('<?= $entity_var_singular ?>.edit.success',[],'<?= $entity_var_singular ?>'));

            return $this->redirectToRoute('<?= $route_name ?>_show',['id' => $<?= $entity_var_singular ?>->getId()]);
        }

        return $this->render('<?= $templates_path ?>/show.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>Clone,
            'modalEdit' => [
                '<?= $entity_var_singular ?>' => $<?= $entity_var_singular ?>Clone,
                'form' => $form->createView(),
                'title' => $this->translator->trans('<?= $entity_var_singular ?>.edit.modaltitle', [], '<?= $entity_var_singular ?>'),
                'requiredFields' => true,
            ],
            'modalDeleteRoute' => 'delete_app_<?= $entity_var_singular ?>',
        ]);
    }
}
