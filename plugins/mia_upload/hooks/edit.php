<?php if((getval("uploader","")!="")&&(getval("uploader","")!="local")){?>
<style>#mainform{display:none;}</style>
<?php }else{ ?>
<style> #mainform{display:block;} </style><?php } ?>

<?php
function  HookMia_uploadEditEditbeforeheader(){
    global $baseurl;
    $resource_types=get_resource_types();
    $resource_extensions=array();
    $display_extensions = array();
    for($i=0; $i<count($resource_types); $i++){
        $display_extensions[get_resource_type_name($i+1)]=$resource_types[$i]['allowed_extensions'];
        //explode the allowed extensions into an array and remove any whitespaces?*
        $extension = preg_replace('/\s*/', '', $resource_types[$i]['allowed_extensions']);
        $before = explode("," , strtolower($extension));
        foreach($before as $after){
            $resource_extensions[] = $after;
        };
    };
    $resource_extensions = json_encode($resource_extensions);
    $getTMS=sql_query("select ref, tms_field from resource_type_field where tms_field != ''");
    $TMS = json_encode($getTMS);
    ?>

<script type="text/javascript">
        var identifier = 0;
        var filesinbatch = new Array();
        var allowedext = <?php echo($resource_extensions); ?>;
        var display_ext = <?php echo json_encode($display_extensions, true); ?>;
        var jpray = <?php echo($TMS); ?>;
</script>
<?php
}
?>

<?php
function HookMia_uploadEditAutolivejs(){
  if((getval("uploader","")!="")&&(getval("uploader","")!="local")){
  ?>
  <style>
    <?php include(__DIR__."/../css/upload_styles.css");?>
  </style>
<?php
  global $baseurl, $TMS;
  ?>
<script type="text/javascript">
 pre_upload_form = '<div id="progressBar"></div>'+
    '<div class="form-wrap">'+
    '<nav id="upload-navigation">'+
    '<ul>'+
      '<li class="active"><a href="#" id="single-upload">Upload</a></li>'+
      '<li><a id="batch-upload">Batch Upload</a></li>'+
      '<a id="info-formats" href="#">Available File Formats.</a>'+
    '</ul>'+
  '</nav>'+
        '<div id ="err-msg"></div>'+
        '<div id="upload-stat">'+
         '<ul>'+
              '<li id="ups-tot">Completed: <span id="ups-proc">0</span> / <span id="ups-fcount">0</span></li>'+
              '<li id="ups-err">Errors: <span>0</span></li>'+
              '<li id="ups-wrn">Warnings: <span>0</span></li>'+
              '<li id="ups-scc">Success: <span>0</span></li>'+
          '</ul>'+
        '</div>'+
        '<form id="upload_form">'+
            '<div id="dragandrophandler">Drag and Drop Files here</div>'+
            '<label for="file_input">Select File:</label>'+
            '<input id="file_input" type="file">'+
            '<input id="submit_btn" type="submit" value="Start upload" disabled>'+
       '</form>'+
        '<ul id="file_list" style="display: none;"></ul>'+
    '</div>'+
    '<div id="meta-message"></div>';
    if (jQuery(" .form-wrap ").length == 0){
    jQuery("#mainform").before(pre_upload_form);
    jQuery(".QuestionSubmit").after('<div id="upload-log" style="display:none">'+
                                '<div id="upload-log-msg"></div>'+
                                '<div id="success-actions" style="display:none; opacity:0;"></div>'+
                                '</div>'
                              );
    }
        filecount = 0;

        function ChunkedUploader(file, options, identifier) {
            if (!this instanceof ChunkedUploader) {
                return new ChunkedUploader(file, options);
            }
            this.file = file;
            this.options = jQuery.extend({
            url: '<?php echo $baseurl?>/plugins/mia_upload/pages/chunk.php'
            }, options);
            this.file_size = this.file.size;
            this.chunk_size = (1024*1000);
            this.range_start = 0;
            this.range_end = this.chunk_size;
            this.identifier = identifier;
            if ('mozSlice' in this.file) {
                this.slice_method = 'mozSlice';
            }
            else if ('webkitSlice' in this.file) {
                this.slice_method = 'webkitSlice';
            }
            else {
                this.slice_method = 'slice';
            }
        }//end Chunk Func

//--------------------+
//    Upload Class    |
//--------------------+
var refs = "";
var completedfiles = 0;
var fileerr = 0;
var filewarn = 0;
var filesucc = 0;
var bStatMsg = "";
called = false;
var batch_started = false;

ChunkedUploader.prototype = {
    _batchStatus: function(data){
        if(data.status=="abort"){
            filecount--;
            jQuery("#ups-fcount").empty().html(filecount);
            if (filecount == 0  && batch_started == true){
                location.reload(); 
            }
        }else{
            completedfiles++;
            jQuery("#ups-proc").empty().html(completedfiles);
            var batch_url = "search.php?search=!list";
            if(data.status == "success"){
                refs += data.ref+":";
            }
        }
        if (completedfiles == filecount && completedfiles != 0){
            if(filesucc > 0){
                bStatMsg += "<p>You can view your uploaded content <a href = '"+batch_url+refs+"'>here</a>.</p>"
            }
            if(fileerr > 0){
                bStatMsg += "It appears that there where some errors while ingesting your files. Please check the status log below for further details";
            }
            //upload is complete
            jQuery("#upload_form").fadeOut("fast");
            jQuery("#err-msg").append("<h2>Batch Upload complete</h2>"+bStatMsg);
        }
    },
    _singleShowMeta: function(data){
        setResourceType(data);
        CentralSpacePost(document.getElementById('mainform'),true,data);
        jQuery(document).ajaxStop(function(){
            if(called == false){
                showMetadataForm(data);
                jQuery(".form-wrap").fadeOut("fast");
            }
        })
    },
    _processComplete: function(data){
        if(uploadType=="batch"){
          data = JSON.parse(data);
        }
        if(data.status == "success"){
            filesucc++;
            jQuery("#ups-scc span").empty().html(filesucc).css({"color":"#0a9775","font-weight":"bold"});
            jQuery("#"+this.identifier).append(" - Successfully Uploaded").css("color","#0a9775");
            jQuery("#"+this.identifier+" .loadingbar span").css("background","#0a9775");
            jQuery("#"+this.identifier+" .loadingbar").removeClass("meter");
            if(uploadType == "batch"){
                this._batchStatus(data);
            }else{
                this._singleShowMeta(data);
            }
        }
        else{
            this.error();
        }
        jQuery("#"+this.identifier+" .prc-stat").fadeOut("slow");
    },
    _processFile:function(){
        if(uploadType == "batch"){
            var url = "/plugins/mia_upload/pages/upload_batch.php";
        }else if(uploadType== "single"){
            var url = "/plugins/mia_upload/pages/upload2.php";
        }
        jQuery("#"+this.identifier+" .loadingbar").addClass("meter");
        jQuery("#"+this.identifier+" .abort").hide("fast");
        jQuery("#"+this.identifier+" .pb").hide("fast");
        var fd = new FormData();
        formdata = JSON.stringify(jQuery("#mainform :input[value!=''][value!=' , ']").serializeArray());
        filedata = JSON.stringify(this.file.name);
        fd.append('file',filedata);
        if(uploadType == "batch"){
          fd.append('form',formdata);
        }
        this.callajax(url,fd);
    },

    // Event Handlers ____________________________________________________

    _updateProgress: function(){
        var percentage =  Math.ceil(this.range_start / this.file_size * 100); 
        status = "Loaded:"+this.range_start.formatBytes()+" of "+this.file_size.formatBytes()+" Total:"+percentage + "%";
        jQuery("#"+this.identifier+" .statusbar").html(status);
        jQuery("#"+this.identifier+" .loadingbar span").animate({
            'width':percentage+"%",
        },100);
    },
    _updateProcessMessage(id){
        var prc_message = [
            "Still Processing... Please Wait",
            "It may take some time... just be patient",
            "Dont give up now. We're almost there!",
            "We're In the process of processing this process...",
        ];
        smg = -1;
        showMsg();
        function showMsg(){
            setTimeout(function(){
                smg++;
                jQuery("#"+id).find(".statusbar span").empty().html(prc_message[smg]);
                if(smg < (prc_message.length-1)){
                    showMsg();
                }
            },15000);
        }
    },
    _onUploadComplete: function(){
       console.log(this.file.name+ "Successfully Uploaded");
       status = "Loaded:"+this.file_size.formatBytes()+" of "+this.file_size.formatBytes()+" Total: 100% <br/><span class='prc-stat'>Processing... Please Wait - this may take a minute</span>";
        jQuery("#"+this.identifier+" .statusbar").html(status);
        jQuery("#"+this.identifier+" .loadingbar span").animate({
            'width':"100%",
        },100);
        this._processFile();
        this._updateProcessMessage(this.identifier);
    },
    _onChunkComplete: function() {
        if (this.range_end === this.file_size) {
            this._updateProgress();
            this._onUploadComplete();
            return;
        }
        var self = this,
            chunk;
        // Update our ranges
        this.range_start = this.range_end;
        this.range_end = this.range_start + this.chunk_size;
        this._updateProgress();
        // Continue as long as we aren't paused
        if (!this.is_paused) {
            this._upload();
        }
    },

    // Internal Methods __________________________________________________

    _upload: function() {

        var self = this,
            chunk;
        // Slight timeout needed here (File read / AJAX readystate conflict?)
        setTimeout(function() {
            // Prevent range overflow
            if (self.range_end > self.file_size) {
                self.range_end = self.file_size;
            }

            chunk = self.file[self.slice_method](self.range_start, self.range_end);
            jQuery.ajax(self.options.url, {
                data: chunk,
                type: 'PUT',
                cache:false,
                processData: false,
                contentType: false,
                context: self,
                async:   true,
                contentType: 'application/octet-stream',
                headers: (self.range_start !== 0) ? {
                    'Content-Range': ('bytes ' + self.range_start + '-' + self.range_end + '/' + self.file_size),
                    'filename':self.file.name,
                } : {'filename':self.file.name, 'Content-Range':'initial chunk'},
                success: self._onChunkComplete,
                statusCode: {
                    404: function(){
                        alert('Page Not Found');
                    }
                }
            });
        }, 50);
    },
    // Public Methods ____________________________________________________
    error:function(msg,id){
        jQuery("#"+id).append(msg);
        jQuery("#"+id+" .loadingBar").css({"background":"#CC3333","font-weight":"bold"});
        jQuery("#"+id+" .abort").remove();
        jQuery("#"+id+" button").remove();
        fileerr++;
        jQuery("#ups-err span").empty().html(fileerr).css("color","#A8352F");
        this._batchStatus(data = {"status":"error"});
    },
    start:function(){
        jQuery.ajax({
            url: "<?php echo $baseurl?>/plugins/mia_upload/pages/check_existing.php?name="+this.file.name,
            cache:false,
            type: "POST",
            context: this,
            contentType: "json",
            success:function(data){
                data=JSON.parse(data);
                if(data.success==false){
                    if(uploadType=="single"){
			jQuery("#"+this.identifier).remove();
			filesinbatch=new Array();;
                        filecount = 0;
                        uploaders=[];
			jQuery("#err-msg").html("").append(data.msg).effect("shake");
                        data = "";
			file = "";
                        setTimeout(function(){
                            jQuery("#err-msg").html("");
                        },3000);
                    }else{
                        this.error(data.msg,this.identifier);
                    }
                }else if(data.success==true){
                    batch_started = true;
                    jQuery("#upload_form").fadeOut("fast");
                    jQuery("#upload-navigation").fadeOut("slow");
                    jQuery("#"+this.identifier+" .pb").prop("disabled","");
                    this._upload();
                }
            },
        });
    },
    kill:function(){this._upload={};},
    pause:function(){this.is_paused = true;},
    resume:function(){this.is_paused = false;this._upload();},
    callajax:function(url,fd){
        jQuery.ajax({
            url: url,
            processData:false,
            data: fd,
            contentType: false,
            type: "POST",
            cache:false,
            context: this,
            success: this._processComplete,
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                this.error(err.Message);
            },
            statusCode: {
                500: function(){
                    alert('internal server malfunction');
                }
            }
        })
    }
};//end Class

//jQuery("document").ready(function(){
    //set initial attributes on load
    jQuery("#mainform").css("display","none");
    jQuery("#dragandrophandler").css("display","none");
    jQuery("#submit_btn").css("display","none");
    jQuery("#submit_btn").prop("disabled","disabled");
    uploadType="single";

    //set global vars
    var clearer = false,
        display_ray = [],
        upload_form = jQuery('#upload_form'),
        file_input = jQuery('#file_input'),
        file_list = jQuery('#file_list'),
        submit_btn = jQuery('#submit_btn'),
        uploaders = [],
        filesadded = false;

    //instantiate object to convert file size bytes
    Number.prototype.formatBytes = function() {
        var units = ['B', 'KB', 'MB', 'GB', 'TB'],
            bytes = this,
            i;
        for (i = 0; bytes >= 1024 && i < 4; i++) {
            bytes /= 1024;
        }
        return bytes.toFixed(2) + units[i];
    }

    //make sure returned allowed extentions are properly formatted
    jQuery.each(display_ext, function (k,v){
        display_ray.push("<h3>"+ k +"</h3><p>"+ v.replace(/,/g,',  ') +"</p>");
    });


    //---------------------------+
    //    Nav Click Functions    |
    //---------------------------+

    //single upload
    jQuery("body").on("click","#single-upload",function(e){
        e.preventDefault();
        if(filecount > 0){
            var r = confirm("Are you sure? This will clear any files currently in the batch.");
            if(r == false){
                clearer = true;
            }
        }
        if(clearer == false){
            jQuery("#upload-stat").fadeOut("fast");
            jQuery("#upload_form label").empty().html("Select File");
            jQuery("#file_input").removeAttr("multiple");
            jQuery("#file_list").hide();
            uploads = {};
            filesinbatch=[];
            filecount = 0;
            jQuery("#file_list").html("");
            jQuery("#dragandrophandler").fadeOut("fast");
            jQuery("#submit_btn").fadeOut("fast");
            jQuery('#upload-navigation li').removeClass('active');
            jQuery(this).parent('li').addClass('active');
            jQuery('#mainform').css('display','none');
            uploadType = "single";
        }else{
            clearer = false;
        }
    });

    //batch upload
    jQuery("body").on("click","#batch-upload",function(e){
        e.preventDefault();
        jQuery("#upload_form label").empty().html("Select Files");
        jQuery("#file_input").prop("multiple","multiple");
        jQuery("#dragandrophandler").fadeIn("fast");
        jQuery("#submit_btn").fadeIn("fast");
        jQuery('#upload-navigation li').removeClass('active');
        jQuery('.QuestionSubmit, #question_resourcetype, #meta-message, #resource_title').css('display','none');
        jQuery(this).parent('li').addClass('active');
        uploadType = "batch";
        if(filecount!=0){
            jQuery('#mainform').css({'display':'block'})
        }
    });

    //allowed extensions help
    jQuery("body").on("click","#info-formats", function(e){
        e.preventDefault();
        jQuery(".overlay").remove();
        jQuery('body').append('<div class="overlay" style="display:none"><div class="overlay-wrap"><a style="color:#F00">[x] - close</a>'+display_ray.join('')+'</div><div>');
        jQuery(".overlay").show( "fold", 1000 );
        jQuery("body").on("click",".overlay", function(e){ 
            jQuery(".overlay, .ui-effects-wrapper").fadeOut();
        })
    });

    upload_form.on('submit', onFormSubmit);

    //------------------
    //  Adding Files
    //------------------

    function onFilesSelected(files,obj) {
        var file,
        list_item,
        uploader;

        for (var i = 0; i < files.length; i++) {
            file = files[i];
            var extension = file.name.replace(/^.*\./, "").toLowerCase();

            //if the file is an allowed extension and is not already in the batch
            if(jQuery.inArray(extension, allowedext)!==-1 && jQuery.inArray(file.name, filesinbatch) ==-1){
                if(identifier == 0 && filesadded == false){
                    identifier = 0;
                }else{
                    identifier++;
                }
                filesadded = true;
                filecount++;
                jQuery("#ups-fcount").empty().html(filecount);
                uploader = new ChunkedUploader(file,'',identifier);
                uploaders.push(uploader);
                list_item = jQuery('<li id="'+identifier+'"><h4>' + file.name + '(' + file.size.formatBytes() + ') </h4><button class="pb" disabled="disabled">Pause</button><a class="abort">Delete</a><div class="statusbar"></div><div class="loadingbar"><span><span></span></span></div></li>').data('uploader', uploader);
                file_list.append(list_item);
                filesinbatch.push(file.name);
                if(uploadType == "single"){
                    jQuery.each(uploaders, function(i, uploader) {
                        uploader.start();
                    });
                }else if(uploadType=="batch"){
                    jQuery("#mainform").show().css({"float":"none","margin":"auto"});
                    jQuery("#upload-stat").fadeIn("slow");
                }
            //else if the file is an allowed extension but alread exists in batch
            }else if(jQuery.inArray(file.name, filesinbatch) !=-1){
                jQuery('#upload_form').effect("shake",{times:1},100);
                jQuery('#err-msg').after().append("<div class='shake'>" + files[i].name + " already exists in batch.</div>");
                setTimeout(function(){
                    jQuery('.shake').fadeOut(1000, function(){
                        jQuery(this).remove();
                    });
                },3500);

            //else the file is not an allowed extension
            }else{
                jQuery('#upload_form').effect("shake",{times:1},100);
                jQuery('#err-msg').after().append("<div class='shake'>REJECTED " + file.name + ": " + extension + " is not an allowed extension</div>");
                setTimeout(function(){
                    jQuery('.shake').fadeOut(1000, function(){
                        jQuery(this).remove();
                    });
                },3500);
            }
        }
        file_list.show();
        submit_btn.attr('disabled', false);
        file_list.find('.abort').off('click');
        file_list.find('.abort').on('click', removeFile);
        file_list.find('button').off('click');
        file_list.find('button').on('click', onPauseClick);
    }//end onFilesSelected

    //Loop through the files and submit the form
    function onFormSubmit(e) {
        if(filecount != 0){
            jQuery.each(uploaders, function(i, uploader) {
            uploader.start();
        });
        jQuery("#mainform").fadeOut("fast");
        jQuery("#upload_form").fadeOut("fast");
        jQuery("#upload-navigation").fadeOut("slow");
        file_list.find('button').prop("disabled","");
        // Prevent default form submission
        }else{
            alert("Please Add A File.");
        }
        e.preventDefault();
    }

    //Remove files from the display list and the upload que
    function removeFile(){
        var btn = jQuery(this).parent("li"),
        uploader = btn.data('uploader');
        uploader._batchStatus(data={"status":"abort"});
        //find and remove the instance from the que
        uind = uploaders.indexOf(uploader);
        uploaders.splice(uind, 1);
        //find and remove te instance from the batch names
        fibidx = filesinbatch.indexOf(uploader.file.name);
        filesinbatch.splice(fibidx,1);
        //finally remove the file from the display list
        btn.remove();
        uploader.kill();
        if (batch_started==true){
        jQuery.ajax({
            url:"<?php echo $baseurl?>/plugins/mia_upload/pages/abort_upload.php?filename="+uploader.file.name,
            type:"POST",
            cache: false,
            dataType: "JSON",
            processData: false,
            success: function(){console.log(data)},
            error: function(){console.log(data)},
        });
        };
        //if this is the last file hide the form and prevent submit
        if(filecount <= 0){
            jQuery("#submit_btn").prop("disabled","disabled");
            jQuery("#mainform").fadeOut("fast");
        }
    }

    function onPauseClick(e) {
        var btn = jQuery(this),
            uploader = btn.parent('li').data('uploader');

        if (btn.hasClass('paused')) {
            btn.removeClass('paused').text('Pause');
            uploader.resume();
        }
        else {
            btn.addClass('paused').text('Resume');
            uploader.pause();
        }
    }

    //-------------------------------------+
    //      Drag and Drop Functions        |
    //-------------------------------------+
    var obj = jQuery("#dragandrophandler");
    obj.on('dragenter', function (e){
        e.stopPropagation();
        e.preventDefault();
        jQuery(this).css('border', '2px solid #0B85A1');
    });
    obj.on('dragover', function (e){
        e.stopPropagation();
        e.preventDefault();
    });
    obj.on('drop', function (e){
        jQuery(this).css('border', '2px dotted #444444');
        jQuery(this).effect('highlight');
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;
        onFilesSelected(files,obj);
    });
    jQuery(document).on('dragenter', function (e){
        e.stopPropagation();
        e.preventDefault();
    });

    //stop default behavior of opening files in browser
    jQuery(document).on('dragover', function (e){
        e.stopPropagation();
        e.preventDefault();
        obj.css('border', '2px dotted #0B85A1');
    });
    jQuery(document).on('drop', function (e){
        e.stopPropagation();
        e.preventDefault();
    });
    jQuery("body").on('change','#file_input',function(e){
    var files = jQuery('#file_input').prop("files");
        onFilesSelected(files,obj);
        });
    //});

//------------------------------
//    AJAX Callback Functions
//------------------------------
//for single upload save
var keepgoing = true;
var handlesuccessLog = function(data,textStatus){
    if(data !=""){
        var pData=JSON.parse(data);
        jQuery('#upload-log-msg').empty().html('<li>'+pData.percent+'%<br/>Status:'+pData.message+'</li>');
        jQuery( "#progressBar" ).progressbar({value:parseInt(pData.percent)});
    };
    if(keepgoing){
        setTimeout(checkForMessages,500);
    }else{
        jQuery('#progressBar').animate({'opacity':'0'},2000);
    };
};
var xhrUpload = function(){
    var xhr = new window.XMLHttpRequest();
    xhr.upload.addEventListener("progress", function(evt){
        if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            jQuery('#progressBar').css({'display':'block'});
            jQuery( "#progressBar" ).progressbar({value: percentComplete*100});
        };
    }, false);
    return xhr;
};
//save complete
var handleSuccessSave = function(datasave, textStatus, jqXHR){
  if(typeof datasave.error === 'undefined'){
    jQuery('#LoadingBox').css({'display':'none'});
    jQuery('#progressBar').fadeOut("slow");
    keepgoing = false;
    jQuery('#upload-log').append('<h2 style="color:#009933;">'+datasave.textStatus+'</h2>');
    jQuery('#success-actions').css('display','block').html('<a href="<?php echo $baseurl;?>/pages/edit.php?ref=<?php echo $_GET['ref']?>">Load Another Resource</a><a href="<?php echo $baseurl;?>/pages/view.php?ref='+datasave.ref+'">View Resource</a>').animate({'opacity':'1'},2000);
  }else{
    keepgoing = false; alert(datasave.textStatus);
    jQuery('#LoadingBox').css({'display':'none'});
    jQuery('#upload-log').css('display','none');
  };
};
var handleErr = function(data){
    console.log(data);
};
var handleerrorLog = function(data,textStatus){alert('error');};
var handleTMSSuccess = function(data){
    console.log(data);
};

var beforeSendSave = function (){ jQuery('#progressBar').css({'display':'block'});setTimeout(checkForMessages,1000);};

//------------------------
//    Main AJAX Function
//------------------------

var sendajax = function(XHR, url, datas, type, Datatype, processData, cache, contentType, Err, Succ, beforeSend){
console.log(XHR+" - "+url+" - "+datas+" - "+type+" - "+Datatype+" - "+processData+" - "+cache+" - "+contentType+" - "+Err+" - "+Succ+" - "+beforeSend);
    jQuery.ajax({
        url: url,
        data: datas,
        type: type,
        xhr: XHR,
        cache: cache,
        dataType:Datatype,
        processData: processData,
        contentType: contentType,
        beforeSend: beforeSend,
        success: Succ,
        error: Err,
        statusCode: {
            404: function(){
                alert('Page Not Found');
            }
        }
    });
}
//--------------------
//    AJAX Calls
//--------------------
var checkForMessages = function(data){
    keepgoing=true;
    sendajax(xhrUpload, "ajax/upload_report.php?log="+filelog, "data", "GET", "json", false, false, true, handleerrorLog, handlesuccessLog, "");
};

//keep this save function
submitform = (function(e){
    var executed = false;
    return function (e) {
    if (!executed) {
      executed = true;
      jQuery('#LoadingBox').css('display','block');
      jQuery('#upload-log').css('display','block');
      var datasave = jQuery("#mainform :input[value!=''][value!=' , ']").serialize();
      e.stopPropagation();
      e.preventDefault();
      sendajax(xhrUpload, "/plugins/mia_upload/pages/save.php?log="+filelog, datasave, "POST", "json", true, false, "application/x-www-form-urlencoded; charset=UTF-8", handleErr, handleSuccessSave, beforeSendSave);
    }
  };
})();


//------------------------------------------------
//    Form Response Function Metadata Matching
//------------------------------------------------

function parseData(data){
    parsedData = '';
    //get all fields
    if(data.exif.length > 1){
        for(i=0; i<data.exif.length; i++){
            whoop=data.exif[i];
            parsedData += "<li><b>"+whoop.field.replace(/([a-z])([A-Z])/g, '$1 $2')+":</b> <em>"+ whoop.value + "</em></li>"; 
        };
    };
    return  parsedData;
};
function matchFormValues(data){
//    if(matchform==true){
//      matchform=false;
      var list = [];
      var found=[];
      var parsedData ='';
      if(data.tms_error){
        jQuery('#mainform').append("<input type='hidden' name='tms_error' value='"+data.tms_error+"'/>");
      }
      //create a list of all exif ids to compare
      for(i=0; i<data.exif.length; i++){
        parsedData=data.exif[i];
        list.push(""+parsedData.id+"");
      };
      //make sure that the list is in an array format and map out all ids
      var trimmedlist = jQuery.makeArray(list);
      var IDs = jQuery("#mainform :input[id]").map(function() { return this.id; }).get();
      //loop through all of the form ids
      for (i=0;i<IDs.length;i++){
        // remove text from ids to only compare intiger values
        var idonly = IDs[i].slice(6);
        // see if form id exists in exif list
        var matchedid = jQuery.inArray(idonly,trimmedlist);
        // if the id does exist
        if(matchedid>=0){
            for(v = 0; v < data.exif.length; v++){
                pD=data.exif[v];
                if(pD.id == idonly){
                    if(jQuery('#'+IDs[i]).is('input') && jQuery('#'+IDs[i]).prop('type')!="hidden" || jQuery('#'+IDs[i]).is('textarea') ){
                        jQuery('#'+IDs[i]).empty().attr('value',pD.value);
                    }
                    else if(jQuery('#'+IDs[i]).is('select')){
                        jQuery('#'+IDs[i]+' option[value="'+pD.value+'"]').prop('selected',true);
                    }
                    else if(jQuery('#'+IDs[i]).prop('type')=="hidden"){
                        jQuery('#'+IDs[i]).empty().attr('value',pD.value);
                        jQuery('#'+IDs[i]+'_selector').empty().attr('value',pD.value);
                    };
                };
            };
            jQuery('#'+IDs[i]).addClass('matched-resource');
            jQuery('#'+IDs[i]).parents('.Question').css({
             //   'display':'none',
                'background':'#F5F5F5',
            });
            found.push(idonly);
        }else{
            list2 = [];
            jQuery.grep(trimmedlist, function(value) {
            if (jQuery.inArray(value, found) == -1) list2.push(parseInt(value));
            });
        };


    };//close for loop

    //--------------------
    //Match TMS Values
    //--------------------

    var refs = new Array();
    var pTms = '';
    var iTms = '';
    if(data.tms){
        for(tm=0; tm<data.tms.length; tm++){
            var pd=(data.tms[tm]);
            pTms += "<li><b>" + pd.tmsfield+"</b><em>" + pd.tmsvalue + "</em></li>";
            iTms += "<input type='hidden' id='field_"+pd.ref+"' name='field_"+pd.ref+"' value='"+pd.tmsvalue+"'/>";
        }

        //get only reference IDS for form field matching
        for(i=0; i<jpray.length; i++){
            refs.push(jpray[i]['ref']);
        };
        jQuery('#meta-message').append('<div><h3> Here is what we found in TMS : </h3><br/>'+pTms+'</div>');
        jQuery('#mainform').append(iTms);
    };
        for(z=0; z<data.exif.length; z++){
            cleandata=data.exif[z];
            dataId = cleandata.id;
            var matchedrefs = jQuery.inArray(dataId,refs);
            arraycheck = jQuery.inArray(dataId,list2);
            if(arraycheck>=0){
                jQuery('#mainform').append('<input type="hidden" name="field_'+dataId+'"  id="field_'+dataId+'" value="'+cleandata.value+'"/>');
            };
        };
};
//extract the filename from the extension and set the variable so we can pass the value to ajax to setup our file log creation
function setupFileLog(data){
        rsType=data.rs;
        filelog = rsType.fileName.replace(/\.[^/.]+$/, "");
};
 setResourceType = function(data){
        rsType=data.rs;
        jQuery('#rsType').empty().attr('value',rsType.resourceType);
        //jQuery('#rsTypelabel').empty().html('Resource Type: ' + rsType.resourceName);
        jQuery('#rsTemp').empty().attr('value',rsType.tmpFile);
        jQuery('.rsFileName').empty().attr('value',rsType.fileName);
        jQuery('#resource_title').html("Title " + rsType.fileName);
        jQuery("#resourcetype option").each(function(k,v){
            if(rsType.resourceType == v.value){
              v.selected = "selected";
            }
        });
};

//Next button for single upload -> required fields
reqnextbtn = function(){
   //check required
   var errors="";
   jQuery.each(reqfields, function(k,v){
        var value = jQuery("#mainform").find("#field_"+v.ref).val();
        if(value == ""){
            errors+="- "+v.title+" is a required field. \n";
        }
    });
   //if no errors
   if(errors.length===0){
     jQuery("#mainform").find(".Question").show("slow");
     jQuery(".QuestionSubmit").css("display","block");
     jQuery("#reqnext").css("display","none");
     jQuery(".Tab").show("slow");
     jQuery(".BasicsBox h1:first").empty().html("<span style='color:#009933;'>Awesome!</span> Now here is the rest.");
     jQuery(".BasicsBox h2:first").empty().html("Please fill out any additional information that should be added.");
     jQuery(".BasicsBox p:first").empty().html("When you're ready click <em>\"Save Resource\"</em> to finish uploading and save your file,");
   }else{
       alert(errors+"\n \n Please complete all all required fields.");
   }
}

showMetadataForm = function(data){
            called = true;
            setResourceType(data);
            jQuery("#CentralSpace").append("<a id='single_upload_cancel'>Cancel Upload</a>");
            jQuery("#mainform").show("slow").css("float","left");
            jQuery(".editsave").val("Save Resource");
            jQuery(".BasicsBox h1:first").empty().html('Almost ready, but first things first!');
            jQuery(".BasicsBox h2:first").empty().html('Lets get the required stuff out of the way.');
            jQuery(".BasicsBox p:first").empty().html('Metadata has been matched against this form, make sure to click through each of the tabs and complete all required fields. When you\'r ready click "next" to dipslay all other fields.');
            jQuery('#meta-message').fadeIn('slow');
            jQuery("#question_resourcetype input[type='select']").remove();
            jQuery("#question_resourcetype").append(''+
                '<h2 id="resource_title">Hey</h2>'+
                //'<label id="rsTypelabel" for="resourcetype"></label>'+
		'<input id="rsType" type="hidden" name="resource_type" value="">'+
                '<input class="rsFileName" type="hidden" name="file_name" value="">'+
		'<input class="rsFileName" type="hidden" name="field_8" value="">'
            );
            setResourceType(data);
            //display metadata that was found and matched
            setupFileLog(data);
            var imgsrc = rsType.tmpFile;
            jQuery('#meta-message').empty().html('<img style="max-width:200px;" src="'+imgsrc+'"/><h3> Here is what we found in your metadata:</h3> <ul>'+parseData(data)+'</ul>');        
            matchFormValues(data);
            showRequired(data);

}
var reqfields;
showRequired = function(data){
    reqfields = data.required;
    //reqfields=JSON.parse(data.required);
    jQuery("#mainform").find(".Question").css("display","none");
    jQuery(".QuestionSubmit").css("display","none");
    jQuery("#question_resourcetype").css("display","block");
    jQuery.each(reqfields, function(k,v){
    jQuery("label[for='field_"+v.ref+"']").parents(".Question").css("display","block").addClass("required");
    jQuery("#mainform").find("#field_"+v.ref).parents(".Question").css("display","block").addClass("required");
    });
    jQuery(".TabbedPanel .TabPanelInner").each(function(k,v){
        if(jQuery(this).children(".required").length == 0){
             var parentid=jQuery(this).parent().prop("id");
             jQuery("#tabswitch"+parentid.substring(3)).css("display","none");
        }
    })
    jQuery("#mainform").append('<a id="reqnext" onclick="reqnextbtn();">Next<a/>');
}
jQuery("body").on("click",".editsave", function(e){
    e.preventDefault();
    submitform(e);
});

jQuery("body").on("click","#single_upload_cancel", function(){
  location.reload();
});
</script>
<?php
}
}
?>
<?php
function HookMia_uploadEditReplaceedittype(){
// Remove unwanted sections of the form
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
  jQuery("#resourcetype").attr("disabled","disabled");
  jQuery("#UploadOptionsSection").prev("h1").remove();
  jQuery("#UploadOptionsSection, #question_copyfrom").remove();
});
</script>
<?php
}
?>
