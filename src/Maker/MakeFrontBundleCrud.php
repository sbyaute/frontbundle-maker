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
use Symfony\Component\Validator\Validation;

class MakeFrontBundleCrud extends AbstractMaker
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
        return 'make:frontbundle:crud';
    }

    /**
     * {@inheritdoc}
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates FrontBundle CRUD for Doctrine entity class')
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

        $iter = 0;
        do {
            $formClassDetails = $generator->createClassNameDetails(
                $entityClassDetails->getRelativeNameWithoutSuffix().($iter ?: '').'Type',
                'Form\\',
                'Type'
            );
            ++$iter;
        } while (class_exists($formClassDetails->getFullName()));

        //$arr = $inflector->pluralize($entityClassDetails->getShortName());
        $arr[] = $entityClassDetails->getShortName();
        $entityVarPlural = lcfirst(reset($arr));
        $entityVarSingular = lcfirst($entityClassDetails->getShortName());

        $entityTwigVarPlural = Str::asTwigVariable($entityVarPlural);
        $entityTwigVarSingular = Str::asTwigVariable($entityVarSingular);

        $routeName = Str::asRouteName($controllerClassDetails->getRelativeNameWithoutSuffix());
        $templatesPath = Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix());

        $generator->generateController(
            $controllerClassDetails->getFullName(),
            $this->skeletonDir . 'crud/controller/Controller.tpl.php',
            array_merge([
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'form_full_class_name' => $formClassDetails->getFullName(),
                'form_class_name' => $formClassDetails->getShortName(),
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
                'translation_domain' => '".$entityVarSingular."' ";
        }

        $this->formTypeRenderer->render(
            $formClassDetails,
            //$entityDoctrineDetails->getFormFields(),
            $FormFields,
            $entityClassDetails
        );

        $templates = [            
            '_form' => [
                'entity_twig_var_singular' => $entityTwigVarSingular,
            ],
            'edit' => [
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_twig_var_singular' => $entityTwigVarSingular,
                'entity_identifier' => $entityDoctrineDetails->getIdentifier(),
                'route_name' => $routeName,
                'base_layout' => $this->baseLayout,
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
            'new' => [
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_twig_var_singular' => $entityTwigVarSingular,
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
                $this->skeletonDir . 'crud/templates/'.$template.'.tpl.php',
                $variables
            );
        }

        $generator->generateFile(
            'src\Repository\\'.$repositoryVars['repository_class_name'].'.php',
            $this->skeletonDir . 'crud/repository/repository.tpl.php',
            [
                'namespace' => substr($repositoryClassDetails->getFullName(),0,-strlen($repositoryClassDetails->getRelativeName())-1),
                'repository_full_class_name' => $repositoryVars['repository_full_class_name'],
                'repository_class_name' => $repositoryVars['repository_class_name'],
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
            ]
        );

        $generator->generateFile(
            'translations\\'.$entityVarSingular.'.fr.yaml',
            $this->skeletonDir . 'crud/translation/translation.tpl.php',
            [
                'entity_class_name' => $entityVarSingular,
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
