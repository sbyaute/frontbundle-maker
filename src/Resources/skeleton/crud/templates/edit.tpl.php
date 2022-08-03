{% extends '<?= $base_layout ?>' %}

{% block page_title %}{{ '<?= $entity_twig_var_singular ?>.edit.pagetitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
{% block page_subtitle %}{{ '<?= $entity_twig_var_singular ?>.edit.subtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}

{% block page_content %}

    {{ generate_delete_modal('<?= strtolower($entity_class_name) ?>','delete_<?= strtolower($entity_class_name) ?>') }}

    <div class="row">
        <div class="col-md-12">
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
            {% block box_title %}{{ '<?= $entity_twig_var_singular ?>.edit.boxtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
                {% block box_body %}
                    {{ form_start(form) }}
                    {{ form_widget(form) }}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> {{ '<?= $entity_twig_var_singular ?>.edit.btn.mettreajour'|trans({},'<?= $entity_twig_var_singular ?>') }}</button>
                        {{ generate_delete_button('<?= strtolower($entity_class_name) ?>', <?= strtolower($entity_class_name) ?>.id) }}
                    </div>
                    {{ form_end(form) }}
                {% endblock %}
                {% block box_footer %}
                    <a href="{{ path('<?= $route_name ?>_index') }}">{{ '<?= $entity_twig_var_singular ?>.edit.btn.retourliste'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>
                {% endblock %}
            {% endembed %}
        </div>
    </div>
{% endblock %}