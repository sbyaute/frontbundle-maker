{% extends '<?= $base_layout ?>' %}

{% block page_title %}{{ '<?= $entity_twig_var_singular ?>.show.pagetitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
{% block page_subtitle %}{{ '<?= $entity_twig_var_singular ?>.show.subtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}

{% block page_content %}

    {{ generate_delete_modal('<?= $entity_twig_var_singular ?>','delete_<?= $route_name ?>') }}

    <div class="row">
        <div class="col-md-12">
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
                {% block box_title %}{{ '<?= $entity_twig_var_singular ?>.show.boxtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
                {% block box_body %}
                    <table class="table table-striped">
                        <tbody>
                        <?php foreach ($entity_fields as $field): ?>
                            <tr>
                                <th>{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({},'<?= $entity_twig_var_singular ?>') }}</th>
                                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pull-right modal-footer">
                        <a href="{{ path('<?= $route_name ?>_edit', {'id': <?= $entity_twig_var_singular ?>.id}) }}" class="btn btn-info"><i class="fa fa-pen"></i> {{ '<?= $entity_twig_var_singular ?>.show.btn.editer'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>
                        {{ generate_delete_button('<?= $entity_twig_var_singular ?>', <?= $entity_twig_var_singular ?>.id) }}
                    </div>
                {% endblock %}
                {% block box_footer %}
                    <a href="{{ path('<?= $route_name ?>_index') }}">{{ '<?= $entity_twig_var_singular ?>.show.btn.retourliste'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>
                {% endblock %}
            {% endembed %}
        </div>
    </div>
{% endblock %}