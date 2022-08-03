<div class="modal fade" id="modalEditNew" tabindex="-1" role="dialog" aria-labelledby="modalEditNewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalEditNewLabel">{{ modal.title }}</h4>
            </div>
            {% embed '@AdminLTE/Widgets/box-widget.html.twig' %}
                {% form_theme modal.form '@AdminLTE/layout/form-theme-horizontal.html.twig' %}
                {% block box_before %}{{ form_start(modal.form) }}{% endblock %}
{#                {% block box_title %}{{ 'Title'|trans }}{% endblock %}#}
                {% block box_body %}
                    {% if modal.requiredFields is defined and modal.requiredFields == true %}
                        <div class="form-group"><div class="col-sm-9"></div><div class="col-sm-3 text-warning">* Champs obligatoires</div></div>
                    {% endif %}
                    {% if modal.form is defined and modal.form is not empty %}
{#                        {{ form_widget(modal.form) }}#}
                        {% for row in modal.form %}
                            {% if row.vars.clicked is not defined %}
                                {{ form_row(row) }}
                            {% endif %}
                        {% endfor %}
                    {% endif %}

                    {% if modal.message is defined and modal.message is not empty %}
                        {{ modal.message | raw }}
                    {% endif %}
                {% endblock %}

                {% block box_footer %}
                    <div class="row">
                        {% for row in modal.form %}
                            {% if row.vars.clicked is defined %}
                            <div class="col-sm-6 {% if row.vars.name is not same as('btn_fermer') %} text-right {% endif %}">
                                {{ form_widget(row) }}
                            </div>
                            {% endif %}
                        {% endfor %}
                    </div>
                {% endblock %}

                {% block box_after %}
                    {% if modal.form is defined and modal.form is not empty %}
                        {{ form_end(modal.form) }}
                    {% endif %}
                {% endblock %}
            {% endembed %}
        </div>
    </div>
</div>
