<?php
//echo _logWrap($params["notes"]);
$_vars["log"][] = array("message" => $params["content_list"][0], "type" => "info");

$total = 0;
$html_table = "";
if ( !empty( $params["content_list"] ) ) {
	$arg = array(
		"data" => $params["content_list"],
		"templates" => array(
			"tpl_head" => "<tr class='text-center'>
<td></td> 
{{field_names}}
<td><b>actions</b></td>
</tr>",
			"tpl_record" => "<tr>
<td>
<input type='checkbox' id='edit-nodes-{{id}}' name='nodes[]' value='id-{{id}}' class='form-checkbox'>
</td>
{{field_columns}}	
	<td>
<a class='btn btn-green-shadow' href='?q=content/view&id={{id}}'>view</a>
<a class='btn btn-darkblue' href='?q=content/edit&id={{id}}'>edit</a>
<a class='btn btn-red' href='?q=content/remove&id={{id}}'>remove</a>
	</td>
</tr>"
		) 
	);
	$html_table = widget_table($arg);
	$total = count($params["content_list"]);
}
?>

<h1>List content</h1>
<h3>num elements: <?php echo $total ?></h3>
<ul>
	<li><a href='?q=content/create'>add new content item</a></li>
	<li><a href='?q=content/clear'>clear table content</a></li>
	<li><a href='?q=content/set_values'>set init values (content types, filter formats...)</a></li>
<!-- class="inline-list" -->	
</ul>

<form name="form_list_content" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
	<div>
<!--		
 <div class="row">
	<td>
		 <div class="form-item form-type-checkbox form-item-nodes-744">
 <label class="element-invisible" for="edit-nodes-744">Обновить passwords </label>
 <input type="checkbox" id="edit-nodes-744" name="nodes[744]" value="744" class="form-checkbox">
		</div>
	</td>
	<td>
		<a href="/sites/mydb/?q=node/744">passwords</a> 
	</td>
	<td>Page</td>
	<td>
		<a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a>
	</td>
	<td>опубликовано</td>
	<td>04/25/2020 - 10:03</td>
	<td>Русский</td>
	<td>
		<ul class="links inline">
			<li class="edit first">
<a href="/sites/mydb/?q=node/744/edit&amp;destination=admin/content">изменить</a>
			</li>
			<li class="delete last">
<a href="/sites/mydb/?q=node/744/delete&amp;destination=admin/content">удалить</a>
			</li>
		</ul>
	</td>
</div>
-->	
<?php
echo $html_table;
?>	
<input type="submit" id="" name="op" value="Remove selected items" class="form-submit">

<!--	
	<fieldset class="form-wrapper" id="edit-filters">
		<legend><span class="fieldset-legend">Показать материалы, у которых</span></legend>
		<div class="fieldset-wrapper">
			<div class="exposed-filters">
				<div class="clearfix form-wrapper" id="edit-status">
					
					<div class="filters form-wrapper" id="edit-filters--2">
						
						<div class="form-item form-type-select form-item-status">
							<label for="edit-status--2">состояние </label>
							 <select id="edit-status--2" name="status" class="form-select">
								 <option value="[any]" selected="selected">любой</option>
								 <option value="status-1">опубликовано</option>
								 <option value="status-0">не опубликовано</option>
								 <option value="promote-1">на главной</option>
								 <option value="promote-0">не на главной</option>
								 <option value="sticky-1">прикреплено</option>
								 <option value="sticky-0">не прикреплено</option>
							</select>
						</div>

						<div class="form-item form-type-select form-item-type">
							<label for="edit-type">тип </label>
							<select id="edit-type" name="type" class="form-select">
								<option value="[any]" selected="selected">любой</option>
								<option value="page">Page</option>
							</select>
						</div>
						
						<div class="form-item form-type-select form-item-language">
							<label for="edit-language">язык. </label>
							<select id="edit-language" name="language" class="form-select">
								<option value="[any]" selected="selected">любой</option>
								<option value="und">Нейтральный по отношению к языку</option>
								<option value="en">Английский</option>
								<option value="ru">Русский</option>
							</select>
						</div>
					</div>
					
					<div class="container-inline form-actions form-wrapper" id="edit-actions">
