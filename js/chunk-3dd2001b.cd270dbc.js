(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3dd2001b"],{3324:function(a,t,e){},3699:function(a,t,e){"use strict";var o=e("3324"),l=e.n(o);l.a},"700d":function(a,t,e){"use strict";e.r(t);var o=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",[e("div",{staticClass:"crumbs"},[e("el-breadcrumb",{attrs:{separator:"/"}},[e("el-breadcrumb-item",[e("i",{staticClass:"el-icon-lx-cascades"}),a._v(" 基本配置")])],1)],1),e("div",{staticClass:"container"},[e("div",{staticClass:"form-box"},[e("el-form",{ref:"form",attrs:{model:a.form,"label-width":"80px"}},[e("el-form-item",{attrs:{label:"医院名称"}},[e("el-input",{attrs:{placeholder:"请输入医院名称"},model:{value:a.form.hospitalname,callback:function(t){a.$set(a.form,"hospitalname",t)},expression:"form.hospitalname"}})],1),e("el-form-item",{attrs:{label:"城市"}},[e("el-input",{attrs:{placeholder:"请输入医院所在城市"},model:{value:a.form.city,callback:function(t){a.$set(a.form,"city",t)},expression:"form.city"}})],1),e("el-form-item",{attrs:{label:"医院地址"}},[e("el-input",{attrs:{placeholder:"请输入医院地址"},model:{value:a.form.address,callback:function(t){a.$set(a.form,"address",t)},expression:"form.address"}})],1),e("el-form-item",{attrs:{label:"地理纬度"}},[e("el-input",{attrs:{placeholder:"请输入医院地理纬度"},model:{value:a.form.lat,callback:function(t){a.$set(a.form,"lat",t)},expression:"form.lat"}})],1),e("el-form-item",{attrs:{label:"地理经度"}},[e("el-input",{attrs:{placeholder:"请输入医院地理经度"},model:{value:a.form.lng,callback:function(t){a.$set(a.form,"lng",t)},expression:"form.lng"}})],1),e("el-form-item",{attrs:{label:"联系电话"}},[e("el-input",{attrs:{placeholder:"请输入联系电话"},model:{value:a.form.tel,callback:function(t){a.$set(a.form,"tel",t)},expression:"form.tel"}})],1),e("el-form-item",{attrs:{label:"营业时间"}},[e("el-input",{attrs:{placeholder:"请输入营业时间"},model:{value:a.form.time,callback:function(t){a.$set(a.form,"time",t)},expression:"form.time"}})],1),e("el-form-item",{attrs:{label:"医院Logo"}},[e("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.logo},action:a.uploadUrl(),"on-error":a.uploadError,"on-success":a.handleAvatarSuccess1,"before-upload":a.beforeAvatarUpload,"on-progress":a.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[a.form.logoImgUrl?e("img",{staticClass:"avatar",attrs:{src:a.form.logoImgUrl}}):e("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1),e("el-form-item",{attrs:{label:"团队图片"}},[e("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.teamimg},action:a.uploadUrl(),"on-error":a.uploadError,"on-success":a.handleAvatarSuccess2,"before-upload":a.beforeAvatarUpload,"on-progress":a.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[a.form.teamImgUrl?e("img",{staticClass:"avatar",attrs:{src:a.form.teamImgUrl}}):e("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1),e("el-form-item",{attrs:{label:"医院环境"}},[e("el-upload",{staticClass:"avatar-uploader",attrs:{name:"image","with-credentials":"",data:{id:this.form.hospitalimg},action:a.uploadUrl(),"on-error":a.uploadError,"on-success":a.handleAvatarSuccess3,"before-upload":a.beforeAvatarUpload,"on-progress":a.uploading,"show-file-list":!1,"auto-upload":!0,enctype:"multipart/form-data"}},[a.form.hospitalImgUrl?e("img",{staticClass:"avatar",attrs:{src:a.form.hospitalImgUrl}}):e("i",{staticClass:"el-icon-plus avatar-uploader-icon"})])],1),e("el-form-item",[e("el-button",{attrs:{type:"primary"},on:{click:a.onSubmit}},[a._v("确定")])],1)],1)],1)])])},l=[],s={name:"baseform",data:function(){return{form:{id:"",hospitalname:"",logo:"",logoImgUrl:"",teamimg:"",teamImgUrl:"",hospitalimg:"",hospitalImgUrl:"",time:"",address:"",tel:"",lat:"",lng:"",city:""},loading:!1}},created:function(){this.getData()},methods:{uploadUrl:function(){var a=this.$api.uploadUrl+"/Images/upload";return a},beforeAvatarUpload:function(a){console.log(a),this.loading=!0},uploading:function(a,t,e){},uploadError:function(a){this.$message.error(a.msg)},handleAvatarSuccess1:function(a,t){this.loading=!1,console.log(a),this.form.logo=a.data,this.form.logoImgUrl=URL.createObjectURL(t.raw),this.$message.success(a.msg)},handleAvatarSuccess2:function(a,t){this.loading=!1,console.log(a),this.form.teamimg=a.data,this.form.teamImgUrl=URL.createObjectURL(t.raw),this.$message.success(a.msg)},handleAvatarSuccess3:function(a,t){this.loading=!1,console.log(a),this.form.hospitalimg=a.data,this.form.hospitalImgUrl=URL.createObjectURL(t.raw),this.$message.success(a.msg)},getData:function(){var a=this;this.$api.post("ShopConfig/getConfig",null,function(t){a.form=t.data,console.log(t)},function(t){a.tableData=[],a.$message.error(t.msg)})},onSubmit:function(){var a=this,t=this.$qs.stringify({id:this.form.id,hospitalname:this.form.hospitalname,time:this.form.time,address:this.form.address,tel:this.form.tel,lat:this.form.lat,lng:this.form.lng,city:this.form.city});this.$api.post("ShopConfig/saveConfig",t,function(t){a.$message.success(t.msg),console.log(t)},function(t){a.tableData=[],a.$message.error(t.msg)})}}},r=s,i=(e("3699"),e("17cc")),m=Object(i["a"])(r,o,l,!1,null,"17500a32",null);t["default"]=m.exports}}]);