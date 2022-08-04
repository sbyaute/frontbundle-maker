<!-- Modal -->
<div class="modal fade" id="modalDelete_{{ modal.name }}" tabindex="-1" role="dialog" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation de suppression</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ form_start(modal.form) }}
            <div class="modal-body">
                <p>{{ '<?= $entity_twig_var_singular ?>.modal.deletemessage'|trans({}, '<?= $entity_twig_var_singular ?>')|raw }}</p>
            </div>
            <div class="modal-footer">
                {% for row in modal.form %}
                {{ form_widget(row) }}
                {% endfor %}
                {{ form_end(modal.form) }}
            </div>
            {{ form_end(modal.form) }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->