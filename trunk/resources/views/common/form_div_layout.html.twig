{% extends "form_div_layout.html.twig" %}

{# Widgets #}

{% block choice_widget_expanded %}
{% spaceless %}
    {% for child in form %}
        {{ form_label(
            child,
            null,
            {
                in_choice_list: true,
                widget        : form_widget(child),
                multiple      : multiple,
            }
        ) }}
    {% endfor %}
{% endspaceless %}
{% endblock choice_widget_expanded %}

{% block datetime_widget %}
{% spaceless %}
    {% if widget == 'single_text' %}
        {{ block('form_widget_simple') }}
    {% else %}
        <div {{ block('widget_container_attributes') }}>
            {{ form_errors(form.date) }}
            {{ form_errors(form.time) }}
            {{ form_widget(form.date, { datetime: true } ) }}&nbsp;
            {{ form_widget(form.time, { datetime: true } ) }}
        </div>
    {% endif %}
{% endspaceless %}
{% endblock datetime_widget %}

{% block date_widget %}
{% spaceless %}
    {% if widget == 'single_text' %}
        {{ block('form_widget_simple') }}
    {% else %}
        {% if datetime is not defined or false == datetime %}
        <div {{ block('widget_container_attributes') }}>
        {% endif %}
            {{ date_pattern|replace({
                '{{ year }}':  form_widget(form.year, {attr: { class : 'span1'} }),
                '{{ month }}': form_widget(form.month, {attr: { class : 'span1'} }),
                '{{ day }}':   form_widget(form.day, {attr: { class : 'span1' } }),
            })|raw }}
        {% if datetime is not defined or false == datetime %}
        </div>
        {% endif %}
    {% endif %}
{% endspaceless %}
{% endblock date_widget %}

{% block time_widget %}
{% spaceless %}
    {% if widget == 'single_text' %}
        {{ block('form_widget_simple') }}
    {% else %}
        {% if datetime is not defined or false == datetime %}
        <div {{ block('widget_container_attributes') }}>
        {% endif %}
            {{ form_widget(form.hour, { attr: { class : 'span1' } }) }}:{{ form_widget(form.minute, { attr: { class : 'span1' } }) }}{% if with_seconds %}:{{ form_widget(form.second, { attr: { class : 'span1' } }) }}{% endif %}
        {% if datetime is not defined or false == datetime %}
        </div>
        {% endif %}

    {% endif %}
{% endspaceless %}
{% endblock time_widget %}

{% block money_widget %}
{% spaceless %}
    {% set append = '{{' == money_pattern[0:2] %}
    <div class="{{ append ? 'input-append' : 'input-prepend' }}">
        {% if not append %}
            <span class="add-on">
                {{ money_pattern|replace({ '{{ widget }}':''}) }}
            </span>
        {% endif %}
        {{ block('form_widget_simple') }}
        {% if append %}
            <span class="add-on">
                {{ money_pattern|replace({ '{{ widget }}':''}) }}
            </span>
        {% endif %}
    </div>

{% endspaceless %}
{% endblock money_widget %}

{% block percent_widget %}
{% spaceless %}
    <div class="input-append">
        {{ parent() }}
        <span class="add-on">%</span>
    </div>
{% endspaceless %}
{% endblock percent_widget %}

{# Labels #}

{% block form_label %}
{% spaceless %}
    {% if in_choice_list is defined and in_choice_list and widget is defined %}
        {% if not compound %}
            {% set label_attr = label_attr|merge({for: id}) %}
        {% endif %}
        {% if required %}
            {% set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' required')|trim}) %}
        {% endif %}
        {% if label is empty %}
            {% set label = name|humanize %}
        {% endif %}

        {% if multiple is defined and multiple %}
            {% set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' checkbox')|trim}) %}
        {% elseif multiple is defined and not multiple %}
            {% set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' radio')|trim}) %}
        {% endif %}
        <label{% for attrname, attrvalue in label_attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %}>
            {{ widget|raw }}
            <span>
                {{ label|trans({}, translation_domain) }}
            </span>
        </label>
    {% else %}
        {% set label_attr = label_attr|merge({class: (label_attr.class|default('') ~ ' control-label')|trim}) %}
        {{ parent() }}
    {% endif %}
{% endspaceless %}
{% endblock form_label %}

{# Rows #}

{% block form_row %}
{% spaceless %}
    <div class="control-group{% if not form.vars.valid %} error{% endif %}">
        {{ form_label(form, label|default(null)) }}
        <div class="controls">
            {{ form_widget(form) }}
            {{ form_errors(form) }}
        </div>
    </div>
{% endspaceless %}
{% endblock form_row %}

{% block form_errors %}
{% spaceless %}
    {% if errors|length > 0 %}
    {% if form.parent %}<span class="help-inline">{% else %}<div class="alert alert-error error" >{% endif %}
        {{ parent() }}
    {% if form.parent %}</span>{% else %}</div>{% endif %}
    {% endif %}
{% endspaceless %}
{% endblock form_errors %}
