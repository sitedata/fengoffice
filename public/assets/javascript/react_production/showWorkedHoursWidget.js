var showWorkedHoursWidget=function(e){function t(t){for(var a,i,s=t[0],l=t[1],c=t[2],d=0,p=[];d<s.length;d++)i=s[d],Object.prototype.hasOwnProperty.call(o,i)&&o[i]&&p.push(o[i][0]),o[i]=0;for(a in l)Object.prototype.hasOwnProperty.call(l,a)&&(e[a]=l[a]);for(u&&u(t);p.length;)p.shift()();return n.push.apply(n,c||[]),r()}function r(){for(var e,t=0;t<n.length;t++){for(var r=n[t],a=!0,s=1;s<r.length;s++){var l=r[s];0!==o[l]&&(a=!1)}a&&(n.splice(t--,1),e=i(i.s=r[0]))}return e}var a={},o={5:0},n=[];function i(t){if(a[t])return a[t].exports;var r=a[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,i),r.l=!0,r.exports}i.m=e,i.c=a,i.d=function(e,t,r){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(i.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)i.d(r,a,function(t){return e[t]}.bind(null,a));return r},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="";var s=window.webpackJsonp_name_=window.webpackJsonp_name_||[],l=s.push.bind(s);s.push=t,s=s.slice();for(var c=0;c<s.length;c++)t(s[c]);var u=l;return n.push([162,0]),r()}({162:function(e,t,r){function a(e){return(a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(e,t){for(var r=0;r<t.length;r++){var a=t[r];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}function n(e,t){return!t||"object"!==a(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function i(e){return(i=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function s(e,t){return(s=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var l=r(1),c=r(21),u=r(42),d=u.ResponsiveContainer,p=u.AreaChart,m=u.Area,f=u.XAxis,h=u.YAxis,y=u.CartesianGrid,g=u.Tooltip,v=(u.Legend,r(24)),b=v.AxisNumberFormatter,w=v.FormatNumber,E=function(e){function t(e){var r;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),(r=n(this,i(t).call(this,e))).state={worked:e.data.worked?e.data.worked:0,estimated:e.data.estimated?e.data.estimated:0,workedTitle:e.data.workedTitle?e.data.workedTitle:"Total worked hours",estimatedTitle:e.data.estimatedTitle?e.data.estimatedTitle:"Total estimated hours",chartData:e.data.chartData?e.data.chartData:"",decimals:e.data.decimals?e.data.decimals:0,decimalsSeparator:e.data.decimalsSeparator?e.data.decimalsSeparator:".",thousandSeparator:e.data.thousandSeparator?e.data.thousandSeparator:","},r}var r,a,c;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&s(e,t)}(t,e),r=t,(a=[{key:"render",value:function(){var e=this.state.decimals,t=this.state.decimalsSeparator,r=this.state.thousandSeparator,a=w(this.state.worked,e,t,r),o=w(this.state.estimated,e,t,r),n=this.state.workedTitle,i=this.state.estimatedTitle,s=this.state.chartData,c=Math.round(a/o*100),u={width:c+"%"};return Math.round(.96*o),l.createElement("div",{className:"progress-widget-container"},l.createElement("p",{style:u,"data-value":c}),l.createElement("div",{className:"progress-info-container"},l.createElement("div",{className:"progress-total"},l.createElement("div",null,n),l.createElement("div",null,l.createElement("svg",{className:"progress-total__icon progress-total__icon--completed",version:"1.1",xmlns:"http://www.w3.org/2000/svg",width:"24",height:"24",viewBox:"0 0 24 24"},l.createElement("path",{d:"M3.984 12q0-3.281 2.367-5.648t5.648-2.367 5.648 2.367 2.367 5.648-2.367 5.648-5.648 2.367-5.648-2.367-2.367-5.648z"})),l.createElement("span",{className:"progress-total__number"},a)," ",l.createElement("span",{className:"progress-total__unit"},"hrs"))),l.createElement("div",{className:"progress-total"},l.createElement("div",null,i),l.createElement("div",null,l.createElement("svg",{className:"progress-total__icon progress-total__icon--estimated",version:"1.1",xmlns:"http://www.w3.org/2000/svg",width:"24",height:"24",viewBox:"0 0 24 24"},l.createElement("path",{d:"M3.984 12q0-3.281 2.367-5.648t5.648-2.367 5.648 2.367 2.367 5.648-2.367 5.648-5.648 2.367-5.648-2.367-2.367-5.648z"})),l.createElement("span",{className:"progress-total__number"},o)," ",l.createElement("span",{className:"progress-total__unit"},"hrs")))),s&&l.createElement("div",{className:"progress-widget-chart"},l.createElement(d,{width:"100%",height:200},l.createElement(p,{data:s,margin:{top:10,right:0,left:0,bottom:-10},padding:{}},l.createElement("defs",null,l.createElement("linearGradient",{id:"colorEstimated",x1:"0",y1:"0",x2:"0",y2:"1"},l.createElement("stop",{offset:"5%",stopColor:"#d9d9d9",stopOpacity:.6}),l.createElement("stop",{offset:"95%",stopColor:"#d9d9d9",stopOpacity:.6})),l.createElement("linearGradient",{id:"colorWorkedHours",x1:"0",y1:"0",x2:"0",y2:"1"},l.createElement("stop",{offset:"5%",stopColor:"#20a1f8",stopOpacity:.6}),l.createElement("stop",{offset:"95%",stopColor:"#20a1f8",stopOpacity:.6}))),l.createElement(y,{strokeDasharray:"3 3",vertical:!1}),l.createElement(f,{dy:15,axisLine:!1,tickSize:0,stroke:"#888888",dataKey:"date",interval:"preserveStartEnd",minTickGap:70,height:40}),l.createElement(h,{dx:10,tickFormatter:b,axisLine:!1,tickSize:0,stroke:"#888888",orientation:"right",width:50}),l.createElement(g,{formatter:function(a){return w(a,e,t,r)}}),l.createElement(m,{type:"monotone",dataKey:"estimated",stroke:"#888888",fill:"url(#colorEstimated)",isAnimationActive:!1}),l.createElement(m,{type:"monotone",dataKey:"worked",stroke:"#20a1f8",fill:"url(#colorWorkedHours)",isAnimationActive:!1})))))}}])&&o(r.prototype,a),c&&o(r,c),t}(l.Component);e.exports=function(e,t){c.render(l.createElement(E,{data:e}),t)}},24:function(e,t,r){"use strict";r.r(t),r.d(t,"AxisNumberFormatter",(function(){return a})),r.d(t,"FormatNumber",(function(){return o}));var a=function(e){return e>1e9?(e/1e9).toString()+"B":e>1e6?(e/1e6).toString()+"M":e>1e3?(e/1e3).toString()+"K":e.toString()},o=function(e,t,r,a){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var o=isFinite(+e)?+e:0,n=isFinite(+t)?Math.abs(t):0,i=void 0===a?",":a,s=void 0===r?".":r,l="";return(l=(n?function(e,t){if(-1===(""+e).indexOf("e"))return+(Math.round(e+"e+"+t)+"e-"+t);var r=(""+e).split("e"),a="";return+r[1]+t>0&&(a="+"),(+(Math.round(+r[0]+"e"+a+(+r[1]+t))+"e-"+t)).toFixed(t)}(o,n).toString():""+Math.round(o)).split("."))[0].length>3&&(l[0]=l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,i)),(l[1]||"").length<n&&(l[1]=l[1]||"",l[1]+=new Array(n-l[1].length+1).join("0")),l.join(s)}}});