<input type="submit" id="edit-submit" name="op" value="Фильтр" class="form-submit">
					</div>
				</div>
			</div>
		</div>
	</fieldset>
-->
	
<!--	
	<fieldset class="container-inline form-wrapper" id="edit-options">
		<legend><span class="fieldset-legend">Изменить настройки</span></legend>
		<div class="fieldset-wrapper">
			<div class="form-item form-type-select form-item-operation">
				<label class="element-invisible" for="edit-operation">Операции </label>
				<select id="edit-operation" name="operation" class="form-select">
					<option value="publish">Опубликовать выбранные материалы</option>
					<option value="unpublish">Снять с публикации выбранные материалы</option>
					<option value="promote">Разместить выбранные материалы на главной</option>
					<option value="demote">Убрать выбранные материалы с главной страницы</option>
					<option value="sticky">Прикрепить выбранные материалы вверху списков</option>
					<option value="unsticky">Отменить прикрепление выбранных материалов вверху списков</option>
					<option value="delete">Удалить выбранные материалы</option>
				</select>
			</div>
<input type="submit" id="edit-submit--2" name="op" value="Обновить" class="form-submit">
		</div>
	</fieldset>
-->	

<!--
<table class="sticky-header" style="position: fixed; top: 30px; left: 377px; visibility: hidden;">
	<thead style="">
		<tr>
			<th class="select-all">
<input type="checkbox" class="form-checkbox" title="Отметить все строки таблицы">
			</th>
			<th>
<a href="/?q=admin/content&amp;sort=asc&amp;order=" title="сортировать по Язык" class="active">Язык</a>
			</th>
			<th>Действия</th>
		</tr>
	</thead>
</table>
-->

<!--
<table class="sticky-enabled table-select-processed tableheader-processed sticky-table">
 <thead><tr><th class="select-all"><input type="checkbox" class="form-checkbox" title="Отметить все строки таблицы"></th><th><a href="/sites/mydb/?q=admin/content&amp;sort=asc&amp;order=%D0%97%D0%B0%D0%B3%D0%BE%D0%BB%D0%BE%D0%B2%D0%BE%D0%BA" title="сортировать по Заголовок" class="active">Заголовок</a></th><th><a href="/sites/mydb/?q=admin/content&amp;sort=asc&amp;order=%D0%A2%D0%B8%D0%BF" title="сортировать по Тип" class="active">Тип</a></th><th>Автор</th><th><a href="/sites/mydb/?q=admin/content&amp;sort=asc&amp;order=%D0%A1%D0%BE%D1%81%D1%82%D0%BE%D1%8F%D0%BD%D0%B8%D0%B5" title="сортировать по Состояние" class="active">Состояние</a></th><th class="active"><a href="/sites/mydb/?q=admin/content&amp;sort=asc&amp;order=%D0%9E%D0%B1%D0%BD%D0%BE%D0%B2%D0%BB%D0%B5%D0%BD%D0%BE" title="сортировать по Обновлено" class="active">Обновлено<img src="http://vbox1/sites/mydb/misc/arrow-asc.png" alt="сортировать по возрастанию" title="сортировать по возрастанию" width="13" height="13"></a></th><th><a href="/sites/mydb/?q=admin/content&amp;sort=asc&amp;order=%D0%AF%D0%B7%D1%8B%D0%BA" title="сортировать по Язык" class="active">Язык</a></th><th>Действия</th> </tr></thead>
