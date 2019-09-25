(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-74353bda"],{"026f":function(e,t,o){"use strict";var r=o("cbc9"),i=o.n(r);i.a},"533f":function(e,t,o){"use strict";o.r(t);var r=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"table"},[o("div",{staticClass:"crumbs"},[o("el-breadcrumb",{attrs:{separator:"/"}},[o("el-breadcrumb-item",[o("i",{staticClass:"el-icon-lx-cascades"}),e._v(" 医生管理")])],1)],1),o("div",{staticClass:"container"},[o("div",{staticClass:"handle-box"},[o("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:e.select_word,callback:function(t){e.select_word=t},expression:"select_word"}}),o("el-button",{attrs:{type:"primary",icon:"search"},on:{click:e.search}},[e._v("搜索")]),o("el-button",{staticStyle:{float:"right"},attrs:{type:"primary"},on:{click:function(t){return e.handleEdit(void 0,void 0,1)}}},[e._v("添加")])],1),o("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:e.data,border:""},on:{"selection-change":e.handleSelectionChange}},[o("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),o("el-table-column",{attrs:{prop:"id",label:"ID",width:"70",align:"center"}}),o("el-table-column",{attrs:{prop:"doctor_name",align:"center",label:"医生姓名"}}),o("el-table-column",{attrs:{prop:"doctor_title",align:"center",width:"300",label:"医生职称"}}),o("el-table-column",{attrs:{prop:"face",width:"180",align:"center",label:"医生头像"},scopedSlots:e._u([{key:"default",fn:function(e){return[o("el-popover",{attrs:{placement:"left",title:"",width:"500",trigger:"hover"}},[o("img",{staticStyle:{"max-width":"100%"},attrs:{src:e.row.face,width:"150"}}),o("img",{staticStyle:{"max-width":"130px",height:"auto","max-height":"100px"},attrs:{slot:"reference",src:e.row.face,alt:e.row.face},slot:"reference"})])]}}])}),o("el-table-column",{attrs:{prop:"card",width:"180",align:"center",label:"医生卡片"},scopedSlots:e._u([{key:"default",fn:function(e){return[o("el-popover",{attrs:{placement:"left",title:"",width:"500",trigger:"hover"}},[o("img",{staticStyle:{"max-width":"100%"},attrs:{src:e.row.card,width:"150"}}),o("img",{staticStyle:{"max-width":"130px",height:"auto","max-height":"100px"},attrs:{slot:"reference",src:e.row.card,alt:e.row.card},slot:"reference"})])]}}])}),o("el-table-column",{attrs:{prop:"browse",align:"center",width:"100",label:"浏览量"}}),o("el-table-column",{attrs:{prop:"praise",align:"center",width:"100",label:"点赞量"}}),o("el-table-column",{attrs:{prop:"sort",label:"排序",width:"130",align:"center"}}),o("el-table-column",{attrs:{prop:"datetime",label:"更新时间",width:"180",align:"center",sortable:""}}),o("el-table-column",{attrs:{label:"操作",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-button",{attrs:{type:"text",icon:"el-icon-edit"},on:{click:function(o){return e.handleEdit(t.$index,t.row,2)}}},[e._v("编辑")]),o("el-button",{staticClass:"red",attrs:{type:"text",icon:"el-icon-delete"},on:{click:function(o){return e.handleDelete(t.$index,t.row)}}},[e._v("删除")])]}}])})],1),o("div",{staticClass:"pagination"},[o("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:e.sumPage},on:{"current-change":e.handleCurrentChange}})],1)],1),o("el-dialog",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],attrs:{title:"编辑",visible:e.editVisible,width:"70%"},on:{"update:visible":function(t){e.editVisible=t}}},[o("el-form",{ref:"form",attrs:{rules:e.rules,model:e.form,"label-width":"100px"}},[o("el-form-item",{attrs:{label:"医生姓名",prop:"doctor_name"}},[o("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"请输入医生姓名"},model:{value:e.form.doctor_name,callback:function(t){e.$set(e.form,"doctor_name",t)},expression:"form.doctor_name"}})],1),o("el-form-item",{attrs:{label:"医生职称",prop:"doctor_title"}},[o("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"请输入医生职称"},model:{value:e.form.doctor_title,callback:function(t){e.$set(e.form,"doctor_title",t)},expression:"form.doctor_title"}})],1),o("el-form-item",{attrs:{label:"从业时长",prop:"year"}},[o("el-input",{staticStyle:{width:"100px","margin-right":"10px"},attrs:{placeholder:"请输入医生从业时长"},model:{value:e.form.year,callback:function(t){e.$set(e.form,"year",t)},expression:"form.year"}}),o("span",[e._v("年")])],1),o("el-form-item",{attrs:{label:"擅长",prop:"goodat"}},[o("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"请输入医生擅长项目"},model:{value:e.form.goodat,callback:function(t){e.$set(e.form,"goodat",t)},expression:"form.goodat"}})],1),o("el-form-item",{attrs:{label:"医生头像"}},[o("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.doctor_face},action:e.uploadUrl(),"on-error":e.uploadError,"on-success":e.handleAvatarSuccess,"before-upload":e.beforeAvatarUpload,"on-progress":e.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[e.form.face?o("img",{staticClass:"avatar",attrs:{src:e.form.face}}):o("i",{staticClass:"el-icon-plus avatar-uploader-icon"})]),o("span",{staticStyle:{color:"red"}},[e._v("建议尺寸200*200")])],1),o("el-form-item",{attrs:{label:"医生卡片"}},[o("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.doctor_card},action:e.uploadUrl(),"on-error":e.uploadError,"on-success":e.handleAvatarSuccess2,"before-upload":e.beforeAvatarUpload,"on-progress":e.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[e.form.card?o("img",{staticClass:"avatar",attrs:{src:e.form.card}}):o("i",{staticClass:"el-icon-plus avatar-uploader-icon"})]),o("span",{staticStyle:{color:"red"}},[e._v("建议尺寸670*350")])],1),o("el-form-item",{attrs:{label:"评分"}},[o("el-rate",{model:{value:e.form.score,callback:function(t){e.$set(e.form,"score",t)},expression:"form.score"}})],1),o("el-form-item",{attrs:{label:"浏览量",prop:"browse"}},[o("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入浏览量"},model:{value:e.form.browse,callback:function(t){e.$set(e.form,"browse",t)},expression:"form.browse"}})],1),o("el-form-item",{attrs:{label:"点赞量",prop:"praise"}},[o("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入点赞量"},model:{value:e.form.praise,callback:function(t){e.$set(e.form,"praise",t)},expression:"form.praise"}})],1),o("el-form-item",{attrs:{label:"接单量",prop:"ordernum"}},[o("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入接单量"},model:{value:e.form.ordernum,callback:function(t){e.$set(e.form,"ordernum",t)},expression:"form.ordernum"}})],1),o("el-form-item",{attrs:{label:"粉丝数量",prop:"follow"}},[o("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入粉丝数量"},model:{value:e.form.follow,callback:function(t){e.$set(e.form,"follow",t)},expression:"form.follow"}})],1),o("el-form-item",{attrs:{label:"排序"}},[o("el-input",{staticStyle:{width:"150px"},model:{value:e.form.sort,callback:function(t){e.$set(e.form,"sort",t)},expression:"form.sort"}}),o("span",{staticStyle:{color:"red"}},[e._v("  注：数值越大展示越靠前，不输入则默认排序")])],1),o("el-form-item",{attrs:{label:"关联日记"}},[o("el-checkbox-group",{model:{value:e.type,callback:function(t){e.type=t},expression:"type"}},e._l(e.goodsList,function(t){return o("el-checkbox",{key:t.id,attrs:{label:t.id}},[e._v(e._s(t.name))])}),1)],1),o("el-form-item",{attrs:{label:"档案"}},[o("quill-editor",{ref:"myTextEditor",attrs:{options:e.editorOption},model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}})],1),o("el-form-item",{attrs:{label:"证书"}},[o("quill-editor",{ref:"myTextEditor",attrs:{options:e.editorOption},model:{value:e.form.cert,callback:function(t){e.$set(e.form,"cert",t)},expression:"form.cert"}})],1),o("el-form-item",{attrs:{label:"项目"}},[o("quill-editor",{ref:"myTextEditor",attrs:{options:e.editorOption},model:{value:e.form.project,callback:function(t){e.$set(e.form,"project",t)},expression:"form.project"}})],1)],1),o("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:function(t){e.editVisible=!1}}},[e._v("取 消")]),o("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.saveEdit("form")}}},[e._v("确 定")])],1)],1),o("el-dialog",{attrs:{title:"提示",visible:e.delVisible,width:"300px",center:""},on:{"update:visible":function(t){e.delVisible=t}}},[o("div",{staticClass:"del-dialog-cnt"},[e._v("删除不可恢复，是否确定删除？")]),o("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:function(t){e.delVisible=!1}}},[e._v("取 消")]),o("el-button",{attrs:{type:"primary"},on:{click:e.deleteRow}},[e._v("确 定")])],1)])],1)},i=[],a=(o("fa26"),o("fb59"),o("1b79"),o("3040"),o("cac2"),o("1e58"),o("b881")),l=o("b049");a["Quill"].register("modules/ImageExtend",l["a"]);var s={name:"basetable",components:{quillEditor:a["quillEditor"]},data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{id:"",doctor_name:"",doctor_title:"",goodat:"",year:"",doctor_face:"",face:"",doctor_card:"",card:"",score:0,browse:"",praise:"",follow:"",ordernum:"",content:"",cert:"",project:"",sort:"",datetime:""},idx:-1,dialogVisible:!1,AddOrSave:"",rules:{doctor_name:[{required:!0,message:"请输入医生姓名",trigger:"blur"}],doctor_title:[{required:!0,message:"请输入医生职称",trigger:"blur"}],year:[{required:!0,message:"请输入医生从业时长",trigger:"blur"}],goodat:[{required:!0,message:"请输入医生擅长项目",trigger:"blur"}],browse:[{required:!0,message:"请输入浏览量",trigger:"blur"}],praise:[{required:!0,message:"请输入点赞量",trigger:"blur"}],follow:[{required:!0,message:"请输入粉丝数量",trigger:"blur"}],ordernum:[{required:!0,message:"请输入接单量",trigger:"blur"}]},dialogImageUrl:"",isShowBigImg:!1,editorOption:{modules:{ImageExtend:{loading:!0,name:"image",action:this.$api.uploadUrl+"/Images/uploadEditorImage",response:function(e){return e.data}},toolbar:{container:l["c"],handlers:{image:function(){l["b"].emit(this.quill.id)}}}}},inputVisible:!1,inputValue:"",loading:!1,goodsList:[],type:[]}},created:function(){this.getData()},computed:{data:function(){var e=this;return this.tableData.filter(function(t){for(var o=0;o<e.del_list.length;o++)if(t.title===e.del_list[o].title){!0;break}return t})}},methods:{onEditorChange:function(e){e.editor;var t=e.html;e.text;this.form.details=t},handleRemove:function(e,t){var o=this,r=null;r=void 0!=e.id?e.id:e.response.data;var i=this.$qs.stringify({imgId:r,id:this.form.id});console.log(i),this.$api.post("ShopIntegralGoods/delImage",i,function(e){var t=o.form.swiperimgTemp;t.forEach(function(e,t,o){e==r&&o.splice(t,1)}),o.$message.success(e.msg)},function(e){var t=o.form.swiperimgTemp;t.forEach(function(e,t,o){e==r&&o.splice(t,1)}),o.$message.error(e.msg)})},handlePictureCardPreview:function(e){this.dialogImageUrl=e.url,this.isShowBigImg=!0},uploadUrl:function(){var e=this.$api.uploadUrl+"/Images/upload";return e},beforeAvatarUpload:function(e){console.log(this.form.pic),console.log(e),this.loading=!0},uploading:function(e,t,o){},uploadError:function(e){this.$message.error(e.msg)},handleAvatarSuccess:function(e,t){this.loading=!1,console.log(e),this.form.doctor_face=e.data,this.form.face=URL.createObjectURL(t.raw),this.$message.success(e.msg)},handleAvatarSuccess2:function(e,t){this.loading=!1,console.log(e),this.form.doctor_card=e.data,this.form.card=URL.createObjectURL(t.raw),this.$message.success(e.msg)},handleCurrentChange:function(e){this.cur_page=e,this.getData()},getData:function(){var e=this,t=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("ShopDoctor/getDoctorList",t,function(t){e.tableData=t.data.list,e.sumPage=10*t.data.sumPage,e.cur_page=t.data.currentPage,console.log(t)},function(t){e.tableData=[],e.$message.error(t.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(e,t){return e.url},filterTag:function(e,t){return t.tag===e},handleEdit:function(e,t,o){if(this.getCaseList(),this.AddOrSave=o,1==o&&(this.form={id:null,doctor_name:null,doctor_title:null,goodat:null,year:null,doctor_face:null,face:null,doctor_card:null,card:null,score:0,browse:null,praise:null,follow:0,ordernum:null,content:null,cert:null,project:null,sort:null,datetime:null},this.type=[]),void 0!=e&&void 0!=t){this.idx=e;var r=this.tableData[e];this.form={id:r.id,doctor_name:r.doctor_name,doctor_title:r.doctor_title,goodat:r.goodat,year:r.year,doctor_face:r.doctor_face,face:r.face,doctor_card:r.doctor_card,card:r.card,score:r.score,browse:r.browse,praise:r.praise,follow:r.follow,ordernum:r.ordernum,content:r.content,cert:r.cert,project:r.project,sort:r.sort,datetime:r.datetime},this.type=[];for(var i=0;i<t.caseList.length;i++)this.type.push(t.caseList[i]["id"])}this.editVisible=!0,console.log(this.form)},handleDelete:function(e,t){this.idx=e,this.form=t,this.delVisible=!0},delAll:function(){var e=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var t=0;t<e;t++)this.multipleSelection[t].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(e){this.multipleSelection=e},saveEdit:function(e){var t=this;this.$refs[e].validate(function(e){if(!e)return console.log("请填写所需数据"),!1;t.editVisible=!1;var o=null;o=1==t.AddOrSave?t.$qs.stringify({doctor_name:t.form.doctor_name,doctor_title:t.form.doctor_title,goodat:t.form.goodat,year:t.form.year,doctor_face:t.form.doctor_face,doctor_card:t.form.doctor_card,score:t.form.score,browse:t.form.browse,praise:t.form.praise,follow:t.form.follow,ordernum:t.form.ordernum,content:t.escapeStringHTML(t.form.content),cert:t.escapeStringHTML(t.form.cert),project:t.escapeStringHTML(t.form.project),sort:t.form.sort,linkcase:t.type.join(",")}):t.$qs.stringify({id:t.form.id,doctor_name:t.form.doctor_name,doctor_title:t.form.doctor_title,goodat:t.form.goodat,year:t.form.year,doctor_face:t.form.doctor_face,doctor_card:t.form.doctor_card,score:t.form.score,browse:t.form.browse,praise:t.form.praise,follow:t.form.follow,ordernum:t.form.ordernum,content:t.escapeStringHTML(t.form.content),cert:t.escapeStringHTML(t.form.cert),project:t.escapeStringHTML(t.form.project),sort:t.form.sort,linkcase:t.type.join(",")}),t.$api.post("ShopDoctor/saveDoctor",o,function(e){t.getData(),t.$message.success(e.msg)},function(e){t.$message.error(e.msg)})})},deleteRow:function(){var e=this,t=this.$qs.stringify({id:this.form.id});this.$api.post("ShopDoctor/deleteDoctor",t,function(t){e.getData(),e.$message.success(t.msg+t.data+"条数据")},function(t){e.$message.error(t.msg)}),this.delVisible=!1},escapeStringHTML:function(e){return e&&(e=e.replace(/&lt;/g,"<"),e=e.replace(/&gt;/g,">"),e=e.replace(/&quot;/g,'"')),e},submit:function(){var e=this.escapeStringHTML(this.form.details);console.log(e)},getCaseList:function(){var e=this;this.$api.post("ShopDoctor/getCaseList",null,function(t){console.log(t),e.goodsList=t.data},function(t){e.$message.error(t.msg)})}}},n=s,c=(o("026f"),o("17cc")),d=Object(c["a"])(n,r,i,!1,null,"63d66d77",null);t["default"]=d.exports},b049:function(e,t,o){"use strict";o.d(t,"b",function(){return r}),o.d(t,"a",function(){return i}),o.d(t,"c",function(){return l});const r={watcher:{},active:null,on:function(e,t){this.watcher[e]||(this.watcher[e]=t)},emit:function(e,t=1){this.active=this.watcher[e],1===t&&a()}};class i{constructor(e,t={}){this.id=Math.random(),this.quill=e,this.quill.id=this.id,this.config=t,this.file="",this.imgURL="",e.root.addEventListener("paste",this.pasteHandle.bind(this),!1),e.root.addEventListener("drop",this.dropHandle.bind(this),!1),e.root.addEventListener("dropover",function(e){e.preventDefault()},!1),this.cursorIndex=0,r.on(this.id,this)}pasteHandle(e){r.emit(this.quill.id,0);let t,o,i,a=e.clipboardData,l=0;if(a){if(t=a.items,!t)return;for(o=t[0],i=a.types||[];l<i.length;l++)if("Files"===i[l]){o=t[l];break}if(o&&"file"===o.kind&&o.type.match(/^image\//i)){this.file=o.getAsFile();let e=this;if(e.config.size&&e.file.size>=1024*e.config.size*1024)return void(e.config.sizeError&&e.config.sizeError());this.config.action}}}dropHandle(e){r.emit(this.quill.id,0);const t=this;e.preventDefault(),t.config.size&&t.file.size>=1024*t.config.size*1024?t.config.sizeError&&t.config.sizeError():(t.file=e.dataTransfer.files[0],this.config.action?t.uploadImg():t.toBase64())}toBase64(){const e=this,t=new FileReader;t.onload=(t=>{e.imgURL=t.target.result,e.insertImg()}),t.readAsDataURL(e.file)}uploadImg(){const e=this;e.quillLoading;let t=e.config,o=new FormData;o.append(t.name,e.file),t.editForm&&t.editForm(o);let i=new XMLHttpRequest;i.open("post",t.action,!0),t.headers&&t.headers(i),t.change&&t.change(i,o),i.onreadystatechange=function(){if(4===i.readyState)if(200===i.status){let o=JSON.parse(i.responseText);e.imgURL=t.response(o),r.active.uploadSuccess(),e.insertImg(),e.config.success&&e.config.success()}else e.config.error&&e.config.error(),r.active.uploadError()},i.upload.onloadstart=function(e){r.active.uploading(),t.start&&t.start()},i.upload.onprogress=function(e){let t=(e.loaded/e.total*100|0)+"%";r.active.progress(t)},i.upload.onerror=function(e){r.active.uploadError(),t.error&&t.error()},i.upload.onloadend=function(e){t.end&&t.end()},i.send(o)}insertImg(){const e=r.active;e.quill.insertEmbed(r.active.cursorIndex,"image",e.imgURL),e.quill.update(),e.quill.setSelection(e.cursorIndex+1)}progress(e){e="[uploading"+e+"]",r.active.quill.root.innerHTML=r.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,e)}uploading(){let e=(r.active.quill.getSelection()||{}).index||r.active.quill.getLength();r.active.cursorIndex=e,r.active.quill.insertText(r.active.cursorIndex,"[uploading...]",{color:"red"},!0)}uploadError(){r.active.quill.root.innerHTML=r.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"[upload error]")}uploadSuccess(){r.active.quill.root.innerHTML=r.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"")}}function a(){let e=document.querySelector(".quill-image-input");null===e&&(e=document.createElement("input"),e.setAttribute("type","file"),e.classList.add("quill-image-input"),e.style.display="none",e.addEventListener("change",function(){let t=r.active;t.file=e.files[0],e.value="",t.config.size&&t.file.size>=1024*t.config.size*1024?t.config.sizeError&&t.config.sizeError():t.config.action?t.uploadImg():t.toBase64()}),document.body.appendChild(e)),e.click()}const l=[["bold","italic","underline","strike"],["blockquote","code-block"],[{header:1},{header:2}],[{list:"ordered"},{list:"bullet"}],[{script:"sub"},{script:"super"}],[{indent:"-1"},{indent:"+1"}],[{direction:"rtl"}],[{size:["small",!1,"large","huge"]}],[{header:[1,2,3,4,5,6,!1]}],[{color:[]},{background:[]}],[{font:[]}],[{align:[]}],["clean"],["link","image","video"]]},cbc9:function(e,t,o){}}]);