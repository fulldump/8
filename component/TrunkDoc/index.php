<div class="docuss">

	<h1>¿Qué es [[COMPONENT name=TrunkLogo]]?</h1>

	<p>[[COMPONENT name=TrunkLogo]] es un conjunto de componentes gráficos <strong>independientes</strong> y <strong>convenciones</strong>
		para construir interfaces gráficas de administración rápidamente. Es la base para todas las interfaces de
		administración de TreeWeb.</p>

	<p>Son independientes de bibliotecas de terceros por varios motivos:</p>

	<ul>
		<li>No se necesita soporte para navegadores antiguos</li>
		<li>Deben ser fáciles de entender, utilizar y extender</li>
		<li>Los componentes son realmente sencillos</li>
		<li>Se aprovechan las últimas implementaciones del estándar HTML5</li>
	</ul>

	<p>También es un conjunto de convenciones y reglas para facilitar el uso, modificación y creación de nuevos componentes.</p>

	
	<h1>Componentes</h1>

	<p>La construcción de Trunk se basa en el sistema de componentes de TreeWeb (html/php + css + js + ajax). Esto no significa que todos los
		componentes estén orientados a js, css, html al mismo tiempo, sino que puede haber
		componentes que sólo sean un conjunto de estilos css, otros que sólo tengan un pequeño script php con algo de css y otros que lo
		tengan todo.</p>
	
	
	<h2>TrunkLogo</h2>

	<p>El logo de Tunk de esta misma página. Consiste en un poco de html y css y puede ser utilizado desde cualquier fragmento html:</p>

	<code lang="html">
&lt;h1>Este es el título de <b>&#91;&#91;COMPONENT name=TrunkLogo]]</b> &lt;/h1>
	</code>

	
	<h2>TrunkDoc</h2>

	<p>Es esta misma documentación que estás leyendo. Puede ser utilizado desde cualquier fragmento html:</p>

	<code lang="html">
<b>&#91;&#91;COMPONENT name=TrunkLogo]]</b>
	</code>
	
	

	<h2>TrunkButton</h2>
	
	<div style="text-align: center;">
		[[COMPONENT name=TrunkButton text='&lt; default &gt;']]

		[[COMPONENT name=TrunkButton text=blue class=blue]]

		[[COMPONENT name=TrunkButton text=gren class=green]]

		[[COMPONENT name=TrunkButton text=aqua class=aqua]]

		[[COMPONENT name=TrunkButton text=orange class=orange]]

		[[COMPONENT name=TrunkButton text=red class=red]]

		[[COMPONENT name=TrunkButton text=purple class=purple]]

		[[COMPONENT name=TrunkButton text=gray class=gray]]

		[[COMPONENT name=TrunkButton text=none class=none]]
	</div>

	<h2>TrunkInputButton</h2>
	
	<div>
	[[COMPONENT name=TrunkInputButton]]
	</div>
	
	<h2>TrunkInputButton</h2>
	
	<p>This is a exclusive JavaScript component (for the moment).</p>

	[[COMPONENT name="TrunkTab"]]
	<div id="example-trunk-tab"></div>
	<div id="example-trunk-tab-text" style="text-align: center; padding: 64px;"></div>

	<script type="text/javascript">
	(function(){
		var text = document.getElementById('example-trunk-tab-text');
		var tt = trunk.create('Tab');
		tt.add('One').dom.addEventListener('click', function(e){ text.innerHTML='I am the one'; }, true);
		tt.add('Two').dom.addEventListener('click', function(e){ text.innerHTML='I am the two'; }, true);
		tt.add('Three').dom.addEventListener('click', function(e){ text.innerHTML='I am the three'; }, true);
		document.getElementById('example-trunk-tab').appendChild(tt.dom);
		tt.select(0).dom.click();
	})();
	</script>

	<p>The basic usage is:</p>
	<code>
