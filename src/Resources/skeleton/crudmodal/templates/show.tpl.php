{% extends '<?= $base_layout ?>' %}

{% set page_title = '<?= $entity_twig_var_singular ?>.show.pagetitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_title#}
{% set page_subtitle = '<?= $entity_twig_var_singular ?>.show.subtitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_subtitle#}
{% set page_picto = '<?= $entity_twig_var_singular ?>.show.pictotitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_picto#}

{% block content %}

{% if modalEdit is defined and modalEdit is not empty %}
{{ include('<?= $entity_twig_var_singular ?>/_modal.html.twig', {modal: modalEdit}) }}
{% endif %}

{% if modalDelete is defined and modalDelete is not empty %}
{{ include('<?= $entity_twig_var_singular ?>/_delete_modal.html.twig', {modal: modalDelete}) }}
{% endif %}

<div class="card" >

    <h5 class="card-header ">
        {{ '<?= $entity_twig_var_singular ?>.show.card-title'|trans({}, '<?= $entity_twig_var_singular ?>') }}
    </h5>

    <div class="card-body">

        <table class="table table-striped">
            <tbody>
<?php foreach ($entity_fields as $field): ?>
<?php if ($field['type'] == 'boolean'): ?>
                <tr>
                    <th>{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}</th>
                    <td>{{ <?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?> ? '<span><i class="bi-check-lg"></i></span>' : '<span><i class="bi-x-lg"></i></span>' }}</td>
                </tr>
<?php else: ?>
        <tr>
            <th>{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}</th>
            <td>{{ <?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?> }}</td>
        </tr>
<?php endif ?>
<?php endforeach; ?>
            </tbody>
        </table>
        <div class="row">
            <span class="text-end">
                <button class="btn btn-primary" data-bs-target="#modalEditNew" data-bs-toggle="modal" type="button"><i class="bi-pen"></i> Editer</button>
                <button class="delete-entity btn btn-danger" data-bs-target="#modalDelete_<?= $entity_twig_var_singular ?>" data-bs-toggle="modal" type="button" data-id="{{ <?= $entity_twig_var_singular ?>.id }}"><i class="bi-trash"></i> {{ '<?= $entity_twig_var_singular ?>.show.btn_supprimer'|trans({}, '<?= $entity_twig_var_singular ?>') }}</button>
            </span>
            <a class="text-start" href="{{ path('<?= $route_name ?>_index') }}">{{ '<?= $entity_twig_var_singular ?>.show.btn_retourliste'|trans({}, '<?= $entity_twig_var_singular ?>') }}</a>
        </div>
    </div>
{% endblock %}
