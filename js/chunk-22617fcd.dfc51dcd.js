(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-22617fcd"],{"2b99":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"table"},[i("div",{staticClass:"crumbs"},[i("el-breadcrumb",{attrs:{separator:"/"}},[i("el-breadcrumb-item",[i("i",{staticClass:"el-icon-lx-cascades"}),t._v(" 轮播图管理")])],1)],1),i("div",{staticClass:"container"},[i("div",{staticClass:"handle-box"},[i("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:t.select_word,callback:function(e){t.select_word=e},expression:"select_word"}}),i("el-button",{attrs:{type:"primary",icon:"search"},on:{click:t.search}},[t._v("搜索")]),i("el-button",{staticStyle:{float:"right"},attrs:{type:"primary"},on:{click:function(e){return t.handleEdit(void 0,void 0,1)}}},[t._v("添加")])],1),i("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:t.data,border:""},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),i("el-table-column",{attrs:{prop:"id",label:"ID",width:"70",align:"center"}}),t._v("\\\n            "),i("el-table-column",{attrs:{prop:"title",align:"center",label:"标题"}}),i("el-table-column",{attrs:{prop:"b_image",align:"center",label:"图片"},scopedSlots:t._u([{key:"default",fn:function(t){return[i("el-popover",{attrs:{placement:"left",title:"",width:"500",trigger:"hover"}},[i("img",{staticStyle:{"max-width":"100%"},attrs:{src:t.row.b_image}}),i("img",{staticStyle:{"max-width":"130px",height:"auto","max-height":"100px"},attrs:{slot:"reference",src:t.row.b_image,alt:t.row.b_image},slot:"reference"})])]}}])}),i("el-table-column",{attrs:{prop:"sort",label:"排序",width:"100",align:"center"}}),i("el-table-column",{attrs:{prop:"datetime",label:"更新时间",align:"center",sortable:"",width:"200"}}),i("el-table-column",{attrs:{label:"操作",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{type:"text",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit(e.$index,e.row,2)}}},[t._v("编辑")]),i("el-button",{staticClass:"red",attrs:{type:"text",icon:"el-icon-delete"},on:{click:function(i){return t.handleDelete(e.$index,e.row)}}},[t._v("删除")])]}}])})],1),i("div",{staticClass:"pagination"},[i("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.sumPage},on:{"current-change":t.handleCurrentChange}})],1)],1),i("el-dialog",{attrs:{title:"编辑",visible:t.editVisible,width:"80%"},on:{"update:visible":function(e){t.editVisible=e}}},[i("el-form",{ref:"form",attrs:{model:t.form,"label-width":"100px"}},[i("el-form-item",{attrs:{label:"标题"}},[i("el-input",{staticStyle:{width:"350px"},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),i("el-form-item",{attrs:{label:"图片"}},[i("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.imgid},action:t.uploadUrl(),"on-error":t.uploadError,"on-success":t.handleAvatarSuccess,"before-upload":t.beforeAvatarUpload,"on-progress":t.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[t.form.b_image?i("img",{staticClass:"avatar",attrs:{src:t.form.b_image}}):i("i",{staticClass:"el-icon-plus avatar-uploader-icon"})]),i("span",{staticStyle:{color:"red"}},[t._v("建议尺寸1125*540")])],1),i("el-form-item",{attrs:{label:"排序"}},[i("el-input",{staticStyle:{width:"100px"},model:{value:t.form.sort,callback:function(e){t.$set(t.form,"sort",e)},expression:"form.sort"}}),i("span",{staticStyle:{color:"red"}},[t._v("  注：数值越大展示越靠前，不输入则默认排序")])],1),i("el-form-item",{attrs:{label:"商品详情"}},[i("quill-editor",{ref:"myTextEditor",attrs:{options:t.editorOption},model:{value:t.form.details,callback:function(e){t.$set(t.form,"details",e)},expression:"form.details"}})],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.editVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.saveEdit}},[t._v("确 定")])],1)],1),i("el-dialog",{attrs:{title:"提示",visible:t.delVisible,width:"300px",center:""},on:{"update:visible":function(e){t.delVisible=e}}},[i("div",{staticClass:"del-dialog-cnt"},[t._v("删除不可恢复，是否确定删除？")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.delVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.deleteRow}},[t._v("确 定")])],1)])],1)},l=[],o=(i("fa26"),i("fb59"),i("3040"),i("cac2"),i("1e58"),i("b881")),s=i("b049");o["Quill"].register("modules/ImageExtend",s["a"]);var r={name:"basetable",components:{quillEditor:o["quillEditor"]},data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{id:"",title:"",imgid:"",b_image:"",details:"",sort:"",datetime:""},idx:-1,dialogVisible:!1,AddOrSave:"",editorOption:{modules:{ImageExtend:{loading:!0,name:"image",action:this.$api.uploadUrl+"/Images/uploadEditorImage",response:function(t){return t.data}},toolbar:{container:s["c"],handlers:{image:function(){s["b"].emit(this.quill.id)}}}}}}},created:function(){this.getData()},computed:{data:function(){var t=this;return this.tableData.filter(function(e){for(var i=0;i<t.del_list.length;i++)if(e.title===t.del_list[i].title){!0;break}return e})}},methods:{uploadUrl:function(){var t=this.$api.uploadUrl+"/Images/upload";return t},beforeAvatarUpload:function(t){},uploading:function(t,e,i){},uploadError:function(t){this.$message.error(t.msg)},handleAvatarSuccess:function(t,e){console.log(t),this.form.imgid=t.data,this.form.b_image=URL.createObjectURL(e.raw),this.getData(),this.$message.success(t.msg)},handleCurrentChange:function(t){this.cur_page=t,this.getData()},getData:function(){var t=this,e=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("ShopBanner/getBannerList",e,function(e){t.tableData=e.data.list,t.sumPage=10*e.data.sumPage,t.cur_page=e.data.currentPage,console.log(e)},function(e){t.tableData=[],t.$message.error(e.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(t,e){return t.url},filterTag:function(t,e){return e.tag===t},handleEdit:function(t,e,i){if(this.AddOrSave=i,1==i&&(this.form={id:null,title:null,imgid:null,b_image:null,details:null,sort:null,datetime:null}),void 0!=t&&void 0!=e){this.idx=t;var a=this.tableData[t];this.form={id:a.id,title:a.title,imgid:a.imgid,b_image:a.b_image,details:a.details,sort:a.sort,datetime:a.datetime}}this.editVisible=!0,console.log(this.form)},handleDelete:function(t,e){this.idx=t,this.form=e,this.delVisible=!0},delAll:function(){var t=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var e=0;e<t;e++)this.multipleSelection[e].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(t){this.multipleSelection=t},saveEdit:function(){var t=this;this.editVisible=!1;var e=null;e=1==this.AddOrSave?this.$qs.stringify({imgid:this.form.imgid,title:this.form.title,details:this.escapeStringHTML(this.form.details),sort:this.form.sort}):this.$qs.stringify({id:this.form.id,title:this.form.title,details:this.escapeStringHTML(this.form.details),sort:this.form.sort}),this.$api.post("ShopBanner/saveBanner",e,function(e){t.getData(),t.$message.success(e.msg)},function(e){t.$message.error(e.msg)})},deleteRow:function(){var t=this,e=this.$qs.stringify({id:this.form.id});console.log(this.form),this.$api.post("ShopBanner/deleteBanner",e,function(e){t.getData(),t.$message.success(e.msg+e.data+"条数据")},function(e){t.$message.error(e.msg)}),this.delVisible=!1},escapeStringHTML:function(t){return t&&(t=t.replace(/&lt;/g,"<"),t=t.replace(/&gt;/g,">"),t=t.replace(/&quot;/g,'"')),t},submit:function(){var t=this.escapeStringHTML(this.form.details);console.log(t)}}},n=r,c=(i("36a7"),i("17cc")),d=Object(c["a"])(n,a,l,!1,null,"820e38a2",null);e["default"]=d.exports},"36a7":function(t,e,i){"use strict";var a=i("37f5"),l=i.n(a);l.a},"37f5":function(t,e,i){},b049:function(t,e,i){"use strict";i.d(e,"b",function(){return a}),i.d(e,"a",function(){return l}),i.d(e,"c",function(){return s});const a={watcher:{},active:null,on:function(t,e){this.watcher[t]||(this.watcher[t]=e)},emit:function(t,e=1){this.active=this.watcher[t],1===e&&o()}};class l{constructor(t,e={}){this.id=Math.random(),this.quill=t,this.quill.id=this.id,this.config=e,this.file="",this.imgURL="",t.root.addEventListener("paste",this.pasteHandle.bind(this),!1),t.root.addEventListener("drop",this.dropHandle.bind(this),!1),t.root.addEventListener("dropover",function(t){t.preventDefault()},!1),this.cursorIndex=0,a.on(this.id,this)}pasteHandle(t){a.emit(this.quill.id,0);let e,i,l,o=t.clipboardData,s=0;if(o){if(e=o.items,!e)return;for(i=e[0],l=o.types||[];s<l.length;s++)if("Files"===l[s]){i=e[s];break}if(i&&"file"===i.kind&&i.type.match(/^image\//i)){this.file=i.getAsFile();let t=this;if(t.config.size&&t.file.size>=1024*t.config.size*1024)return void(t.config.sizeError&&t.config.sizeError());this.config.action}}}dropHandle(t){a.emit(this.quill.id,0);const e=this;t.preventDefault(),e.config.size&&e.file.size>=1024*e.config.size*1024?e.config.sizeError&&e.config.sizeError():(e.file=t.dataTransfer.files[0],this.config.action?e.uploadImg():e.toBase64())}toBase64(){const t=this,e=new FileReader;e.onload=(e=>{t.imgURL=e.target.result,t.insertImg()}),e.readAsDataURL(t.file)}uploadImg(){const t=this;t.quillLoading;let e=t.config,i=new FormData;i.append(e.name,t.file),e.editForm&&e.editForm(i);let l=new XMLHttpRequest;l.open("post",e.action,!0),e.headers&&e.headers(l),e.change&&e.change(l,i),l.onreadystatechange=function(){if(4===l.readyState)if(200===l.status){let i=JSON.parse(l.responseText);t.imgURL=e.response(i),a.active.uploadSuccess(),t.insertImg(),t.config.success&&t.config.success()}else t.config.error&&t.config.error(),a.active.uploadError()},l.upload.onloadstart=function(t){a.active.uploading(),e.start&&e.start()},l.upload.onprogress=function(t){let e=(t.loaded/t.total*100|0)+"%";a.active.progress(e)},l.upload.onerror=function(t){a.active.uploadError(),e.error&&e.error()},l.upload.onloadend=function(t){e.end&&e.end()},l.send(i)}insertImg(){const t=a.active;t.quill.insertEmbed(a.active.cursorIndex,"image",t.imgURL),t.quill.update(),t.quill.setSelection(t.cursorIndex+1)}progress(t){t="[uploading"+t+"]",a.active.quill.root.innerHTML=a.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,t)}uploading(){let t=(a.active.quill.getSelection()||{}).index||a.active.quill.getLength();a.active.cursorIndex=t,a.active.quill.insertText(a.active.cursorIndex,"[uploading...]",{color:"red"},!0)}uploadError(){a.active.quill.root.innerHTML=a.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"[upload error]")}uploadSuccess(){a.active.quill.root.innerHTML=a.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"")}}function o(){let t=document.querySelector(".quill-image-input");null===t&&(t=document.createElement("input"),t.setAttribute("type","file"),t.classList.add("quill-image-input"),t.style.display="none",t.addEventListener("change",function(){let e=a.active;e.file=t.files[0],t.value="",e.config.size&&e.file.size>=1024*e.config.size*1024?e.config.sizeError&&e.config.sizeError():e.config.action?e.uploadImg():e.toBase64()}),document.body.appendChild(t)),t.click()}const s=[["bold","italic","underline","strike"],["blockquote","code-block"],[{header:1},{header:2}],[{list:"ordered"},{list:"bullet"}],[{script:"sub"},{script:"super"}],[{indent:"-1"},{indent:"+1"}],[{direction:"rtl"}],[{size:["small",!1,"large","huge"]}],[{header:[1,2,3,4,5,6,!1]}],[{color:[]},{background:[]}],[{font:[]}],[{align:[]}],["clean"],["link","image","video"]]}}]);