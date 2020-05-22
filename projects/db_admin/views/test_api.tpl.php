<h1>test API</h1>
	<ul>
<li><a href='?q=form-import'>Import</a></li>
<li><a href='?q=form-export'>Export</a></li>
	</ul>

<pre>
api/console_import.php	
api/_import.php	
api/_export.php	

//post/get request
api/content.php
(
	[title] => APACHE, htaccess
	[type] => 2
	[body_value] => "test 1test 1test 1test 1test 1test 1"
	[q] => content/save
)
        
api/content.php?format=json
{
  "title":"note2",
	"parent_id":0
	"type_id":1
	"body_value":"test 1test 1test 1test 1test 1test 1"
	"q":"content/save"
	"q":"content/get"
	"q":"content/remove"
	"q":"content/list"
}

api/content.php?format=xml
<xroot>
	.....
</xroot>

api/content.php?q=get
api/content.php?q=remove
api/content.php?q=list
		
api/content-links.php?q=list

api/content-links.php?q=get-hierarchy 

api/content-links.php?q=remove

api/taxonomy.php?q=list
api/taxonomy.php?q=term-create
api/taxonomy.php?q=term-save
api/taxonomy.php?q=term-remove
api/taxonomy.php?q=term-group-create
api/taxonomy.php?q=term-group-save
api/taxonomy.php?q=term-group-list
api/taxonomy.php?q=term-group-remove
		
</pre>