var tt = trunk.create('Tab');
tt.add('One').dom.addEventListener('click', function(e){ tib.input.value='I am the one'; }, true);
tt.add('Two').dom.addEventListener('click', function(e){ tib.input.value='I am the two'; }, true);
tt.add('Three').dom.addEventListener('click', function(e){ tib.input.value='I am the three'; }, true);
the_parent_node_you_want.appendChild(tt.dom);
	</code>

	<p>Trik: you can choice if the event is raised when you select a tab programatically:</p>
	<code>
// Fire all events:
tt.get(0).dom.click();

// Select without fire events:
tt.select(0).dom.click();
	</code>
	
	<h1>Convenciones y reglas</h1>
	
	<h2>Nombrado de componentes</h2>
	
	<p>Todos los componentes de esta biblioteca comienzan por <tt>Trunk</tt> y van seguidos por el nombre del componente en camelcase,
		por ejemplo: <tt>TrunkButton</tt>, <tt>TrunkList</tt>, <tt>TrunkBigPanel</tt>, <tt>TrunkPopup</tt> son nombres de componente válidos </p>
	
	<h2>Espacios de nombres</h2>

	<p>Los navegadores web no tienen espacios de nombres encapsulados en la mayoría de los aspectos. Por ejemplo, si
		en una página añadimos una regla como <tt>* {color: red !important;}</tt>, probablemente todas las
		letras y bordes de la página se vean de color rojo. Por lo tanto <strong>no hay espacios de nombres ni ámbitos
		para el CSS</strong>.</p>

	<p>En JavaScript tampoco hay espacios de nombres, ya que en un navegador web las variables 'globales' son
		atributos del objeto <tt>window</tt>, que es accesible desde cualquier punto de la ejecución de JavaScript.</p>

	<p>Para evitar colisiones de nombre y encontrarnos con comportamientos indeseados al combinar el uso de varios
		componentes, se deben seguir una serie de reglas a la hora de desarrollar y modificar componentes.</p>

	<h3>Limitar el ámbito de las reglas CSS</h3>

	<p>Si el componente utiliza código html generado en el lado del servidor, éste debe ser envuelto con un tag
		que contenga el atributo <tt>component="TrunkName"</tt> para limitar la aplicación de reglas CSS.
		Siempre que sea viable técnicamente. Por ejemplo el siguiente bloque de php/html:</p>

	<code>
&lt;div <b>component="TrunkClock"</b>>
	&lt;?php foreach ($countries as $country) { ?>
	&lt;div class="country">
		&lt;div class="row-clock">
			&lt;div class="hours">&lt;?=$country->hours?>&lt;/div>
			&lt;div class="minutes">&lt;?=$country->minutes?>&lt;/div>
			&lt;div class="seconds">&lt;?=$country->seconds?>&lt;/div>
		&lt;/div>
		&lt;div class="row-country">&lt;?=$country->name?>&lt;/div>
	&lt;/div>
	&lt;?php } ?>
&lt;/div>
	</code>

	<p>De esta forma, las reglas CSS se deben aplicar siempre limitando con el selector
		<tt>[component="TrunkClock"]</tt>, por ejemplo:</p>

	<code>
<b>[component="TrunkClock"]</b> .country {
  display: block;
  padding: 16px;
  background: black;
}

<b>[component="TrunkClock"]</b> .hours,
<b>[component="TrunkClock"]</b> .minutes,
<b>[component="TrunkClock"]</b> .seconds {
  display: inline-block;
  background: gray;
  margin: 4px;
  border-radius: 16px;
  text-align: center;
  width: 100px;
  height: 100px;
  font-size: 70px;
}

<b>[component="TrunkClock"]</b> .hours {
  color: red;
}

<b>[component="TrunkClock"]</b> .minutes {
  color: green;
}

<b>[component="TrunkClock"]</b> .seconds {
  color: blue;
}

<b>[component="TrunkClock"]</b> .row-country {
  color: white;
  text-align: center;
}
	</code>		
	
	
</div>