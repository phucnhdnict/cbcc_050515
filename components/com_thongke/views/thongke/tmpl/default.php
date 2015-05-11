<?php
/**
 * Author: Phucnh
 * Date created: Mar 19, 2015
 * Company: DNICT
 */ 
defined( '_JEXEC' ) or die( 'Truy cập không hợp lệ' );
$user   = JFactory::getUser();
?>
<style>
#main-content-tree{
 height: 280px;
 overflow: auto;
}
table.dataTable thead .sorting, 
table.dataTable thead .sorting_asc, 
table.dataTable thead .sorting_desc {
    background : none;
}
</style>
<div>
	<h4 class="header lighter blue">
		Danh sách công chức <i class="icon-double-angle-right"></i><small><span id="tendonvi"></span></small>
	</h4>
</div>
<div id="tab_danhsach" role="tabpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<?php if (core::_checkPerAction($user->id, 'com_thongke', 'thongke', 'tab_cctsbnn','site')) { ?>
        	<li> 
            	<a data-toggle="tab" href="#congchuc_tapsu_bonhiemngach">
            				Công chức Tập sự được bổ nhiệm ngạch biên chế<span id="sl_cctsbnn"></span>
            	</a>
        	</li>
        	<?php }?>
        	<?php if (core::_checkPerAction($user->id, 'com_thongke', 'thongke', 'tab_ldhdccvc','site')) { ?>
        	<li> 
            	<a data-toggle="tab" href="#ldhd_ccvc">
            				LĐHĐ chuyển ngạch biên chế<span id="sl_ldhd_ccvc"></span>
            	</a>
        	</li>
        	<?php }?>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">
			<?php if (core::_checkPerAction($user->id, 'com_thongke', 'thongke', 'tab_cctsbnn','site')) { ?>
			<div class="tab-pane" id="congchuc_tapsu_bonhiemngach"></div>
			<?php }?>
			<?php if (core::_checkPerAction($user->id, 'com_thongke', 'thongke', 'tab_ldhdccvc','site')) { ?>
			<div class="tab-pane"  id="ldhd_ccvc"></div>
			<?php }?>
		</div>
