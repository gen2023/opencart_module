{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      </div>
      <h1>{{ heading_title }}</h1>
      <ul class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
        <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="container-fluid">{% if error_warning %}
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    {% if success %}
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> {{ success }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="row">
      <div class="col-md-9 col-md-pull-3 col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> {{ text_list }}</h3>
          </div>
          <div class="panel-body">
            <form action="{{ delete }}" method="post" enctype="multipart/form-data" id="form-product">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                      <td class="text-center">{{ column_image }}</td>
                      <td class="text-left">{% if sort == 'pd.name' %} <a href="{{ sort_name }}" class="{{ order|lower }}">{{ column_name }}</a> {% else %} <a href="{{ sort_name }}">{{ column_name }}</a> {% endif %}</td>
                      <td class="text-left">{% if sort == 'p.model' %} <a href="{{ sort_model }}" class="{{ order|lower }}">{{ column_model }}</a> {% else %} <a href="{{ sort_model }}">{{ column_model }}</a> {% endif %}</td>
                      <td class="text-right">{% if sort == 'p.price' %} <a href="{{ sort_price }}" class="{{ order|lower }}">{{ column_price }}</a> {% else %} <a href="{{ sort_price }}">{{ column_price }}</a> {% endif %}</td>
                      <td class="text-right">{% if sort == 'p.quantity' %} <a href="{{ sort_quantity }}" class="{{ order|lower }}">{{ column_quantity }}</a> {% else %} <a href="{{ sort_quantity }}">{{ column_quantity }}</a> {% endif %}</td>
                      <td class="text-left">{% if sort == 'p.status' %} <a href="{{ sort_status }}" class="{{ order|lower }}">{{ column_status }}</a> {% else %} <a href="{{ sort_status }}">{{ column_status }}</a> {% endif %}</td>
                      <td class="text-right">{{ column_action }}</td>
                    </tr>
                  </thead>
                  <tbody>                  
                  {% if products %}
                  {% for product in products %}
                  <tr>
                    <td class="text-center">{% if product.product_id in selected %}
                      <input type="checkbox" name="selected[]" value="{{ product.product_id }}" checked="checked" />
                      {% else %}
                      <input type="checkbox" name="selected[]" value="{{ product.product_id }}" />
                      {% endif %}</td>
                    <td class="text-center">{% if product.image %} <img src="{{ product.image }}" alt="{{ product.name }}" class="img-thumbnail" /> {% else %} <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span> {% endif %}</td>
                    <td class="text-left">{{ product.name }}</td>
                    <td class="text-left">{{ product.model }}</td>
                    <td class="text-right">{% if product.special %} <span style="text-decoration: line-through;">{{ product.price }}</span><br/>
                      <div class="text-danger">{{ product.special }}</div>
                      {% else %}
                      {{ product.price }}
                      {% endif %}</td>
                    <td class="text-right">{% if product.quantity <= 0 %} <span class="label label-warning">{{ product.quantity }}</span> {% elseif product.quantity <= 5 %} <span class="label label-danger">{{ product.quantity }}</span> {% else %} <span class="label label-success">{{ product.quantity }}</span> {% endif %}</td>
                    <td class="text-left">{{ product.status }}</td>
                    <td class="text-right"><a href="{{ product.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                  </tr>
                  {% endfor %}
                  {% else %}
                  <tr>
                    <td class="text-center" colspan="8">{{ text_no_results }}</td>
                  </tr>
                  {% endif %}
                    </tbody>                  
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left">{{ pagination }}</div>
              <div class="col-sm-6 text-right">{{ results }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{ footer }} 