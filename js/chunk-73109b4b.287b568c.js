(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-73109b4b"],{1491:function(e,t,i){"use strict";i.r(t);var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"table"},[i("div",{staticClass:"crumbs"},[i("el-breadcrumb",{attrs:{separator:"/"}},[i("el-breadcrumb-item",[i("i",{staticClass:"el-icon-lx-cascades"}),e._v(" 发放礼品管理")])],1)],1),i("div",{staticClass:"container"},[i("div",{staticClass:"handle-box"},[i("el-input",{staticClass:"handle-input mr10",attrs:{placeholder:"筛选关键词"},model:{value:e.select_word,callback:function(t){e.select_word=t},expression:"select_word"}}),i("el-button",{attrs:{type:"primary",icon:"search"},on:{click:e.search}},[e._v("搜索")]),i("el-button",{staticStyle:{float:"right"},attrs:{type:"primary"},on:{click:function(t){return e.handleEdit(void 0,void 0,1)}}},[e._v("发放奖品")])],1),i("el-table",{ref:"multipleTable",staticClass:"table",attrs:{data:e.data,border:""},on:{"selection-change":e.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"55",align:"center"}}),i("el-table-column",{attrs:{prop:"id",label:"ID",width:"70",align:"center"}}),i("el-table-column",{attrs:{prop:"username",align:"center",label:"发奖用户名",formatter:e.formatter}}),i("el-table-column",{attrs:{prop:"nickname",align:"center",label:"收奖用户昵称"}}),i("el-table-column",{attrs:{prop:"tel",align:"center",label:"收奖用户手机号"}}),i("el-table-column",{attrs:{prop:"prize",align:"center",label:"奖品名称"}}),i("el-table-column",{attrs:{prop:"datetime",align:"center",label:"发奖时间",sortable:"",width:"155"}}),i("el-table-column",{attrs:{label:"操作",width:"150",align:"center"},scopedSlots:e._u([{key:"default",fn:function(e){}}])})],1),i("div",{staticClass:"pagination"},[i("el-pagination",{attrs:{background:"",layout:"prev, pager, next",total:e.sumPage},on:{"current-change":e.handleCurrentChange}})],1)],1),i("el-dialog",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],attrs:{title:"编辑",visible:e.editVisible,width:"30%"},on:{"update:visible":function(t){e.editVisible=t}}},[i("el-form",{ref:"form",attrs:{model:e.form,"label-width":"100px"}},[i("el-form-item",{attrs:{label:"收奖用户"}},[i("el-select",{attrs:{placeholder:"请选择收奖用户"},model:{value:e.selectedUser,callback:function(t){e.selectedUser=t},expression:"selectedUser"}},e._l(e.userList,function(t){return i("el-option",{key:t.id,attrs:{label:t.nickname,value:t.id}},[e._v(e._s(t.nickname)+"\n                    ")])}),1)],1),i("el-form-item",{attrs:{label:"奖品"}},[i("el-select",{attrs:{placeholder:"请选择奖品"},model:{value:e.selectedPrize,callback:function(t){e.selectedPrize=t},expression:"selectedPrize"}},e._l(e.prizeList,function(t){return i("el-option",{key:t.id,attrs:{label:t.prize,value:t.id}},[e._v(e._s(t.prize)+"\n                    ")])}),1)],1)],1),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:e.hideEditVisible}},[e._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.saveEdit("form")}}},[e._v("确 定")])],1)],1),i("el-dialog",{attrs:{title:"提示",visible:e.delVisible,width:"300px",center:""},on:{"update:visible":function(t){e.delVisible=t}}},[i("div",{staticClass:"del-dialog-cnt"},[e._v("删除不可恢复，是否确定删除？")]),i("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[i("el-button",{on:{click:function(t){e.delVisible=!1}}},[e._v("取 消")]),i("el-button",{attrs:{type:"primary"},on:{click:e.deleteRow}},[e._v("确 定")])],1)])],1)},s=[],l={name:"basetable",data:function(){return{url:"./vuetable.json",tableData:[],cur_page:1,number:10,sumPage:10,multipleSelection:[],select_cate:"",select_word:"",del_list:[],is_search:!1,editVisible:!1,delVisible:!1,form:{id:"",wechatuserid:"",prizeid:"",datetime:"",username:"",nickname:"",tel:"",prize:""},idx:-1,dialogVisible:!1,AddOrSave:"",loading:!1,userList:[],prizeList:[],selectedUser:"",selectedPrize:""}},created:function(){this.getData()},computed:{data:function(){var e=this;return this.tableData.filter(function(t){for(var i=0;i<e.del_list.length;i++)if(t.username===e.del_list[i].username){!0;break}return t})}},methods:{handleCurrentChange:function(e){this.cur_page=e,this.getData()},getData:function(){var e=this,t=this.$qs.stringify({select_word:this.select_word,pageIndex:this.cur_page,number:this.number});this.$api.post("GrantPrize/getGrantPrizeList",t,function(t){e.tableData=t.data.list,e.sumPage=10*t.data.sumPage,e.cur_page=t.data.currentPage,console.log(e.tableData)},function(t){e.tableData=[],e.$message.error(t.msg)})},search:function(){this.is_search=!0,this.getData()},formatter:function(e,t){return e.username},filterTag:function(e,t){return t.tag===e},handleEdit:function(e,t,i){if(this.getPrizeList(),this.getwechatUserList(),console.log(t),this.AddOrSave=i,1==i&&(this.form={id:"",wechatuserid:"",prizeid:"",datetime:"",username:"",nickname:"",tel:"",prize:""},this.selectedUser="",this.selectedPrize=""),void 0!=e&&void 0!=t){this.idx=e;var a=this.tableData[e];this.form={id:a.id,wechatuserid:a.wechatuserid,prizeid:a.prizeid,datetime:a.datetime,username:a.username,nickname:a.nickname,tel:a.tel,prize:a.prize}}this.editVisible=!0},handleDelete:function(e,t){this.idx=e,this.form=t,this.delVisible=!0},delAll:function(){var e=this.multipleSelection.length;this.del_list=this.del_list.concat(this.multipleSelection);for(var t=0;t<e;t++)this.multipleSelection[t].b_title+" ";console.log(this.del_list)},handleSelectionChange:function(e){this.multipleSelection=e},saveEdit:function(e){var t=this;this.editVisible=!1;var i=JSON.parse(localStorage.getItem("userInfo")).id,a=null;a=1==this.AddOrSave?this.$qs.stringify({adminuserid:i,wechatuserid:this.selectedUser,prizeid:this.selectedPrize}):this.$qs.stringify({id:this.form.id,status:this.form.statusMsg}),this.$api.post("GrantPrize/saveGrantPrize",a,function(e){t.getData(),t.$message.success(e.msg)},function(e){t.$message.error(e.msg)})},deleteRow:function(){var e=this,t=this.$qs.stringify({id:this.form.id});this.$api.post("InCode/deleteInCode",t,function(t){e.getData(),e.$message.success(t.msg+t.data+"条数据")},function(t){e.$message.error(t.msg)}),this.delVisible=!1},getwechatUserList:function(){var e=this;this.$api.post("GrantPrize/wechatUserList",null,function(t){console.log(t),e.userList=t.data},function(t){e.$message.error(t.msg)})},getPrizeList:function(){var e=this;this.$api.post("GrantPrize/getPrizeList",null,function(t){console.log(t),e.prizeList=t.data},function(t){e.$message.error(t.msg)})},hideEditVisible:function(){this.editVisible=!1}}},r=l,n=(i("f7ef"),i("17cc")),o=Object(n["a"])(r,a,s,!1,null,"c5963362",null);t["default"]=o.exports},"23d2":function(e,t,i){},f7ef:function(e,t,i){"use strict";var a=i("23d2"),s=i.n(a);s.a}}]);