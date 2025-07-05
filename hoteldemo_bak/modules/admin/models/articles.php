
<?php if ( !($this->site_id > 0) ) { ?>
	<div  id="default">
		<span style="color:red; font-weight:bold; font-size:12px;">Please Select a site on the right corner of this page.</span>
	</div>
<?php } else { ?>
	<link href="/common/css/Select.css" media="screen" rel="Stylesheet" type="text/css" /> 
	<script type="text/javascript" src="/common/extjs_plugins/colorpicker/colorpicker.js"></script>
	<script type="text/javascript" src="/common/extjs_plugins/colorpicker/colorpickerfield.js"></script>
	<link rel="stylesheet" type="text/css" href="/common/css/colorpicker.css" />
	
	<link rel="stylesheet" type="text/css" href="/common/css/FieldOverride.css" />
	<script type="text/javascript" src="/common/extjs_plugins/FieldOverride.js"></script>
	
	<script language="javascript" type="text/javascript" src="/common/extjs_plugins/ShowPicture.js"></script>
	<script type='text/javascript' src='/common/extjs_plugins/Select.js'></script>
	<script type='text/javascript' src='/common/extjs_plugins/CheckColumn.js'></script>
	
	<script type='text/javascript' src='/common/extjs_plugins/fckeditor/fckeditor.js'></script>
	<script type='text/javascript' src='/common/extjs_plugins/FCKeditor.js'></script>
	
	<link rel="stylesheet" type="text/css" href="/common/css/Select.css" />
    <script language="javascript" type="text/javascript" src="/common/extjs_plugins/Select.js"></script>

    <script type="text/javascript" src="/common/extjs_plugins/Ext.ux.form.DateTime.js"></script>
    
    <script type="text/javascript">
    var arrCategories =  [
    	<?php if(is_array($this->categories)) foreach ($this->categories as $idx=>$category) { ?>
    		['<?php echo htmlentities($category['section_id'], ENT_QUOTES); ?>', '<?php echo htmlentities($category['section_name'], ENT_QUOTES); ?>']<?php if($idx < count($this->categories)-1) { ?>,<?php } ?>
    	<?php } ?>
    ];
	var categoriesStore = new Ext.data.SimpleStore({
		id: 0,
        fields: ['category_id', 'category_name'],
        data : arrCategories
    });
    </script>
	
    
    <script type='text/javascript' src='/js/articles.js'></script>
    
	<style>
		.thumb{
	        background: #dddddd;
	        padding: 3px;
		}
		
		.thumb img{
	        height: 60px;
		}
		
		.thumb-wrap{
	        /*float: left;*/
	        margin: 4px;
	        margin-right: 0;
	        padding: 5px;
		}
		
		.thumb-wrap span{
	        display: block;
	        overflow: hidden;
	        text-align: center;
		}
		
		.x-view-over{
		    border:1px solid #dddddd;
		    background: #efefef url(../../resources/images/default/grid/row-over.gif) repeat-x left top;
	        padding: 4px;
		}
		
		.x-view-selected{
	        background: #eff5fb url(images/selected.gif) no-repeat right bottom;
	        border:1px solid #99bbe8;
	        padding: 4px;
		}
		.x-view-selected .thumb{
	        background:transparent;
		}
		
		.loading-indicator {
	        font-size:11px;
	        background-image:url('../../resources/images/default/grid/loading.gif');
	        background-repeat: no-repeat;
	        background-position: left;
	        padding-left:20px;
	        margin:10px;
		}

	</style>
	
	<div id='articlesRender'></div>
	<div id='addArticleRender' style='visibility:hidden; position:absolute; z-index:99; width:300px; margin-left:75px; top:12px;'></div>
	<div id='editArticleRender' style='visibility:hidden; position:absolute; z-index:99; width:300px; margin-left:75px; top:12px;'>
		<div id='exportArticleRender' style='visibility:hidden; position:absolute; z-index:100; width:100px; margin-left:175px; top:800px;'></div>
		<div id='addRelatedLinkRender' style='visibility:hidden; position:absolute; z-index:100; width:100px; margin-left:75px; top:900px;'></div>
		<div id='editRelatedLinkRender' style='visibility:hidden; position:absolute; z-index:100; width:100px; margin-left:75px; top:900px;'></div>
	</div>	
<?php } ?>

