(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-da3fd0e4"],{"629f":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"table"},[i("div",{staticClass:"crumbs"},[i("el-breadcrumb",{attrs:{separator:"/"}},[i("el-breadcrumb-item",[i("i",{staticClass:"el-icon-lx-cascades"}),t._v(" Banner管理")])],1)],1),i("div",{staticClass:"container"},[i("div",{staticClass:"handle-box"},[i("el-button",{staticClass:"handle-del mr10",attrs:{type:"danger",icon:"delete"},on:{click:t.delAll}},[t._v("批量删除")]),i("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:t.select_word,callback:function(e){t.select_word=e},expression:"select_word"}}),i("el-button",{attrs:{type:"primary",icon:"search"},on:{click:t.search}},[t._v("搜索")]),i("el-button",{staticStyle:{float:"right"},attrs:{type:"primary"},on:{click:function(e){return t.handleEdit(void 0,void 0,1)}}},[t._v("添加")])],1),i("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:t.data,border:""},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),i("el-table-column",{attrs:{prop:"b_id",label:"ID",width:"70",align:"center"}}),i("el-table-column",{attrs:{prop:"b_title",label:"标题",formatter:t.formatter}}),i("el-table-column",{attrs:{prop:"b_image",label:"图片"},scopedSlots:t._u([{key:"default",fn:function(t){return[i("el-popover",{attrs:{placement:"left",title:"",width:"500",trigger:"hover"}},[i("img",{staticStyle:{"max-width":"100%"},attrs:{src:t.row.b_image}}),i("img",{staticStyle:{"max-width":"130px",height:"auto","max-height":"100px"},attrs:{slot:"reference",src:t.row.b_image,alt:t.row.b_image},slot:"reference"})])]}}])}),i("el-table-column",{attrs:{prop:"b_datetime",label:"更新时间",sortable:"",width:"200"}}),i("el-table-column",{attrs:{label:"操作",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{type:"text",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit(e.$index,e.row,2)}}},[t._v("编辑")]),i("el-button",{staticClass:"red",attrs:{type:"text",icon:"el-icon-delete"},on:{click:function(i){return t.handleDelete(e.$index,e.row)}}},[t._v("删除")])]}}])})],1),i("div",{staticClass:"pagination"},[i("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.sumPage},on:{"current-change":t.handleCurrentChange}})],1)],1),i("el-dialog",{attrs:{title:"编辑",visible:t.editVisible,width:"30%"},on:{"update:visible":function(e){t.editVisible=e}}},[i("el-form",{ref:"form",attrs:{model:t.form,"label-width":"50px"}},[i("el-form-item",{attrs:{label:"标题"}},[i("el-input",{model:{value:t.form.b_title,callback:function(e){t.$set(t.form,"b_title",e)},expression:"form.b_title"}})],1),i("el-form-item",{attrs:{label:"图片"}},[i("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.b_imgid},action:t.uploadUrl(),"on-error":t.uploadError,"on-success":t.handleAvatarSuccess,"before-upload":t.beforeAvatarUpload,"on-progress":t.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[t.form.b_image?i("img",{staticClass:"avatar",attrs:{src:t.form.b_image}}):i("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.editVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.saveEdit}},[t._v("确 定")])],1)],1),i("el-dialog",{attrs:{title:"提示",visible:t.delVisible,width:"300px",center:""},on:{"update:visible":function(e){t.delVisible=e}}},[i("div",{staticClass:"del-dialog-cnt"},[t._v("删除不可恢复，是否确定删除？")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.delVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.deleteRow}},[t._v("确 定")])],1)])],1)},l=[],s={name:"basetable",data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{b_id:"",b_title:"",b_image:"",b_datetime:"",b_imgid:""},idx:-1,dialogVisible:!1,AddOrSave:""}},created:function(){this.getData()},computed:{data:function(){var t=this;return this.tableData.filter(function(e){for(var i=0;i<t.del_list.length;i++)if(e.b_title===t.del_list[i].b_title){!0;break}return e})}},methods:{uploadUrl:function(){var t=this.$api.uploadUrl+"/Images/upload";return t},beforeAvatarUpload:function(t){},uploading:function(t,e,i){},uploadError:function(t){this.$message.error(t.msg)},handleAvatarSuccess:function(t,e){console.log(t),this.form.b_imgid=t.data,this.form.b_image=URL.createObjectURL(e.raw),this.getData(),this.$message.success(t.msg)},handleCurrentChange:function(t){this.cur_page=t,this.getData()},getData:function(){var t=this,e=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("Banner/getBannerList",e,function(e){t.tableData=e.data.list,t.sumPage=10*e.data.sumPage,t.cur_page=e.data.currentPage,console.log(e)},function(e){t.tableData=[],t.$message.error(e.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(t,e){return t.b_title},filterTag:function(t,e){return e.tag===t},handleEdit:function(t,e,i){if(this.AddOrSave=i,1==i&&(this.form={b_id:null,b_title:null,b_image:null,b_datetime:null,b_imgid:null}),void 0!=t&&void 0!=e){this.idx=t;var a=this.tableData[t];this.form={b_id:a.b_id,b_title:a.b_title,b_image:a.b_image,b_datetime:a.b_datetime,b_imgid:a.b_imgid}}this.editVisible=!0,console.log(this.form)},handleDelete:function(t,e){this.idx=t,this.form=e,this.delVisible=!0},delAll:function(){var t=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var e=0;e<t;e++)this.multipleSelection[e].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(t){this.multipleSelection=t},saveEdit:function(){var t=this;this.editVisible=!1;var e=null;e=1==this.AddOrSave?this.$qs.stringify({b_imgid:this.form.b_imgid,b_title:this.form.b_title}):this.$qs.stringify({b_id:this.form.b_id,b_title:this.form.b_title}),this.$api.post("Banner/saveBanner",e,function(e){t.getData(),t.$message.success(e.msg)},function(e){t.$message.error(e.msg)})},deleteRow:function(){var t=this,e=this.$qs.stringify({b_id:this.form.b_id});console.log(this.form),this.$api.post("Banner/deleteBanner",e,function(e){t.getData(),t.$message.success(e.msg+e.data+"条数据")},function(e){t.$message.error(e.msg)}),this.delVisible=!1}}},r=s,n=(i("edf4"),i("17cc")),o=Object(n["a"])(r,a,l,!1,null,"4da019d1",null);e["default"]=o.exports},edf4:function(t,e,i){"use strict";var a=i("fd63"),l=i.n(a);l.a},fd63:function(t,e,i){}}]);