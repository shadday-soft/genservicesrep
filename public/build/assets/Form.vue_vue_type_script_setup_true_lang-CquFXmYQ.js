import{B as w,i as h,o as u,e as d,m as c,j as b,d as m,b as g,u as o,k as f,w as k,n as v,t as y,l as S}from"./app-DvtZCFRZ.js";import{_ as x}from"./Input.vue_vue_type_script_setup_true_lang-Cmcu8thF.js";import{A as V}from"./ActividadsService-BMGlIN0W.js";import{s as B}from"./index-BnCPYkfq.js";import{f as P}from"./index-BZxFKszo.js";import{s as C}from"./index-CV_lAkge.js";var T=`
    .p-toggleswitch {
        display: inline-block;
        width: dt('toggleswitch.width');
        height: dt('toggleswitch.height');
    }

    .p-toggleswitch-input {
        cursor: pointer;
        appearance: none;
        position: absolute;
        top: 0;
        inset-inline-start: 0;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
        opacity: 0;
        z-index: 1;
        outline: 0 none;
        border-radius: dt('toggleswitch.border.radius');
    }

    .p-toggleswitch-slider {
        cursor: pointer;
        width: 100%;
        height: 100%;
        border-width: dt('toggleswitch.border.width');
        border-style: solid;
        border-color: dt('toggleswitch.border.color');
        background: dt('toggleswitch.background');
        transition:
            background dt('toggleswitch.transition.duration'),
            color dt('toggleswitch.transition.duration'),
            border-color dt('toggleswitch.transition.duration'),
            outline-color dt('toggleswitch.transition.duration'),
            box-shadow dt('toggleswitch.transition.duration');
        border-radius: dt('toggleswitch.border.radius');
        outline-color: transparent;
        box-shadow: dt('toggleswitch.shadow');
    }

    .p-toggleswitch-handle {
        position: absolute;
        top: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: dt('toggleswitch.handle.background');
        color: dt('toggleswitch.handle.color');
        width: dt('toggleswitch.handle.size');
        height: dt('toggleswitch.handle.size');
        inset-inline-start: dt('toggleswitch.gap');
        margin-block-start: calc(-1 * calc(dt('toggleswitch.handle.size') / 2));
        border-radius: dt('toggleswitch.handle.border.radius');
        transition:
            background dt('toggleswitch.transition.duration'),
            color dt('toggleswitch.transition.duration'),
            inset-inline-start dt('toggleswitch.slide.duration'),
            box-shadow dt('toggleswitch.slide.duration');
    }

    .p-toggleswitch.p-toggleswitch-checked .p-toggleswitch-slider {
        background: dt('toggleswitch.checked.background');
        border-color: dt('toggleswitch.checked.border.color');
    }

    .p-toggleswitch.p-toggleswitch-checked .p-toggleswitch-handle {
        background: dt('toggleswitch.handle.checked.background');
        color: dt('toggleswitch.handle.checked.color');
        inset-inline-start: calc(dt('toggleswitch.width') - calc(dt('toggleswitch.handle.size') + dt('toggleswitch.gap')));
    }

    .p-toggleswitch:not(.p-disabled):has(.p-toggleswitch-input:hover) .p-toggleswitch-slider {
        background: dt('toggleswitch.hover.background');
        border-color: dt('toggleswitch.hover.border.color');
    }

    .p-toggleswitch:not(.p-disabled):has(.p-toggleswitch-input:hover) .p-toggleswitch-handle {
        background: dt('toggleswitch.handle.hover.background');
        color: dt('toggleswitch.handle.hover.color');
    }

    .p-toggleswitch:not(.p-disabled):has(.p-toggleswitch-input:hover).p-toggleswitch-checked .p-toggleswitch-slider {
        background: dt('toggleswitch.checked.hover.background');
        border-color: dt('toggleswitch.checked.hover.border.color');
    }

    .p-toggleswitch:not(.p-disabled):has(.p-toggleswitch-input:hover).p-toggleswitch-checked .p-toggleswitch-handle {
        background: dt('toggleswitch.handle.checked.hover.background');
        color: dt('toggleswitch.handle.checked.hover.color');
    }

    .p-toggleswitch:not(.p-disabled):has(.p-toggleswitch-input:focus-visible) .p-toggleswitch-slider {
        box-shadow: dt('toggleswitch.focus.ring.shadow');
        outline: dt('toggleswitch.focus.ring.width') dt('toggleswitch.focus.ring.style') dt('toggleswitch.focus.ring.color');
        outline-offset: dt('toggleswitch.focus.ring.offset');
    }

    .p-toggleswitch.p-invalid > .p-toggleswitch-slider {
        border-color: dt('toggleswitch.invalid.border.color');
    }

    .p-toggleswitch.p-disabled {
        opacity: 1;
    }

    .p-toggleswitch.p-disabled .p-toggleswitch-slider {
        background: dt('toggleswitch.disabled.background');
    }

    .p-toggleswitch.p-disabled .p-toggleswitch-handle {
        background: dt('toggleswitch.handle.disabled.background');
    }
`,F={root:{position:"relative"}},O={root:function(n){var i=n.instance,a=n.props;return["p-toggleswitch p-component",{"p-toggleswitch-checked":i.checked,"p-disabled":a.disabled,"p-invalid":i.$invalid}]},input:"p-toggleswitch-input",slider:"p-toggleswitch-slider",handle:"p-toggleswitch-handle"},z=w.extend({name:"toggleswitch",style:T,classes:O,inlineStyles:F}),j={name:"BaseToggleSwitch",extends:B,props:{trueValue:{type:null,default:!0},falseValue:{type:null,default:!1},readonly:{type:Boolean,default:!1},tabindex:{type:Number,default:null},inputId:{type:String,default:null},inputClass:{type:[String,Object],default:null},inputStyle:{type:Object,default:null},ariaLabelledby:{type:String,default:null},ariaLabel:{type:String,default:null}},style:z,provide:function(){return{$pcToggleSwitch:this,$parentInstance:this}}},p={name:"ToggleSwitch",extends:j,inheritAttrs:!1,emits:["change","focus","blur"],methods:{getPTOptions:function(n){var i=n==="root"?this.ptmi:this.ptm;return i(n,{context:{checked:this.checked,disabled:this.disabled}})},onChange:function(n){if(!this.disabled&&!this.readonly){var i=this.checked?this.falseValue:this.trueValue;this.writeValue(i,n),this.$emit("change",n)}},onFocus:function(n){this.$emit("focus",n)},onBlur:function(n){var i,a;this.$emit("blur",n),(i=(a=this.formField).onBlur)===null||i===void 0||i.call(a,n)}},computed:{checked:function(){return this.d_value===this.trueValue},dataP:function(){return P({checked:this.checked,disabled:this.disabled,invalid:this.$invalid})}}},N=["data-p-checked","data-p-disabled","data-p"],A=["id","checked","tabindex","disabled","readonly","aria-checked","aria-labelledby","aria-label","aria-invalid"],I=["data-p"],L=["data-p"];function $(e,n,i,a,r,t){return u(),h("div",c({class:e.cx("root"),style:e.sx("root")},t.getPTOptions("root"),{"data-p-checked":t.checked,"data-p-disabled":e.disabled,"data-p":t.dataP}),[d("input",c({id:e.inputId,type:"checkbox",role:"switch",class:[e.cx("input"),e.inputClass],style:e.inputStyle,checked:t.checked,tabindex:e.tabindex,disabled:e.disabled,readonly:e.readonly,"aria-checked":t.checked,"aria-labelledby":e.ariaLabelledby,"aria-label":e.ariaLabel,"aria-invalid":e.invalid||void 0,onFocus:n[0]||(n[0]=function(){return t.onFocus&&t.onFocus.apply(t,arguments)}),onBlur:n[1]||(n[1]=function(){return t.onBlur&&t.onBlur.apply(t,arguments)}),onChange:n[2]||(n[2]=function(){return t.onChange&&t.onChange.apply(t,arguments)})},t.getPTOptions("input")),null,16,A),d("div",c({class:e.cx("slider")},t.getPTOptions("slider"),{"data-p":t.dataP}),[d("div",c({class:e.cx("handle")},t.getPTOptions("handle"),{"data-p":t.dataP}),[b(e.$slots,"handle",{checked:t.checked})],16,L)],16,I)],16,N)}p.render=$;var U={name:"InputSwitch",extends:p,mounted:function(){console.warn("Deprecated since v4. Use ToggleSwitch component instead.")}};const D={class:"flex flex-col gap-2"},E={key:0,class:"text-red-500"},G={class:"mt-6 flex justify-end col-span-2"},W=m({__name:"Form",props:{actividad:{}},emits:["close"],setup(e,{emit:n}){const i=n,a=e,r=new V(a.actividad),t=r.form;return(M,l)=>(u(),h("div",null,[d("form",{onSubmit:l[2]||(l[2]=S(s=>o(r).submit(()=>i("close")),["prevent"])),class:"grid grid-cols-2 gap-4"},[g(x,{modelValue:o(t).nombre,"onUpdate:modelValue":l[0]||(l[0]=s=>o(t).nombre=s),label:"Nombre",error:o(t).errors.nombre},null,8,["modelValue","error"]),d("div",D,[l[3]||(l[3]=d("label",{class:"text-sm font-medium text-gray-700 dark:text-gray-300"},"Activo",-1)),g(o(U),{modelValue:o(t).active,"onUpdate:modelValue":l[1]||(l[1]=s=>o(t).active=s),binary:!0},{handle:k(({checked:s})=>[d("i",{class:v(["!text-xs pi",{"pi-check":s,"pi-times":!s}])},null,2)]),_:1},8,["modelValue"]),o(t).errors.active?(u(),h("small",E,y(o(t).errors.active),1)):f("",!0)]),d("div",G,[g(o(C),{type:"submit",label:"Guardar",icon:"pi pi-save",loading:o(r).form.processing},null,8,["loading"])])],32)]))}});export{W as _};
