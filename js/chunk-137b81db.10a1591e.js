(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-137b81db"],{"279d":function(e,t,i){"use strict";var l=i("5901"),a=i.n(l);a.a},5901:function(e,t,i){},b049:function(e,t,i){"use strict";i.d(t,"b",function(){return l}),i.d(t,"a",function(){return a}),i.d(t,"c",function(){return s});const l={watcher:{},active:null,on:function(e,t){this.watcher[e]||(this.watcher[e]=t)},emit:function(e,t=1){this.active=this.watcher[e],1===t&&o()}};class a{constructor(e,t={}){this.id=Math.random(),this.quill=e,this.quill.id=this.id,this.config=t,this.file="",this.imgURL="",e.root.addEventListener("paste",this.pasteHandle.bind(this),!1),e.root.addEventListener("drop",this.dropHandle.bind(this),!1),e.root.addEventListener("dropover",function(e){e.preventDefault()},!1),this.cursorIndex=0,l.on(this.id,this)}pasteHandle(e){l.emit(this.quill.id,0);let t,i,a,o=e.clipboardData,s=0;if(o){if(t=o.items,!t)return;for(i=t[0],a=o.types||[];s<a.length;s++)if("Files"===a[s]){i=t[s];break}if(i&&"file"===i.kind&&i.type.match(/^image\//i)){this.file=i.getAsFile();let e=this;if(e.config.size&&e.file.size>=1024*e.config.size*1024)return void(e.config.sizeError&&e.config.sizeError());this.config.action}}}dropHandle(e){l.emit(this.quill.id,0);const t=this;e.preventDefault(),t.config.size&&t.file.size>=1024*t.config.size*1024?t.config.sizeError&&t.config.sizeError():(t.file=e.dataTransfer.files[0],this.config.action?t.uploadImg():t.toBase64())}toBase64(){const e=this,t=new FileReader;t.onload=(t=>{e.imgURL=t.target.result,e.insertImg()}),t.readAsDataURL(e.file)}uploadImg(){const e=this;e.quillLoading;let t=e.config,i=new FormData;i.append(t.name,e.file),t.editForm&&t.editForm(i);let a=new XMLHttpRequest;a.open("post",t.action,!0),t.headers&&t.headers(a),t.change&&t.change(a,i),a.onreadystatechange=function(){if(4===a.readyState)if(200===a.status){let i=JSON.parse(a.responseText);e.imgURL=t.response(i),l.active.uploadSuccess(),e.insertImg(),e.config.success&&e.config.success()}else e.config.error&&e.config.error(),l.active.uploadError()},a.upload.onloadstart=function(e){l.active.uploading(),t.start&&t.start()},a.upload.onprogress=function(e){let t=(e.loaded/e.total*100|0)+"%";l.active.progress(t)},a.upload.onerror=function(e){l.active.uploadError(),t.error&&t.error()},a.upload.onloadend=function(e){t.end&&t.end()},a.send(i)}insertImg(){const e=l.active;e.quill.insertEmbed(l.active.cursorIndex,"image",e.imgURL),e.quill.update(),e.quill.setSelection(e.cursorIndex+1)}progress(e){e="[uploading"+e+"]",l.active.quill.root.innerHTML=l.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,e)}uploading(){let e=(l.active.quill.getSelection()||{}).index||l.active.quill.getLength();l.active.cursorIndex=e,l.active.quill.insertText(l.active.cursorIndex,"[uploading...]",{color:"red"},!0)}uploadError(){l.active.quill.root.innerHTML=l.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"[upload error]")}uploadSuccess(){l.active.quill.root.innerHTML=l.active.quill.root.innerHTML.replace(/\[uploading.*?\]/,"")}}function o(){let e=document.querySelector(".quill-image-input");null===e&&(e=document.createElement("input"),e.setAttribute("type","file"),e.classList.add("quill-image-input"),e.style.display="none",e.addEventListener("change",function(){let t=l.active;t.file=e.files[0],e.value="",t.config.size&&t.file.size>=1024*t.config.size*1024?t.config.sizeError&&t.config.sizeError():t.config.action?t.uploadImg():t.toBase64()}),document.body.appendChild(e)),e.click()}const s=[["bold","italic","underline","strike"],["blockquote","code-block"],[{header:1},{header:2}],[{list:"ordered"},{list:"bullet"}],[{script:"sub"},{script:"super"}],[{indent:"-1"},{indent:"+1"}],[{direction:"rtl"}],[{size:["small",!1,"large","huge"]}],[{header:[1,2,3,4,5,6,!1]}],[{color:[]},{background:[]}],[{font:[]}],[{align:[]}],["clean"],["link","image","video"]]},ce49:function(e,t,i){"use strict";i.r(t);var l=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"table"},[i("div",{staticClass:"crumbs"},[i("el-breadcrumb",{attrs:{separator:"/"}},[i("el-breadcrumb-item",[i("i",{staticClass:"el-icon-lx-cascades"}),e._v(" 积分商品")])],1)],1),i("div",{staticClass:"container"},[i("div",{staticClass:"handle-box"},[i("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:e.select_word,callback:function(t){e.select_word=t},expression:"select_word"}}),i("el-button",{attrs:{type:"primary",icon:"search"},on:{click:e.search}},[e._v("搜索")]),i("el-button",{staticStyle:{float:"right"},attrs:{type:"primary"},on:{click:function(t){return e.handleEdit(void 0,void 0,1)}}},[e._v("添加")])],1),i("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:e.data,border:""},on:{"selection-change":e.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),i("el-table-column",{attrs:{prop:"id",label:"ID",width:"70",align:"center"}}),i("el-table-column",{attrs:{prop:"title",align:"center",label:"商品名称"}}),i("el-table-column",{attrs:{prop:"b_image",width:"180",align:"center",label:"缩略图"},scopedSlots:e._u([{key:"default",fn:function(e){return[i("el-popover",{attrs:{placement:"left",title:"",width:"500",trigger:"hover"}},[i("img",{staticStyle:{"max-width":"100%"},attrs:{src:e.row.b_image,width:"150"}}),i("img",{staticStyle:{"max-width":"130px",height:"auto","max-height":"100px"},attrs:{slot:"reference",src:e.row.b_image,alt:e.row.b_image},slot:"reference"})])]}}])}),i("el-table-column",{attrs:{prop:"integral",align:"center",width:"150",label:"所需积分"}}),i("el-table-column",{attrs:{prop:"salesvolume",align:"center",width:"150",label:"销量"}}),i("el-table-column",{attrs:{prop:"isup",align:"center",width:"150",label:"上架"},scopedSlots:e._u([{key:"default",fn:function(t){return[1==t.row.isup?i("div",{staticStyle:{color:"#409EFF"}},[e._v("是")]):i("div",{staticStyle:{color:"red"}},[e._v("否")])]}}])}),i("el-table-column",{attrs:{prop:"sort",label:"排序",width:"150",align:"center"}}),i("el-table-column",{attrs:{prop:"datetime",label:"更新时间",width:"180",align:"center",sortable:""}}),i("el-table-column",{attrs:{label:"操作",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[i("el-button",{attrs:{type:"text",icon:"el-icon-edit"},on:{click:function(i){return e.handleEdit(t.$index,t.row,2)}}},[e._v("编辑")]),i("el-button",{staticClass:"red",attrs:{type:"text",icon:"el-icon-delete"},on:{click:function(i){return e.handleDelete(t.$index,t.row)}}},[e._v("删除")])]}}])})],1),i("div",{staticClass:"pagination"},[i("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:e.sumPage},on:{"current-change":e.handleCurrentChange}})],1)],1),i("el-dialog",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],attrs:{title:"编辑",visible:e.editVisible,width:"70%"},on:{"update:visible":function(t){e.editVisible=t}}},[i("el-form",{ref:"form",attrs:{rules:e.rules,model:e.form,"label-width":"100px"}},[i("el-form-item",{attrs:{label:"商品名称",prop:"title"}},[i("el-input",{staticStyle:{width:"400px"},attrs:{placeholder:"请输入副标题"},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),i("el-form-item",{attrs:{label:"缩略图"}},[i("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"","list-type":"picture-card",data:{id:this.form.pic},action:e.uploadUrl(),"on-error":e.uploadError,"on-success":e.handleAvatarSuccess,"before-upload":e.beforeAvatarUpload,"on-progress":e.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[e.form.b_image?i("img",{staticClass:"avatar",attrs:{src:e.form.b_image}}):i("i",{staticClass:"el-icon-plus"})]),i("span",{staticStyle:{color:"red"}},[e._v("建议尺寸225*225")])],1),i("el-form-item",{attrs:{label:"轮播图"}},[i("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"","list-type":"picture-card",data:{id:null},action:e.uploadUrl(),"on-error":e.uploadError,"on-success":e.handleAvatarSuccess2,"before-upload":e.beforeAvatarUpload,"on-progress":e.uploading,"auto-upload":!0,"on-preview":e.handlePictureCardPreview,"on-remove":e.handleRemove,"file-list":this.form.swiperimgList,enctype:"multipart/form-data"}},[i("i",{staticClass:"el-icon-plus"})]),i("el-dialog",{attrs:{visible:e.isShowBigImg,"append-to-body":!0,width:"60%",top:"10vh"},on:{"update:visible":function(t){e.isShowBigImg=t}}},[i("img",{attrs:{width:"100%",src:e.dialogImageUrl,alt:""}})]),i("span",{staticStyle:{color:"red"}},[e._v("建议尺寸1125*648")])],1),i("el-form-item",{attrs:{label:"所需积分",prop:"integral"}},[i("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入所需积分"},model:{value:e.form.integral,callback:function(t){e.$set(e.form,"integral",t)},expression:"form.integral"}})],1),i("el-form-item",{attrs:{label:"销量",prop:"salesvolume"}},[i("el-input",{staticStyle:{width:"150px"},attrs:{placeholder:"请输入销量"},model:{value:e.form.salesvolume,callback:function(t){e.$set(e.form,"salesvolume",t)},expression:"form.salesvolume"}})],1),i("el-form-item",{attrs:{label:"上架"}},[i("el-switch",{model:{value:e.form.isup,callback:function(t){e.$set(e.form,"isup",t)},expression:"form.isup"}})],1),i("el-form-item",{attrs:{label:"排序"}},[i("el-input",{staticStyle:{width:"150px"},model:{value:e.form.sort,callback:function(t){e.$set(e.form,"sort",t)},expression:"form.sort"}}),i("span",{staticStyle:{color:"red"}},[e._v("  注：数值越大展示越靠前，不输入则默认排序")])],1),i("el-form-item",{attrs:{label:"商品详情"}},[i("quill-editor",{ref:"myTextEditor",attrs:{options:e.editorOption},model:{value:e.form.details,callback:function(t){e.$set(e.form,"details",t)},expression:"form.details"}})],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(t){e.editVisible=!1}}},[e._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.saveEdit("form")}}},[e._v("确 定")])],1)],1),i("el-dialog",{attrs:{title:"提示",visible:e.delVisible,width:"300px",center:""},on:{"update:visible":function(t){e.delVisible=t}}},[i("div",{staticClass:"del-dialog-cnt"},[e._v("删除不可恢复，是否确定删除？")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(t){e.delVisible=!1}}},[e._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:e.deleteRow}},[e._v("确 定")])],1)])],1)},a=[],o=(i("fa26"),i("e71a"),i("fb59"),i("1b79"),i("3040"),i("cac2"),i("1e58"),i("b881")),s=i("b049");o["Quill"].register("modules/ImageExtend",s["a"]);var r={name:"basetable",components:{quillEditor:o["quillEditor"]},data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{id:"",title:"",pic:"",b_image:"",imglist:"",swiperimgList:[],integral:"",salesvolume:"",isup:"",details:"",sort:"",datetime:""},idx:-1,dialogVisible:!1,AddOrSave:"",rules:{title:[{required:!0,message:"请输入商品名称",trigger:"blur"}],integral:[{required:!0,message:"请输入所需积分",trigger:"blur"}],salesvolume:[{required:!0,message:"请输入销量",trigger:"blur"}]},dialogImageUrl:"",isShowBigImg:!1,editorOption:{modules:{ImageExtend:{loading:!0,name:"image",action:this.$api.uploadUrl+"/Images/uploadEditorImage",response:function(e){return e.data}},toolbar:{container:s["c"],handlers:{image:function(){s["b"].emit(this.quill.id)}}}}},inputVisible:!1,inputValue:"",loading:!1}},created:function(){this.getData()},computed:{data:function(){var e=this;return this.tableData.filter(function(t){for(var i=0;i<e.del_list.length;i++)if(t.title===e.del_list[i].title){!0;break}return t})}},methods:{onEditorChange:function(e){e.editor;var t=e.html;e.text;this.form.details=t},handleRemove:function(e,t){var i=this,l=null;l=void 0!=e.id?e.id:e.response.data;var a=this.$qs.stringify({imgId:l,id:this.form.id});console.log(a),this.$api.post("ShopIntegralGoods/delImage",a,function(e){var t=i.form.swiperimgTemp;t.forEach(function(e,t,i){e==l&&i.splice(t,1)}),i.$message.success(e.msg)},function(e){var t=i.form.swiperimgTemp;t.forEach(function(e,t,i){e==l&&i.splice(t,1)}),i.$message.error(e.msg)})},handlePictureCardPreview:function(e){this.dialogImageUrl=e.url,this.isShowBigImg=!0},uploadUrl:function(){var e=this.$api.uploadUrl+"/Images/upload";return e},beforeAvatarUpload:function(e){console.log(this.form.pic),console.log(e),this.loading=!0},uploading:function(e,t,i){},uploadError:function(e){this.$message.error(e.msg)},handleAvatarSuccess:function(e,t){this.loading=!1,console.log(e),this.form.pic=e.data,this.form.b_image=URL.createObjectURL(t.raw),this.$message.success(e.msg)},handleAvatarSuccess2:function(e,t){this.loading=!1,console.log(e),this.form.swiperimgTemp.push(e.data),this.$message.success(e.msg),console.log(this.form.swiperimgTemp)},handleCurrentChange:function(e){this.cur_page=e,this.getData()},getData:function(){var e=this,t=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("ShopIntegralGoods/getCommodityList",t,function(t){e.tableData=t.data.list,e.sumPage=10*t.data.sumPage,e.cur_page=t.data.currentPage,console.log(t)},function(t){e.tableData=[],e.$message.error(t.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(e,t){return e.url},filterTag:function(e,t){return t.tag===e},handleEdit:function(e,t,i){if(this.AddOrSave=i,1==i&&(this.form={id:null,title:null,pic:null,b_image:null,imglist:null,swiperimgList:[],integral:null,salesvolume:null,isup:null,details:null,sort:null,datetime:null,swiperimgTemp:[]}),void 0!=e&&void 0!=t){this.idx=e;var l=this.tableData[e];this.form={id:l.id,title:l.title,pic:l.pic,b_image:l.b_image,imglist:l.imglist,swiperimgList:l.swiperimgList,integral:l.integral,salesvolume:l.salesvolume,isup:l.isup,details:this.escapeStringHTML(l.details),sort:l.sort,datetime:l.datetime,swiperimgTemp:l.imglist?l.imglist.split(","):[]}}this.editVisible=!0,console.log(this.form)},handleDelete:function(e,t){this.idx=e,this.form=t,this.delVisible=!0},delAll:function(){var e=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var t=0;t<e;t++)this.multipleSelection[t].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(e){this.multipleSelection=e},saveEdit:function(e){var t=this;this.$refs[e].validate(function(e){if(!e)return console.log("请填写所需数据"),!1;t.editVisible=!1;var i=null;i=1==t.AddOrSave?t.$qs.stringify({title:t.form.title,pic:t.form.pic,imglist:t.form.swiperimgTemp.join(","),integral:t.form.integral,salesvolume:t.form.salesvolume,isup:t.form.isup?1:0,sort:t.form.sort,details:t.escapeStringHTML(t.form.details)}):t.$qs.stringify({id:t.form.id,title:t.form.title,pic:t.form.pic,imglist:t.form.swiperimgTemp.join(","),integral:t.form.integral,salesvolume:t.form.salesvolume,isup:t.form.isup?1:0,sort:t.form.sort,details:t.escapeStringHTML(t.form.details)}),console.log({id:t.form.id,title:t.form.title,pic:t.form.pic,imglist:t.form.swiperimgTemp.join(","),integral:t.form.integral,salesvolume:t.form.salesvolume,isup:t.form.isup?1:0,sort:t.form.sort,details:t.escapeStringHTML(t.form.details)}),t.$api.post("ShopIntegralGoods/saveCommodity",i,function(e){t.getData(),t.$message.success(e.msg),console.log(e)},function(e){t.$message.error(e.msg)})})},deleteRow:function(){var e=this,t=this.$qs.stringify({id:this.form.id});console.log(t),this.$api.post("ShopIntegralGoods/deleteCommodity",t,function(t){e.getData(),e.$message.success(t.msg+t.data+"条数据")},function(t){e.$message.error(t.msg)}),this.delVisible=!1},escapeStringHTML:function(e){return e&&(e=e.replace(/&lt;/g,"<"),e=e.replace(/&gt;/g,">"),e=e.replace(/&quot;/g,'"')),e},submit:function(){var e=this.escapeStringHTML(this.form.details);console.log(e)}}},n=r,c=(i("279d"),i("17cc")),d=Object(c["a"])(n,l,a,!1,null,"3d45b760",null);t["default"]=d.exports}}]);