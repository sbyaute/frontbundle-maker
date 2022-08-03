{% extends '<?= $base_layout ?>' %}

{% block page_title %}{{ '<?= $entity_twig_var_singular ?>.index.pagetitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
{% block page_subtitle %}{{ '<?= $entity_twig_var_singular ?>.index.subtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}

{% block page_content %}

    {% if modal is defined and modal is not empty %}
        {{ include('<?= $entity_twig_var_singular ?>/_modal.html.twig', { modal: modal } ) }}
    {% endif %}

    <div class="row">
        <div class="col-md-12">
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
            {% block box_title %}{{ '<?= $entity_twig_var_singular ?>.index.boxtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
                {% block box_tools %}
                    <div class="pull-right ">
                        <button class="btn btn-success " data-toggle="modal" data-target="#modalEditNew"><i class="fa fa-plus-square"></i> {{ '<?= $entity_twig_var_singular ?>.index.btn.ajouter'|trans({},'<?= $entity_twig_var_singular ?>') }}</button>
                    </div>
                {% endblock %}
                {% block box_body %}
                    <table id="<?= $entity_twig_var_singular ?>-datatable" class="table table-striped stripe table-bordered table-hover">
                        <thead>
                        <tr>
<?php foreach ($entity_fields as $field): ?>
                            <th>{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({},'<?= $entity_twig_var_singular ?>') }}</th>
<?php endforeach; ?>
                            <th>{{ '<?= $entity_twig_var_singular ?>.index.action'|trans({},'<?= $entity_twig_var_singular ?>')|raw }}</th>
                        </tr>
                        </thead>

                        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
                        <tr>
<?php foreach ($entity_fields as $field): ?>
                            <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
<?php endforeach; ?>
                            <td><a class="btn btn-xs btn-primary" href="/<?= $entity_twig_var_singular ?>/{{ <?= $entity_twig_var_singular ?>.id }}"><i class="fa fa-search"></i> {{ '<?= $entity_twig_var_singular ?>.index.btn.voir'|trans({},'<?= $entity_twig_var_singular ?>') }}</a></td>
                        </tr>
                        {% endfor %}

                        <tbody>
                        </tbody>
                    </table>
                {% endblock %}
                {% block box_footer %}
                {% endblock %}
            {% endembed %}
        </div>
    </div>
{% endblock %}
{% block javascripts %}
    {{ parent() }}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#<?= $entity_twig_var_singular ?>-datatable').DataTable({
                "serverSide": false,
                "processing": true,
                "searching": true,
            });
        });
    </script>
{% endblock %}