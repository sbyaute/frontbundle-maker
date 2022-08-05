{% extends '<?= $base_layout ?>' %}

{% set page_title = '<?= $entity_twig_var_singular ?>.index.pagetitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_title#}
{% set page_subtitle = '<?= $entity_twig_var_singular ?>.index.subtitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_subtitle#}
{% set page_picto = '<?= $entity_twig_var_singular ?>.index.pictotitle'|trans({}, '<?= $entity_twig_var_singular ?>') %}
{# twigcs use-var page_picto#}

{% block content %}

    {% block stylesheets %}
        {{ parent() }}
        <link rel="stylesheet" href="{{ asset('assets/bootstrap-table/1.20.2/css/bootstrap-table.min.css') }}">
    {% endblock %}

    <!-- Modal -->
    {% if modal is defined and modal is not empty %}
        {{ include('<?= $entity_twig_var_singular ?>/_modal.html.twig', {modal: modal}) }}
    {% endif %}

    <div class="card" >

        <h5 class="card-header ">
            {{ '<?= $entity_twig_var_singular ?>.index.card-title'|trans({}, '<?= $entity_twig_var_singular ?>') }}
        </h5>

        <div class="card-body">

            <div class="toolbar">
                <button id="ajouter" class="btn btn-success" data-bs-target="#modalEditNew" data-bs-toggle="modal" type="button">
                    <i class="bi-plus-square"></i>  {{ '<?= $entity_twig_var_singular ?>.index.btn_ajouter'|trans({}, '<?= $entity_twig_var_singular ?>') }}
                </button>
            </div>

            <table id="table"
                   data-toggle="table">
            </table>
        </div>
    </div>
{% endblock %}

{% block javascripts %}
    {{ parent() }}
    <script src="{{ asset('assets/jquery/tableexport/1.10.21/tableExport.min.js') }}"></script>
    <script src="{{ asset('assets/jquery/tableexport/1.10.21/libs/jsPDF/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/jquery/tableexport/1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-table/1.20.2/js/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-table/1.20.2/js/bootstrap-table-locale-all.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-table/1.20.2/dist/extensions/export/bootstrap-table-export.min.js') }}"></script>
    <script>
        $('#table').bootstrapTable({
            locale: 'fr',
            classes: 'table table-striped table-hover',
            theadClasses: 'table-primary text-white',
            toolbar: ".toolbar",
            // paginationVAlign: 'top',
            // pageList: '[10, 25, 50, 100, all]',
            pagination: true,
            search: true,
            searchHighlight: true,
            showColumns: true,
            showExport: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'excel', 'pdf'],
            columns: [
<?php foreach ($entity_fields as $field): ?>
<?php if ($field['type'] == 'boolean'): ?>
                {
                    field: '<?= $field['fieldName'] ?>',
                    title: '{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                    formatter: formatYesNo
                },
<?php else: ?>
                {
                    field: '<?= $field['fieldName'] ?>',
                    title: '{{ '<?= $entity_twig_var_singular ?>.<?= $field['fieldName'] ?>'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                    sortable: true,
                    searchable: true
                },
<?php endif ?>
<?php endforeach; ?>
                {
                    field: 'action',
                    title: '{{ '<?= $entity_twig_var_singular ?>.action'|trans({}, '<?= $entity_twig_var_singular ?>') }}',
                    align: 'center',
                    formatter: buttonFormater
                }
            ],
            exportOptions: {
                ignoreColumn: ["action"]
            },
            data : {{ tableData|raw }}
        })

        function formatYesNo(value,row,index){
            return value===false ? '<span><i class="bi-x-lg"></i></span>' : '<span><i class="bi-check-lg"></i></span>';
        }

        function buttonFormater(value, row, index) {
            var url = '{{ path("<?= $route_name ?>_show", {'id': 'row_id'}) }}';
            var html = '<a href="'+url.replace("row_id", row.id)+'" class="edit"><i class="bi-search"></i></a>';
            // html += '<a href="'+url.replace("row_id", row.id)+'" class="trash"><i class="bi-trash am-coul-brique-700" style="margin-left:1em;"></i></a>';
            return html;
        }
    </script>
{% endblock %}
