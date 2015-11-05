<!DOCTYPE html>
<?php
include "../include/db.php";
include "../include/general.php";
include "../include/resource_functions.php";
include "../include/image_processing.php";
include "../include/search_functions.php";
include "../include/mia_functions.php";
//phpinfo();exit();
print_r(refine_searchstring("art in bloom"));
//var_dump(get_user(8));
echo(get_resource_path(473, true, "", false, $extension="mp3"));
?>
<head>
  <title>Testing</title>
  <script type="text/javascript" src="http://localhost/lib/js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="http://localhost/lib/js/jquery-ui-1.10.2.custom.min.js"></script>
  <style type="text/css">
    body{margin:0;padding:0;}
    #search-results > li{width: 18%; display:inline-block; vertical-align:top;  word-wrap:break-word; margin: 1%; border-bottom: 20px solid #CCC}
    .ui-datepicker{background: #FFF; border: 1px solid; padding: 10px;}
    .ui-datepicker-current-day{background:#FF0000;}
    #loader{position:fixed; text-align:center; top: 0; left:0; background: rgba(255,255,255,0.8); width: 100%; height: 50%; padding: 25% 0; z-index: 10000; display: none;}
  </style>
</head>

<body>
<div id="loader">Quering Results<br/><img src="../gfx/ajax-loader.gif"/></div>
<h1>Resourcespace Custom API Search</h1>
<form id="elastic">
<input type="submit" value="Update Results to Elastic Search">
</form>
<form id="searchForm">
    <label for="searchTerm">
        <input id="searchTerm" type="text" name="searchTerm" />
    </label>
    <input type="submit" id="doSrchBtn" value="search"/>
    <label for="view_json">Format as JSON
        <input id="view_json" name="view_json" type="checkbox"/>
    </label>
    <br/><br/>
    <fieldset id="results_per_page">
        <legend>Results Per Page</legend>
        <input type="radio" class="per_page" name="per_page" value="10" checked>10
        <input type="radio" class="per_page" name="per_page" value="25">25
        <input type="radio" class="per_page" name="per_page" value="50">50
        <input type="radio" class="per_page" name="per_page" value="200">200
        <input type="radio" class="per_page" name="per_page" value="">Show All
    </fieldset>
    <fieldset>
       <legend>Limit By Resource:</legend>
       <label> Photo: <input class="rsType" type="checkbox" value="1" /></label>
       <label> Document: <input class="rsType"  type="checkbox" value="2"/></label>
       <label> Video: <input class="rsType" type="checkbox" value="3" /></label>
       <label> Audio: <input class="rsType" type="checkbox" value="4" /></label>
    </fieldset>
    <fieldset>
        <legend>Filter By Date:</legend>
        <label>
            <select id="byDate">
                <option value="">--</option>
                <option value="12">Loaded</option>
                <option value="148">Modified</option>
                <option value="85">Original Created</option>
                <option value="102">Subject Created</option>
            </select>
        </label>
        <label>From:</label>
            <input class="datepicker" id="datepickerFrom" type="text"/>
        <label>To:</label>
            <input class="datepicker" id="datepickerTo" type="text"/>
    </fieldset>

<!-- Filter by Dimensions -->

    <fieldset>
        <legend>Filter By Dimensions:</legend>
         <label>Width:</label>
             <select id="width_mod" name="width_mod">
                 <option value="0">Equal</option>
                 <option value="1">Less Than</option>
                 <option value="2">Greater Than</option>
             </select>
             <input id="width" name="width" type="text"/> pixels
         <br/>
         <label>Height: </label>
             <select id="height_mod" name="height_mod">
                 <option value="0">Equal</option>
                 <option value="1">Less Than</option>
                 <option value="2">Greater Than</option>
             </select>
             <input id="height" name="height" type="text"/> pixels
         <br/>
         <label>File Size</label>
             <select id="size_mod" name="size_mod">
                 <option value="0">Equal</option>
                 <option value="1">Less Than</option>
                 <option value="2">Greater Than</option>
             </select>
             <input id="size" name="size" type="text"/>
             <select id="size_type" name ="size_type">
                 <option value="0">Kb</option>
                 <option value="1">Mb</option>
                 <option value="2">Gb</option>
             </select>
         <br/>
         <label>Resolution:</label>
             <select id="resolution_mod" name="resolution_mod">
                 <option value="0">Equal</option>
                 <option value="1">Greater Than</option>
                 <option value="2">Less Than</option>
             </select>
             <input id="resolution" name="resolution" type="text"/>
    </fieldset>
    </form>

    <div id="pg-wrap"></div>
    <ul id="search-results"></ul>

</body>

<script type="text/javascript">

  var apikey = "";
  var perpage;
  var currentpage;

  jQuery(document).ready(function(){
    currentpage = 1;
    limitresourcetype = [];
    var d = new Date();
    var month = d.getMonth()+1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' +
    ((''+month).length<2 ? '0' : '') + month + '-' +
    ((''+day).length<2 ? '0' : '') + day;

    jQuery( "#datepickerFrom" ).datepicker({
      dateFormat: "yy-mm-dd",
      altField:"#datepickerFrom"
    });
    jQuery("#datepickerTo").datepicker({
      dateFormat: "yy-mm-dd",
      altField:"#datepickerTo",
      buttonImage:"/",
      buttonImageOnly: true
    }).datepicker("setDate", output);

    //make sure datepicker popups are hidden on load
    jQuery(".ui-datepicker").css("display","none");
  });
var viewjson = false;
  jQuery('#view_json').click(function(){
      if(jQuery('#view_json').is(":checked")){
         viewjson = true;
         formatasjson(returnedData);
      }else{
         viewjson = false;
        formatashtml(returnedData);
      }
  });
var returnedData;
var elasticcall=false;
jQuery("#elastic input").click(function(){
   elasticcall=true;
})
var elasticResponse = function(returnedData){
   alert('Resource(s) Successfully added to Elastic Search');
}
var formatasjson = function(returnedData){
    var parsedReturned = JSON.parse(returnedData)['resources'];
    var pagination = JSON.parse(returnedData)['pagination'];
    jQuery('#search-results').empty().html("<pre>"+JSON.stringify(parsedReturned,null,"    ")+"</pre>");
    makePagination(pagination,parsedReturned);
}
var formatashtml = function(returnedData){
       var parsedReturned = JSON.parse(returnedData)['resources'];
         var pagination = JSON.parse(returnedData)['pagination'];
         jQuery('#search-results').empty().html();
         for(i=0; i< parsedReturned.length; i++){
             jQuery('#search-results').append('<li id="resource-'+i+'"</li>');
             if(parsedReturned[i]['resource_type']==1){
                jQuery('#resource-'+i).append('<img style="width:80%;" src="'+parsedReturned[i]['original_link']+'"/>');
             }
              jQuery.each(parsedReturned[i], function(key, value){
                 if(key == 'original_link'){
                     jQuery('#resource-'+i).append('<li><b>' + key + ':</b> <a target="_BLANK" href="'+value+'">Link to Resource</a></li>');
                 }else{
                     jQuery('#resource-'+i).append('<li><b>' + key + ':</b> <em>' + value + '</em></li>');
                 }
             });
          };
          makePagination(pagination,parsedReturned);
}
  var handleSucc = function(data){
    returnedData = data.responseText;
    jQuery('#loader').css('display','none');
    jQuery('#doSrchBtn').prop("disabled","");
    if(elasticcall == true){
      elasticcall = false;
      elasticResponse(returnedData);
    }else if(viewjson == true){
      formatasjson(returnedData);
    }else{
      formatashtml(returnedData);
    }
  };

  var before = '';

  jQuery('.rsType').click(function(){
    if(jQuery(this).prop('checked') == true){
        limitresourcetype.push(jQuery(this).val());
    }else{
      var removeItem = jQuery(this).val();
      limitresourcetype = jQuery.grep(limitresourcetype, function(value) {
        return value != removeItem;
      });
    }
  });

//--------------------------
//    Pagination
//--------------------------

function makePagination(pagination,parsedReturned){
    var paginate;
    var after;
    resource_track = "Total Matched Resources: " + pagination.total_resources;
    paginate = " On Page " + currentpage + " of " + pagination.total_pages;
    if (currentpage != 1){
        before = '<a href="#" id="page-prev"> Prev </a>'
    };
    if(currentpage != pagination['total_pages'] && pagination['total_pages']>1){
        after = '<a href="#" id="page-next"> Next </a>';
    }else{
        after = "";
    };
    if(parsedReturned != ''){
    jQuery('#pg-wrap').empty().append('<br/>' + resource_track + '<br/>' + before + paginate + after);
    }else{
    jQuery('#pg-wrap').empty().append('<br/>' + resource_track);
    };
    jQuery("#page-next").click(function(e){
        e.preventDefault();
        currentpage = currentpage+1;
        makeCall(currentpage);
    });
    jQuery("#page-prev").click(function(e){
        e.preventDefault();
        currentpage =currentpage-1;
        makeCall(currentpage);
    });
};

var handleErr = function(data){
    console.log('Error'+data);
};

var inputValue;
jQuery('#searchTerm').keyup(function(){
    inputValue = jQuery(this).val();
}).keyup();
jQuery('#searchTerm').focusout(function() {
    inputValue = jQuery(this).val();
});

var data;
var ajax = function(url, datas, type, Datatype, processData, cache, contentType, Err, Succ){
    jQuery.ajax({
        url: url,
        data: datas,
        type: type,
        cache: cache,
        dataType:Datatype,
        processData: processData,
        contentType: contentType,
        success: Succ,
        error: Err,
        statusCode: {
            404: function(){
                alert('Page Not Found');
            }
        }
    });
}

jQuery("#doSrchBtn").click(function(e){
    e.preventDefault();
    jQuery(this).prop("disabled","disabled");
    currentpage =1;
    jsn="";
    makeCall();
});
jQuery("#elastic").click(function(e){
    e.preventDefault();
    jQuery(this).prop("disabled","disabled");
    jsn="elastic";
    makeCall();
});
var jsn ="";
function makeCall(){
jQuery('#loader').css('display','block');
    dateFrom = jQuery('#datepickerFrom').val();
    dateTo = jQuery('#datepickerTo').val();
    byDate = jQuery('#byDate').val();
    var perpage = jQuery("#results_per_page input[type='radio']:checked").prop("value");
    if(perpage == ""){
        currentpage="";
    }
    var width="";
    var height="";
    var resolution="";
    var size="";
    if(jQuery('#width').val()!=""){
    var width=jQuery('#width').val()+":"+jQuery('#width_mod').val();
    };
    if(jQuery('#height').val()!=""){
    var height=jQuery('#height').val()+":"+jQuery('#height_mod').val();
    };
    if(jQuery('#resolution').val()!=""){
    var resolution=jQuery('#resolution').val()+":"+jQuery('#resolution_mod').val();
    };
    if(jQuery('#size').val()!=""){
    var size=jQuery('#size').val()+":"+jQuery('#size_mod').val()+":"+jQuery('#size_type').val();
    };
    jQuery('#search-results').empty().html('');
    inputValue = inputValue.replace(/([~.:!#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, ', ').replace(/^(,)+|(,)+$/g,'');
    ajax("http://localhost/plugins/api_search/?key=" + apikey+
        "&search="+inputValue+
        "&results_per_page="+perpage+
        "&page="+currentpage+
        "&restypes="+limitresourcetype+
        "&original=TRUE&bydate="+byDate+
        "&datefrom="+dateFrom+
        "&dateto="+dateTo+
        "&width="+width+
        "&height="+height+
        "&size="+size+
        "&fulldata=TRUE"+
        "&viewjson="+jsn+
        "&resolution="+resolution,data,"GET",true,false,"JSON",handleErr,handleSucc
    );

};
</script>
