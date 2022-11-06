{% extends '<?= $base_layout ?>' %}

{% set page_title = '<?= $entity_twig_var_singular ?>.index.pagetitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_title#}
{% set page_subtitle = '<?= $entity_twig_var_singular ?>.index.subtitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_subtitle#}
{% set page_picto = '<?= $entity_twig_var_singular ?>.index.pictotitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_picto#}

{% block content %}
    <!-- Modal -->
    {% if modal is defined and modal is not empty %}
        {{ include('<?= $entity_twig_var_singular ?>/_modal.html.twig', {modal: modal}) }}
    {% endif %}
    <div class="card" >
        <h5 class="card-header ">
            {{ '<?= $entity_twig_var_singular ?>.index.card-title'|trans({}, '<?= $entity_twig_var_singular ?>') }}
        </h5>
        <div class="card-body">
            <table id="<?= $entity_twig_var_singular ?>-dataTable" class="table table-striped table-hover">
                <thead class="table-primary text-white">
                <tr>
<?php foreach ($entity_fields as $field): ?>
                    <th>{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>')|raw }}</th>
<?php endforeach; ?>
                    <th>{{ '<?= $entity_twig_var_singular ?>.action'|trans({}, '<?= $entity_twig_var_singular ?>')|raw }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}

{% block javascripts %}
    {{ parent() }}
    <script type="text/javascript">
        window.addEventListener('load', function() {
            $('#<?= $entity_twig_var_singular ?>-dataTable').DataTable({
                "serverSide": false,
                "processing": true,
                "stateSave": false,
                "order": [[0, 'desc']],
                data: JSON.parse(JSON.stringify({{ tableData|raw }})),
                // dom: 'Blfrtip',
                dom: '<"container-fluid"<"row"<"col"l><"col"f><"col-auto"B>>>rt<"container-fluid"<"row"<"col"i><"col"p>>>',
                buttons: {
                    buttons: [
                        {
                            text: '<i class="bi-plus-square"></i>  {{ '<?= $entity_twig_var_singular ?>.index.btn_ajouter'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                            className: 'btn btn-success',
                            action: function ( e, dt, node, config ) {
                                $('#modalEditNew').modal('show')
                            }
                        },
                        {
                            extend: 'collection',
                            autoClose: 'true',
                            text: '',
                            tag: 'span',
                            className: 'bi bi-download fa fa-cog fa-2x',
                            buttons: [ 'copy',
                                {
                                    extend: 'csv',
                                    text: 'CSV',
                                    charset: 'utf-8',
                                    fieldSeparator: ';',
                                    bom: true,
                                    exportOptions: {
                                        columns: ':visible'
                                    },
                                },
                                'print', 'excel', 'pdf'
                            ],
                        }
                    ]
                },
                "columns": [
<?php foreach ($entity_fields as $field): ?>
<?php if ($field['type'] == 'boolean'): ?>
                    {
                        "data": '<?= $field['fieldName'] ?>',
                        "name": '{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                        "render": function ( data, type, row, meta ) {
                            return data === false ? '<span><i class="bi-x-lg"></i></span>' : '<span><i class="bi-check-lg"></i></span>';
                        },
                        "searchable": false,
                        "className": "text-center",
                        "width": "5%",
                    },
<?php else: ?>
                    {
                        "data": '<?= $field['fieldName'] ?>',
                        "name": '{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                    },
<?php endif ?>
<?php endforeach; ?>
                    {
                        "data": null,
                        "render": function ( data, type, row, meta ) {
                            var url = '{{ path("app_<?= $entity_twig_var_singular ?>_show", {'id': 'row_id'}) }}';
                            var html = '<a href="'+url.replace("row_id", row.id)+'" class="edit"><i class="bi-search"></i></a>';
                            // html += '<a href="'+url.replace("row_id", row.id)+'" class="trash"><i class="bi-trash am-coul-brique-700" style="margin-left:1em;"></i></a>';
                            return html;
                        },
                        "searchable": false,
                        "orderable": false,
                        "className": "text-center",
                        "width": "5%",
                    },
                ]
            });
        });
    </script>
{% endblock %}
