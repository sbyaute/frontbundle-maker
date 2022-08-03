{% extends '<?= $base_layout ?>' %}

{% block page_title %}{{ '<?= $entity_twig_var_singular ?>.index.pagetitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
{% block page_subtitle %}{{ '<?= $entity_twig_var_singular ?>.index.subtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}

{% block page_content %}

    {{ generate_delete_modal('<?= $entity_twig_var_singular ?>', 'delete_<?= $entity_twig_var_singular ?>') }}
    <div id="<?= $entity_twig_var_singular ?>_delete_button_template" style="display: none">{{ generate_delete_button('<?= $entity_twig_var_singular ?>', '__id__') }}</div>

    <div class="row">
        <div class="col-md-12">
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
            {% block box_title %}{{ '<?= $entity_twig_var_singular ?>.index.boxtitle'|trans({},'<?= $entity_twig_var_singular ?>') }}{% endblock %}
                {% block box_tools %}
                    <div class="pull-right ">
                        <a href="{{ path('<?= $entity_twig_var_singular ?>_new') }}" class="btn btn-success"><i class="fa fa-plus-square"></i> {{ '<?= $entity_twig_var_singular ?>.index.btn.ajouter'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>
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
                "serverSide": true,
                "processing": true,
                "searching": true,
                "ajax": "/<?= $entity_twig_var_singular ?>/ajax_table/data",
                "rowId": "id",
                "columns": [
<?php foreach ($entity_fields as $field): ?>
                    {
                        "data": "<?= $field['fieldName'] ?>",
                        "name": "<?= $field['fieldName'] ?>"
                    },
<?php endforeach; ?>
                    {
                        'data': null,
                        "width": "200px",
                        "class": "btn-group modal-footer",
                        'orderable': false,
                        'searchable': false,
                        "render": function (item) {
                            return '<a class="btn btn-xs btn-primary" href="/<?= $entity_twig_var_singular ?>/'+item.id+'"><i class="fa fa-search"></i> {{ '<?= $entity_twig_var_singular ?>.index.btn.voir'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>'+
                                '<a class="btn btn-xs btn-info" href="/<?= $entity_twig_var_singular ?>/'+item.id+'/edit"><i class="fa fa-pen"></i> {{ '<?= $entity_twig_var_singular ?>.index.btn.editer'|trans({},'<?= $entity_twig_var_singular ?>') }}</a>'+
                                $('#<?= $entity_twig_var_singular ?>_delete_button_template').html().replace('__id__', item.id).replace('btn','btn btn-xs')
                        }
                    }
                ]
            });
        });
    </script>
{% endblock %}