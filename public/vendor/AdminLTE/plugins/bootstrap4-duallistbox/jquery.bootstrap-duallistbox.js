!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof module&&module.exports?module.exports=function(t,n){return void 0===n&&(n="undefined"!=typeof window?require("jquery"):require("jquery")(t)),e(n),n}:e(jQuery)}((function(e){var t="bootstrapDualListbox",n={filterTextClear:"show all",filterPlaceHolder:"Filter",moveSelectedLabel:"Move selected",moveAllLabel:"Move all",removeSelectedLabel:"Remove selected",removeAllLabel:"Remove all",moveOnSelect:!0,moveOnDoubleClick:!0,preserveSelectionOnMove:!1,selectedListLabel:!1,nonSelectedListLabel:!1,helperSelectNamePostfix:"_helper",selectorMinimalHeight:100,showFilterInputs:!0,nonSelectedFilter:"",selectedFilter:"",infoText:"Showing all {0}",infoTextFiltered:'<span class="badge badge-warning">Filtered</span> {0} from {1}',infoTextEmpty:"Empty list",filterOnValues:!1,sortByInputOrder:!1,eventMoveOverride:!1,eventMoveAllOverride:!1,eventRemoveOverride:!1,eventRemoveAllOverride:!1,btnClass:"btn-outline-secondary",btnMoveText:"&gt;",btnRemoveText:"&lt;",btnMoveAllText:"&gt;&gt;",btnRemoveAllText:"&lt;&lt;"},s=/android/i.test(navigator.userAgent.toLowerCase());function i(s,i){this.element=e(s),this.settings=e.extend({},n,i),this._defaults=n,this._name=t,this.init()}function l(e){e.element.trigger("change")}function o(t){t.element.find("option").each((function(n,s){var i=e(s);void 0===i.data("original-index")&&i.data("original-index",t.elementCount++),void 0===i.data("_selected")&&i.data("_selected",!1)}))}function r(t,n,s){t.element.find("option").each((function(i,l){var o=e(l);o.data("original-index")===n&&(o.prop("selected",s),s?(o.attr("data-sortindex",t.sortIndex),t.sortIndex++):o.removeAttr("data-sortindex"))}))}function a(e,t){return console.log(e,t),e.replace(/{(\d+)}/g,(function(e,n){return void 0!==t[n]?t[n]:e}))}function c(e){if(e.settings.infoText){var t=e.elements.select1.find("option").length,n=e.elements.select2.find("option").length,s=e.element.find("option").length-e.selectedElements,i=e.selectedElements,l="";l=0===s?e.settings.infoTextEmpty:a(t===s?e.settings.infoText:e.settings.infoTextFiltered,[t,s]),e.elements.info1.html(l),e.elements.box1.toggleClass("filtered",!(t===s||0===s)),l=0===i?e.settings.infoTextEmpty:a(n===i?e.settings.infoText:e.settings.infoTextFiltered,[n,i]),e.elements.info2.html(l),e.elements.box2.toggleClass("filtered",!(n===i||0===i))}}function h(t){t.selectedElements=0,t.elements.select1.empty(),t.elements.select2.empty(),t.element.find("option").each((function(n,s){var i=e(s);i.prop("selected")?(t.selectedElements++,t.elements.select2.append(i.clone(!0).prop("selected",i.data("_selected")))):t.elements.select1.append(i.clone(!0).prop("selected",i.data("_selected")))})),t.settings.showFilterInputs&&(m(t,1),m(t,2)),c(t)}function m(t,n){if(t.settings.showFilterInputs){u(t,n),t.elements["select"+n].empty().scrollTop(0);var s,i=t.element.find("option"),l=t.element;l=1===n?i.not(":selected"):l.find("option:selected");try{s=new RegExp(e.trim(t.elements["filterInput"+n].val()),"gi")}catch(e){s=new RegExp("/a^/","gi")}l.each((function(l,o){var r=e(o),a=!0;(o.text.match(s)||t.settings.filterOnValues&&r.attr("value").match(s))&&(a=!1,t.elements["select"+n].append(r.clone(!0).prop("selected",r.data("_selected")))),i.eq(r.data("original-index")).data("filtered"+n,a)})),c(t)}}function u(t,n){var s=t.element.find("option");t.elements["select"+n].find("option").each((function(t,n){var i=e(n);s.eq(i.data("original-index")).data("_selected",i.prop("selected"))}))}function d(e){var t=e.children("option");t.sort((function(e,t){var n=parseInt(e.getAttribute("data-sortindex")),s=parseInt(t.getAttribute("data-sortindex"));return n>s?1:n<s?-1:0})),t.detach().appendTo(e)}function v(t,n){t.find("option").sort((function(t,n){return e(t).data("original-index")>e(n).data("original-index")?1:-1})).appendTo(t),h(n)}function f(t){"all"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect?"moved"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect||u(t,1):(u(t,1),u(t,2)),t.elements.select1.find("option:selected").each((function(n,s){var i=e(s);i.data("filtered1")||r(t,i.data("original-index"),!0)})),h(t),l(t),t.settings.sortByInputOrder?d(t.elements.select2):v(t.elements.select2,t)}function p(t){"all"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect?"moved"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect||u(t,2):(u(t,1),u(t,2)),t.elements.select2.find("option:selected").each((function(n,s){var i=e(s);i.data("filtered2")||r(t,i.data("original-index"),!1)})),h(t),l(t),v(t.elements.select1,t),t.settings.sortByInputOrder&&d(t.elements.select2)}function g(t){t.elements.form.submit((function(e){t.elements.filterInput1.is(":focus")?(e.preventDefault(),t.elements.filterInput1.focusout()):t.elements.filterInput2.is(":focus")&&(e.preventDefault(),t.elements.filterInput2.focusout())})),t.element.on("bootstrapDualListbox.refresh",(function(e,n){t.refresh(n)})),t.elements.filterClear1.on("click",(function(){t.setNonSelectedFilter("",!0)})),t.elements.filterClear2.on("click",(function(){t.setSelectedFilter("",!0)})),!1===t.settings.eventMoveOverride&&t.elements.moveButton.on("click",(function(){f(t)})),!1===t.settings.eventMoveAllOverride&&t.elements.moveAllButton.on("click",(function(){!function(t){"all"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect?"moved"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect||u(t,1):(u(t,1),u(t,2)),t.element.find("option").each((function(n,s){var i=e(s);i.data("filtered1")||(i.prop("selected",!0),i.attr("data-sortindex",t.sortIndex),t.sortIndex++)})),h(t),l(t)}(t)})),!1===t.settings.eventRemoveOverride&&t.elements.removeButton.on("click",(function(){p(t)})),!1===t.settings.eventRemoveAllOverride&&t.elements.removeAllButton.on("click",(function(){!function(t){"all"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect?"moved"!==t.settings.preserveSelectionOnMove||t.settings.moveOnSelect||u(t,2):(u(t,1),u(t,2)),t.element.find("option").each((function(t,n){var s=e(n);s.data("filtered2")||(s.prop("selected",!1),s.removeAttr("data-sortindex"))})),h(t),l(t)}(t)})),t.elements.filterInput1.on("change keyup",(function(){m(t,1)})),t.elements.filterInput2.on("change keyup",(function(){m(t,2)}))}i.prototype={init:function(){this.container=e('<div class="bootstrap-duallistbox-container row"> <div class="box1 col-md-6">   <label></label>   <span class="info-container">     <span class="info"></span>     <button type="button" class="btn btn-sm clear1" style="float:right!important;"></button>   </span>   <input class="form-control filter" type="text">   <div class="btn-group buttons">     <button type="button" class="btn moveall"></button>     <button type="button" class="btn move"></button>   </div>   <select multiple="multiple"></select> </div> <div class="box2 col-md-6">   <label></label>   <span class="info-container">     <span class="info"></span>     <button type="button" class="btn btn-sm clear2" style="float:right!important;"></button>   </span>   <input class="form-control filter" type="text">   <div class="btn-group buttons">     <button type="button" class="btn remove"></button>     <button type="button" class="btn removeall"></button>   </div>   <select multiple="multiple"></select> </div></div>').insertBefore(this.element),this.elements={originalSelect:this.element,box1:e(".box1",this.container),box2:e(".box2",this.container),filterInput1:e(".box1 .filter",this.container),filterInput2:e(".box2 .filter",this.container),filterClear1:e(".box1 .clear1",this.container),filterClear2:e(".box2 .clear2",this.container),label1:e(".box1 > label",this.container),label2:e(".box2 > label",this.container),info1:e(".box1 .info",this.container),info2:e(".box2 .info",this.container),select1:e(".box1 select",this.container),select2:e(".box2 select",this.container),moveButton:e(".box1 .move",this.container),removeButton:e(".box2 .remove",this.container),moveAllButton:e(".box1 .moveall",this.container),removeAllButton:e(".box2 .removeall",this.container),form:e(e(".box1 .filter",this.container)[0].form)},this.originalSelectName=this.element.attr("name")||"";var t="bootstrap-duallistbox-nonselected-list_"+this.originalSelectName,n="bootstrap-duallistbox-selected-list_"+this.originalSelectName;return this.elements.select1.attr("id",t),this.elements.select2.attr("id",n),this.elements.label1.attr("for",t),this.elements.label2.attr("for",n),this.selectedElements=0,this.sortIndex=0,this.elementCount=0,this.setFilterTextClear(this.settings.filterTextClear),this.setFilterPlaceHolder(this.settings.filterPlaceHolder),this.setMoveSelectedLabel(this.settings.moveSelectedLabel),this.setMoveAllLabel(this.settings.moveAllLabel),this.setRemoveSelectedLabel(this.settings.removeSelectedLabel),this.setRemoveAllLabel(this.settings.removeAllLabel),this.setMoveOnSelect(this.settings.moveOnSelect),this.setMoveOnDoubleClick(this.settings.moveOnDoubleClick),this.setPreserveSelectionOnMove(this.settings.preserveSelectionOnMove),this.setSelectedListLabel(this.settings.selectedListLabel),this.setNonSelectedListLabel(this.settings.nonSelectedListLabel),this.setHelperSelectNamePostfix(this.settings.helperSelectNamePostfix),this.setSelectOrMinimalHeight(this.settings.selectorMinimalHeight),o(this),this.setShowFilterInputs(this.settings.showFilterInputs),this.setNonSelectedFilter(this.settings.nonSelectedFilter),this.setSelectedFilter(this.settings.selectedFilter),this.setInfoText(this.settings.infoText),this.setInfoTextFiltered(this.settings.infoTextFiltered),this.setInfoTextEmpty(this.settings.infoTextEmpty),this.setFilterOnValues(this.settings.filterOnValues),this.setSortByInputOrder(this.settings.sortByInputOrder),this.setEventMoveOverride(this.settings.eventMoveOverride),this.setEventMoveAllOverride(this.settings.eventMoveAllOverride),this.setEventRemoveOverride(this.settings.eventRemoveOverride),this.setEventRemoveAllOverride(this.settings.eventRemoveAllOverride),this.setBtnClass(this.settings.btnClass),this.setBtnMoveText(this.settings.btnMoveText),this.setBtnRemoveText(this.settings.btnRemoveText),this.setBtnMoveAllText(this.settings.btnMoveAllText),this.setBtnRemoveAllText(this.settings.btnRemoveAllText),this.element.hide(),g(this),h(this),this.element},setFilterTextClear:function(e,t){return this.settings.filterTextClear=e,this.elements.filterClear1.html(e),this.elements.filterClear2.html(e),t&&h(this),this.element},setFilterPlaceHolder:function(e,t){return this.settings.filterPlaceHolder=e,this.elements.filterInput1.attr("placeholder",e),this.elements.filterInput2.attr("placeholder",e),t&&h(this),this.element},setMoveSelectedLabel:function(e,t){return this.settings.moveSelectedLabel=e,this.elements.moveButton.attr("title",e),t&&h(this),this.element},setMoveAllLabel:function(e,t){return this.settings.moveAllLabel=e,this.elements.moveAllButton.attr("title",e),t&&h(this),this.element},setRemoveSelectedLabel:function(e,t){return this.settings.removeSelectedLabel=e,this.elements.removeButton.attr("title",e),t&&h(this),this.element},setRemoveAllLabel:function(e,t){return this.settings.removeAllLabel=e,this.elements.removeAllButton.attr("title",e),t&&h(this),this.element},setMoveOnSelect:function(e,t){if(s&&(e=!0),this.settings.moveOnSelect=e,this.settings.moveOnSelect){this.container.addClass("moveonselect");var n=this;this.elements.select1.on("change",(function(){f(n)})),this.elements.select2.on("change",(function(){p(n)})),this.elements.moveButton.detach(),this.elements.removeButton.detach()}else this.container.removeClass("moveonselect"),this.elements.select1.off("change"),this.elements.select2.off("change"),this.elements.moveButton.insertAfter(this.elements.moveAllButton),this.elements.removeButton.insertBefore(this.elements.removeAllButton);return t&&h(this),this.element},setMoveOnDoubleClick:function(e,t){if(s&&(e=!1),this.settings.moveOnDoubleClick=e,this.settings.moveOnDoubleClick){this.container.addClass("moveondoubleclick");var n=this;this.elements.select1.on("dblclick",(function(){f(n)})),this.elements.select2.on("dblclick",(function(){p(n)}))}else this.container.removeClass("moveondoubleclick"),this.elements.select1.off("dblclick"),this.elements.select2.off("dblclick");return t&&h(this),this.element},setPreserveSelectionOnMove:function(e,t){return s&&(e=!1),this.settings.preserveSelectionOnMove=e,t&&h(this),this.element},setSelectedListLabel:function(e,t){return this.settings.selectedListLabel=e,e?this.elements.label2.show().html(e):this.elements.label2.hide().html(e),t&&h(this),this.element},setNonSelectedListLabel:function(e,t){return this.settings.nonSelectedListLabel=e,e?this.elements.label1.show().html(e):this.elements.label1.hide().html(e),t&&h(this),this.element},setHelperSelectNamePostfix:function(e,t){return this.settings.helperSelectNamePostfix=e,e?(this.elements.select1.attr("name",this.originalSelectName+e+"1"),this.elements.select2.attr("name",this.originalSelectName+e+"2")):(this.elements.select1.removeAttr("name"),this.elements.select2.removeAttr("name")),t&&h(this),this.element},setSelectOrMinimalHeight:function(e,t){this.settings.selectorMinimalHeight=e;var n=this.element.height();return this.element.height()<e&&(n=e),this.elements.select1.height(n),this.elements.select2.height(n),t&&h(this),this.element},setShowFilterInputs:function(e,t){return e?(this.elements.filterInput1.show(),this.elements.filterInput2.show()):(this.setNonSelectedFilter(""),this.setSelectedFilter(""),h(this),this.elements.filterInput1.hide(),this.elements.filterInput2.hide()),this.settings.showFilterInputs=e,t&&h(this),this.element},setNonSelectedFilter:function(e,t){if(this.settings.showFilterInputs)return this.settings.nonSelectedFilter=e,this.elements.filterInput1.val(e),t&&h(this),this.element},setSelectedFilter:function(e,t){if(this.settings.showFilterInputs)return this.settings.selectedFilter=e,this.elements.filterInput2.val(e),t&&h(this),this.element},setInfoText:function(e,t){return this.settings.infoText=e,e?(this.elements.info1.show(),this.elements.info2.show()):(this.elements.info1.hide(),this.elements.info2.hide()),t&&h(this),this.element},setInfoTextFiltered:function(e,t){return this.settings.infoTextFiltered=e,t&&h(this),this.element},setInfoTextEmpty:function(e,t){return this.settings.infoTextEmpty=e,t&&h(this),this.element},setFilterOnValues:function(e,t){return this.settings.filterOnValues=e,t&&h(this),this.element},setSortByInputOrder:function(e,t){return this.settings.sortByInputOrder=e,t&&h(this),this.element},setEventMoveOverride:function(e,t){return this.settings.eventMoveOverride=e,t&&h(this),this.element},setEventMoveAllOverride:function(e,t){return this.settings.eventMoveAllOverride=e,t&&h(this),this.element},setEventRemoveOverride:function(e,t){return this.settings.eventRemoveOverride=e,t&&h(this),this.element},setEventRemoveAllOverride:function(e,t){return this.settings.eventRemoveAllOverride=e,t&&h(this),this.element},setBtnClass:function(e,t){return this.settings.btnClass=e,this.elements.moveButton.attr("class","btn move").addClass(e),this.elements.removeButton.attr("class","btn remove").addClass(e),this.elements.moveAllButton.attr("class","btn moveall").addClass(e),this.elements.removeAllButton.attr("class","btn removeall").addClass(e),t&&h(this),this.element},setBtnMoveText:function(e,t){return this.settings.btnMoveText=e,this.elements.moveButton.html(e),t&&h(this),this.element},setBtnRemoveText:function(e,t){return this.settings.btnMoveText=e,this.elements.removeButton.html(e),t&&h(this),this.element},setBtnMoveAllText:function(e,t){return this.settings.btnMoveText=e,this.elements.moveAllButton.html(e),t&&h(this),this.element},setBtnRemoveAllText:function(e,t){return this.settings.btnMoveText=e,this.elements.removeAllButton.html(e),t&&h(this),this.element},getContainer:function(){return this.container},refresh:function(e){var t;o(this),e?(t=this).elements.select1.find("option").each((function(){t.element.find("option").data("_selected",!1)})):(u(this,1),u(this,2)),h(this)},destroy:function(){return this.container.remove(),this.element.show(),e.data(this,"plugin_"+t,null),this.element}},e.fn[t]=function(n){var s,l=arguments;return void 0===n||"object"==typeof n?this.each((function(){e(this).is("select")?e.data(this,"plugin_"+t)||e.data(this,"plugin_"+t,new i(this,n)):e(this).find("select").each((function(t,s){e(s).bootstrapDualListbox(n)}))})):"string"==typeof n&&"_"!==n[0]&&"init"!==n?(this.each((function(){var o=e.data(this,"plugin_"+t);o instanceof i&&"function"==typeof o[n]&&(s=o[n].apply(o,Array.prototype.slice.call(l,1)))})),void 0!==s?s:this):void 0}}));
