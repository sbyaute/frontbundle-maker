{% extends '<?= $base_layout ?>' %}

{% block page_title %}{{ '<?= $entity_twig_var_singular ?>.new.pagetitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
{% block page_subtitle %}{{ '<?= $entity_twig_var_singular ?>.new.subtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}

{% block page_content %}
    <div class="row">
        <div class="col-md-12">
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
            {% block box_title %}{{ '<?= $entity_twig_var_singular ?>.new.boxtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
                {% block box_body %}
                    {{ include('<?= $entity_twig_var_singular ?>/_form.html.twig') }}
                {% endblock %}
                {% block box_footer %}
                    <a href="{{ path('<?= $entity_twig_var_singular ?>_index') }}">{{ '<?= $entity_twig_var_singular ?>.new.btn.retourliste'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>
                {% endblock %}
            {% endembed %}
        </div>
    </div>
{% endblock %}