<tbody>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-744">
  <label class="element-invisible" for="edit-nodes-744">Обновить passwords </label>
 <input type="checkbox" id="edit-nodes-744" name="nodes[744]" value="744" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/744">passwords</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/25/2020 - 10:03</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/744/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/744/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1247">
  <label class="element-invisible" for="edit-nodes-1247">Обновить torrents password </label>
 <input type="checkbox" id="edit-nodes-1247" name="nodes[1247]" value="1247" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1247">torrents password</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/24/2020 - 20:53</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1247/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1247/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1241">
  <label class="element-invisible" for="edit-nodes-1241">Обновить services, payments, passwords </label>
 <input type="checkbox" id="edit-nodes-1241" name="nodes[1241]" value="1241" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1241">services, payments, passwords</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/19/2020 - 10:18</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1241/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1241/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1238">
  <label class="element-invisible" for="edit-nodes-1238">Обновить web passwords </label>
 <input type="checkbox" id="edit-nodes-1238" name="nodes[1238]" value="1238" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1238">web passwords</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/19/2020 - 10:04</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1238/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1238/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1246">
  <label class="element-invisible" for="edit-nodes-1246">Обновить create sources repository (samba share on router) </label>
 <input type="checkbox" id="edit-nodes-1246" name="nodes[1246]" value="1246" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1246">create sources repository (samba share on router)</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/10/2020 - 11:52</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1246/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1246/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1219">
  <label class="element-invisible" for="edit-nodes-1219">Обновить genealogy </label>
 <input type="checkbox" id="edit-nodes-1219" name="nodes[1219]" value="1219" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1219">genealogy</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/09/2020 - 11:08</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1219/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1219/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1228">
  <label class="element-invisible" for="edit-nodes-1228">Обновить Москаленко Николай Егорович </label>
 <input type="checkbox" id="edit-nodes-1228" name="nodes[1228]" value="1228" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1228">Москаленко Николай Егорович</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/09/2020 - 11:03</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1228/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1228/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1227">
  <label class="element-invisible" for="edit-nodes-1227">Обновить Лаптев Иван Андреевич </label>
 <input type="checkbox" id="edit-nodes-1227" name="nodes[1227]" value="1227" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1227">Лаптев Иван Андреевич</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/09/2020 - 11:03</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1227/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1227/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1189">
  <label class="element-invisible" for="edit-nodes-1189">Обновить компиляция PHP из исходников </label>
 <input type="checkbox" id="edit-nodes-1189" name="nodes[1189]" value="1189" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1189">компиляция PHP из исходников</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/08/2020 - 18:18</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1189/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1189/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1245">
  <label class="element-invisible" for="edit-nodes-1245">Обновить компиляция PHP из исходников, windows, VS 2008 (9.0) </label>
 <input type="checkbox" id="edit-nodes-1245" name="nodes[1245]" value="1245" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1245">компиляция PHP из исходников, windows, VS 2008 (9.0)</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/08/2020 - 18:17</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1245/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1245/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1244">
  <label class="element-invisible" for="edit-nodes-1244">Обновить Сценарии программ </label>
 <input type="checkbox" id="edit-nodes-1244" name="nodes[1244]" value="1244" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1244">Сценарии программ</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/06/2020 - 09:13</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1244/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1244/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-672">
  <label class="element-invisible" for="edit-nodes-672">Обновить hosting sites </label>
 <input type="checkbox" id="edit-nodes-672" name="nodes[672]" value="672" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/672">hosting sites</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 16:05</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/672/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/672/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1243">
  <label class="element-invisible" for="edit-nodes-1243">Обновить коммунальные платежи </label>
 <input type="checkbox" id="edit-nodes-1243" name="nodes[1243]" value="1243" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1243">коммунальные платежи</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 15:05</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1243/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1243/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1239">
  <label class="element-invisible" for="edit-nodes-1239">Обновить дом, инфо </label>
 <input type="checkbox" id="edit-nodes-1239" name="nodes[1239]" value="1239" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1239">дом, инфо</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 13:22</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1239/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1239/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1242">
  <label class="element-invisible" for="edit-nodes-1242">Обновить shops, passwords </label>
 <input type="checkbox" id="edit-nodes-1242" name="nodes[1242]" value="1242" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1242">shops, passwords</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 13:10</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1242/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1242/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-723">
  <label class="element-invisible" for="edit-nodes-723">Обновить personal info </label>
 <input type="checkbox" id="edit-nodes-723" name="nodes[723]" value="723" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=private_info">personal info</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 12:42</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/723/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/723/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-745">
  <label class="element-invisible" for="edit-nodes-745">Обновить banks, passwords </label>
 <input type="checkbox" id="edit-nodes-745" name="nodes[745]" value="745" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/745">banks, passwords</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/02/2020 - 12:41</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/745/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/745/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-729">
  <label class="element-invisible" for="edit-nodes-729">Обновить php </label>
 <input type="checkbox" id="edit-nodes-729" name="nodes[729]" value="729" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/729">php</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/23/2020 - 11:02</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/729/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/729/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1237">
  <label class="element-invisible" for="edit-nodes-1237">Обновить install Composer </label>
 <input type="checkbox" id="edit-nodes-1237" name="nodes[1237]" value="1237" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1237">install Composer</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/11/2020 - 11:27</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1237/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1237/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-791">
  <label class="element-invisible" for="edit-nodes-791">Обновить mysql </label>
 <input type="checkbox" id="edit-nodes-791" name="nodes[791]" value="791" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/791">mysql</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/09/2020 - 12:31</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/791/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/791/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-839">
  <label class="element-invisible" for="edit-nodes-839">Обновить работа </label>
 <input type="checkbox" id="edit-nodes-839" name="nodes[839]" value="839" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/839">работа</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/02/2020 - 11:41</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/839/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/839/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1236">
  <label class="element-invisible" for="edit-nodes-1236">Обновить поликлиника </label>
 <input type="checkbox" id="edit-nodes-1236" name="nodes[1236]" value="1236" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1236">поликлиника</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>01/01/2020 - 07:54</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1236/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1236/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1071">
  <label class="element-invisible" for="edit-nodes-1071">Обновить телефоны, коммун. службы </label>
 <input type="checkbox" id="edit-nodes-1071" name="nodes[1071]" value="1071" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1071">телефоны, коммун. службы</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>09/03/2019 - 14:51</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1071/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1071/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-738">
  <label class="element-invisible" for="edit-nodes-738">Обновить контакты </label>
 <input type="checkbox" id="edit-nodes-738" name="nodes[738]" value="738" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/738">контакты</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>09/03/2019 - 14:50</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/738/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/738/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1233">
  <label class="element-invisible" for="edit-nodes-1233">Обновить 1 мировая </label>
 <input type="checkbox" id="edit-nodes-1233" name="nodes[1233]" value="1233" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1233">1 мировая</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 20:09</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1233/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1233/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1235">
  <label class="element-invisible" for="edit-nodes-1235">Обновить Гражданская война </label>
 <input type="checkbox" id="edit-nodes-1235" name="nodes[1235]" value="1235" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1235">Гражданская война</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 20:08</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1235/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1235/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1232">
  <label class="element-invisible" for="edit-nodes-1232">Обновить казачьи войсковые части </label>
 <input type="checkbox" id="edit-nodes-1232" name="nodes[1232]" value="1232" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1232">казачьи войсковые части</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 20:07</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1232/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1232/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1234">
  <label class="element-invisible" for="edit-nodes-1234">Обновить второй Урупский полк </label>
 <input type="checkbox" id="edit-nodes-1234" name="nodes[1234]" value="1234" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1234">второй Урупский полк</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 20:03</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1234/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1234/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1231">
  <label class="element-invisible" for="edit-nodes-1231">Обновить село Макарово, Листопадовка </label>
 <input type="checkbox" id="edit-nodes-1231" name="nodes[1231]" value="1231" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1231">село Макарово, Листопадовка</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 19:57</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1231/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1231/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1229">
  <label class="element-invisible" for="edit-nodes-1229">Обновить Филипповское, Царский дар, Великовечное </label>
 <input type="checkbox" id="edit-nodes-1229" name="nodes[1229]" value="1229" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1229">Филипповское, Царский дар, Великовечное</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 19:56</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1229/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1229/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1226">
  <label class="element-invisible" for="edit-nodes-1226">Обновить деревня Красавино </label>
 <input type="checkbox" id="edit-nodes-1226" name="nodes[1226]" value="1226" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1226">деревня Красавино</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 19:50</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1226/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1226/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1230">
  <label class="element-invisible" for="edit-nodes-1230">Обновить станица Кужорская </label>
 <input type="checkbox" id="edit-nodes-1230" name="nodes[1230]" value="1230" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1230">станица Кужорская</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 19:49</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1230/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1230/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1225">
  <label class="element-invisible" for="edit-nodes-1225">Обновить Леонидовы </label>
 <input type="checkbox" id="edit-nodes-1225" name="nodes[1225]" value="1225" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1225">Леонидовы</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:56</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1225/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1225/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1221">
  <label class="element-invisible" for="edit-nodes-1221">Обновить Москаленко </label>
 <input type="checkbox" id="edit-nodes-1221" name="nodes[1221]" value="1221" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1221">Москаленко</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:54</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1221/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1221/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1224">
  <label class="element-invisible" for="edit-nodes-1224">Обновить Гридасовы </label>
 <input type="checkbox" id="edit-nodes-1224" name="nodes[1224]" value="1224" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1224">Гридасовы</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:53</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1224/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1224/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1222">
  <label class="element-invisible" for="edit-nodes-1222">Обновить Васины </label>
 <input type="checkbox" id="edit-nodes-1222" name="nodes[1222]" value="1222" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1222">Васины</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:52</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1222/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1222/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1223">
  <label class="element-invisible" for="edit-nodes-1223">Обновить Шаталовы </label>
 <input type="checkbox" id="edit-nodes-1223" name="nodes[1223]" value="1223" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1223">Шаталовы</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:47</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1223/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1223/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1220">
  <label class="element-invisible" for="edit-nodes-1220">Обновить Лаптевы </label>
 <input type="checkbox" id="edit-nodes-1220" name="nodes[1220]" value="1220" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1220">Лаптевы</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/30/2019 - 16:23</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1220/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1220/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1192">
  <label class="element-invisible" for="edit-nodes-1192">Обновить net pwd </label>
 <input type="checkbox" id="edit-nodes-1192" name="nodes[1192]" value="1192" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1192">net pwd</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/26/2019 - 20:37</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1192/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1192/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1121">
  <label class="element-invisible" for="edit-nodes-1121">Обновить D-LINK WIRELESS ROUTER, MAC FILTERING </label>
 <input type="checkbox" id="edit-nodes-1121" name="nodes[1121]" value="1121" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1121">D-LINK WIRELESS ROUTER, MAC FILTERING</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>06/23/2019 - 18:09</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1121/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1121/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1206">
  <label class="element-invisible" for="edit-nodes-1206">Обновить Heroku.com </label>
 <input type="checkbox" id="edit-nodes-1206" name="nodes[1206]" value="1206" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1206">Heroku.com</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>05/28/2019 - 19:38</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1206/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1206/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1130">
  <label class="element-invisible" for="edit-nodes-1130">Обновить mobile </label>
 <input type="checkbox" id="edit-nodes-1130" name="nodes[1130]" value="1130" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1130">mobile</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>05/27/2019 - 19:45</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1130/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1130/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-817">
  <label class="element-invisible" for="edit-nodes-817">Обновить windows_faq </label>
 <input type="checkbox" id="edit-nodes-817" name="nodes[817]" value="817" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/817">windows_faq</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>05/12/2019 - 11:30</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/817/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/817/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1122">
  <label class="element-invisible" for="edit-nodes-1122">Обновить XRandR: настройка режимов работы с дисплеями (мониторами) в Linux </label>
 <input type="checkbox" id="edit-nodes-1122" name="nodes[1122]" value="1122" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1122">XRandR: настройка режимов работы с дисплеями (мониторами) в Linux</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>05/05/2019 - 11:17</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1122/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1122/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1218">
  <label class="element-invisible" for="edit-nodes-1218">Обновить git, сбросить master </label>
 <input type="checkbox" id="edit-nodes-1218" name="nodes[1218]" value="1218" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1218">git, сбросить master</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/14/2019 - 12:13</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1218/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1218/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-1087">
  <label class="element-invisible" for="edit-nodes-1087">Обновить git, откатить коммиты </label>
 <input type="checkbox" id="edit-nodes-1087" name="nodes[1087]" value="1087" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1087">git, откатить коммиты</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>04/14/2019 - 12:09</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1087/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1087/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1216">
  <label class="element-invisible" for="edit-nodes-1216">Обновить install sqlite </label>
 <input type="checkbox" id="edit-nodes-1216" name="nodes[1216]" value="1216" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1216">install sqlite</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/16/2019 - 20:08</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1216/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1216/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-750">
  <label class="element-invisible" for="edit-nodes-750">Обновить mix </label>
 <input type="checkbox" id="edit-nodes-750" name="nodes[750]" value="750" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/750">mix</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/16/2019 - 19:12</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/750/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/750/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="odd"><td><div class="form-item form-type-checkbox form-item-nodes-1031">
  <label class="element-invisible" for="edit-nodes-1031">Обновить Кроссбразерное отображение флэшки </label>
 <input type="checkbox" id="edit-nodes-1031" name="nodes[1031]" value="1031" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=node/1031">Кроссбразерное отображение флэшки</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>03/10/2019 - 16:13</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/1031/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/1031/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
 <tr class="even"><td><div class="form-item form-type-checkbox form-item-nodes-437">
  <label class="element-invisible" for="edit-nodes-437">Обновить notes </label>
 <input type="checkbox" id="edit-nodes-437" name="nodes[437]" value="437" class="form-checkbox">
