(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3fd9ffd2"],{"06b7":function(t,e,i){"use strict";var a=i("fad5"),l=i.n(a);l.a},"2d81":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("el-button",{attrs:{loading:t.downloadLoading,type:"primary"},on:{click:t.handleDownload}},[t._v("导出 Excel")])},l=[],n={name:"ExportExcel",props:{list:{required:!0,type:Array},tHeader:{required:!0,type:Array},tValue:{required:!0,type:Array},filename:{type:String,default:"导出数据"}},data:function(){return{downloadLoading:!1,autoWidth:!0,bookType:"xlsx"}},methods:{handleDownload:function(){var t=this;this.downloadLoading=!0,Promise.all([i.e("chunk-5f35d1d8"),i.e("chunk-0ef1754a")]).then(i.bind(null,"d7f7")).then(function(e){var i=t.formatJson(t.tValue,t.list);e.export_json_to_excel({header:t.tHeader,data:i,filename:t.filename,autoWidth:t.autoWidth,bookType:t.bookType}),t.downloadLoading=!1})},formatJson:function(t,e){return e.map(function(e){return t.map(function(t){return e[t]})})}}},s=n,r=i("17cc"),o=Object(r["a"])(s,a,l,!1,null,null,null);e["a"]=o.exports},e02b:function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"table"},[i("div",{staticClass:"crumbs"},[i("el-breadcrumb",{attrs:{separator:"/"}},[i("el-breadcrumb-item",[i("i",{staticClass:"el-icon-lx-cascades"}),t._v(" 内部码管理")])],1)],1),i("div",{staticClass:"container"},[i("div",{staticClass:"handle-box"},[i("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:t.select_word,callback:function(e){t.select_word=e},expression:"select_word"}}),i("el-button",{attrs:{type:"primary",icon:"search"},on:{click:t.search}},[t._v("搜索")]),i("export-excel",{staticStyle:{float:"right"},attrs:{list:t.excelData,filename:t.filename,tHeader:t.tHeader,tValue:t.tValue}})],1),i("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:t.data,border:""},on:{"selection-change":t.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),i("el-table-column",{attrs:{prop:"id",label:"ID",width:"70",align:"center"}}),i("el-table-column",{attrs:{prop:"nickname",align:"center",label:"微信昵称",formatter:t.formatter}}),i("el-table-column",{attrs:{prop:"tel",align:"center",label:"手机号码"}}),i("el-table-column",{attrs:{prop:"prize",align:"center",label:"奖品名称"}}),i("el-table-column",{attrs:{prop:"code",align:"center",label:"兑换码"}}),i("el-table-column",{attrs:{prop:"statusMsg",align:"center",label:"状态"}}),i("el-table-column",{attrs:{prop:"province",align:"center",label:"省份"}}),i("el-table-column",{attrs:{prop:"city",align:"center",label:"城市"}}),i("el-table-column",{attrs:{prop:"datetime",align:"center",label:"更新时间",sortable:"",width:"155"}}),i("el-table-column",{attrs:{label:"操作",width:"150",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{type:"text",icon:"el-icon-edit"},on:{click:function(i){return t.handleEdit(e.$index,e.row,2)}}},[t._v("编辑")])]}}])})],1),i("div",{staticClass:"pagination"},[i("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:t.sumPage},on:{"current-change":t.handleCurrentChange}})],1)],1),i("el-dialog",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],attrs:{title:"编辑",visible:t.editVisible,width:"30%"},on:{"update:visible":function(e){t.editVisible=e}}},[i("el-form",{ref:"form",attrs:{model:t.form,"label-width":"100px"}},[i("el-form-item",{attrs:{label:"领奖状态"}},[i("el-select",{attrs:{placeholder:"请选择领奖状态"},model:{value:t.form.statusMsg,callback:function(e){t.$set(t.form,"statusMsg",e)},expression:"form.statusMsg"}},t._l(t.statusList,function(e){return i("el-option",{key:e.id,attrs:{label:e.title,value:e.id}},[t._v(t._s(e.title)+"\n                    ")])}),1)],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:t.hideEditVisible}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.saveEdit("form")}}},[t._v("确 定")])],1)],1),i("el-dialog",{attrs:{title:"提示",visible:t.delVisible,width:"300px",center:""},on:{"update:visible":function(e){t.delVisible=e}}},[i("div",{staticClass:"del-dialog-cnt"},[t._v("删除不可恢复，是否确定删除？")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(e){t.delVisible=!1}}},[t._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:t.deleteRow}},[t._v("确 定")])],1)])],1)},l=[],n=i("2d81"),s={name:"basetable",components:{ExportExcel:n["a"]},data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{id:"",nickname:"",tel:"",prize:"",statusMsg:"",status:"",province:"",city:"",datetime:""},idx:-1,dialogVisible:!1,AddOrSave:"",loading:!1,statusList:[{id:1,title:"未领取"},{id:2,title:"已领取"},{id:3,title:"已使用"}],excelData:[],tHeader:["ID","微信昵称","手机号码","奖品名称","兑换码","状态","省份","城市","更新时间"],tValue:["id","nickname","tel","prize","code","statusMsg","province","city","datetime"],filename:"砸金蛋所有中奖名单数据"}},created:function(){this.getData(),this.exportExcel()},computed:{data:function(){var t=this;return this.tableData.filter(function(e){for(var i=0;i<t.del_list.length;i++)if(e.title===t.del_list[i].title){!0;break}return e})}},methods:{handleCurrentChange:function(t){this.cur_page=t,this.getData()},getData:function(){var t=this,e=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("WinPrize/getWinPrizeList",e,function(e){t.tableData=e.data.list,t.sumPage=10*e.data.sumPage,t.cur_page=e.data.currentPage,console.log(t.tableData)},function(e){t.tableData=[],t.$message.error(e.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(t,e){return t.nickname},filterTag:function(t,e){return e.tag===t},handleEdit:function(t,e,i){if(console.log(e),this.AddOrSave=i,1==i&&(this.form={id:null,nickname:null,tel:null,prize:null,statusMsg:null,status:null,province:null,city:null,datetime:null}),void 0!=t&&void 0!=e){this.idx=t;var a=this.tableData[t];this.form={id:a.id,nickname:a.nickname,tel:a.tel,prize:a.prize,statusMsg:a.statusMsg,status:a.status,province:a.province,city:a.city,datetime:a.datetime}}this.editVisible=!0},handleDelete:function(t,e){this.idx=t,this.form=e,this.delVisible=!0},delAll:function(){var t=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var e=0;e<t;e++)this.multipleSelection[e].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(t){this.multipleSelection=t},saveEdit:function(t){var e=this;this.editVisible=!1;var i=null;i=1==this.AddOrSave?this.$qs.stringify({title:this.form.title,number:this.form.number}):this.$qs.stringify({id:this.form.id,status:this.form.statusMsg}),this.$api.post("WinPrize/saveWinPrize",i,function(t){e.getData(),e.$message.success(t.msg)},function(t){e.$message.error(t.msg)})},deleteRow:function(){var t=this,e=this.$qs.stringify({id:this.form.id});this.$api.post("InCode/deleteInCode",e,function(e){t.getData(),t.$message.success(e.msg+e.data+"条数据")},function(e){t.$message.error(e.msg)}),this.delVisible=!1},hideEditVisible:function(){this.editVisible=!1},exportExcel:function(){var t=this;this.$api.post("WinPrize/exportExcel",null,function(e){t.excelData=e.data},function(t){console.log(t)})}}},r=s,o=(i("06b7"),i("17cc")),c=Object(o["a"])(r,a,l,!1,null,"57bea65c",null);e["default"]=c.exports},fad5:function(t,e,i){}}]);