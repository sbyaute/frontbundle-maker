<!-- Modal -->
{% form_theme modal.form 'bootstrap_5_horizontal_layout.html.twig' %}
<div class="modal fade" id="modalEditNew" tabindex="-1" role="dialog" aria-labelledby="modalEditNewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalEditNewLabel">{{ modal.title }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {% if modal.form is defined and modal.form is not empty %}
                {{ form_start(modal.form, {attr: {novalidate: 'novalidate', class: 'form-horizontal'}}) }}
            {% endif %}
            <div class="modal-body">
                {% if modal.requiredFields is defined and modal.requiredFields == true %}
                    <div class="mb-3 row text-end">
                        <div class="text-danger">* Champs obligatoires</div>
                    </div>
                {% endif %}

                {% if modal.form is defined and modal.form is not empty %}
                    {% for row in modal.form %}
                        {% if row.vars.clicked is not defined %}
                        {% if row.vars.name is defined and row.vars.name is not same as 'btn_fermer' %}
                            {{ form_row(row) }}
                        {% endif %}
                        {% endif %}
                    {% endfor %}
                {% endif %}

                {% if modal.message is defined and modal.message is not empty %}
                    {{ modal.message|raw }}
                {% endif %}
            </div>
            <div class="modal-footer">
                {% for row in modal.form %}
                    {% if row.vars.clicked is defined %}
                        {{ form_widget(row) }}
                    {% endif %}
                    {% if row.vars.name is defined and row.vars.name is same as 'btn_fermer' %}
                        {{ form_widget(row) }}
                    {% endif %}
                {% endfor %}
            </div>
            {% if modal.form is defined and modal.form is not empty %}
                {{ form_end(modal.form) }}
            {% endif %}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
