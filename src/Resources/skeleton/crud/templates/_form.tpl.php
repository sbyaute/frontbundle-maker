{{ form_start(form) }}
    {{ form_widget(form) }}
<div class="modal-footer">
    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> {{ '<?= $entity_twig_var_singular ?>.form.btn.valider'|trans({},'<?= $entity_twig_var_singular ?>') }}</button>
</div>
{{ form_end(form) }}