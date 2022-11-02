<?php

namespace Sbyaute\FrontBundleMakerBundle\Maker;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
// use Symfony\Component\String\Inflector\EnglishInflector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Renderer\FormTypeRenderer;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class MakeFrontBundleCrudModal extends AbstractMaker
{
    private $doctrineHelper;
    private $formTypeRenderer;
    private $skeletonDir;
    private $baseLayout;


    public function __construct(DoctrineHelper $doctrineHelper, FormTypeRenderer $formTypeRenderer)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->formTypeRenderer = $formTypeRenderer;
    }

    public function setConfiguration($configuration){
        $this->skeletonDir = $configuration['skeleton_dir'];
        $this->baseLayout = $configuration['base_layout'];
    }

    public static function getCommandName(): string
    {
        return 'make:frontbundle:crudmodal';
    }

    /**
     * {@inheritdoc}
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates FrontBundle CRUD (modal mode) for Doctrine entity class')
            ->addArgument('entity-class', InputArgument::OPTIONAL, sprintf('The class name of the entity to create CRUD (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
        ;

        $inputConfig->setArgumentAsNonInteractive('entity-class');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('entity-class')) {
            $argument = $command->getDefinition()->getArgument('entity-class');

            $entities = $this->doctrineHelper->getEntitiesForAutocomplete();

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);

            $value = $io->askQuestion($question);

            $input->setArgument('entity-class', $value);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        //$inflector = new EnglishInflector();

        $entityClassDetails = $generator->createClassNameDetails(
            Validator::entityExists($input->getArgument('entity-class'), $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity\\'
        );

        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        $repositoryVars = [];

        if (null !== $entityDoctrineDetails->getRepositoryClass()) {
            $repositoryClassDetails = $generator->createClassNameDetails(
                '\\'.$entityDoctrineDetails->getRepositoryClass(),
                'Repository\\',
                'Repository'
            );

            //$arr = $inflector->singularize($repositoryClassDetails->getShortName());
            $arr[] = $repositoryClassDetails->getShortName();
            $repositoryVars = [
                'repository_full_class_name' => $repositoryClassDetails->getFullName(),
                'repository_class_name' => $repositoryClassDetails->getShortName(),
                'repository_var' => lcfirst(reset($arr)),
            ];
        }

        $controllerClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'Controller',
            'Controller\\',
            'Controller'
        );

//        $iter = 0;
//        do {
//            $formClassDetails = $generator->createClassNameDetails(
//                $entityClassDetails->getRelativeNameWithoutSuffix().($iter ?: '').'Type',
//                $entityClassDetails->getRelativeNameWithoutSuffix().'Type',
//                'Form\\',
//                'Type'
//            );
//            ++$iter;
//        } while (class_exists($formClassDetails->getFullName()));

        $formCreateClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'CreateType',
            'Form\\'.$entityClassDetails->getRelativeNameWithoutSuffix().'\\',
            'Type'
        );

        $formUpdateClassDetails = $generator->createClassNameDetails(
            $entityClassDetails->getRelativeNameWithoutSuffix().'UpdateType',
            'Form\\'.$entityClassDetails->getRelativeNameWithoutSuffix().'\\',
            'Type'
        );
        
        //$arr = $inflector->pluralize($entityClassDetails->getShortName());
        $arr[] = $entityClassDetails->getShortName();

        $entityVarPlural = lcfirst($entityClassDetails->getShortName()).'s';
        $entityVarSingular = lcfirst($entityClassDetails->getShortName());

        $entityTwigVarPlural = Str::asTwigVariable($entityVarPlural);
        $entityTwigVarSingular = Str::asTwigVariable($entityVarSingular);

        $routeName = Str::asRouteName($controllerClassDetails->getRelativeNameWithoutSuffix());
        $templatesPath = Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix());

        $generator->generateController(
            $controllerClassDetails->getFullName(),
            $this->skeletonDir . 'crudmodal/controller/Controller.tpl.php',
            array_merge([
                'parent_class_name' => 'AbstractController',
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'form_create_full_class_name' => $formCreateClassDetails->getFullName(),
                'form_create_class_name' => $formCreateClassDetails->getShortName(),
                'form_update_full_class_name' => $formUpdateClassDetails->getFullName(),
                'form_update_class_name' => $formUpdateClassDetails->getShortName(),
                'route_path' => Str::asRoutePath($controllerClassDetails->getRelativeNameWithoutSuffix()),
                'route_name' => $routeName,
                'templates_path' => $templatesPath,
                'entity_var_plural' => $entityVarPlural,
                'entity_twig_var_plural' => $entityTwigVarPlural,
                'entity_var_singular' => $entityVarSingular,
                'entity_twig_var_singular' => $entityTwigVarSingular,
                'entity_identifier' => $entityDoctrineDetails->getIdentifier(),
                'entity_fields' => $entityDoctrineDetails->getDisplayFields(),
            ],
                $repositoryVars
            )
        );

        $FormFields = $entityDoctrineDetails->getFormFields();
        foreach($FormFields as $name=>$option) {
            $FormFields[$name]['type'] = $FormFields[$name]['type'] ?? null;
            $FormFields[$name]['options_code'] = "                'label' => '".$entityVarSingular.".".$name."',
                'translation_domain' => '".$entityVarSingular."',";
        }

        $name='btn_fermer';
        $FormFields[$name]['type'] = \Symfony\Component\Form\Extension\Core\Type\ButtonType::class;
        $FormFields[$name]['options_code'] = "                'label' => '".$entityVarSingular.".".$name."',
                'translation_domain' => '".$entityVarSingular."',
                'label_html' => true,
                'attr' => [
                    'class' => 'btn btn-outline-primary me-auto',
                    'data-bs-dismiss' => 'modal',
                ],";

        $name='btn_valider';
        $FormFields[$name]['type'] = \Symfony\Component\Form\Extension\Core\Type\SubmitType::class;
        $FormFields[$name]['options_code'] = "                'label' => '".$entityVarSingular.".".$name."',
                'translation_domain' => '".$entityVarSingular."',
                'label_html' => true,
                'attr' => [
                    'class' => 'btn btn-success',
                ],";

        $this->formTypeRenderer->render(
            $formCreateClassDetails,
            $FormFields,
            $entityClassDetails
        );

        unset($FormFields[$name]);
        $name='btn_mettreajour';
        $FormFields[$name]['type'] = \Symfony\Component\Form\Extension\Core\Type\SubmitType::class;
        $FormFields[$name]['options_code'] = "                'label' => '".$entityVarSingular.".".$name."',
                'translation_domain' => '".$entityVarSingular."',
                'label_html' => true,
                'attr' => [
                    'class' => 'btn btn-success',
                ],";
        
        $this->formTypeRenderer->render(
            $formUpdateClassDetails,
            $FormFields,
            $entityClassDetails
        );
 
        $templates = [
            '_modal' => [
                'route_name' => $routeName,
                'entity_twig_var_singular' => $entityTwigVarSingular,
                'entity_identifier' => $entityDoctrineDetails->getIdentifier(),
            ],
            'index' => [
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_twig_var_plural' => $entityTwigVarPlural,
                'entity_twig_var_singular' => $entityTwigVarSingular,
                'entity_identifier' => $entityDoctrineDetails->getIdentifier(),
                'entity_fields' => $entityDoctrineDetails->getDisplayFields(),
                'route_name' => $routeName,
                'base_layout' => $this->baseLayout,
            ],
            'show' => [
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_twig_var_singular' => $entityTwigVarSingular,
                'entity_identifier' => $entityDoctrineDetails->getIdentifier(),
                'entity_fields' => $entityDoctrineDetails->getDisplayFields(),
                'route_name' => $routeName,
                'base_layout' => $this->baseLayout,
            ],
        ];

        foreach ($templates as $template => $variables) {
            $generator->generateTemplate(
                $templatesPath.'/'.$template.'.html.twig',
                $this->skeletonDir . 'crudmodal/templates/'.$template.'.tpl.php',
                $variables
            );
        }

//        $generator->generateFile(
//            'src\Repository\\'.$repositoryVars['repository_class_name'].'.php',
//            $this->skeletonDir . 'crudmodal/repository/repository.tpl.php',
//            [
//                'namespace' => substr($repositoryClassDetails->getFullName(),0,-strlen($repositoryClassDetails->getRelativeName())-1),
//                'repository_full_class_name' => $repositoryVars['repository_full_class_name'],
//                'repository_class_name' => $repositoryVars['repository_class_name'],
//                'entity_full_class_name' => $entityClassDetails->getFullName(),
//                'entity_class_name' => $entityClassDetails->getShortName(),
//            ]
//        );

        $generator->generateFile(
            'translations\\'.$entityVarSingular.'.fr.yaml',
            $this->skeletonDir . 'crudmodal/translation/translation.tpl.php',
            [
                'entity_class_name' => $entityVarSingular,
                'entity_fields' => $entityDoctrineDetails->getDisplayFields(),
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text(sprintf('Next: Check your new CRUD by going to <fg=yellow>%s/</>', Str::asRoutePath($controllerClassDetails->getRelativeNameWithoutSuffix())));
    }

    /**
     * {@inheritdoc}
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            Route::class,
            'router'
        );

        $dependencies->addClassDependency(
            AbstractType::class,
            'form'
        );

        $dependencies->addClassDependency(
            Validation::class,
            'validator'
        );

        $dependencies->addClassDependency(
            TwigBundle::class,
            'twig-bundle'
        );

        $dependencies->addClassDependency(
            DoctrineBundle::class,
            'orm-pack'
        );

        $dependencies->addClassDependency(
            CsrfTokenManager::class,
            'security-csrf'
        );

        $dependencies->addClassDependency(
            ParamConverter::class,
            'annotations'
        );
    }
}