</div>
</td><td><a href="/sites/mydb/?q=book_notes">notes</a> </td><td>Page</td><td><a href="/sites/mydb/?q=user/1" title="Информация о пользователе." class="username">admin</a></td><td>опубликовано</td><td>12/14/2018 - 19:28</td><td>Русский</td><td><ul class="links inline"><li class="edit first"><a href="/sites/mydb/?q=node/437/edit&amp;destination=admin/content">изменить</a></li>
<li class="delete last"><a href="/sites/mydb/?q=node/437/delete&amp;destination=admin/content">удалить</a></li>
</ul></td> </tr>
</tbody>
</table>
<h2 class="element-invisible">Страницы</h2><div class="item-list"><ul class="pager"><li class="pager-current first">1</li>
<li class="pager-item"><a title="На страницу номер 2" href="/sites/mydb/?q=admin/content&amp;page=1">2</a></li>
<li class="pager-item"><a title="На страницу номер 3" href="/sites/mydb/?q=admin/content&amp;page=2">3</a></li>
<li class="pager-item"><a title="На страницу номер 4" href="/sites/mydb/?q=admin/content&amp;page=3">4</a></li>
<li class="pager-item"><a title="На страницу номер 5" href="/sites/mydb/?q=admin/content&amp;page=4">5</a></li>
<li class="pager-item"><a title="На страницу номер 6" href="/sites/mydb/?q=admin/content&amp;page=5">6</a></li>
<li class="pager-item"><a title="На страницу номер 7" href="/sites/mydb/?q=admin/content&amp;page=6">7</a></li>
<li class="pager-item"><a title="На страницу номер 8" href="/sites/mydb/?q=admin/content&amp;page=7">8</a></li>
<li class="pager-item"><a title="На страницу номер 9" href="/sites/mydb/?q=admin/content&amp;page=8">9</a></li>
<li class="pager-next"><a title="На следующую страницу" href="/sites/mydb/?q=admin/content&amp;page=1">следующая ›</a></li>
<li class="pager-last last"><a title="На последнюю страницу" href="/sites/mydb/?q=admin/content&amp;page=9">последняя »</a></li>
</ul></div><input type="hidden" name="form_build_id" value="form-y5ggdMEGrdW3l9Iof9a1NXQgcRCG7gOvjosFY7XbyLA">
<input type="hidden" name="form_token" value="LKrsjw5IuKR0D6K-NJprOuTUQx286xlFtpboG0UpVOk">
<input type="hidden" name="form_id" value="node_admin_content">
-->

	</div>
</form>