</div>
<div id="div_xemchitiet"></div>
<script type="text/javascript">
var donvi_id;
var type;
var date = function(dateObject) {
    var d = new Date(dateObject);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var date = day + "/" + month + "/" + year;

    return date;
};
var year = function(dateObject) {
    var d = new Date(dateObject);
    var year = d.getFullYear();
    return year;
};
var refresh = function(){
	// nhớ bổ sung các check tab tồn tại thì mới ajax
	if (jQuery('#congchuc_tapsu_bonhiemngach').length>0){
		jQuery.blockUI();
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo JUri::base(true)?>/index.php?option=com_thongke&view=thongke&format=raw&task=dsach_cctsbnn',
			data:{donvi_id: donvi_id},
			success: function(data){
				var xhtml='<table role="grid" id="tbl_cctsbnn" class="table table-striped table-bordered table-hover dataTable"></table>';
				jQuery('#congchuc_tapsu_bonhiemngach').html(xhtml);
				jQuery('#sl_cctsbnn').html(' ('+data.length+')');
				var table = jQuery('#tbl_cctsbnn').DataTable({
				"data": data,
				"oTableTools": {
					"sSwfPath": "/media/cbcc/js/dataTables-1.10.0/swf/copy_csv_xls_pdf.swf",		
					"aButtons": [
									{
										"sExtends": "xls",
										"sButtonText": "Excel",
										"mColumns": [ 0,1,2,3,4,5,6,7,8 ],
										"sFileName": "Congchuctapsubonhiemngach.xls",
										"oSelectorOpts": { filter: 'applied'},
									},
									{ 	"sExtends":"print",
										"bShowAll": false
									},
										
								]
				},
				"deferRender":true,
		        "columns": [
		                    {"title":"Tên","width": "20%", "data": "e_name" , "render": function (data, type, row, meta) {
		                        return '<a style="cursor:pointer;" class="btn_edit_hoso" idhoso="'+row.id+'">'+data+'</a>';
		                    }},
		                    {"title":"Ngày sinh", "data": "ngaysinh", "render": function(data, type, row, meta){
			                    if (row.danhdaunamsinh == 1) return year(data);
			                    else return date(data);
			                 }},
		                    // chỉnh == null => nhân viên
		                    {"title":"Chức vụ", "data": "congtac_chucvu" },
		                    {"title":"Phòng công tác", "data": "congtac_phong" },			                   
		                    {"title":"Ngày tập sự", "data": "ngaytapsu" },
		                    {"title":"Ngày bổ nhiệm ngạch", "data": "ngaybonhiemngach" },
		                    {"title":"Tên ngạch", "data": "luong_tenngach" },
		                    {"title":"Bậc", "data": "luong_bac" },
		                    {"title":"Hệ số", "data": "luong_heso" },
		                ],
				"bSort": true,
			   	"columnDefs": [
						{
							"targets": [0],
							"orderable": false
					}],
				 "stateSave": true,
				});
				jQuery.unblockUI();
			}
		});
	}
	if (jQuery('#ldhd_ccvc').length>0){
		jQuery.blockUI();
		jQuery.ajax({
			type: 'POST',
			url: '<?php echo JUri::base(true)?>/index.php?option=com_thongke&view=thongke&format=raw&task=dsach_ldhd_ccvc',
			data:{donvi_id: donvi_id},
			success: function(data){
				var xhtml='<table role="grid" id="tbl_ldhd_ccvc" class="table table-striped table-bordered table-hover dataTable"></table>';
				jQuery('#ldhd_ccvc').html(xhtml);
				jQuery('#sl_ldhd_ccvc').html(' ('+data.length+')');
				var table = jQuery('#tbl_ldhd_ccvc').DataTable({
				"data": data,
				"oTableTools": {
					"sSwfPath": "/media/cbcc/js/dataTables-1.10.0/swf/copy_csv_xls_pdf.swf",		
					"aButtons": [
									{
										"sExtends": "xls",
										"sButtonText": "Excel",
										"mColumns": [ 0,1,2,3,4,5,6,7,8 ],
										"sFileName": "Laodonghopdong.xls",
										"oSelectorOpts": { filter: 'applied'},
									},
									{ 	"sExtends":"print",
										"bShowAll": false
									},
										
								]
				},
				"deferRender":true,
		        "columns": [
		                    {"title":"Tên","width": "20%", "data": "e_name" , "render": function (data, type, row, meta) {
		                        return '<a style="cursor:pointer;" class="btn_edit_hoso" idhoso="'+row.id+'">'+data+'</a>';
		                    }},
		                    {"title":"Ngày sinh", "data": "ngaysinh", "render": function(data, type, row, meta){
			                    if (row.danhdaunamsinh == 1) return year(data);
			                    else return date(data);
			                 }},
		                    // chỉnh == null => nhân viên
		                    {"title":"Chức vụ", "data": "congtac_chucvu" },
		                    {"title":"Phòng công tác", "data": "congtac_phong" },			                   
		                    {"title":"Ngày tập sự", "data": "ngaytapsu" },
		                    {"title":"Ngày bổ nhiệm ngạch", "data": "ngaybonhiemngach" },
		                    {"title":"Tên ngạch", "data": "luong_tenngach" },
		                    {"title":"Bậc", "data": "luong_bac" },
		                    {"title":"Hệ số", "data": "luong_heso" },
		                ],
				"bSort": true,
			   	"columnDefs": [
						{
							"targets": [0],
							"orderable": false
					}],
				 "stateSave": true,
				});
				jQuery.unblockUI();
			}
		});
	}
}
jQuery(document).ready(function($){
	createTreeviewInMenuBar('Cây đơn vị');
	$('body').delegate('.btn_edit_hoso', 'click', function(){
		idhoso = $(this).attr('idhoso');
		$.blockUI();
		$.ajax({
			type: 'GET',
			url: '<?php echo JURI::base(true);?>/index.php?option=com_hoso&view=hoso&format=raw&task=hoso_detail',
			data: {idHoso : idhoso},
			success: function(data){
				$('#tab_danhsach').hide();
				$('#div_xemchitiet').html(data);
				$.unblockUI();
			}
		});
	});
	$('body').delegate('#btn_back_detail', 'click', function(){
		$('#div_xemchitiet').html('');
		$('#tab_danhsach').show();
	});
	$("#main-content-tree").jstree({
		   "plugins" : ["themes","json_data","types","ui","cookies"],
		   "json_data" : {
		  "data" : [{ "attr" : { "id" : "<?php echo $this->root_info['root_id'];?>"},
		     "state" : "closed",
		     "data" : {
		       "title" : "<?php echo $this->root_info['root_name'];?>",
		       "attr" : { "href" : "#" }
		      }
		  }],
		  "ajax" : {
		   "url" : "<?php echo JURI::base(true);?>/index.php",
		   "data" : function (n) {
		    return {
		     "option" : "com_thongke",                            
		     "view" : "treeview",
		     "task" : "treeThongke",
		     "format" : "raw",                            
		     "id" : n.attr ? n.attr("id").replace("node_","") : + root_id
		    };
		   }
		  }
		  },
		  "checkbox":{
		   "two_state": true,
//		    real_checkboxes: true,
		   "override_ui": false
		  },
		  "types" : {
		   "valid_children" : [ "root" ],
		   "types" : {
		    "file" : {
		     "icon" : { 
		      "image" : "<?php echo JUri::root(true);?>/media/cbcc/js/jstree/file.png" 
		     }                 
		    },
		    "folder" : {
		     "icon" : { 
		      "image" : "<?php echo JUri::root(true);?>/media/cbcc/js/jstree/folder.png" 
		     }
		    },
		    "default" : {
		     "valid_children" : [ "default" ]
		    }
		   }
		  }  
		 }).bind("select_node.jstree", function (e, data) {
			donvi_id = data.rslt.obj.attr("id").replace("node_","");
			type = data.rslt.obj.attr('rel');
			if(type == "file" || type == "folder" || type== undefined){
				$('#tendonvi').html($('.jstree-clicked').text());
				refresh();
			}else{
				data.inst.toggle_node(data.rslt.obj);
			}
		 });	
});

</script>